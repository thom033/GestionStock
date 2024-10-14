<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_mdl extends CI_Model{
    public function save($date, $qtt, $pu) {
        $data = array(
            'daty' => $date,
            'qtt' => $qtt,
            'pu' => $pu,
            'total' => $qtt * $pu
        );
        try {
            $this->db->insert('input', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getInputByDate($date){
        $query = $this->db->query("select * from input where daty = '$date' ");
        return $query->result_array();
    }
}