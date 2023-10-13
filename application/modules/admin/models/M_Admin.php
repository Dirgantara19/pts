<?php


class M_Admin extends CI_Model
{



    public $tables = array();

    protected $response = NULL;




    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->config->load('ion_auth', TRUE);

        // initialize db tables data
        $this->tables  = $this->config->item('tables', 'ion_auth');
        $this->join               = $this->config->item('join', 'ion_auth');


        // initialize our hooks object
        $this->_ion_hooks = new stdClass;


        $this->trigger_events('model_constructor');
    }

    public function limit($limit)
    {
        $this->trigger_events('limit');
        $this->_ion_limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->trigger_events('offset');
        $this->_ion_offset = $offset;

        return $this;
    }

    public function where($where, $value = NULL)
    {
        $this->trigger_events('where');

        if (!is_array($where)) {
            $where = array($where => $value);
        }

        array_push($this->_ion_where, $where);

        return $this;
    }

    public function like($like, $value = NULL, $position = 'both')
    {
        $this->trigger_events('like');

        array_push($this->_ion_like, array(
            'like'     => $like,
            'value'    => $value,
            'position' => $position
        ));

        return $this;
    }

    public function select($select)
    {
        $this->trigger_events('select');

        $this->_ion_select[] = $select;

        return $this;
    }

    public function order_by($by, $order = 'desc')
    {
        $this->trigger_events('order_by');

        $this->_ion_order_by = $by;
        $this->_ion_order    = $order;

        return $this;
    }

    public function row()
    {
        $this->trigger_events('row');

        $row = $this->response->row();

        return $row;
    }

    public function row_array()
    {
        $this->trigger_events(array('row', 'row_array'));

        $row = $this->response->row_array();

        return $row;
    }

    public function result()
    {
        $this->trigger_events('result');

        $result = $this->response->result();

        return $result;
    }

    public function result_array()
    {
        $this->trigger_events(array('result', 'result_array'));

        $result = $this->response->result_array();

        return $result;
    }

    public function num_rows()
    {
        $this->trigger_events(array('num_rows'));

        $result = $this->response->num_rows();

        return $result;
    }


    public function count_users()
    {
        return $this->teachers()->num_rows();
    }


    public function count_teachers()
    {
        return $this->db->count_all_results('users_mapel_kelas');
    }


    public function count_siswa()
    {
        return $this->db->count_all_results('siswa');
    }

    public function count_kelas()
    {
        return $this->db->count_all_results('kelas');
    }



    /**
     * users
     *
     * @return object Users
     * @author Dirgantara Praditya
     **/
    public function teachers($groups = 'guru')
    {
        $this->trigger_events('users');

        // Default selects
        $this->db->select(array(
            $this->tables['users'] . '.full_name as guru',
            $this->tables['users'] . '.id as id',
        ));

        $this->db->from($this->tables['users']);
        $this->db->where($this->tables['users'] . '.active', 1);


        // filter by group id(s) if passed
        if (isset($groups)) {
            // build an array if only one group was passed
            if (!is_array($groups)) {
                $groups = array($groups);
            }

            // join and then run a where_in against the group ids
            if (isset($groups) && !empty($groups)) {
                $this->db->distinct();
                $this->db->join(
                    $this->tables['users_groups'],
                    $this->tables['users_groups'] . '.' . $this->join['users'] . '=' . $this->tables['users'] . '.id',
                    'inner'
                );
            }

            // verify if group name or group id was used and create and put elements in different arrays
            $group_names = array();
            foreach ($groups as $group) {
                $group_names[] = $group;
            }
            // if group name was used we do one more join with groups
            if (!empty($group_names)) {
                $this->db->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . ' = ' . $this->tables['groups'] . '.id', 'inner');
                $this->db->where_in($this->tables['groups'] . '.name', $group_names);
            }
        }

        $this->db->where('(SELECT COUNT(*) FROM ' . $this->tables['users_groups'] . ' ug WHERE ug.user_id = ' . $this->tables['users'] . '.id) = 1');

        $this->response = $this->db->get();

        return $this;
    }


    /**
     * user
     *
     * @return object
     * @author Ben Edmunds
     **/
    public function teacher($id = NULL)
    {
        $this->trigger_events('user');

        // if no id was passed use the current users id
        $id = isset($id) ? $id : $this->session->userdata('user_id');

        $this->limit(1);
        $this->order_by($this->tables['users'] . '.id', 'desc');
        $this->where($this->tables['users'] . '.id', $id);

        $this->teachers();

        return $this;
    }




    public function set_hook($event, $name, $class, $method, $arguments)
    {
        $this->_ion_hooks->{$event}[$name] = new stdClass;
        $this->_ion_hooks->{$event}[$name]->class     = $class;
        $this->_ion_hooks->{$event}[$name]->method    = $method;
        $this->_ion_hooks->{$event}[$name]->arguments = $arguments;
    }

    public function remove_hook($event, $name)
    {
        if (isset($this->_ion_hooks->{$event}[$name])) {
            unset($this->_ion_hooks->{$event}[$name]);
        }
    }

    public function remove_hooks($event)
    {
        if (isset($this->_ion_hooks->$event)) {
            unset($this->_ion_hooks->$event);
        }
    }

    protected function _call_hook($event, $name)
    {
        if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method)) {
            $hook = $this->_ion_hooks->{$event}[$name];

            return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
        }

        return FALSE;
    }

    public function trigger_events($events)
    {
        if (is_array($events) && !empty($events)) {
            foreach ($events as $event) {
                $this->trigger_events($event);
            }
        } else {
            if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events)) {
                foreach ($this->_ion_hooks->$events as $name => $hook) {
                    $this->_call_hook($events, $name);
                }
            }
        }
    }
}
