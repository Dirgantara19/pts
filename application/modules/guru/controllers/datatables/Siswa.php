<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Siswa extends MY_Controller
{

    protected $table_ion_auth;
    protected $table;
    protected $table_relasi1;
    protected $table_relasi2;
    protected $title_toastr;


    public function __construct()
    {
        parent::__construct();
        // if (!$this->input->is_ajax_request()) {
        //     redirect('admin/siswa');
        // }

        $this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
        $this->table = $this->table_ion_auth['siswa'];
        $this->table_relasi1 = $this->table_ion_auth['kelas_siswa'];
        $this->table_relasi2 = $this->table_ion_auth['raport'];

        $this->title_toastr = 'Siswa Information';
        $this->load->model('M_Siswa');
    }


    public function ajax_list()
    {
        echo $this->M_Siswa->ajax_list();
    }

    public function export()
    {

        $mapelid = $this->input->post('mapelid');
        $kelasid = $this->input->post('kelasid');

        $tahunid = $this->input->post('tahunid');


        $tahun_id = $this->crud->table('tahun')->get_by_key(['id' => $tahunid]);


        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $style_primary = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => Border::BORDER_THIN],
                'right' => ['borderStyle'  => Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => Border::BORDER_THIN],
                'left' => ['borderStyle'  => Border::BORDER_THIN]
            ]
        ];
        $style_secondary = [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => Border::BORDER_THIN],
                'right' => ['borderStyle'  => Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => Border::BORDER_THIN],
                'left' => ['borderStyle'  => Border::BORDER_THIN]
            ]
        ];

        $style_third = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => Border::BORDER_THIN],
                'right' => ['borderStyle'  => Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => Border::BORDER_THIN],
                'left' => ['borderStyle'  => Border::BORDER_THIN]
            ]
        ];


        $columns = ['NO', 'GURU PENGAMPU', 'NIP/NIK', 'NIS', 'NAMA', 'KELAS', 'MAPEL', 'NILAI', 'TAHUN AJARAN'];

        $columnIndex = 'A';
        foreach ($columns as $columnHeader) {
            $sheet->setCellValue($columnIndex . '1', $columnHeader);
            $sheet->getStyle($columnIndex . '1')->applyFromArray($style_primary);
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);


            $columnIndex++;
        }



        $id = $this->data['user_info']['id'];

        $sql = "SELECT 
                `b`.`full_name` as `guru`,
                `b`.`nip_or_nik` as `nip_or_nik`,
                `c`.`nama` as `mapel`,
                `d`.`kelas` as `kelas`,
                `f`.`nama` as `nama_siswa`,
                `f`.`nis` as `nis_siswa`
            FROM `users_mapel_kelas` as `a`
             JOIN `users` as `b` ON `a`.`user_id` = `b`.`id`
             JOIN `mapel` as `c` ON `a`.`mapel_id` = `c`.`id`
             JOIN `kelas` as `d` ON `a`.`kelas_id` = `d`.`id`
             JOIN `kelas_siswa` as `e` ON `d`.`id` = `e`.`kelas_id`
             JOIN `siswa` as `f` ON `e`.`siswa_nis` = `f`.`nis`
            WHERE `a`.`mapel_id` = ?
            AND `a`.`kelas_id` = ?
            ";

        if (!$this->ion_auth->is_admin() && !$this->ion_auth->is_programmer()) {
            $id = $this->data['user_info']['id'];
            $sql .= "AND `b`.`id` = $id";
        }
        $data = $this->db->query($sql, array($mapelid, $kelasid))->result();

        if ($data) {
            $no = 1;
            $numrow = 2;
            foreach ($data as $x) {
                $namefile = $x->kelas . '-' . $x->mapel;

                $sheet->setCellValue('A' . $numrow, $no);
                $sheet->setCellValue('B' . $numrow, $x->guru);
                $sheet->setCellValue('C' . $numrow, $x->nip_or_nik);
                $sheet->setCellValue('D' . $numrow, $x->nis_siswa);
                $sheet->setCellValue('E' . $numrow, $x->nama_siswa);
                $sheet->setCellValue('F' . $numrow, $x->kelas);
                $sheet->setCellValue('G' . $numrow, $x->mapel);
                $sheet->setCellValue('H' . $numrow, '');
                $sheet->setCellValue('I' . $numrow,  $tahun_id->thn_ajaran . " - " . $tahun_id->semester);


                $sheet->getStyle('A' . $numrow)->applyFromArray($style_primary);
                $sheet->getStyle('B' . $numrow)->applyFromArray($style_secondary);
                $sheet->getStyle('C' . $numrow)->applyFromArray($style_primary);
                $sheet->getStyle('D' . $numrow)->applyFromArray($style_primary);
                $sheet->getStyle('E' . $numrow)->applyFromArray($style_secondary);
                $sheet->getStyle('F' . $numrow)->applyFromArray($style_secondary);
                $sheet->getStyle('G' . $numrow)->applyFromArray($style_secondary);
                $sheet->getStyle('H' . $numrow)->applyFromArray($style_secondary);
                $sheet->getStyle('I' . $numrow)->applyFromArray($style_secondary);

                $no++;
                $numrow++;
            }

            ob_start();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $xlsxData = ob_get_contents();
            ob_end_clean();
            $response =  array(
                'status' => TRUE,
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsxData),
                'namefile' => 'siswa-' . url_title($namefile, 'dash', true)
            );
        } else {
            $response = ['status' => false, 'error' => 'Error: Failed to exported, cant find data'];
        }

        die(json_encode($response));
    }

    public function import()
    {
        $file = $_FILES['fileraport'];

        if (isset($file['name']) && !empty($file['name']) && $file['error'] === 0) {
            $spreadsheet = new Spreadsheet();
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);


            if ('xls' == $ext) {
                $reader = IOFactory::createReader('Xls');
            } else if ('xlsx' == $ext) {
                $reader = IOFactory::createReader('Xlsx');
            } else {
                $reader = IOFactory::createReader('Csv');
            }
            $spreadsheet = $reader->load($file['tmp_name']);
            $sheetdata = $spreadsheet->getActiveSheet()->toArray();

            $defaultcol = 9;
            $nullcell = [];
            $col_eliminate = [];
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
                    'type' => 1,
                    'problem' => 'Ensure that the data has a maximum of nine columns.',
                    'solution' => $col_eliminate
                ];
            } else {
                if (!empty($nullcell)) {
                    $response = [
                        'error' => 'Error: Data contains null value.',
                        'type' => 2,
                        'problem' => 'Data null found',
                        'solution' => $nullcell
                    ];
                } else {
                    $data = [];

                    for ($i = 1; $i < count($sheetdata); $i++) {
                        $nip_or_nik = $sheetdata[$i][2];
                        $nis = $sheetdata[$i][3];
                        $kelas = $sheetdata[$i][5];
                        $mapel = $sheetdata[$i][6];
                        $nilai = $sheetdata[$i][7];
                        $tahun = $sheetdata[$i][8];

                        $this->db->where('nip_or_nik', $nip_or_nik);
                        $user_query = $this->db->get('users');
                        $user_row = $user_query->row();
                        $user_id = ($user_row) ? $user_row->id : null;

                        $kelas_slug = url_title($kelas, 'dash', true);
                        $this->db->where('slug', $kelas_slug);
                        $kelas_query = $this->db->get('kelas');
                        $kelas_row = $kelas_query->row();
                        $kelas_id = ($kelas_row) ? $kelas_row->id : null;

                        $mapel_slug = url_title($mapel, 'dash', true);
                        $this->db->where('slug', $mapel_slug);
                        $mapel_query = $this->db->get('mapel');
                        $mapel_row = $mapel_query->row();
                        $mapel_id = ($mapel_row) ? $mapel_row->id : null;

                        list($tahun_ajaran, $semester) = explode(' - ', $tahun);

                        $this->db->where('semester', $semester);
                        $this->db->where('thn_ajaran', $tahun_ajaran);
                        $tahun_query = $this->db->get('tahun');
                        $tahun_row = $tahun_query->row();
                        $tahun_id = ($tahun_row) ? $tahun_row->id : null;


                        $data[] = array(
                            'user_id' => $user_id,
                            'siswa_nis' => $nis,
                            'kelas_id' => $kelas_id,
                            'mapel_id' => $mapel_id,
                            'nilai' => $nilai,
                            'tahun_id' => $tahun_id,
                        );
                    }

                    if (!empty($data)) {
                        $existingData = [];
                        foreach ($data as $row) {
                            $existingData[] = $this->db->get_where($this->table_relasi2, array(
                                'user_id' => $row['user_id'],
                                'siswa_nis' => $row['siswa_nis'],
                                'kelas_id' => $row['kelas_id'],
                                'mapel_id' => $row['mapel_id'],
                            ))->row();
                        }

                        if (!empty($existingData) && !in_array(null, $existingData, true)) {
                            $response = ['error' => 'Error: Data already exists.'];
                        } else {


                            $this->db->insert_batch($this->table_relasi2, $data);
                            $response = ['success' => 'Success: Data record!'];
                        }
                    } else {
                        $response = ['error' => 'Error: Data is empty or not in correct format.'];
                    }
                }
            }
        } else {
            $response = ['error' => 'Error: File not uploaded or invalid file.'];
        }

        echo json_encode($response);
    }
}
