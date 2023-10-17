<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Siswa extends MY_Controller
{

    public $table_ion_auth;
    public $table;
    public $table_relasi1;
    public $table_relasi2;


    public function __construct()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('admin/siswa');
        }

        parent::__construct();
        $this->load->model('Crud_model', 'crud');
        $this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
        $this->table = $this->table_ion_auth['siswa'];
        $this->table_relasi1 = $this->table_ion_auth['kelas_siswa'];
        $this->table_relasi2 = $this->table_ion_auth['raport'];

        $this->load->model('M_Siswa');
    }



    public function ajax_list()
    {

        echo $this->M_Siswa->ajax_list();
    }

    public function get_id()
    {

        $nis = $this->input->post('nis', true);

        $data = $this->M_Siswa->get_by_nis($nis);
        echo json_encode($data);
    }

    public function save()
    {

        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);

        $this->form_validation->set_rules('nama', 'nama', 'required');
        $this->form_validation->set_rules('kelas_id', 'kelas_id', 'required');
        if ($id) {
            $this->form_validation->set_rules('nis', 'nis', 'required');
        } else {
            $this->form_validation->set_rules('nis', 'nis', 'required|is_unique[siswa.nis]');
        }


        if ($this->form_validation->run() != false) {

            $datasiswa = [
                'nama' => $this->input->post('nama', true),
                'nis' => $nis,
            ];


            if (!empty($id)) {
                $siswa = $this->crud->table($this->table)->get_by_key(['id' => $id]);

                if ($siswa) {
                    $id_table_relasi1 = $this->crud->table($this->table_relasi1)->get_by_key(['siswa_nis' => $siswa->nis]);
                    $id_table_relasi2 = $this->crud->table($this->table_relasi2)->get_by_key_with_result(['siswa_nis' => $siswa->nis]);

                    if ($id_table_relasi1) {


                        $datakelas_siswa = [
                            'kelas_id' => $this->input->post('kelas_id'),
                            'siswa_nis' => $datasiswa['nis']
                        ];


                        $this->crud->table($this->table_relasi1)->update($datakelas_siswa, ['siswa_nis' => $siswa->nis]);
                    }

                    if ($id_table_relasi2) {


                        $raport = [];

                        foreach ($id_table_relasi2 as $row) {
                            $raport[] = [
                                'id' => $row->id,
                                'siswa_nis' => $datasiswa['nis'],
                                'kelas_id' => $this->input->post('kelas_id'),
                            ];
                        }


                        $this->crud->table($this->table_relasi2)->update_batch($raport, 'id');
                    }
                    $this->crud->table($this->table)->update($datasiswa, ['id' => $siswa->id]);
                }





                $response = $this->__response('success', 'Success: Data Updated!');
            } else {
                $siswa = $this->crud->table($this->table)->insert($datasiswa);
                if ($siswa) {

                    $datakelas_siswa = [
                        'kelas_id' => $this->input->post('kelas_id'),
                        'siswa_nis' => $datasiswa['nis']
                    ];

                    $this->crud->table($this->table_relasi1)->insert($datakelas_siswa);
                }

                $response = $this->__response('success', 'Success: Data insert!');
            }
        } else {
            $errors = validation_errors();
            $response = ["errors" => $errors];
        }
        echo json_encode($response);
    }
    public function delete()
    {
        $nis = $this->input->post('nis', true);

        $this->db->trans_start();

        $response = ['error' => 'Error: Data not deleted!'];

        if ($nis) {
            $this->db->where('siswa_nis', $nis);
            $this->db->delete($this->table_relasi2);

            $this->db->where('siswa_nis', $nis);
            $this->db->delete($this->table_relasi1);

            $this->db->where('nis', $nis);
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
        $array_nis = $this->input->post('array_nis');

        $this->db->trans_start();

        $response = ['error' => 'Error: Data cannot be deleted!'];

        if (!empty($array_nis)) {

            $this->db->where_in('siswa_nis', $array_nis);
            $this->db->delete($this->table_relasi2);

            $this->db->where_in('siswa_nis', $array_nis);
            $this->db->delete($this->table_relasi1);

            $this->db->where_in('nis', $array_nis);
            $this->db->delete($this->table);
        }


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = $this->__response('error', 'Error: Data not deleted due to a transaction error!');
        } else {
            $response = $this->__response('success', 'Success: Data deleted!');
        }

        echo json_encode($response);
    }


    public function import()
    {
        $file = $_FILES['filesiswa'];

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
                $defaultcol = 4;
                $nullcell = [];
                $col_eliminate = [];
                $datasiswa = [];
                $datarelasi = [];

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

                            $nis = $sheetdata[$i][1];
                            $nama = $sheetdata[$i][2];
                            $kelas = $sheetdata[$i][3];

                            $slug_kelas = url_title($kelas, 'dash', true);

                            $query_kelas = $this->crud->table('kelas')->get_by_key(['slug' => $slug_kelas]);


                            $datasiswa[] = array(
                                'nis' => $nis,
                                'nama' => $nama,
                            );

                            $datarelasi[] = [
                                'kelas_slug' => $query_kelas->slug,
                                'siswa_nis' => $nis,
                            ];
                        }


                        if (!empty($datasiswa) && !empty($datarelasi)) {


                            $kelas_data = $this->crud->table('kelas')->get_all();
                            $kelas_id = array_column($kelas_data, 'id');
                            $kelasNotExists = false;

                            $siswa = $this->crud->table($this->table)->get_all();
                            $siswa_nis = array_column($siswa, 'nis');
                            $existing_data = [];
                            $dataAlreadyExists = false;

                            foreach ($datasiswa as $siswa) {
                                if (in_array($siswa['nis'], $siswa_nis)) {
                                    $dataAlreadyExists = true;
                                    $existing_data[] = ['exists_data' => $siswa['nis']];
                                }
                            }

                            foreach ($datarelasi as $kelas) {
                                if (in_array($kelas['id'], $kelas_id)) {
                                    $kelasNotExists = true;
                                    $existing_data[] = ['exists_data' => $kelas];
                                }
                            }

                            if ($dataAlreadyExists) {
                                $response = [
                                    'error' => 'Error: Some data already exist.',
                                    'type' => 4,
                                    'problem' => 'Some data already exist.',
                                    'solution' => $existing_data,
                                ];
                            } else if ($kelasNotExists) {

                                $response = [
                                    'error' => 'Error: Some data kelas not exist.',
                                    'problem' => 'Some data kelas not exist.',
                                ];
                            } else {
                                $this->db->insert_batch($this->table, $datasiswa);
                                $this->db->insert_batch($this->table_relasi1, $datarelasi);
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
