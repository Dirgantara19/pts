<?php
class M_Gurupengampu extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_sql()
    {
        $sql = "SELECT
        `a`.`full_name` AS `guru`,
        `a`.`nip_or_nik` AS `nip_or_nik`,
        `b`.`id` AS `id`,
        `c`.`kelas` AS `kelas`,
        `d`.`nama` AS `mengajar`
        FROM `users` AS `a`
        JOIN `users_mapel_kelas` AS `b` ON `a`.`id` = `b`.`user_id`
        JOIN `kelas` AS `c` ON `b`.`kelas_id` = `c`.`id`
        JOIN `mapel` AS `d` ON `b`.`mapel_id` = `d`.`id`
        WHERE `a`.`active` = 1";

        return $sql;
    }

    private function get_query()
    {
        $columns_order = array(
            null,
            null,
            'guru',
            'nip_or_nik',
            'mengajar',
            'kelas',
        );


        $sql = $this->get_sql();



        if (isset($_POST['search']['value'])) {
            $search_value = $_POST['search']['value'];
            $sql .= " AND (`full_name` LIKE '%$search_value%' OR `kelas` LIKE '%$search_value%' OR `nama` LIKE '%$search_value%')";
        }
        if (isset($_POST['order']['0']['column'])) {
            $order_column = $_POST['order']['0']['column'];
            $order_direction = $_POST['order']['0']['dir'];

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
        return $this->db->count_all_results('users_mapel_kelas');
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

        return json_encode($response);
    }
}