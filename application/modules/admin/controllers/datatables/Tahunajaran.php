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
        $this->form_validation->set_rules('semester', 'semester', 'required|is_unique[tahun.semester]');

        if ($this->form_validation->run() != false) {


            if (!empty($id)) {
                $this->crud->table($this->table)->update($data, ['id' => $id]);
                $status = ['success' => 'Success : Data updated!'];
            } else {
                $this->crud->table($this->table)->insert($data);
                $status = ['success' => 'Data inserted!'];
            }
        } else {
            $errors = validation_errors();
            $status = ["errors" => $errors];
        }
        echo json_encode($status);
    }


    public function delete()
    {
        $id = $this->input->post('id', true);
        $mapel = $this->crud->table($this->table)->get_by_key(['id' => $id]);
        $raport = $this->crud->table($this->table_relasi)->get_by_key_with_result(['tahun_id' => $id]);


        if ($mapel) {

            if (!empty($raport)) {
                foreach ($raport as $data) {
                    $this->crud->table($this->table_relasi)->delete(['tahun_id' => $data->tahun_id]);
                }
            }
            $this->crud->table($this->table)->delete(['id' => $id]);
            $status = ['success' => 'Success: Data deleted!'];
        } else {
            $status = ['error' => 'Error: Data not deleted!'];
        }
        echo json_encode($status);
    }

    public function bulk_delete()
    {
        $array_id = $this->input->post('array_id');
        foreach ($array_id as $id) {
            $tahun[] = $this->crud->table($this->table)->get_by_key(['id' => $id]);
            $raport[] = $this->crud->table($this->table_relasi)->get_by_key_with_result(['tahun_id' => $id]);

            if ($tahun) {
                if (!empty($raport)) {
                    foreach ($raport as $subarray) {
                        foreach ($subarray as $data) {
                            $this->crud->table($this->table_relasi)->delete(['tahun_id' => $data->tahun_id]);
                        }
                    }
                }
                foreach ($tahun as $data) {
                    $this->crud->table($this->table)->delete(['id' => $data->id]);
                }
                $status = ['success' => 'Success: Data deleted!'];
            } else {
                $status = ['error' => 'Error: Data not deleted!'];
            }
        }
        echo json_encode($status);
    }
}
