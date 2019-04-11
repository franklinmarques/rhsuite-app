<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: frank
 * Date: 09/01/2019
 * Time: 01:51
 */
class DataTables
{

    /**
     * List of chosen select columns.
     *
     * @var array
     * @access private
     */
    private $select = [];

    /**
     * Alias name for main table.
     *
     * @var string
     * @access private
     */
    private $alias = 't';

    /**
     * Subquery which the query is targeting.
     *
     * @var string
     * @access private
     */
    private $subquery = '';

    /**
     * List of searchable columns.
     *
     * @var array
     * @access private
     */
    private $search = [];

    /**
     * List of orderable columns.
     *
     * @var array
     * @access private
     */
    private $order = [];

    /**
     * Number of total fields returned.
     *
     * @var integer
     * @access private
     */
    private $recordsTotal = 0;

    /**
     * Number of filtered fields returned.
     *
     * @var integer
     * @access private
     */
    private $recordsFiltered = 0;


    // -------------------------------------------------------------------------

    /**
     * Constructor.
     *
     * @param  array $config
     * @return void
     */
    public function __construct($config = [])
    {
        if (is_array($config) and count($config) > 0) {
            $this->setOptions($config);
        }

        log_message('debug', "DataTables Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Run parameterized methods.
     *
     * @param  array $config
     * @param  bool $reset
     * @return void
     */
    public function setOptions($config = [], $reset = true)
    {
        if ($reset) {
            $this->reset();
        }

        foreach ($config as $attribute => $value) {
            $method = 'set' . ucfirst($attribute);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Reset instance attributes
     *
     * @return void
     */
    public function reset()
    {
        $this->select = [];
        $this->alias = 't';
        $this->subquery = '';
        $this->search = [];
        $this->order = [];
        $this->recordsTotal = 0;
        $this->recordsFiltered = 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Set list of selectors, searches and orders columns.
     *
     * @param  array $columns
     * @param  bool $reset
     * @return void
     */
    public function setColumns($columns, $reset = false)
    {
        $this->setSelect($columns, $reset);
        $this->setSearch($columns, $reset);
        $this->setOrder($columns, $reset);
    }

    // -------------------------------------------------------------------------

    /**
     * Set list of chosen select columns.
     *
     * @param  array $select
     * @param  bool $reset
     * @return void
     */
    public function setSelect($select, $reset = false)
    {
        $this->setClause('select', $select, $reset);
    }

    // -------------------------------------------------------------------------

    /**
     * Set alias name for main table.
     *
     * @param  string $alias
     * @return void
     */
    public function setAlias($alias = 't')
    {
        if (strlen($this->subquery) == 0) {
            $this->alias = strlen($alias) > 0 ? ($alias . '.') : 't';
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Set subquery into from clause.
     *
     * @param  string $query
     * @return void
     */
    public function setSubquery($query = null)
    {
        $CI = &get_instance();
        $this->subquery = isset($query) ? $query : $CI->db->last_query();
    }

    // -------------------------------------------------------------------------

    /**
     * Set list of searchable columns.
     *
     * @param  array $search
     * @param  bool $reset
     * @return void
     */
    public function setSearch($search, $reset = false)
    {
        $this->setClause('search', $search, $reset);
    }

    // -------------------------------------------------------------------------

    /**
     * Set list of orderable columns.
     *
     * @param  array $order
     * @param  bool $reset
     * @return void
     */
    public function setOrder($order, $reset = false)
    {
        foreach ($order as $key => $value) {
            if (is_int($key)) {
                $order[$value] = $value;
                unset($order[$key]);
            }
        }

        $this->setClause('order', $order, $reset);
    }

    // -------------------------------------------------------------------------

    /**
     * Set the number of total fields returned.
     *
     * @return void
     */
    public function setRecordsTotal()
    {
        $CI = &get_instance();
        $this->recordsTotal = $CI->db->result_id->num_rows ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Set the parameterized attributes.
     *
     * @param  string $clause
     * @param  aray|string $values
     * @param  bool $reset
     * @return void
     */
    private function setClause($clause, $values, $reset = false)
    {
        if (is_string($values)) {
            $values = explode(',', trim($values));
        }

        if ($reset) {
            $this->$clause = [];
        }

        $this->$clause = array_merge($this->$clause, $values);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Generate the output object from query string.
     *
     * @param  string $query
     * @param  bool $reset
     * @return object
     */
    public function query($query, $reset = true)
    {
        $CI = &get_instance();

        $db_result = $CI->db->query($query);

        return $this->generate($db_result, $reset);
    }

    // -------------------------------------------------------------------------

    /**
     * Generate the output object from query result object.
     *
     * @param  object $query
     * @param  bool $reset
     * @return object
     */
    public function generate($query, $reset = true)
    {
        // Create singleton instance reference
        $CI = &get_instance();


        // Check if query parameter is a valid query result object
        if (get_class($query) !== str_replace('_driver', '_result', get_class($CI->db))) {
            return false;
        }


        // Prepare subquery
        $subquery = $this->subquery;
        if (empty($subquery)) {
            $subquery = $CI->db->last_query();
        }


        // Prepare list of fields
        $fields = $query->list_fields();

        // If list fields has cleared, set the query result again
        if (empty($fields)) {
            $query = $CI->db->query("SELECT {$this->alias}* FROM ({$subquery}) {$this->alias}");
            $fields = $query->list_fields();
        }


        // Set number of total fields returned
        $this->recordsTotal = $query->num_rows();


        // Prepare select fields
        $select = array_intersect($this->select, $fields);
        if (empty($select)) {
            $select = [$this->alias . '.*'];
        } else {
            $fields = array_merge($select, $fields); // Rearrange list fields with priority order to select list
            $select = array_map(function ($field) {
                return $this->alias . '.' . $field;
            }, $select);
        }


        // use values from $_GET or $_POST
        $input = $CI->input->post();


        // prepare search conditions
        $search = array();
        if (!empty($input['search']['value'])) {
            $searchColumns = array();

            $searchable = array_column($input['columns'], 'searchable');
            foreach ($fields as $key => $field) {
                if (!empty($searchable[$key])) {
                    $searchColumns[] = $field;
                }
            }

            if ($this->search) {
                $searchColumns = array_intersect($this->search, $searchColumns);
            }

            foreach ($searchColumns as $field) {
                $search[] = $this->alias . '.' . $field . " LIKE '%" . $input['search']['value'] . "%'";
            }
        }


        // prepare order parameters
        $orderBy = array();
        if (!empty($input['order'])) {
            $orderColumns = array_map(function ($column) {
                return array_key_exists($column, $this->order) ? $this->order[$column] : $column;
            }, $fields);

            foreach ($input['order'] as $order) {
                if (isset($orderColumns[$order['column']]) and $orderColumns[$order['column']] !== null) {
                    $orderBy[] = $this->alias . '.' . $orderColumns[$order['column']] . ' ' . strtoupper($order['dir']);
                }
            }
        }


        // building main query
        $sql = 'SELECT ' . implode(', ', $select) .
            ' FROM (' . $subquery . ') ' . $this->alias;
        if ($search) {
            $sql .= ' WHERE ' . implode(' OR ', $search);
            // Set number of filtered fields returned
            $this->recordsFiltered = $CI->db->query($sql)->num_rows();
        } else {
            $this->recordsFiltered = $this->recordsTotal;
        }
        if ($orderBy) {
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        if ($input['length'] > 0) {
            $sql .= ' LIMIT ' . $input['start'] . ', ' . $input['length'];
        }


        // run main query and return data result
        $data = $CI->db->query($sql)->result();


        // create the output object
        $output = new stdClass();

        // set the output parameters
        $output->draw = intval($input['draw']);
        $output->recordsTotal = $this->recordsTotal;
        $output->recordsFiltered = $this->recordsFiltered;
        $output->data = $data;


        // reset all class attributes if requested
        if ($reset) {
            $this->reset();
        }

        // return output
        return $output;
    }

}
