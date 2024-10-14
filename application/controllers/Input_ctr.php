<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_ctr extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("input_mdl");

    }

    public function save(){
        $date = $this->input->post('date');
        $qtt =  $this->input->post('quantity');
        $pu = $this->input->post('unitPrice');
        try {
            $this->input_mdl->save($date, $qtt, $pu);
            $this->session->set_flashdata('success', 'Data saved successfully!');
        } catch (\Throwable $th) {
            $this->session->set_flashdata('error', 'Data saved unsuccessfully!');
            throw $th;
        }   
        $this->load->view('input');     
    }
	
    
}