<?php

class M_Guru extends CI_Model
{


    public function teachers($id)
    {
        $sql = "SELECT 
                `us`.`full_name` as `full_name`,
                `ke`.`id` as `kelas_id`,
                `ke`.`kelas` as `kelas`,
                `ma`.`id` as `mapel_id`,
                `ma`.`nama` as `mapel`

                FROM `users_mapel_kelas` as `umk`
                JOIN `users` as `us` ON `umk`.`user_id` = `us`.`id`
                JOIN `mapel` as `ma` ON `umk`.`mapel_id` = `ma`.`id`
                JOIN `kelas` as `ke` ON `umk`.`kelas_id` = `ke`.`id`
            ";


        if (!$this->ion_auth->is_admin() && !$this->ion_auth->is_programmer()) {
            $sql .= "WHERE `us`.`id` = $id";
        }

        return $this->db->query($sql)->result();
    }


    public function tahunsemester()
    {
        return $this->db->get('tahun')->result();
    }
}