<?php
class M_Nilai extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function get_query()
    {
        $kelasid = !empty($this->input->post('kelasid', true)) ? $this->input->post('kelasid', true) : 0;
        $tahunid = !empty($this->input->post('tahunid', true)) ? $this->input->post('tahunid', true) : 0;

        $sql = "SELECT 
                `b`.`nama` as `nama`,
                `b`.`nis` as `nis`,
                `d`.`kelas` as `kelas`,
                `f`.`thn_ajaran` as `thn_ajaran`,
                `f`.`semester` as `semester`,

                SUM(CASE WHEN `c`.`active` = 1 THEN `a`.`nilai` ELSE 0 END) as  `total_nilai`,

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
                    CASE WHEN `c`.`active` = 1 THEN `c`.`id` ELSE NULL END
                ) as `id_raport` 
            FROM `raport` as `a`
            JOIN `siswa` as `b` ON `a`.`siswa_nis` = `b`.`nis`
            JOIN `users` as `c` ON `a`.`user_id` = `c`.`id`
            JOIN `kelas` as `d` ON `a`.`kelas_id` = `d`.`id`
            JOIN `mapel` as `e` ON `a`.`mapel_id` = `e`.`id`
            JOIN `tahun` as `f` ON `a`.`tahun_id` = `f`.`id`
            WHERE `d`.`id` = ?
            AND `f`.`id` = ?
            GROUP BY `b`.`nis`
    
    
            ";

        $columns_order = [null, 'nis', 'nama'];

        if (isset($_POST['search']['value'])) {
            $search_value = $_POST['search']['value'];
            $sql .= " HAVING CONCAT(`nama`, `nis`) LIKE '%$search_value%'";
        }
        if (isset($_POST['order']['0']['column'])) {
            $order_column = $_POST['order']['0']['column'];
            $order_direction = $_POST['order']['0']['dir'];

            $sql .= " ORDER BY " . "`$columns_order[$order_column]`" . "$order_direction";
        }

        $return = ['sql' => $sql, 'kelasid' => $kelasid, 'tahunid' => $tahunid];
        return $return;
    }

    private function get_query_id()
    {
        $nis = $this->input->post('nis', true);

        $sql = "SELECT 
            `b`.`nama` as `nama`,
            `b`.`nis` as `nis`,
            `d`.`kelas` as `kelas`,
            `f`.`thn_ajaran` as `thn_ajaran`,
            `f`.`semester` as `semester`,
            SUM(`a`.`nilai`) as  `total_nilai`,

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
                CASE WHEN `c`.`active` = 1 THEN `a`.`id` ELSE NULL END
            ) as `id_raport` 
            FROM `raport` as `a`
            JOIN `siswa` as `b` ON `a`.`siswa_nis` = `b`.`nis`
            JOIN `users` as `c` ON `a`.`user_id` = `c`.`id`
            JOIN `kelas` as `d` ON `a`.`kelas_id` = `d`.`id`
            JOIN `mapel` as `e` ON `a`.`mapel_id` = `e`.`id`
            JOIN `tahun` as `f` ON `a`.`tahun_id` = `f`.`id`
            WHERE `a`.`siswa_nis` = ?";


        return $this->db->query($sql, [$nis]);
    }

    private function get_query_export()
    {
        $kelasid = $this->input->post('kelasid');
        $tahunid = $this->input->post('tahunid');

        $sql = "SELECT *,
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
                CASE WHEN `c`.`active` = 1 THEN `a`.`id` ELSE NULL END
            ) as `id_raport` 
            FROM `raport` as `a`
            JOIN `siswa` as `b` ON `a`.`siswa_nis` = `b`.`nis`
            JOIN `users` as `c` ON `a`.`user_id` = `c`.`id`
            JOIN `kelas` as `d` ON `a`.`kelas_id` = `d`.`id`
            JOIN `mapel` as `e` ON `a`.`mapel_id` = `e`.`id`
            JOIN `tahun` as `f` ON `a`.`tahun_id` = `f`.`id`
            WHERE `d`.`id` = ?
            AND `f`.`id` = ?
            GROUP BY `b`.`nis`";

        $result = $this->db->query($sql, [$kelasid, $tahunid])->result();

        $siswa = $this->data_stucture($result);

        return $siswa;
    }

    private function recordsFiltered()
    {
        $get_query = $this->get_query();

        $data = $this->db->query($get_query['sql'], array($get_query['kelasid'], $get_query['tahunid'],))->num_rows();
        return $data;
    }

    private function recordsTotal()
    {
        $get_query = $this->get_query();

        $data = $this->db->query($get_query['sql'], array($get_query['kelasid'], $get_query['tahunid'],))->result();
        return count($data);
    }

    public function ajax_list()
    {
        $get_query = $this->get_query();

        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $get_query['sql'] .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
        }
        $result = $this->db->query($get_query['sql'], array($get_query['kelasid'], $get_query['tahunid'],))->result();
        $data = $this->data_stucture($result);

        $response = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->recordsTotal(),
            'recordsFiltered' => $this->recordsFiltered(),
            'data' => $data,
        );

        return json_encode($response);
    }

    private function data_stucture($result)
    {
        $data = [];
        foreach ($result as $row) {
            $nama_mapelArray = explode(',', $row->nama_mapel);
            $slugArray = explode(',', $row->slug_mapel);
            $nilaiArray = explode(',', $row->nilai);
            $idArray = explode(',', $row->id_raport);

            $mapelData = array_map(function ($nama_mapel, $slug, $nilai, $id_raport) {
                return ['nama_mapel' => $nama_mapel, 'slug' => $slug, 'nilai' => $nilai, 'id_raport' => $id_raport];
            }, $nama_mapelArray, $slugArray, $nilaiArray, $idArray);

            $data[] = [
                'nama' => $row->nama,
                'nis' => $row->nis,
                'kelas' => $row->kelas,
                'tahun' => $row->thn_ajaran,
                'semester' => $row->semester,
                'mapel' => $mapelData,
                'total_nilai' => $row->total_nilai,

            ];
        }

        return $data;
    }


    public function get_by_id()
    {
        $result = $this->get_query_id()->result();
        $data = $this->data_stucture($result);


        return json_encode($data);
    }

    public function get_export()
    {
        $result = $this->get_query_export();
        return $result;
    }
}
