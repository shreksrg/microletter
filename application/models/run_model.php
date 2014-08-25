<?php


class Run_model extends CI_Model
{

    public function test()
    {
        $sql = "select * from mic_user where isdel=1";
        $query = $this->db->query($sql);
        $row = $query->result_array();


        var_dump($row[0]);

    }


}