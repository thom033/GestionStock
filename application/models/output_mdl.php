<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_mdl extends CI_Model{
    public function save($date, $qtt, $type) {
        $qtt_dispo = $this->getStockQTT();
        $cmup_data = array();
        $list_output = array();

        if ($qtt_dispo < $qtt) {
            return false;
        }

        if($type==='fifo'){
            $liste_stock= $this->getStock('asc');
            $list_output = $this->generatePU($date, $qtt, $liste_stock, $list_output);

        }
        if ($type === 'lifo') {
            $liste_stock= $this->getStock('desc');
            $list_output = $this->generatePU($date, $qtt, $liste_stock, $list_output);

        }
        if ($type === 'cmup') {
            $liste_stock= $this->getStock('asc');
            $pu = $this->getCMUP(); 
            $list_output = $this->save_cmup($date, $qtt,$pu,$liste_stock,$list_output);
        }        
        
        // Insertion dans la table output
        foreach ($list_output as $output) {
            $this->db->insert('output', $output);
        }
    
        return true;
    }
    
    public function save_cmup($date, $qtt,$PU, $liste_stock, $list_output) {
        foreach ($liste_stock as $stock){
            if($qtt > $stock['qtt']){
                $temp = $stock['qtt'];
                $qtt -= $stock['qtt'];
                $stock['qtt'] -= $temp;
                $list_output[] = ['daty' => $date, 'qtt' => $temp, 'Id_input' => $stock['Id_input'],
                'pu' => $PU, 'total' => $PU* $temp];
                // echo ($qtt."   ");
                $this->generatePU($date, $qtt, $liste_stock, $list_output);
            }
            if($qtt <= $stock['qtt']){
                $stock['qtt'] -= $qtt;
                $list_output[] = ['daty' => $date, 'qtt' => $qtt, 'Id_input' => $stock['Id_input'], 
                'pu' => $PU, 'total' => $PU* $qtt];
                return $list_output;
            }
        }
    }
    
    public function getCMUP() { 
        $query = $this->db->query("CALL get_cmup()");
        $result = $query->row_array(); 
        // Nettoyage pour éviter les erreurs liées aux résultats restants
        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
            $this->db->conn_id->store_result();
        }
        if (empty($result)) {
            return array('total_quantity' => 0, 'total_value' => 0, 'cmup' => 0); // Retourner des valeurs par défaut si vide
        }
        // Retourner uniquement les valeurs de cmup
        return $result['cmup'];
    }
    
    
    
    public function getOutputsByDate($date){
        $query = $this->db->query("select * from output where daty = '$date'");
        return $query->result_array();
    }

    public function getStockQTT() {
        $query = $this->db->select_sum('qtt')->from('current_stock');
        $result = $query->get()->row_array();
        return $result['qtt'];
    }
    public function getStock($orderby) {
        $query = $this->db->query('select * from current_stock order by output_date '. $orderby);
        return $query->result_array();
    }
        

    
    public function getStockByDate($date){
        $query = $this->db->query("CALL get_stock_by_date(?)", array($date));
        return $query->result_array();
    }   
    public function generatePU ($date, $qtt, $liste_stock, $list_output){
        foreach ($liste_stock as $stock){
            if($qtt > $stock['qtt']){
                $temp = $stock['qtt'];
                $qtt -= $stock['qtt'];
                $stock['qtt'] -= $temp;
                $list_output[] = ['daty' => $date, 'qtt' => $temp, 'Id_input' => $stock['Id_input'],
                'pu' => $stock['pu'], 'total' => $stock['pu']* $temp];
                // echo ($qtt."   ");
                $this->generatePU($date, $qtt, $liste_stock, $list_output);
            }
            if($qtt <= $stock['qtt']){
                $stock['qtt'] -= $qtt;
                $list_output[] = ['daty' => $date, 'qtt' => $qtt, 'Id_input' => $stock['Id_input'], 
                'pu' => $stock['pu'], 'total' => $stock['pu']* $qtt];
                return $list_output;
            }
        }
    }
}