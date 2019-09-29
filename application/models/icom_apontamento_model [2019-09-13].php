<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_apontamento_model extends MY_Model
{
    protected static $table = 'icom_apontamento';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'tipo_evento' => 'required|exact_length[2]',
        'horario_entrada' => 'valid_time',
        'horario_intervalo' => 'valid_time',
        'horario_retorno' => 'valid_time',
        'horario_saida' => 'valid_time',
        'desconto_folha' => 'valid_time',
        'acrescimo_horas' => 'valid_time',
        'decrescimo_horas' => 'valid_time',
        'saldo_banco_horas' => 'valid_time',
        'observacoes' => 'max_length[65535]'
    ];

    protected static $tipoEvento = [
        'FJ' => 'Falta com atestado próprio',
        'FN' => 'Falta sem atestado',
        'FC' => 'Falta combinada',
        'AJ' => 'Atraso com atestado próprio',
        'AS' => 'Atraso sem atestado',
        'SP' => 'Saída pós-horário',
        'CO' => 'Compensação',
        'EA' => 'Entrada antecipada',
        'SJ' => 'Saída antecipada com atestado próprio',
        'SN' => 'Saída antecipada sem atestado'
    ];

    protected $afterInsert = ['atualizarBancoHoras'];

    protected $beforeUpdate = ['prepararBancoHoras'];

    protected $afterUpdate = ['restaurarBancoHoras', 'atualizarBancoHoras'];

    protected $beforeDelete = ['prepararBancoHoras'];

    protected $afterDelete = ['restaurarBancoHoras'];

    private $dadosAnteriores;

    protected function prepararBancoHoras($data)
    {
        $this->dadosAnteriores = $this->find($data[self::$primaryKey] ?? null);
        return $data;
    }

    protected function restaurarBancoHoras($data)
    {
        if (!$data['result']) {
            return $data;
        }

        $oldData = $this->dadosAnteriores;
        $this->dadosAnteriores = null;

        $posto = $this->db
            ->select('a.*', false)
            ->join('icom_alocados b', 'b.id_usuario = a.id_usuario AND b.id_funcao = a.id_funcao')
            ->where('b.id', $oldData->id_alocado)
            ->get('icom_postos a')
            ->row();

        $usuario = $this->db
            ->select('id, banco_horas_icom')
            ->where('id', $posto->id_usuario)
            ->get('usuarios')
            ->row();

        $this->load->helper('time');
        $bancoHoras = timeToSec($usuario->banco_horas_icom ?? 0);

        switch ($oldData->tipo_evento) {
            case 'FJ':
            case 'FN':
            case 'FC':
            case 'CO':
                if ($posto->categoria == 'MEI') {
                    $bancoHoras -= (timeToSec($posto->qtde_horas_dia_mei ?? 0) * ($oldData->tipo_evento === 'CO' ? 1 : -1));
                } elseif ($posto->categoria == 'CLT') {
                    $bancoHoras -= (timeToSec($posto->qtde_horas_dia_clt ?? 0) * ($oldData->tipo_evento === 'CO' ? 1 : -1));
                }
                break;
            case 'AJ':
            case 'AS':
            case 'EA':
                if ($oldData->tipo_evento === 'EA') {
                    $horarioEntrada = max(timeToSec($posto->horario_entrada) - timeToSec($oldData->horario_entrada ?? $posto->horario_entrada), 0);
                    $horarioRetorno = max(timeToSec($posto->horario_retorno) - timeToSec($oldData->horario_retorno ?? $posto->horario_retorno), 0);
                } else {
                    $horarioEntrada = max(timeToSec($oldData->horario_entrada ?? 0) - timeToSec($posto->horario_entrada), 0);
                    $horarioRetorno = max(timeToSec($oldData->horario_retorno ?? 0) - timeToSec($posto->horario_retorno), 0);
                }
                $bancoHoras -= ($horarioEntrada + $horarioRetorno);
                break;
            case 'SJ':
            case 'SN':
            case 'SP':
                if ($oldData->tipo_evento === 'SP') {
                    $horarioIntervalo = max(timeToSec($posto->horario_intervalo) - timeToSec($oldData->horario_intervalo ?? $posto->horario_intervalo), 0);
                    $horarioSaida = max(timeToSec($posto->horario_saida) - timeToSec($oldData->horario_saida ?? $posto->horario_saida), 0);
                } else {
                    $horarioIntervalo = max(timeToSec($oldData->horario_intervalo ?? 0) - timeToSec($posto->horario_intervalo), 0);
                    $horarioSaida = max(timeToSec($oldData->horario_saida ?? 0) - timeToSec($posto->horario_saida), 0);
                }
                $bancoHoras -= (($horarioIntervalo + $horarioSaida) * (-1));
        }

        $this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $usuario->id]);

        if (isset($data['data'])) {
            $data['data']['saldo_banco_horas'] = secToTime($bancoHoras);
        }

        return $data;
    }

    protected function atualizarBancoHoras($data)
    {
        if (!$data['result']) {
            return $data;
        }

        $newData = (object)$data['data'];

        $posto = $this->db
            ->select('a.*', false)
            ->join('icom_alocados b', 'b.id_usuario = a.id_usuario AND b.id_funcao = a.id_funcao')
            ->where('b.id', $newData->id_alocado)
            ->get('icom_postos a')
            ->row();

        $usuario = $this->db
            ->select('id, banco_horas_icom')
            ->where('id', $posto->id_usuario)
            ->get('usuarios')
            ->row();

        $this->load->helper('time');
        $bancoHoras = timeToSec($usuario->banco_horas_icom ?? 0);

        switch ($newData->tipo_evento) {
            case 'FJ':
            case 'FN':
            case 'FC':
            case 'CO':
                if ($posto->categoria == 'MEI') {
                    $bancoHoras += (timeToSec($posto->qtde_horas_dia_mei ?? 0) * ($newData->tipo_evento === 'CO' ? 1 : -1));
                } elseif ($posto->categoria == 'CLT') {
                    $bancoHoras += (timeToSec($posto->qtde_horas_dia_clt ?? 0) * ($newData->tipo_evento === 'CO' ? 1 : -1));
                }
                break;
            case 'AJ':
            case 'AS':
            case 'EA':
                if ($newData->tipo_evento === 'EA') {
                    $horarioEntrada = max(timeToSec($posto->horario_entrada) - timeToSec($newData->horario_entrada ?? $posto->horario_entrada), 0);
                    $horarioRetorno = max(timeToSec($posto->horario_retorno) - timeToSec($newData->horario_retorno ?? $posto->horario_retorno), 0);
                } else {
                    $horarioEntrada = max(timeToSec($newData->horario_entrada ?? 0) - timeToSec($posto->horario_entrada), 0);
                    $horarioRetorno = max(timeToSec($newData->horario_retorno ?? 0) - timeToSec($posto->horario_retorno), 0);
                }
                $bancoHoras += ($horarioEntrada + $horarioRetorno);
                break;
            case 'SJ':
            case 'SN':
            case 'SP':
                if ($newData->tipo_evento === 'SP') {
                    $horarioIntervalo = max(timeToSec($posto->horario_intervalo) - timeToSec($newData->horario_intervalo ?? $posto->horario_intervalo), 0);
                    $horarioSaida = max(timeToSec($posto->horario_saida) - timeToSec($newData->horario_saida ?? $posto->horario_saida), 0);
                } else {
                    $horarioIntervalo = max(timeToSec($newData->horario_intervalo ?? 0) - timeToSec($posto->horario_intervalo), 0);
                    $horarioSaida = max(timeToSec($newData->horario_saida ?? 0) - timeToSec($posto->horario_saida), 0);
                }
                $bancoHoras += (($horarioIntervalo + $horarioSaida) * (-1));
        }

        $this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $usuario->id]);

        return $data;
    }

}
