<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Mapel extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/mapel');
        }
        $this->load->model('Crud_model', 'crud');
        $this->load->model('M_Mapel');
        $this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
        $this->table = $this->table_ion_auth['mapel'];
        $this->table_relasi1 = $this->table_ion_auth['users_mapel_kelas'];
        $this->table_relasi2 = $this->table_ion_auth['raport'];

        $this->title_toastr = 'Mapel Information';
    }

    public function ajax_list()
    {
        echo $this->M_Mapel->ajax_list();
    }

    public function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->crud->table($this->table)->get_by_key(['id' => $id]);

        echo json_encode($result);
    }

    public function save()
    {
        $data = $this->input->post();
        $data['slug'] = url_title($this->input->post('nama'), 'dash', TRUE);


        $id = $this->input->post('id', true);
        if (empty($id)) {
            $this->form_validation->set_rules('nama', 'nama', 'required|is_unique[mapel.nama]');
            $this->form_validation->set_rules('sing', 'sing', 'required|is_unique[mapel.sing]');
        } else {
            $this->form_validation->set_rules('nama', 'nama', 'required');
            $this->form_validation->set_rules('sing', 'sing', 'required');
        }

        if ($this->form_validation->run() != false) {
            if (!empty($id)) {
                $this->crud->table($this->table)->update($data, ['id' => $id]);
                $status = ['success' => 'Success: Data update!'];
            } else {
                $this->crud->table($this->table)->insert($data);
                $status = ['success' => 'Success: Data insert!'];
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
        $users_mapel_kelas = $this->crud->table($this->table_relasi1)->get_by_key_with_result(['mapel_id' => $id]);
        $raport = $this->crud->table($this->table_relasi2)->get_by_key_with_result(['mapel_id' => $id]);

        if (!empty($mapel)) {
            if (!empty($users_mapel_kelas)) {
                foreach ($users_mapel_kelas as $data) {
                    $this->crud->table($this->table_relasi1)->delete(['mapel_id' => $data->mapel_id]);
                }
            }
            if (!empty($raport)) {
                foreach ($raport as $data) {
                    $this->crud->table($this->table_relasi2)->delete(['mapel_id' => $data->mapel_id]);
                }
            }
            $this->crud->table($this->table)->delete(['id' => $id]);
            $response = ['success' => 'Success: Data deleted!'];
        } else {
            $response = ['error' => 'Error: Data cant deleted!'];
        }
        echo json_encode($response);
    }

    public function bulk_delete()
    {
        $array_id = $this->input->post('array_id');
        foreach ($array_id as $id) {
            $mapel[] = $this->crud->table($this->table)->get_by_key(['id' => $id]);
            $users_mapel_kelas[] = $this->crud->table($this->table_relasi1)->get_by_key(['mapel_id' => $id]);
            $raport[] = $this->crud->table($this->table_relasi2)->get_by_key_with_result(['mapel_id' => $id]);

            if (!empty($mapel)) {
                if (!empty($users_mapel_kelas)) {
                    foreach ($users_mapel_kelas as $data) {
                        $this->crud->table($this->table_relasi1)->delete(['mapel_id' => $data->mapel_id]);
                    }
                }

                if (!empty($raport)) {
                    foreach ($raport as $subarray) {
                        foreach ($subarray as $data) {
                            $this->crud->table($this->table_relasi2)->delete(['mapel_id' => $data->mapel_id]);
                        }
                    }
                }

                $this->crud->table($this->table)->delete(['id' => $data->id]);

                $response = ['success' => 'Success : Data deleted!'];
            } else {
                $response = ['error' => 'Error: Data cant deleted!'];
            }
        }

        echo json_encode($response);
    }

    public function import()
    {
        $file = $_FILES['filemapel'];

        if (isset($file['name']) && !empty($file['name']) && $file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            if ($ext !== 'xls' && $ext !== 'xlsx' && $ext !== 'csv') {
                $response = ['error' => 'Invalid file format .' . $ext . '. Only .xls, .xlsx, or .csv format is supported.'];
            } else {

                $spreadsheet = new Spreadsheet();

                if ('xls' == $ext) {
                    $reader = IOFactory::createReader('Xls');
                } else if ('xlsx' == $ext) {
                    $reader = IOFactory::createReader('Xlsx');
                } else {
                    $reader = IOFactory::createReader('Csv');
                }
                $spreadsheet = $reader->load($file['tmp_name']);
                $sheetdata = $spreadsheet->getActiveSheet()->toArray();
                $defaultcol = 3;
                $nullcell = [];
                $col_eliminate = [];
                $data = [];

                foreach ($sheetdata as $index => $cell) {
                    $cellcount = count($cell);
                    foreach ($cell as $colIndex => $cellValue) {
                        if ($colIndex >= $defaultcol) {
                            $columnLetter = chr($colIndex + 65);

                            $col_eliminate[] = ['column' => $columnLetter, 'row' => $index + 1];
                        } else if (is_null($cellValue)) {
                            $columnLetter = chr($colIndex + 65);
                            $nullcell[] = ['column' => $columnLetter, 'row' => $index + 1];
                        }
                    }
                }
                if ($cellcount > $defaultcol) {

                    $response = [
                        'error' => 'Error: Data is not in correct format.',
                        'type' => 1, 'problem' => 'Ensure that the data has a minimum of' . $defaultcol . 'columns.',
                        'solution' => $col_eliminate
                    ];
                } else if ($cellcount < $defaultcol) {

                    $col_diff = $defaultcol - $cellcount;

                    $col_fit = [];
                    for ($i = 0; $i < $col_diff; $i++) {
                        $columnLetter = chr($cellcount + $i + 65);
                        $col_fit[] = ['column' => $columnLetter];
                    }

                    $response = [
                        'error' => 'Error: Data is not in correct format.',
                        'type' => 3, 'problem' => 'Ensure that the data has a minimum of' . $defaultcol . 'columns.',
                        'solution' => $col_fit
                    ];
                } else {
                    if (!empty($nullcell)) {
                        $response = [
                            'error' => 'Error: Data contains null value.',
                            'type' => 2, 'problem' => 'Data null found',
                            'solution' => $nullcell
                        ];
                    } else {
                        for ($i = 1; $i < count($sheetdata); $i++) {

                            $sing = $sheetdata[$i][1];
                            $nama = $sheetdata[$i][2];

                            if (!empty($sing) && !empty($nama)) {
                                $data[] = array(
                                    'slug' => url_title($nama, 'dash', TRUE),
                                    'sing' => $sing,
                                    'nama' => $nama,
                                );
                            }
                        }


                        if (!empty($data)) {
                            $mapel = $this->crud->table($this->table)->get_all();
                            $mapel_slug = array_column($mapel, 'slug');
                            $existing_data = [];
                            $dataAlreadyExists = false;

                            foreach ($data as $mapel) {
                                if (in_array($mapel['slug'], $mapel_slug)) {
                                    $dataAlreadyExists = true;
                                    $existing_data[] = ['exists_data' => $mapel['nama']];
                                }
                            }

                            if ($dataAlreadyExists) {
                                $response = [
                                    'error' => 'Error: Some data already exist.',
                                    'type' => 4,
                                    'problem' => 'Some data already exist.',
                                    'solution' => $existing_data,
                                ];
                            } else {
                                $this->db->insert_batch($this->table, $data);
                                $response = ['success' => 'Success: Data record!.'];
                            }
                        } else {
                            $response = ['error' => 'Error: Data is empty.'];
                        }
                    }
                }
            }
        } else {
            $response = ['error' => 'Error: File not uploaded.'];
        }



        echo json_encode($response);
    }
}
