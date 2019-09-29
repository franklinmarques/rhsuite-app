<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected static $table = '';

    protected static $primaryKey = 'id';

    protected static $autoIncrement = true;

    protected static $insertID = 0;

    protected $uploadConfig = [];

    protected $validationRules = [];

    protected $validationLabels = [];

    protected $validationMessages = [];

    protected $skipValidation = false;

    protected $beforeInsert = [];

    protected $afterInsert = [];

    protected $beforeUpdate = [];

    protected $afterUpdate = [];

    protected $afterFind = [];

    protected $beforeDelete = [];

    protected $afterDelete = [];


    //==========================================================================
    public function __construct()
    {
        if (get_called_class() !== __CLASS__ and empty(static::$table)) {
            die('Nome da tabela não encontrado em ' . get_class($this) . '.php');
        }

        parent::__construct();
    }

    //==========================================================================
    public function find($id = null)
    {
        if (is_array($id)) {
            $this->db->where_in(static::$primaryKey, $id);
        } elseif (is_numeric($id) || is_string($id)) {
            $this->db->where(static::$primaryKey, $id);
        }

        return $this->db->get(static::$table)->row();
    }

    //==========================================================================
    public function findAll(int $limit = null, int $offset = 0)
    {
        if (!is_null($limit)) {
            $this->db->limit($limit);
        }

        if (!empty($offset)) {
            $this->db->limit($offset);
        }

        return $this->db->get(static::$table)->result();
    }

    //==========================================================================
    public function findColumn(string $columnName, $columnKey = null, string $defaultValue = '')
    {
        if (strpos($columnName, ',') !== false or strpos($columnKey, ',') !== false) {
            $class = get_called_class();
            throw new InvalidArgumentException("Parâmetro inválido em {$class}::insert()");
        }

        $resultSet = $this->db
            ->select($columnKey)
            ->select($columnName)
            ->order_by($columnName, 'asc')
            ->get(static::$table)
            ->result();

        if ($defaultValue) {
            $defaultValue = ['' => $defaultValue];
        } else {
            $defaultValue = [];
        }

        return array_filter($defaultValue + array_column($resultSet, $columnName, $columnKey));
    }

    //==========================================================================
    public function skipValidation(bool $skip = true)
    {
        $this->skipValidation = $skip;

        return $this;
    }

    //==========================================================================
    public function getUploadConfig(): array
    {
        return $this->uploadConfig;
    }

    //==========================================================================
    public function setUploadConfig(array $config = [])
    {
        $this->uploadConfig = $config;
    }

    //==========================================================================
    public function getValidationRules(array $options = []): array
    {
        $rules = $this->validationRules;

        if (isset($options['except'])) {
            $rules = array_diff_key($rules, array_flip($options['except']));
        } elseif (isset($options['only'])) {
            $rules = array_intersect_key($rules, array_flip($options['only']));
        }

        return $rules;
    }

    //==========================================================================
    public function setValidationRules(array $validationRules, bool $reset = false)
    {
        if ($reset) {
            $this->validationRules = $validationRules;
        } else {
            foreach ($validationRules as $field => $validationRule) {
                $this->setValidationRule($field, $validationRule);
            }
        }
    }

    //==========================================================================
    public function setValidationRule(string $field, $rules)
    {
        if (is_array($rules)) {
            $rules = implode('|', $rules);
        }

        if (strlen($rules) > 0) {
            $this->validationRules[$field] = $rules;
        } else {
            $reflection = new ReflectionClass(get_called_class());

            $reflectionProperty = $reflection->getProperty('validationRules');
            $reflectionProperty->setAccessible(true);
            $propertyValidationRules = $reflectionProperty->getValue(new static);

            if (in_array($field, $propertyValidationRules)) {
                $this->validationRules[$field] = $propertyValidationRules[$field];
            } else {
                unset($this->validationRules[$field]);
            }
        }
    }

    //==========================================================================
    public function setValidationLabels(array $validationLabels)
    {
        $this->validationLabels = $validationLabels;
    }

    //==========================================================================
    public function setValidationLabel(string $field, string $label)
    {
        unset($this->validationLabels[$field]);
        $this->validationLabels[$field] = $label;
    }

    //==========================================================================
    public function setValidationMessages(array $validationMessages)
    {
        $this->validationMessages = $validationMessages;
    }

    //==========================================================================
    public function setValidationMessage(string $field, array $fieldMessages)
    {
        $this->validationMessages[$field] = $fieldMessages;
    }

    //==========================================================================
    public function validate($data): bool
    {
        if ($this->skipValidation === true || empty($this->validationRules)) {
            return true;
        }

        if ($this->load->is_loaded('form_validation') === false) {
            $this->load->library('form_validation');
        }

        if ($data instanceof Entity) {
            $data = $data->toArray();
        }

        $originalData = $data;

        foreach ($data as $column => $value) {
            if (isset($_POST[$column])) {
                $_POST[$column] = $value;
            }
        }

        $validationRules = array_replace($this->validationLabels, $this->validationRules);

        if (static::$autoIncrement) {
            $autoIncrementValue = $data->{static::$primaryKey} ?? ($data[static::$primaryKey] ?? null);

            if (empty(trim($autoIncrementValue))) {
                unset($validationRules[static::$primaryKey]);
            }
        }

        $config = [];

        foreach ($validationRules as $field => $rules) {
            $config[] = [
                'field' => $field,
                'label' => $this->validationLabels[$field] ?? $field,
                'rules' => $rules
            ];
        }

        $this->form_validation->set_rules($config);

        foreach ($this->validationMessages as $lang => $message) {
            $this->form_validation->set_message($lang, $message);
        }

        $return = $this->form_validation->run();

        foreach ($originalData as $originalColumn => $originalValue) {
            if (isset($_POST[$originalColumn])) {
                $_POST[$originalColumn] = $originalValue;
            }
        }

        return $return;
    }

    //==========================================================================
    public function save($data)
    {
        if (empty($data)) {
            return true;
        }

        if (is_object($data) && !empty($data->{static::$primaryKey})) {
            $response = $this->update($data->{static::$primaryKey}, $data);
        } elseif (is_array($data) && !empty($data[static::$primaryKey])) {
            $response = $this->update($data[static::$primaryKey], $data);
        } else {
            $response = $this->insert($data, false);

            if ($response !== false) {
                $response = true;
            }
        }

        return $response;
    }

    //==========================================================================
    public function insert($data = null, bool $returnID = true)
    {
        if (empty($data)) {
            $class = get_called_class();
            throw new InvalidArgumentException("Conjunto de dados vazio em {$class}::insert()");
        }

        static::$insertID = 0;

        $insertID = 0;

        if ($data instanceof Entity) {
            $data = $data->toArray();
        }

        if (is_object($data)) {
            unset($data->{static::$primaryKey});
        } elseif (is_array($data)) {
            unset($data[static::$primaryKey]);
        }

        if ($this->skipValidation === false) {
            if ($this->validate($data) === false) {
                return false;
            }
        }

        $originalData = $data;

        $result = $this->uploadFiles($data);

        $data = $this->trigger('beforeInsert', ['data' => $data]);

        if ($result) {
            $result = $this->db->insert(static::$table, $data['data']);
        }

        if ($result) {
            $insertID = $this->db->insert_id();
        } else {
            $this->deleteFiles($data);
        }

        $this->trigger('afterInsert', ['data' => $originalData, 'result' => $result]);

        if ($result == false) {
            return false;
        }

        static::$insertID = $insertID;

        return $returnID ? static::$insertID : true;
    }

    //==========================================================================
    public function insertBatch(array $set = null, bool $escape = null, int $batchSize = 100)
    {
        if (empty($set)) {
            $set = null;
        } elseif (is_array($set) && $this->skipValidation === false) {
            if (count($set) > $batchSize and $batchSize > 0) {
                $set = array_slice($set, 0, $batchSize);
            }

            foreach ($set as $row) {
                if ($this->validate($row) === false) {
                    return false;
                }
            }
        }

        return $this->db->insert_batch(static::$table, $set);
    }

    //==========================================================================
    public function update($id = null, $data = null): bool
    {
        if (empty($data)) {
            $class = get_called_class();
            throw new InvalidArgumentException("Conjunto de dados vazio em {$class}::update()");
        }

        if (is_numeric($id) || is_string($id)) {
            $id = [$id];
        }

        if ($data instanceof Entity) {
            $data = $data->toArray();
        }

        if ($this->skipValidation === false) {
            if ($this->validate($data) === false) {
                return false;
            }
        }

        if ($this->uploadConfig) {
            $oldData = $this->db->where_in(static::$primaryKey, $id)->get(static::$table)->result();
        } else {
            $oldData = null;
        }

        $originalData = $data;

        $result = $this->uploadFiles($data);

        $data = $this->trigger('beforeUpdate', ['id' => $id, 'data' => $data]);

        if ($result) {
            $result = $this->db->where_in(static::$primaryKey, $data['id'])->update(static::$table, $data['data']);
        }

        if ($result and $oldData) {
            $result = $this->deleteFiles($oldData);

            if ($result == false) {
                $this->db->update_batch(static::$table, $oldData, static::$primaryKey);
            }
        }

        if ($result == false) {
            $this->deleteFiles($data);
        }

        $this->trigger('afterUpdate', ['id' => $id, 'data' => $originalData, 'result' => $result]);

        return $result;
    }

    //==========================================================================
    public function updateBatch(array $set = null, string $index = null, int $batchSize = 100)
    {
        if (empty($set)) {
            $set = null;
        } elseif (is_array($set) && $this->skipValidation === false) {
            if (count($set) > $batchSize and $batchSize > 0) {
                $set = array_slice($set, 0, $batchSize);
            }

            foreach ($set as $row) {
                if ($this->validate($row) === false) {
                    return false;
                }
            }
        }

        return $this->db->update_batch(static::$table, $set, $index);
    }

    //==========================================================================
    public function replace($data = null)
    {
        if (!empty($data) && $this->skipValidation === false) {
            if ($this->validate($data) === false) {
                return false;
            }
        }

        return $this->db->replace(static::$table, $data);
    }

    //==========================================================================
    public function delete($id = null)
    {
        if (!empty($id) && is_numeric($id)) {
            $id = [$id];
        }

        if ($this->uploadConfig) {
            $data = $this->db->where_in(static::$primaryKey, $id)->get(static::$table)->result();
        } else {
            $data = null;
        }

        $this->trigger('beforeDelete', ['id' => $id]);

        $result = $this->db->where_in(static::$primaryKey, $id)->delete(static::$table);

        if ($result and $data) {
            $result = $this->deleteFiles($data);

            if ($result == false) {
                $this->db->insert_batch(static::$table, $data);
            }
        }

        $this->trigger('afterDelete', ['id' => $id, 'result' => $result, 'data' => null]);

        return $result;
    }

    //==========================================================================
    protected function uploadFiles(&$data): bool
    {
        $files = [];

        foreach ($this->uploadConfig as $field => $config) {
            if (!empty($_FILES[$field]['tmp_name'])) {
                $config['file_name'] = utf8_decode($_FILES[$field]['name']);
                $files[$field] = $config;
            }
        }
        if (empty($files)) {
            return true;
        }

        $uploadedFiles = [];
        $error = false;

        foreach ($files as $field => $config) {
            $this->load->library('upload', $config, $field);

            if ($this->$field->do_upload($field)) {
                $data[$field] = $this->$field->data()['file_name'];
                $uploadedFiles[$field] = $config['upload_path'];
            } else {
                $error = true;
                break;
            }
        }

        if ($error) {
            foreach ($uploadedFiles as $file => $path) {
                unlink($path . $file);
                unset($data[$field]);
            }
            return false;
        }

        return true;
    }

    //==========================================================================
    protected function deleteFiles(&$data): bool
    {
        if (empty($data) or empty($this->uploadConfig)) {
            return true;
        }

        $error = false;

        $tempFiles = [];

        if (count($data) === count($data, COUNT_RECURSIVE)) {
            $data = [$data];
        }

        $originalData = $data;

        foreach ($this->uploadConfig as $field => $config) {
            $fileGroup = array_filter(array_column($data, $field));

            foreach ($fileGroup as $file) {
                $filePath = $config['upload_path'] . $file;

                if (file_exists($filePath)) {
                    $tempFiles[$filePath] = tmpfile();

                    fwrite($tempFiles[$filePath], file_get_contents($filePath));

                    $error = @unlink($filePath) !== true;
                }

                if ($error) {
                    break 2;
                }
            }

            foreach ($data as &$row) {
                if (isset($row[$field])) {
                    $row[$field] = null;
                }
            }
        }

        if ($error) {
            foreach ($tempFiles as $oldPath => $tempFile) {
                copy(stream_get_meta_data($tempFile)['uri'], $oldPath);
            }

            $data = $originalData;

            return false;
        }

        return true;
    }

    //==========================================================================
    protected function trigger(string $event, array $data)
    {
        if (!isset($this->{$event}) || empty($this->{$event})) {
            return $data;
        }

        foreach ($this->{$event} as $callback) {
            if (!method_exists($this, $callback)) {
                $class = get_called_class();
                throw new BadMethodCallException("Método não encontrado: {$class}::{$callback}()");
            }

            $data = $this->{$callback}($data);
        }

        return $data;
    }

    //==========================================================================
    public function errors(bool $forceDB = false)
    {
        if ($forceDB === false && $this->skipValidation === false) {
            $errors = '';

            if ($this->load->is_loaded('form_validation')) {
                $errors = $this->form_validation->error_string(' ', ' ');
            }

            if ($this->load->is_loaded('upload')) {
                $errors = $this->upload->display_errors(' ', ' ');
            }

            if (!empty($errors)) {
                return $errors;
            }
        }

        return $error['message'] ?? null;
    }

    //==========================================================================
    public function __call(string $name, array $params)
    {
        $result = null;

        if (method_exists($this->db, $name)) {
            $result = $this->db->$name(...$params);
        }

        if ($result instanceof CI_DB_result) {
            return $result;
        }

        return $this;
    }

    //==========================================================================
    public static function __callStatic(string $name, array $params = [])
    {
        $class = get_called_class();

        if (method_exists($class, $name)) {
            return static::$$name(...$params);
        }

        if (property_exists($class, $name)) {
            if ($params) {
                return static::$$name[$params[0]] ?? null;
            }
            return static::$$name;
        }

        throw new BadMethodCallException("Método não encontrado: {$class}::{$name}()");
    }

}
