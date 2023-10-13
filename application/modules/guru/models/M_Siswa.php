<?php

class M_Siswa extends CI_Model
{
    protected $column_search;
    protected $column_order;
    protected $order;

    public function __construct()
    {
        $this->column_search = [
            'a.full_name',
            'si.nama',
            'si.nis',
            'ke.kelas',
            'ke.jurusan',
        ];
        $this->column_order = [
            null,
            'a.full_name',
            'si.nama',
            'si.nis',
            'ke.kelas'
        ];
        $this->order = ['a.full_name' => 'asc'];
    }



    public function get_sql()
    {
        $kelasid = $this->input->post('kelasid') ?? 0;
        $mapelid = $this->input->post('mapelid') ?? 0;

        $sql = "SELECT *, si.nama AS nama_siswa
                    FROM users_mapel_kelas AS umk
                    JOIN users AS a ON umk.user_id = a.id
                    JOIN mapel AS ma ON umk.mapel_id = ma.id
                    JOIN kelas AS ke ON umk.kelas_id = ke.id
                    JOIN kelas_siswa AS kesi ON ke.id = kesi.kelas_id
                    JOIN siswa AS si ON kesi.siswa_nis = si.nis
                    WHERE ma.id = ? AND ke.id = ?";

        return ['sql' => $sql, 'kelasid' => $kelasid, 'mapelid' => $mapelid];
    }




    private function search_order($sql)
    {

        if (isset($this->column_search)) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($_POST['search']['value']) {
                    if ($i === 0) {
                        $sql .= " AND ( $item LIKE '%" . $_POST['search']['value'] . "%' ";
                    } else {
                        $sql .= " OR $item LIKE '%" . $_POST['search']['value'] . "%' ";
                    }
                    if (count($this->column_search) - 1 == $i) {
                        $sql .= ")";
                    }
                }
                $i++;
            }
        }
        if (isset($_POST['order'])) {
            $sql .= " ORDER BY " . $this->column_order[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
        } elseif (isset($this->order)) {
            $order = $this->order;
            $sql .= " ORDER BY " . key($order) . " " . $order[key($order)];
        }

        return $sql;
    }

    public function ajax_list()
    {
        $sqlData = $this->get_sql();
        $sql = $this->search_order($sqlData['sql']);

        if ($_POST['length'] != -1) {
            $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
        }

        $query = $this->db->query($sql, array($sqlData['mapelid'], $sqlData['kelasid']));
        $data = $query->result();

        $recordsFil = $this->recordsFiltered();
        $recordsTotal = $this->recordsTotal();

        $response = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFil,
            'data' => $data
        );

        echo json_encode($response);
    }

    private function recordsFiltered()
    {
        $sqlData = $this->get_sql();
        $sql = $this->search_order($sqlData['sql']);
        $query = $this->db->query($sql, array($sqlData['mapelid'], $sqlData['kelasid']));
        $recordsFil = $query->num_rows();

        return $recordsFil;
    }

    private function recordsTotal()
    {
        $sqlData = $this->get_sql();
        $query = $this->db->query($sqlData['sql'], array($sqlData['mapelid'], $sqlData['kelasid']));
        $recordsTotal = $query->num_rows();

        return $recordsTotal;
    }
}
