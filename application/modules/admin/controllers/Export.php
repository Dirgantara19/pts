<?php

class Export extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            $this->data['title_toastr'] = "Export Information";
        }
    }

    public function siswa()
    {

        $this->load->model('M_Siswa');
        $sql = $this->M_Siswa->get_sql();
        $data = $this->db->query($sql)->result();

        $dataToExport = [];

        $no = 1;
        foreach ($data as $siswa) {

            $dataToExport[] = [
                'no' => $no++,
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas
            ];
        };

        $columnHeaders = ['NO', 'NIS', 'NAMA', 'KELAS'];

        if ($dataToExport) {
            $exported = exportToExcel($dataToExport, $columnHeaders, 'example_siswa.xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $exported['filename'] . '"');
            header('Cache-Control: max-age=0');
            $exported['writer']->save('php://output');

            exit;
        } else {
            $this->sweetalert->setToastNew('error', 'Error: Data failed to export', $this->data['title_toastr']);
        }
        redirect('admin/siswa', 'refresh');
    }

    public function kelas()
    {

        $data = $this->crud->table('kelas')->get_all();

        $dataToExport = [];

        $no = 1;
        foreach ($data as $kelas) {

            $dataToExport[] = [
                'no' => $no++,
                'kelas' => $kelas->kelas,
                'jurusan' => $kelas->jurusan
            ];
        };

        $columnHeaders = ['NO', 'KELAS', 'JURUSAN'];

        if ($dataToExport) {
            $exported = exportToExcel($dataToExport, $columnHeaders, 'example_kelas.xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $exported['filename'] . '"');
            header('Cache-Control: max-age=0');
            $exported['writer']->save('php://output');

            exit;
        } else {
            $this->sweetalert->setToastNew('success', 'Error: Data failed to export', $this->data['title_toastr']);
        }
        redirect('admin/kelas', 'refresh');
    }


    public function users()
    {

        $this->load->model('M_Users');
        $sql = $this->M_Users->get_sql();
        $data = $this->db->query($sql)->result();

        $dataToExport = [];

        $no = 1;
        foreach ($data as $n) {

            $dataToExport[] = [
                'no' => $no++,
                'username' => $n->username,
                'password' => 'dirahasiakan',
                'full_name' => $n->full_name,
                'nip_or_nik' => $n->nip_or_nik,
            ];
        };


        $columns = ['NO', 'EMAIL', 'PASSWORD', 'NAMA LENGKAP', 'NIP/NIK'];
        $filename = 'example_users.xlsx';

        if ($dataToExport) {
            $result = exportToExcel($dataToExport, $columns, $filename);
            $writer = $result['writer'];
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $result['filename'] . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $this->sweetalert->setToastNew('success', 'Error: Data failed to export', $this->data['title_toastr']);
        }
        redirect('admin/users', 'refresh');
    }


    public function gurupengampu()
    {


        $this->load->model('M_Gurupengampu');
        $sql = $this->M_Gurupengampu->get_sql();
        $data = $this->db->query($sql)->result();

        $dataToExport = [];

        $no = 1;
        foreach ($data as $n) {

            $dataToExport[] = [
                'no' => $no++,
                'nama_guru' => $n->nama_guru,
                'nip_or_nik' => $n->nip_or_nik,
                'mengajar' => $n->mengajar,
                'kelas' => $n->kelas
            ];
        };


        $columns = ['NO', 'NAMA LENGKAP', 'NIP/NIK', 'MENGAJAR', 'KELAS'];
        $filename = 'example_gurupengampu.xlsx';

        if ($dataToExport) {
            $result = exportToExcel($dataToExport, $columns, $filename);
            $writer = $result['writer'];
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $result['filename'] . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $this->sweetalert->setToastNew('success', 'Error: Data failed to export', $this->data['title_toastr']);
        }
        redirect('admin/gurupengampu', 'refresh');
    }


    public function mapel()
    {

        $data = $this->db->get('mapel')->result();


        $dataToExport = [];

        $no = 1;
        foreach ($data as $mapel) {

            $dataToExport[] = [
                'no' => $no++,
                'sing' => $mapel->sing,
                'nama' => $mapel->nama
            ];
        };

        $columns = ['NO', 'SINGKATAN', 'NAMA'];
        $filename = 'example_mapel.xlsx';

        if ($dataToExport) {
            $result = exportToExcel($dataToExport, $columns, $filename);
            $writer = $result['writer'];
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $result['filename'] . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $this->sweetalert->setToastNew('success', 'Error: Data failed to export', $this->data['title_toastr']);
        }
        redirect('admin/mapel', 'refresh');
    }


    public function nilai($kelasid, $tahunid)
    {

        $sql = "SELECT *,
        `b`.`nama` as `nama`,
        `b`.`nis` as `nis`,
        `d`.`kelas` as `kelas`,
        GROUP_CONCAT(
            CASE WHEN `c`.`active` = 1 THEN `e`.`nama` ELSE NULL END
        ) as `nama_mapel`,  
        GROUP_CONCAT(
            CASE WHEN `c`.`active` = 1 THEN `e`.`slug` ELSE NULL END
        ) as `slug_mapel`,  
        GROUP_CONCAT(
            CASE WHEN `c`.`active` = 1 THEN `a`.`nilai` ELSE NULL END
        ) as `nilai`, 
        GROUP_CONCAT(
            CASE WHEN `c`.`active` = 1 THEN `c`.`full_name` ELSE NULL END SEPARATOR '; '
        ) as `guru`, 
        GROUP_CONCAT(
            CASE WHEN `c`.`active` = 1 THEN `a`.`id` ELSE NULL END
        ) as `id_raport`,
        SUM(CASE WHEN `c`.`active` = 1 THEN `a`.`nilai` ELSE 0 END) as `total_nilai`
            FROM `raport` as `a`
            JOIN `siswa` as `b` ON `a`.`siswa_nis` = `b`.`nis`
            JOIN `users` as `c` ON `a`.`user_id` = `c`.`id`
            JOIN `kelas` as `d` ON `a`.`kelas_id` = `d`.`id`
            JOIN `mapel` as `e` ON `a`.`mapel_id` = `e`.`id`
            JOIN `tahun` as `f` ON `a`.`tahun_id` = `f`.`id`
            WHERE `a`.`kelas_id` = ?
            AND `a`.`tahun_id` = ?
            GROUP BY `a`.`siswa_nis`";

        $result = $this->db->query($sql, [$kelasid, $tahunid])->result();
        $data = [];
        foreach ($result as $row) {
            $nama_mapelArray = explode(',', $row->nama_mapel);
            $slugArray = explode(',', $row->slug);
            $nilaiArray = explode(',', $row->nilai);
            $guruArray = explode('; ', $row->guru);
            $idArray = explode(',', $row->id_raport);

            $mapelData = array_map(function ($nama_mapel, $slug, $nilai, $guru, $id_raport) {
                return ['nama_mapel' => $nama_mapel, 'slug' => $slug, 'nilai' => $nilai, 'guru' => $guru, 'id_raport' => $id_raport];
            }, $nama_mapelArray, $slugArray, $nilaiArray, $guruArray, $idArray);

            $data[] = [
                'nama' => $row->nama,
                'nis' => $row->nis,
                'kelas' => $row->kelas,
                'jurusan' => $row->jurusan,
                'tahun' => $row->thn_ajaran,
                'semester' => $row->semester,
                'mapel' => $mapelData,
                'total_nilai' => $row->total_nilai,

            ];
        }


        $mpdf = new \Mpdf\Mpdf();


        foreach ($data as $key => $row) {
            $mpdf->SetTitle($row['kelas']);

            $htmlContent = '
            <html>
            <head>
            <style>

                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                }
                
                header{
                    padding-top: 100px;
                }

                .identity{
                    position:absolute;
                    padding-top: 50px;
                }



                .list-grades{
                    padding-top: 115px;
                    padding-bottom:100px;
                }



            </style>
            </head>

            <body>


                <header>
                    <h2 align="center">PENILAIAN TENGAH SEMESTER</h2>
                </header>

            <div class="identity" style="left: 50px; right:50px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 8%;">Nama</td>
                        <td style="width: 42%;">: ' . $row['nama'] . '</td>
                        <td style="width: 8%;">Tahun</td>
                        <td style="width: 42%;">: ' . $row['tahun'] . '</td>
                    </tr>
                    <tr>
                        <td>NIS</td>
                        <td>: ' . $row['nis'] . '</td>
                        <td>Semester</td>
                        <td>: ' . $row['semester'] . '</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>: ' . $row['kelas'] . '</td>
                        <td>Jurusan</td>
                        <td>: ' . $row['jurusan'] . '</td>
                    </tr>
                </table>




            </div>

            
                    <div class="list-grades" style="position: absolute; top:300px; left:50px; right:50px;"  >
                        <table border="1" cellpadding="1" cellspacing="0" width="100%" >
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th>Guru Pengampu</th>
                            </tr>
                           ';
            $i = 1;

            foreach ($row['mapel'] as $mapel) {

                $htmlContent .= ' <tr>
                                    <td align="center">' . $i++ . '</td>
                                    <td align="center">' . $mapel['nama_mapel'] . '</td>
                                    <td align="center">' . $mapel['nilai'] . '</td>
                                    <td align="center">' . $mapel['guru'] . '</td> 
                            </tr>';
            };
            $htmlContent .= ' </table>
                    </div>
                    <div style="font-weight:bold;position: absolute; bottom:130px; right:50px">
                        <h3>
                        Bantul,...........................
                        </h3>
                        <h3>
                        Walikelas
                        </h3>
                        <pre style="margin-top: 100px;">(                     )</pre>
                    </div>



            </body>
            </html>';


            $mpdf->SetHTMLHeader(
                '
            <table width="100%" style="border-bottom: 3px solid #000000; padding-top:10px; margin-left: 50px; margin-right: 50px; ">
                <tr>
                <td width="15%">
                </td>
                    <td width="85%" style="" align="center">
                        <span style="font-size: 13px">PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA</span><br>
                        <span style="font-size: 18px">DINAS PENDIDIKAN, PEMUDA DAN OLAHRAGA</span><br>
                        <span style="font-size: 16px">BALAI PENDIDIKAN MENENGAH KAB. BANTUL</span><br>
                        <span style="font-size: 18px;">SMKN 1 BANTUL</span><br>
                        <br><br>
                        <span style="font-size: 12px;">Jalan Parangtritis Km 11, Sabdodadi, Bantul; Telepon (0274) 367156<br>
                        Laman <u>www.smkn1bantul.sch.id;</u> Posel smeanbtl@yahoo.com</span>
                    </td>
                </tr>
            </table>'
            );


            $mpdf->AddPage();
            $mpdf->Image('./assets/default/img/kopjogja.png', 35, 15, 20, 30, 'png', '', true, false);
            $mpdf->Image('./assets/default/img/kopaksarajawa.png', 85, 33, 65, 7, 'png', '', true, false);
            $mpdf->WriteHTML($htmlContent);

            $mpdf->SetHTMLFooter('
        <table width="100%">
        <tr>
            <td width="33%">
                <span style="font-weight: bold; font-style: italic;">Dicetak pada : {DATE j-m-Y}</span>
            </td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic;">
            </td>
            
            <td width="33%" style="text-align: right; font-weight: bold;">
            SMK Negeri 1 Bantul

            </td>
        </tr>
        </table> ');
        }



        $mpdf->Output();
    }
}
