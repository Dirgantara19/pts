<?php


class Nilai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/nilai');
        }
        $this->load->model('M_Nilai');
    }



    public function ajax_list()
    {
        echo $this->M_Nilai->ajax_list();
    }
    public function get_id()
    {
        echo $this->M_Nilai->get_by_id();
    }


    public function save()
    {
        $data = array();
        $mapel = $this->db->get('mapel')->result();
        $nis = $this->input->post('nis', true);

        foreach ($mapel as $item) {

            $this->form_validation->set_rules($item->slug, $item->nama, 'numeric');

            $data[] = [
                'siswa_nis' => $nis,
                'nilai' => $this->input->post($item->slug, true),
                'mapel_id' => $item->id
            ];
        }

        if ($this->form_validation->run() == false) {
            $errors = validation_errors();

            $response = ['errors' => $errors];
        } else {
            if ($nis) {
                $this->db->where('siswa_nis', $nis);
                $this->db->update_batch('raport', $data, 'mapel_id');

                $response = ['success' => 'Success: Data update!'];
            } else {
                $response = ['errors' => 'Error: Nis doesnt exist'];
            }
        }


        echo json_encode($response);
    }
}