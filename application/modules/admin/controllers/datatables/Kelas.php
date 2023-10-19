<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Kelas extends MY_Controller
{
    protected $table_ion_auth;
    protected $table;
    protected $table_relasi;

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/kelas');
        }
        $this->load->model('Crud_model', 'crud');
        $this->load->model('M_Kelas');
        $this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
        $this->table = $this->table_ion_auth['kelas'];
        $this->table_relasi1 = $this->table_ion_auth['kelas_siswa'];
        $this->table_relasi2 = $this->table_ion_auth['users_mapel_kelas'];
        $this->table_relasi3 = $this->table_ion_auth['raport'];
        $this->table_relasi4 = $this->table_ion_auth['siswa'];
    }


    public function ajax_list()
    {
        echo $this->M_Kelas->ajax_list();
    }


    public function get_id()
    {
        $id = $this->input->post('id', true);
        $data = $this->db->get_where($this->table, ['id' => $id])->row();
        echo json_encode($data);
    }

    public function save()
    {

        $data = $this->input->post();

        $id = $this->input->post('id', true);

        $data['slug'] = url_title($this->input->post('kelas'), 'dash', true);
        if (!empty($id)) {
            $this->form_validation->set_rules('kelas', 'Kelas', 'required');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        } else {
            $this->form_validation->set_rules('kelas', 'Kelas', 'required|is_unique[kelas.kelas]');
            $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        }
        if ($this->form_validation->run() != false) {


            if (!empty($id)) {

                $this->crud->table($this->table)->update($data, ['id' => $id]);
                $status = ['success' => 'Data berhasil diubah!'];
            } else {
                $this->crud->table($this->table)->insert($data);
                $status = ['success' => 'Data berhasil ditambah!'];
            }
        } else {
            $error = validation_errors();
            $status = ['errors' => $error];
        }
        echo json_encode($status);
    }


    public function delete()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();

        $response = ['error' => 'Error: Data not deleted!'];

        if ($id) {
            $this->db->where('kelas_id', $id);
            $this->db->delete($this->table_relasi1);

            $this->db->where('kelas_id', $id);
            $this->db->delete($this->table_relasi2);

            $this->db->where('kelas_id', $id);
            $this->db->delete($this->table_relasi3);

            $this->db->where('id', $id);
            $this->db->delete($this->table);

            $response = ['success' => 'Success: Data deleted!'];
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['error' => 'Error: Data not deleted due to a transaction error!'];
        } else {
            $response = ['success' => 'Success: Data deleted!'];
        }

        echo json_encode($response);
    }


    public function bulk_delete()
    {
        $array_id = $this->input->post('array_id');
        $this->db->trans_start();

        $response = ['error' => 'Error: Data cannot be deleted!'];

        if (!empty($array_id)) {

            $this->db->where_in('id', $array_id);
            $this->db->delete($this->table);

            $this->db->where_in('kelas_id', $array_id);
            $this->db->delete($this->table_relasi1);

            $this->db->where_in('kelas_id', $array_id);
            $this->db->delete($this->table_relasi2);

            $this->db->where_in('kelas_id', $array_id);
            $this->db->delete($this->table_relasi3);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['error' => 'Error: Data not deleted due to a transaction error!'];
        } else {
            $response = ['success' => 'Success: Data deleted!'];
        }

        echo json_encode($response);
    }




    public function import()
    {
        $file = $_FILES['filekelas'];

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
                        'type' => 1, 'problem' => 'Ensure that the data has a minimum of ' . $defaultcol . 'c olumns.',
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
                        'type' => 3, 'problem' => 'Ensure that the data has a minimum of ' . $defaultcol . ' columns.',
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

                            $kelas = $sheetdata[$i][1];
                            $jurusan = $sheetdata[$i][2];

                            if (!empty($kelas) && !empty($jurusan)) {
                                $data[] = array(
                                    'slug' => url_title($kelas, 'dash', TRUE),
                                    'kelas' => $kelas,
                                    'jurusan' => $jurusan,
                                );
                            }
                        }


                        if (!empty($data)) {
                            $kelas = $this->crud->table($this->table)->get_all();
                            $kelas_slug = array_column($kelas, 'slug');
                            $existing_data = [];
                            $dataAlreadyExists = false;

                            foreach ($data as $kelas) {
                                if (in_array($kelas['slug'], $kelas_slug)) {
                                    $dataAlreadyExists = true;
                                    $existing_data[] = ['exists_data' => $kelas['kelas']];
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
