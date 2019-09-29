<?php


class Buu extends CI_Controller
{

    public function index()
    {
        $tables = $this->db->list_tables();
//        $tables = $data = ['pdi', 'pdi_desenvolvimento'];

        foreach ($tables as $table) {
            $fields = $this->db->list_fields($table);

            $this->db->select('COLUMN_NAME AS name, DATA_TYPE AS type', false);
            $this->db->select(["IF(IS_NULLABLE = 'YES', '?', '') AS is_nullable"], false);
            $this->db->where('TABLE_SCHEMA', $this->db->database);
            $this->db->where('TABLE_NAME', $table);
            $data = $this->db->get('INFORMATION_SCHEMA.COLUMNS')->result();


            $table = $this->camelCase($table);

            $entity = APPPATH . 'entity/' . $table . '.php';
            $buu = fopen($entity, 'w');

            $entityStr = "<?php\n\n";
            $entityStr .= "namespace App\Entities;\n\n";
            $entityStr .= "use CodeIgniter\Entity;\n\n";
            $entityStr .= "class {$table} extends Entity\n{\n";

            file_put_contents($entity, $entityStr, FILE_APPEND);

            foreach ($fields as $field) {
                file_put_contents($entity, '    protected $' . $field . ";\n", FILE_APPEND);
            }

            file_put_contents($entity, "\n    " . 'protected $casts = [' . "\n        ", FILE_APPEND);

            foreach ($data as $k => $row) {
                file_put_contents($entity, "'" . $row->name . "' => '" . $row->is_nullable . $this->setType($row->type) . "'", FILE_APPEND);
                if ($k < (count($data) - 1)) {
                    file_put_contents($entity, ",\n        ", FILE_APPEND);
                }
            }

            file_put_contents($entity, "\n    ];\n\n}\n", FILE_APPEND);

            fclose($buu);
        }

        echo 'Arquivos criados com sucesso.';
    }


    public function createModel()
    {
        $tables = $this->db->list_tables();
//        $tables = $data = ['pdi', 'pdi_desenvolvimento'];

        foreach ($tables as $table) {

            $this->db->select('COLUMN_NAME AS name, DATA_TYPE AS type, IS_NULLABLE AS is_nullable', false);
            $this->db->select(["IF(IS_NULLABLE = 'YES', '?', '') AS isNullable"], false);
            $this->db->select(['IFNULL(CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION) AS max_length'], false);
            $this->db->select("(CASE COLUMN_KEY WHEN 'PRI' THEN 1 END) AS primary_key", false);
            $this->db->select("(CASE EXTRA WHEN 'auto_increment' THEN 1 END) AS auto_increment", false);
            $this->db->where('TABLE_SCHEMA', $this->db->database);
            $this->db->where('TABLE_NAME', $table);
            $data = $this->db->get('INFORMATION_SCHEMA.COLUMNS')->result();
            $primaryKeys = array_filter(array_keys(array_column($data, 'primary_key', 'name')));
            $id = $primaryKeys[0] === 'id' ? 'id' : ($primaryKeys[0] ?? null);


            $this->db->select('COLUMN_NAME AS name', false);
            $this->db->select("CONCAT(REFERENCED_TABLE_NAME, '.', REFERENCED_COLUMN_NAME) AS reference", false);
            $this->db->where('TABLE_SCHEMA', $this->db->database);
            $this->db->where('TABLE_NAME', $table);
            $this->db->where('REFERENCED_TABLE_NAME IS NOT NULL', null, false);
            $this->db->where('REFERENCED_COLUMN_NAME IS NOT NULL', null, false);
            $rowsFK = $this->db->get('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')->result();
            $foreignKeys = array_column($rowsFK, 'reference', 'name');

            $originalTable = $table;
            $table = $this->camelCase($table);

            $entity = APPPATH . 'models2/' . lcfirst($table) . '_model.php';
            $buu = fopen($entity, 'w');

            $entityStr = "<?php\n\ndefined('BASEPATH') OR exit('No direct script access allowed');\n\n";
//            $entityStr .= "namespace App\Models;\n\n";
//            $entityStr .= "use CodeIgniter\Model;\n\n";
            $entityStr .= "class {$table}_model extends MY_Model\n{\n";
            $entityStr .= '    protected static $table = ' . "'" . $originalTable . "';\n";
            if ($id != 'id' and !empty($id)) {
                $entityStr .= '    protected static $primaryKey = ' . "'{$id}';\n";
            }
            $entityStr .= '    protected static $returnType = ' . "'" . $table . "';\n\n";
            $entityStr .= '    protected $allowedFields = [' . "\n        ";

            file_put_contents($entity, $entityStr, FILE_APPEND);

            $setRules = '    protected $validationRules = [' . "\n        ";

            foreach ($data as $k => $row) {
//                file_put_contents($entity, "'" . $row->name . "' => '" . $row->isNullable . $this->setType($row->type) . "'", FILE_APPEND);
                file_put_contents($entity, "'" . $row->name . "'", FILE_APPEND);
                $setRules .= "'" . $row->name . "' => '" . $this->setRules($row, $foreignKeys, $id) . "'";
                if ($k < (count($data) - 1)) {
                    file_put_contents($entity, ",\n        ", FILE_APPEND);
                    $setRules .= ",\n        ";
                }
            }

            file_put_contents($entity, "\n    ];\n\n", FILE_APPEND);
            file_put_contents($entity, $setRules . "\n    ];\n\n}\n", FILE_APPEND);


            fclose($buu);
        }

        echo 'Models criados com sucesso.';
    }


    private function camelCase($str)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }


    private function setType($str)
    {
        switch ($str) {
            case 'date':
            case 'datetime':
                $str = 'datetime';
                break;
            case 'time':
            case 'char':
                $str = 'string';
                break;
            case 'float':
            case 'decimal':
                $str = 'float';
                break;
            case 'int':
            case 'year':
            case 'bigint':
            case 'smallint':
            case 'tinyint':
                $str = 'int';
                break;
            default:
                $str = 'string';
        }

        return $str;
    }


    private function setRules($field, $foreignKeys, $primaryKey)
    {
        $rules = [];

        if ($field->is_nullable == 'NO' and $field->name != $primaryKey) {
            $rules[] = 'required';
        }

        switch ($field->type) {
            case 'date':
            case 'datetime':
            case 'time':
            case 'timestamp':
            case 'year':
                $rules[] = 'valid_' . $field->type;
                break;
            case 'int':
                $rules[] = 'integer';
                break;
            case 'decimal':
            case 'double':
            case 'float':
                $rules[] = 'decimal';
                break;
            case 'bigint':
            case 'smallint':
            case 'tinyint':
                $rules[] = 'numeric';
                break;
            case 'char':
                $rules[] = 'exact_length[' . $field->max_length . ']';
                break;
        }

        if (strpos($field->type, 'unsigned')) {
            $rules[] = 'greater_than[0]';
        }

        if ($field->type != 'char' and $field->max_length) {
            $rules[] = 'max_length[' . $field->max_length . ']';
        }

        if (isset($foreignKeys[$field->name])) {
            $rules[] = 'is_unique[' . $foreignKeys[$field->name] . ']';
        }

        return implode('|', $rules);
    }


}