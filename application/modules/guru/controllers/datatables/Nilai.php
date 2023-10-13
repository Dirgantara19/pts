<?php


class Nilai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('guru/nilai');
        }
        $this->load->model('M_Nilai');
    }



    public function ajax_list()
    {
        echo $this->M_Nilai->ajax_list();
    }



    public function get_id()
    {

        $id = $this->input->post('id');
        $this->db->select('*');
        $this->db->from('raport as a');
        $this->db->join('siswa as b', 'a.siswa_nis = b.nis');
        $this->db->join('users as c', 'a.user_id = c.id');
        $this->db->join('kelas as d', 'a.kelas_id = d.id');
        $this->db->join('mapel as e', 'a.mapel_id = e.id');
        $this->db->join('tahun as f', 'a.tahun_id = f.id');
        $this->db->where('a.id', $id);

        $data = $this->db->get()->row();

        echo json_encode($data);
    }

    public function update_nilai_by_id()
    {
        $id = $this->input->post('id');
        $nilai = $this->input->post('nilai');

        if ($id) {

            $this->crud->table('raport')->update(['nilai' => $nilai], ['id' => $id]);
            $response = ['success' => 'Success: Data updated!'];
        } else {
            $response = ['error' => 'Error: Data failed updated!'];
        }

        echo json_encode($response);
    }
}