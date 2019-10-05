<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_desa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->modul_ini = 200;
	}

	public function index()
	{
	       if (!$this->ion_auth->logged_in() || (in_array('17', gp_read())))
               {
               redirect('hom_desa/konfigurasi');
               }
               else
               {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	       }
	}

	public function konfigurasi()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_read())))
                {
                $this->load->model('provinsi_model');
		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 17;
		$header = $this->header_model->get_data();

		$data['main'] = $this->config_model->get_data();
		$this->load->view('header',$header);
		$this->load->view('nav',$nav);
		// Buat row data desa di konfigurasi_form apabila belum ada data desa
		if ($data['main']) $data['form_action'] = site_url("hom_desa/update/".$data['main']['id']);
			else $data['form_action'] = site_url("hom_desa/insert/");
		$data['list_provinsi'] = $this->provinsi_model->list_data();
		$this->load->view('home/konfigurasi_form',$data);
		$this->load->view('footer');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function insert()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $this->config_model->insert();
		redirect('hom_desa/konfigurasi');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function update($id='')
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $this->config_model->update($id);
		redirect("hom_desa/konfigurasi");
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function ajax_kantor_maps()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_kantor_maps/");
		$this->load->view("home/ajax_kantor_desa_maps", $data);
                }
                else
                {
		  //$data['page'] = "errors/html/error_access";
                  $this->load->view("errors/html/error_access");
	        }
	}

	public function ajax_wilayah_maps()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $data['desa'] = $this->config_model->get_data();
		$data['form_action'] = site_url("hom_desa/update_wilayah_maps/");
		$this->load->view("home/ajax_wilayah_desa_maps", $data);
                }
                else
                {
		  //$data['page'] = "errors/html/error_access";
                  $this->load->view("errors/html/error_access");
	        }

	}

	public function update_kantor_maps()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $this->config_model->update_kantor();
		redirect("hom_desa/konfigurasi");
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function update_wilayah_maps()
	{
		if (!$this->ion_auth->logged_in() || (in_array('17', gp_update())))
                {
                $this->config_model->update_wilayah();
		redirect("hom_desa/konfigurasi");
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

}
