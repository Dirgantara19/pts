<?php

class Crud_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }


    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this;
    }

    public function get_by_key($where)
    {
        $data = $this->db->get_where($this->table, $where)->row();
        return $data;
    }


    public function get_by_key_with_result($where)
    {
        $data = $this->db->get_where($this->table, $where)->result();
        return $data;
    }
    public function get_all()
    {
        $data = $this->db->get($this->table)->result();
        return $data;
    }

    public function update($data, $where)
    {

        $this->db->set($data);
        $this->db->where($where);
        $this->db->update($this->table);
        return $this;
    }

    public function update_batch($data, $where)
    {
        $this->db->update_batch($this->table, $data, $where);
        return $this;
    }

    public function delete($where)
    {
        $this->db->delete($this->table, $where);
        return $this;
    }
}
