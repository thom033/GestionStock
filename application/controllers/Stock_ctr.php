<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_ctr extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('output_mdl');
        $this->load->model('input_mdl');
    }
    public function index(){
    $this->load->view('mvt');
    }
    public function recherche()  {
        $data = array();
        $date = $this->input->get('date');
        $date_avant = new DateTime($date);
        $date_avant->modify('-1 day');
        $data['outputs'] = $this->output_mdl->getOutputsByDate($date);
        $data['inputs'] = $this->input_mdl->getInputByDate($date);
        $data['stocks'] = $this->output_mdl->getStockByDate($date);
        // $data['stocks_avant'] = $this->output_mdl->getStockByDate($date_avant->format('Y-m-d'));
        $data['date'] = $date;

        $this->load->view('mvt',  $data);
    }
}