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
        'hora_extra' => 'valid_time',
        'desconto_folha' => 'valid_time',
        'banco_horas' => 'valid_time',
        'saldo_banco_horas' => 'valid_time',
        'observacoes' => 'max_length[65535]'
    ];

    protected static $tipoEvento = [
        'FJ' => 'Falta com atestado próprio',
        'FN' => 'Falta sem atestado',
        'FC' => 'Falta combinada',
        'BH' => 'Banco de horas',
        'AJ' => 'Atraso com atestado próprio',
        'AS' => 'Atraso sem atestado',
        'SP' => 'Saída pós-horário',
        'CO' => 'Compensação (Trabalho dia de folga - MEI)',
        'EA' => 'Entrada antecipada',
        'SJ' => 'Saída antecipada com atestado próprio',
        'SN' => 'Saída antecipada sem atestado',
        'HE' => 'Hora extra (Trabalho dia de feriado - CLT)'
    ];

    protected $beforeInsert = ['restaurarSaldoBancoHoras', 'prepararSaldoBancoHoras'];

    protected $afterInsert = ['atualizarSaldoBancoHoras'];

    protected $beforeUpdate = ['restaurarSaldoBancoHoras', 'prepararSaldoBancoHoras'];

    protected $afterUpdate = ['atualizarSaldoBancoHoras'];

    protected $beforeDelete = ['restaurarSaldoBancoHoras'];

    protected $afterDelete = ['atualizarSaldoBancoHoras'];

    private $saldoBancoHoras;

    //==========================================================================
    protected function restaurarSaldoBancoHoras($data)
    {
        $this->db->trans_start();

        if (!empty($data[self::$primaryKey]) == false) {
            return $data;
        }

        $row = $this->db
            ->select('a.tipo_evento, a.saldo_banco_horas, b.id_usuario, c.banco_horas_icom')
            ->join('icom_alocados b', 'b.id = a.id_alocado')
            ->join('usuarios c', 'c.id = b.id_usuario')
            ->where_in('a.id', $data[self::$primaryKey])
            ->get('icom_apontamento a')
            ->row();

        if (empty($row)) {
            return $data;
        }

        $this->load->helper('time');

        $bancoHoras = timeToSec($row->banco_horas_icom) - timeToSec($row->saldo_banco_horas);

        $this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $row->id_usuario]);

        return $data;
    }

    //==========================================================================
    protected function prepararSaldoBancoHoras($data)
    {
        if (!empty($data['data']) == false) {
            return $data;
        }

        $row = $this->db
            ->select('a.categoria, a.qtde_horas_dia_mei, a.qtde_horas_dia_clt, b.banco_horas_icom')
            ->select('a.horario_entrada, a.horario_intervalo, a.horario_retorno, a.horario_saida')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->join('icom_alocados c', 'c.id_usuario = b.id AND b.id_funcao = a.id_funcao')
            ->where('c.id', $data['data']['id_alocado'])
            ->get('icom_postos a')
            ->row();

        if (empty($row)) {
            return $data;
        }

        $this->load->helper('time');

        $bancoHoras = null;

        switch ($data['data']['tipo_evento']) {
            case 'FN':
                $descontoFolha = timeToSec($row->categoria == 'MEI' ? $row->qtde_horas_dia_mei : $row->qtde_horas_dia_clt);
                $data['data']['desconto_folha'] = secToTime($descontoFolha * ($descontoFolha < 0 ? 1 : -1));
                break;
            case 'FJ':
            case 'FC':
            case 'CO':
                if ($row->categoria == 'MEI') {
                    $bancoHoras = timeToSec($row->qtde_horas_dia_mei);
                } elseif ($row->categoria == 'CLT' and in_array($data['data']['tipo_evento'], ['FN', 'FC'])) {
                    $bancoHoras = timeToSec($row->qtde_horas_dia_clt);
                }
                if (in_array($data['data']['tipo_evento'], ['FJ', 'FC'])) {
                    $bancoHoras *= (-1);
                }
                break;
            case 'AJ':
            case 'AS':
            case 'EA':
                if ($data['data']['tipo_evento'] === 'EA') {
                    $horarioEntrada = timeToSec($row->horario_entrada) - timeToSec($data['data']['horario_entrada'] ?? $row->horario_entrada);
                    $horarioRetorno = timeToSec($row->horario_retorno) - timeToSec($data['data']['horario_retorno'] ?? $row->horario_retorno);
                    $bancoHoras = max($horarioEntrada, 0) + max($horarioRetorno, 0);
                } elseif (!($row->categoria == 'CLT' and $data['data']['tipo_evento'] == 'AJ')) {
                    $horarioEntrada = timeToSec($data['data']['horario_entrada'] ?? $row->horario_entrada) - timeToSec($row->horario_entrada);
                    $horarioRetorno = timeToSec($data['data']['horario_retorno'] ?? $row->horario_retorno) - timeToSec($row->horario_retorno);
                    $bancoHoras = (max($horarioEntrada, 0) + max($horarioRetorno, 0)) * (-1);
                }
                break;
            case 'HE':
                $horaExtra = timeToSec($row->categoria == 'MEI' ? $row->qtde_horas_dia_mei : $row->qtde_horas_dia_clt);
                $data['data']['hora_extra'] = secToTime($horaExtra * ($horaExtra < 0 ? -1 : 1));
                break;
            case 'SJ':
            case 'SN':
            case 'SP':
                if ($data['data']['tipo_evento'] === 'SP' or ($data['data']['tipo_evento'] === 'HE' and $row->categoria == 'CLT')) {
                    $horarioIntervalo = timeToSec($data['data']['horario_intervalo'] ?? $row->horario_intervalo) - timeToSec($row->horario_intervalo);
                    $horarioSaida = timeToSec($data['data']['horario_saida'] ?? $row->horario_saida) - timeToSec($row->horario_saida);
                    $bancoHoras = max($horarioIntervalo, 0) + max($horarioSaida, 0);
                } elseif (!($row->categoria == 'CLT' and $data['data']['tipo_evento'] == 'SJ')) {
                    $horarioIntervalo = timeToSec($row->horario_intervalo) - timeToSec($data['data']['horario_intervalo'] ?? $row->horario_intervalo);
                    $horarioSaida = timeToSec($row->horario_saida) - timeToSec($data['data']['horario_saida'] ?? $row->horario_saida);
                    $bancoHoras = (max($horarioIntervalo, 0) + max($horarioSaida, 0)) * (-1);
                }
        }

        $data['data']['saldo_banco_horas'] = secToTime($bancoHoras);
        $this->saldoBancoHoras = $bancoHoras;

        return $data;
    }

    //==========================================================================
    protected function atualizarSaldoBancoHoras($data)
    {
        if ($data['result'] and !empty($data['data'])) {
            $row = $this->db
                ->select('a.id_usuario, b.banco_horas_icom', false)
                ->join('usuarios b', 'b.id = a.id_usuario')
                ->where_in('a.id', $data['data']['id_alocado'])
                ->get('icom_alocados a')
                ->row();

            if ($row) {
                $this->load->helper('time');

                $bancoHoras = timeToSec($row->banco_horas_icom) + ($this->saldoBancoHoras ?? 0);
                $this->saldoBancoHoras = null;
                $this->db->update('usuarios', ['banco_horas_icom' => secToTime($bancoHoras)], ['id' => $row->id_usuario]);
            }
        }

        $this->db->trans_complete();

        return $data;
    }

}
