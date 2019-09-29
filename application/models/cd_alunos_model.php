<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cd_alunos_model extends MY_Model
{
    protected static $table = 'cd_alunos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[100]',
        'id_escola' => 'required|is_natural_no_zero|max_length[11]',
        'endereco' => 'max_length[255]',
        'numero' => 'is_natural_no_zero|max_length[11]',
        'complemento' => 'max_length[255]',
        'municipio' => 'max_length[100]',
        'telefone' => 'max_length[50]',
        'contato' => 'max_length[255]',
        'email' => 'valid_email|max_length[255]',
        'cep' => 'valid_cep|max_length[20]',
        'hipotese_diagnostica' => 'required|max_length[255]',
        'nome_responsavel' => 'max_length[100]',
        'observacoes' => 'max_length[4294967295]',
        'data_matricula' => 'valid_date',
        'data_afastamento' => 'valid_date',
        'data_desligamento' => 'valid_date',
        'periodo_manha' => 'is_natural|less_than_equal_to[1]',
        'periodo_tarde' => 'is_natural|less_than_equal_to[1]',
        'periodo_noite' => 'is_natural|less_than_equal_to[1]',
        'status' => 'in_list[A,I,N,F]'
    ];

    protected static $status = [
        'A' => 'Ativo',
        'I' => 'Inativo',
        'N' => 'Não frequente',
        'F' => 'Afastado'
    ];

    protected $beforeUpdate = ['prepararApontamento'];

    protected $afterUpdate = ['atualizarApontamento'];

    //==========================================================================
    protected function prepararApontamento($data)
    {
        $this->db->trans_start();

        return $data;
    }

    //==========================================================================
    protected function atualizarApontamento($data)
    {
        if (!$data['result']) {
            $this->db->trans_complete();
            return $data;
        }

        $matriculados = $this->db
            ->select('a.id, a.id_aluno, a.escola, a.id_alocacao, a.turno')
            ->join('cd_alocacao b', "b.id = a.id_alocacao AND DATE_FORMAT(b.data, '%Y-%m') = '" . date('Y-m') . "'")
            ->where('a.id_aluno', $data['data']['id'])
            ->or_where('a.aluno', $data['data']['nome'])
            ->limit(1)
            ->get('cd_matriculados a')->result();

        $periodos = [];
        if (!empty($data['data']['periodo_manha'])) {
            $periodos[] = 'M';
        }
        if (!empty($data['data']['periodo_tarde'])) {
            $periodos[] = 'T';
        }
        if (!empty($data['data']['periodo_noite'])) {
            $periodos[] = 'N';
        }


        foreach ($matriculados as $matriculado) {
            if (in_array($matriculado->turno, $periodos)) {
                $escola = $this->db
                    ->select('nome')
                    ->where('id', $data['data']['id_escola'])
                    ->get('cd_escolas')
                    ->row();

                $data2 = [
                    'id_alocacao' => $matriculado->id_alocacao,
                    'id_aluno' => $id ?? $matriculado->id_aluno,
                    'aluno' => $data['data']['nome'],
                    'escola' => $escola->nome ?? $matriculado->escola,
                    'status' => $data['data']['status']
                ];

                $this->db->update('cd_matriculados a', $data2, ['a.id' => $matriculado->id]);
            }
        }

        $this->db->trans_complete();

        return $data;
    }

}
