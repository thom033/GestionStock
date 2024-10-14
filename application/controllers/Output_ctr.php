<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_ctr extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('output_mdl');
    }
    public function index() {
        $this->load->view('output');
    }

    public function save()
    {
        $date = $this->input->post('date');
        $qtt =  $this->input->post('quantity');
        $type = $this->input->post('method');
        try {
            if ($this->output_mdl->save($date, $qtt, $type)) {
                $this->session->set_flashdata('success', 'Data saved successfully!');
            } else{
                $this->session->set_flashdata('error', 'Stock insufficient!');
            }
        } catch (\Throwable $th) {
            $this->session->set_flashdata('error', 'Data saved unsuccessfully!');
            throw $th;
        }   
        $this->load->view('output'); 
    }		
}