<?php


class M_Nilai extends CI_Model
{


    protected $column_search;
    protected $column_order;
    protected $order;


    public function __construct()
    {

        $this->column_search = [
            'c.full_name',
            'b.nama',
            'b.nis',
            'd.kelas',
            'd.jurusan',
        ];
        $this->column_order = [
            null,
            'c.full_name',
            'b.nama',
            'b.nis',
            'd.kelas'
        ];
        $this->order = ['c.full_name' => 'asc'];
    }

    public function get_sql()
    {
        $kelasid = $this->input->post('kelasid');
        $mapelid = $this->input->post('mapelid');
        $this->db->select('*, a.id as raport_id, b.nama as nama_siswa');
        $this->db->from('raport as a');
        $this->db->join('siswa as b', 'a.siswa_nis = b.nis');
        $this->db->join('users as c', 'a.user_id = c.id');
        $this->db->join('kelas as d', 'a.kelas_id = d.id');
        $this->db->join('mapel as e', 'a.mapel_id = e.id');
        $this->db->join('tahun as f', 'a.tahun_id = f.id');
        $this->db->where('a.kelas_id', $kelasid);
        $this->db->where('a.mapel_id', $mapelid);
        $this->db->group_by('a.siswa_nis');


        return $this;
    }


    private function search_order()
    {
        if (isset($this->column_search)) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($_POST['search']['value']) {
                    if ($i === 0) {
                        $this->db->group_start();
                        $this->db->like($item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                    if (count($this->column_search) - 1 == $i) {
                        $this->db->group_end();
                    }
                }
                $i++;
            }
        }
        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        return $this;
    }
    public function ajax_list()
    {

        $this->get_sql();
        $this->search_order();
        if ($_POST['length'] != -1) {

            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

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
    public function recordsFiltered()
    {

        $this->get_sql();
        $this->search_order();

        $recordsFil = $this->db->get()->num_rows();

        return $recordsFil;
    }

    public function recordsTotal()
    {

        $this->get_sql();

        $recordsTotal = $this->db->get()->num_rows();

        return $recordsTotal;
    }
}
