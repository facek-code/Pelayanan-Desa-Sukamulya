<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class web_config extends CI_Controller{

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->model('user_model');
    $grup = $this->user_model->sesi_grup($_SESSION['sesi']);
    if($grup!=1 AND $grup!=2) redirect('siteman');
    $this->load->model('header_model');
    $this->load->model('web_config_model');
  }

  function index(){
    $nav['act']= 0;
    $header = $this->header_model->get_data();
    $header['modul'] = 1;
    $data['main'] = $this->web_config_model->get_data();
    $this->load->view('header',$header);
    $this->load->view('home/nav',$nav);
    $data['form_action'] = site_url("web_config/update/");
    $this->load->view('home/konfigurasi_form',$data);
    $this->load->view('footer');
  }

  function update(){
    $this->config_model->update($id);
    redirect("web_config");
  }

}
