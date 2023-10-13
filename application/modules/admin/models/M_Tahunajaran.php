<?php
class M_Tahunajaran extends CI_Model
{
    protected $table = "tahun";

    public function __construct()
    {
        parent::__construct();
    }

    private function get_query()
    {
        $table = $this->table;

        $columns = array(
            'id',
            'thn_ajaran',
            'semester',
        );
        $columns_order = array(
            null,
            null,
            'thn_ajaran',
            'semester',
        );

        $sql = "SELECT " . implode(',', $columns) . " FROM $table";

        if (isset($_POST['search']['value'])) {
            $search_value = $_POST['search']['value'];
            $sql .= " WHERE ";
            for ($i = 0; $i < count($columns); $i++) {
                $sql .= "$columns[$i] LIKE '%$search_value%'";
                if ($i < count($columns) - 1) {
                    $sql .= " OR ";
                }
            }
        }
        if (isset($_POST['order']['0']['column'])) {
            $order_column = $_POST['order']['0']['column'];
            $order_direction = $_POST['order']['0']['dir'];

            // Handle ordering by column
            $sql .= " ORDER BY " . $columns_order[$order_column] . " " . $order_direction;
        }

        return $sql;
    }

    private function recordsFiltered()
    {
        $sql = $this->get_query();
        $data = $this->db->query($sql)->num_rows();
        return $data;
    }

    private function recordsTotal()
    {
        return $this->db->count_all_results($this->table);
    }

    public function ajax_list()
    {
        $sql = $this->get_query();

        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
        }
        $data = $this->db->query($sql)->result();
        $response = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->recordsTotal(),
            'recordsFiltered' => $this->recordsFiltered(),
            'data' => $data,
        );

        echo json_encode($response);
    }
}