<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Gurupengampu extends MY_Controller
{
    protected $table_ion_auth;
    protected $table;
    protected $query;
    protected $column_search;
    protected $column_order;
    protected $order;

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/gurupengampu');
        }
        $this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
        $this->table = $this->table_ion_auth['users_mapel_kelas'];

        $this->load->model('Crud_model', 'crud');
        $this->load->model('admin/M_Gurupengampu');
    }

    public function ajax_list()
    {
        echo $this->M_Gurupengampu->ajax_list();
    }


    public function get_id()
    {
        $id = $this->input->post('id');
        $data = $this->crud->table($this->table)->get_by_key(['id' => $id]);
        echo json_encode($data);
    }

    public function save()
    {

        $id = $this->input->post('id', true);

        $this->form_validation->set_rules('user_id', 'Guru Pengampu', 'required|numeric');
        $this->form_validation->set_rules('kelas_id', 'Kelas', 'required');
        $this->form_validation->set_rules('mapel_id', 'Mapel', 'required');
        if ($this->form_validation->run() != false) {

            $data = $this->input->post();


            if (!empty($id)) {
                $this->crud->table($this->table)->update($data, ['id' => $id]);
                $status = ['success' => 'Data berhasil diubah!'];
            } else {
                $this->crud->table($this->table)->insert($data);
                $status = ['success' => 'Data berhasil ditambah!'];
            }
        } else {
            $error = validation_errors();
            $status = ["errors" => $error];
        }
        echo json_encode($status);
    }


    public function import()
    {
        $file = $_FILES['filegurupengampu'];

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
                $defaultcol = 5;
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
                        'type' => 1, 'problem' => 'Ensure that the data has a minimum of ' . $defaultcol . ' columns.',
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
                            $nip_or_nik = $sheetdata[$i][2];
                            $mapel = $sheetdata[$i][3];
                            $kelas = $sheetdata[$i][4];

                            $mapel_slug = url_title($mapel, 'dash', true);
                            $kelas_slug = url_title($kelas, 'dash', true);

                            $kelas_row = $this->crud->table('kelas')->get_by_key(['slug' => $kelas_slug]);
                            $kelas_id = $kelas_row->id;

                            $mapel_row = $this->crud->table('mapel')->get_by_key(['slug' => $mapel_slug]);
                            $mapel_id = $mapel_row->id;

                            $user_row = $this->crud->table('users')->get_by_key(['nip_or_nik' => $nip_or_nik]);
                            $user_id = $user_row->id;


                            $data[] = array(
                                'user_id'     => $user_id,
                                'mapel_id'     => $mapel_id,
                                'kelas_id' => $kelas_id,
                            );
                        }

                        if (!empty($data)) {
                            $existingData = $this->checkForDuplicates($data);

                            if (!empty($existingData)) {
                                $response = [
                                    'error' => 'Error: Some data already exist.',
                                    'type' => 4,
                                    'problem' => 'Some data already exist.',
                                    'solution' => $existingData,
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


    private function checkForDuplicates($data)
    {
        $existingData = [];

        foreach ($data as $row) {
            $user_id = $row['user_id'];
            $mapel_id = $row['mapel_id'];
            $kelas_id = $row['kelas_id'];

            $query = $this->db->where('user_id', $user_id)
                ->where('mapel_id', $mapel_id)
                ->where('kelas_id', $kelas_id)
                ->get($this->table);

            if ($query->num_rows() > 0) {
                $existingData[] = $row;
            }
        }

        return $existingData;
    }






    public function delete()
    {
        $id = $this->input->post('id', true);

        $this->db->trans_start();

        $status = ['error' => 'Error: Data not deleted!'];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->delete($this->table);

            $deleted_rows = $this->db->affected_rows();

            if ($deleted_rows > 0) {
                $status = ['success' => 'Success: Data deleted!'];
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = ['error' => 'Error: Data not deleted due to a transaction error!'];
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
