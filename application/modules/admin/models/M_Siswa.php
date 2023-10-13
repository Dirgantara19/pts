<?php
class M_Siswa extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_sql()
    {
        $sql = "SELECT * FROM `siswa` as `a`
        JOIN `kelas_siswa` as `b` ON `a`.`nis` = `b`.`siswa_nis`
        JOIN `kelas` as `c` ON `b`.`kelas_id` = `c`.`id`
        ";



        return $sql;
    }

    public function get_by_nis($nis)
    {

        $query = "SELECT *
        FROM `kelas` as `a`
        JOIN `kelas_siswa` as `b` ON `a`.`id` = `b`.`kelas_id`
        JOIN `siswa` as `c` ON `b`.`siswa_nis` = `c`.`nis`
        WHERE `c`.`nis` = $nis
        ";


        $data = $this->db->query($query)->row();

        return $data;
    }

    private function get_query()
    {
        $sql = $this->get_sql();
        $columns_search = array(
            'nama',
            'nis',
            'kelas',
        );
        $columns_order = array(
            null,
            null,
            'nama',
            'nis',
            'kelas',
        );





        if (isset($_POST['search']['value'])) {
            $search_value = $_POST['search']['value'];
            $sql .= " WHERE ";
            for ($i = 0; $i < count($columns_search); $i++) {
                $sql .= "$columns_search[$i] LIKE '%$search_value%'";
                if ($i < count($columns_search) - 1) {
                    $sql .= " OR ";
                }
            }
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
        $data = $this->db->count_all_results('kelas_siswa');
        return $data;
    }

    public function ajax_list()
    {
        $sql = $this->get_query();
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
        }
        $data = $this->db->query($sql)->result();
        $view_data = [];
        $no = 1;
        foreach ($data as $row) {
            $checkbox_trig = '<input class="check-all" onchange="bulkdeletetoggle()"type="checkbox" value="' . $row->nis . '" >';

            $action_buttons = '<a class="btn btn-sm btn-outline-danger mr-1" onclick="hapus(' .
                $row->nis . ')">Delete</a>'
                . '<a class="btn btn-sm btn-outline-success" onclick="edit(' .
                $row->nis . ')">Edit</a>';

            $view_data[] = [
                'checkbox_trig' => $checkbox_trig,
                'no' => $no++,
                'nama' => $row->nama,
                'nis' => $row->nis,
                'kelas' => $row->kelas,
                'action_buttons' => $action_buttons,

            ];
        };
        $response = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->recordsTotal(),
            'recordsFiltered' => $this->recordsFiltered(),
            'data' => $view_data,
        );

        return json_encode($response);
    }
}