<?php

class Imgserver extends CI_Controller
{
    public function index()
    {
        $query = $this->db->query('SELECT * FROM goods');
        foreach ($query->result() as $row)
        {
            echo $row->name;
        }
    }
}