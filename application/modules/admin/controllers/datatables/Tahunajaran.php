<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tahunajaran extends MY_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/tahunajaran');
        }
        $this->load->model('Crud_model', 'crud');
        $this->load->model('M_Tahunajaran');
        $this->table = "tahun";
        $this->table_relasi = "raport";
        $this->title_toastr = 'Mapel Information';
    }

    public function ajax_list()
    {
        echo $this->M_Tahunajaran->ajax_list();
    }

    public function get_id()
    {

        $id = $this->input->post('id', true);
        $data = $this->crud->table($this->table)->get_by_key(['id' => $id]);
        echo json_encode($data);
    }

    public function save()
    {
        $data = $this->input->post();
        $id = $this->input->post('id', true);

        $this->form_validation->set_rules('thn_ajaran', 'thn_ajaran', 'required');
        $this->form_validation->set_rules('semester', 'semester', 'required');
        if (empty($id)) {
            $this->form_validation->set_rules('semester', 'semester', 'required|is_unique[tahun.semester]');
        }

        $status = [];

        if ($this->form_validation->run() != false) {
            $this->db->trans_start();
            try {
                if (!empty($id)) {
                    $this->crud->table($this->table)->update($data, ['id' => $id]);
                    $status = ['success' => 'Success: Data updated!'];
                } else {
                    $this->crud->table($this->table)->insert($data);
                    $status = ['success' => 'Data inserted!'];
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $status = ['error' => 'Transaction failed!'];
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $status = ['error' => 'An error occurred: ' . $e->getMessage()];
            }
        } else {
            $errors = validation_errors();
            $status = ['errors' => $errors];
        }

        echo json_encode($status);
    }



    public function delete()
    {
        $id = $this->input->post('id', true);

        $this->db->trans_start();

        $status = ['error' => 'Error: Data not deleted!'];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->delete($this->table);

            $this->db->where('tahun_id', $id);
            $this->db->delete($this->table_relasi);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = ['error' => 'Error: Data not deleted due to a transaction error!'];
        } else {
            $status = ['success' => 'Success: Data deleted!'];
        }


        echo json_encode($status);
    }

    public function bulk_delete()
    {
        $array_id = $this->input->post('array_id');

        $this->db->trans_start();

        $status = ['error' => 'Error: Data not deleted!'];

        if (!empty($array_id)) {
            $this->db->where_in('id', $array_id);
            $this->db->delete($this->table);

            $this->db->where_in('tahun_id', $array_id);
            $this->db->delete($this->table_relasi);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = ['error' => 'Error: Data not deleted due to a transaction error!'];
        } else {
            $status = ['success' => 'Success: Data deleted!'];
        }

        echo json_encode($status);
    }
}
