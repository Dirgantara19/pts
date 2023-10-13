<?php
class M_Users extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_sql()
    {
        $group = ['guru'];

        $this->db->select(array(
            'users' . '.*',
            'users' . '.full_name as full_name',
            'users' . '.id as id',
            'users' . '.id as user_id'
        ));

        if (isset($groups)) {
            if (!is_array($groups)) {
                $groups = array($groups);
            }

            if (isset($groups) && !empty($groups)) {
                $this->db->distinct();
                $this->db->join(
                    'users_groups',
                    'users_groups' . '.' . 'users' . '=' . 'users' . '.id',
                    'inner'
                );
            }

            $group_ids = array();
            $group_names = array();
            foreach ($groups as $group) {
                if (is_numeric($group)) $group_ids[] = $group;
                else $group_names[] = $group;
            }
            $or_where_in = (!empty($group_ids) && !empty($group_names)) ? 'or_where_mot_in' : 'where_in';
            if (!empty($group_names)) {
                $this->db->join('groups', 'users_groups' . '.' . 'groups' . ' = ' . 'groups' . '.id', 'inner');
                $this->db->where_in('groups' . '.name', $group_names);
            }
            if (!empty($group_ids)) {
                $this->db->{$or_where_in}('users_groups' . '.' . 'groups', $group_ids);
            }
        }


        return $this;
    }

    private function get_query()
    {

        $this->get_sql();


        if (isset($_POST['search']['value'])) {
            $searchValue = $_POST['search']['value'];
            $columns_search = ['full_name'];
            $this->db->group_start();
            foreach ($columns_search as $col) {
                $this->db->like($col, $searchValue);
                $this->db->or_like($col, $searchValue, 'before');
                $this->db->or_like($col, $searchValue, 'after');
            }
            $this->db->group_end();
        }
        if (isset($_POST['order']['0']['column'])) {
            $orderColumn = $_POST['order']['0']['column'];
            $orderDirection = $_POST['order']['0']['dir'];

            $columns_order = [null, null, 'full_name', 'nip_or_nik'];
            $this->db->order_by($columns_order[$orderColumn], $orderDirection);
        }


        return $this;
    }

    private function recordsFiltered()
    {
        $this->get_sql();
        $result = $this->db->get('users')->result();
        $data = $this->data_structure($result);

        return count($data);
    }

    private function recordsTotal()
    {
        $this->get_sql();
        $result = $this->db->get('users')->result();
        $data = $this->data_structure($result);

        return count($data);
    }

    public function ajax_list()
    {
        $this->get_query();
        if (isset($_POST['length']) && $_POST['length'] != -1) {

            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $result = $this->db->get('users')->result();


        $data = $this->data_structure($result);
        $response = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->recordsTotal(),
            'recordsFiltered' => $this->recordsFiltered(),
            'data' => $data,
        );

        return json_encode($response);
    }


    private function data_structure($result)
    {

        $admin_group = $this->config->item('admin_group', 'ion_auth');
        $programmer_group = $this->config->item('programmer_group', 'ion_auth');

        $data = array();
        foreach ($result as $user) {
            $groups_exception = [$admin_group, $programmer_group];
            $groups_users_check = $this->ion_auth->in_group($groups_exception, $user->id);
            $users_groups = $this->ion_auth->get_users_groups($user->id)->result();
            foreach ($users_groups as $group) {
                if ($groups_users_check == false) {
                    $data[] = array(
                        'guru' => htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'),
                        'nip_or_nik' => htmlspecialchars($user->nip_or_nik, ENT_QUOTES, 'UTF-8'),
                        'groups' => $group->name,
                        'id' => $user->id,
                        'active_status' => $user->active,
                    );
                }
            }
        }


        return $data;
    }
}
