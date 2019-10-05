<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengurus extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('pamong_model');
		$this->load->model('header_model');
		$this->load->model('penduduk_model');
		$this->load->model('config_model');
		$this->modul_ini = 200;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect('pengurus');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_read())))
                {
                
                if (isset($_SESSION['cari']))
		$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_SESSION['filter']))
			$data['filter'] = $_SESSION['filter'];
		else $data['filter'] = '';

		$data['main'] = $this->pamong_model->list_data();
		$data['keyword'] = $this->pamong_model->autocomplete();
		$header = $this->header_model->get_data();

		// Menampilkan menu dan sub menu aktif
		$header['minsidebar'] = 1;
		$nav['act'] = 1;
		$nav['act_sub'] = 18;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/pengurus', $data);
		$this->load->view('footer');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function form($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_create())))
                {
                if ($id)
		{
			$data['pamong'] = $this->pamong_model->get_data($id);
			if (!isset($_POST['id_pend'])) $_POST['id_pend'] = $data['pamong']['id_pend'];
			$data['form_action'] = site_url("pengurus/update/$id");
		}
		else
		{
			$data['pamong'] = NULL;
			$data['form_action'] = site_url("pengurus/insert");
		}

		$data['pendidikan_kk'] = $this->penduduk_model->list_pendidikan_kk();
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['penduduk'] = $this->penduduk_model->list_penduduk();
		if (!empty($_POST['id_pend']))
			$data['individu'] = $this->penduduk_model->get_penduduk($_POST['id_pend']);
		else
			$data['individu'] = NULL;
		$header = $this->header_model->get_data();
		// Menampilkan menu dan sub menu aktif
		$nav['act'] = 1;
		$nav['act_sub'] = 18;

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/pengurus_form', $data);
		$this->load->view('footer');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		if ($filter != "")
			$_SESSION['filter'] = $filter;
		else unset($_SESSION['filter']);
		redirect('pengurus');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		if ($cari != '')
			$_SESSION['cari'] = $cari;
		else unset($_SESSION['cari']);
		redirect('pengurus');
	}

	public function insert()
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->insert();
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function update($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->update($id);
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function delete($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_delete())))
                {
                $this->redirect_hak_akses('h', 'pengurus');
		$_SESSION['success'] = 1;
		$outp = $this->pamong_model->delete($id);
		if (!$outp) $_SESSION['success'] = -1;
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view("errors/html/error_access");
	        }
	}

	public function delete_all()
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_delete())))
                {
                $this->redirect_hak_akses('h', 'pengurus');
		$this->pamong_model->delete_all();
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function ttd_on($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->ttd($id, 1);
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function ttd_off($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->ttd($id, 0);
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function ub_on($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->ub($id, 1);
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function ub_off($id = '')
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->ub($id, 0);
		redirect('pengurus');
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function dialog_cetak($o = 0)
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_print())))
                {
                $data['aksi'] = "Cetak";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['form_action'] = site_url("pengurus/cetak/$o");
		$this->load->view('home/ajax_cetak_pengurus', $data);
                }
                else
                {
		  
                  $this->load->view("errors/html/error_access");
	        }
	}

	public function dialog_unduh($o = 0)
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_print())))
                {
                $data['aksi'] = "Unduh";
		$data['pamong'] = $this->pamong_model->list_data(true);
		$data['form_action'] = site_url("pengurus/unduh/$o");
		$this->load->view('home/ajax_cetak_pengurus', $data);
                }
                else
                {
		  
                  $this->load->view("errors/html/error_access");
	        }
	}

	public function cetak($o = 0)
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_print())))
                {
                $data['input'] = $_POST;
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
  	        $data['desa'] = $this->config_model->get_data();
                $data['main'] = $this->pamong_model->list_data();
		$this->load->view('home/pengurus_print', $data);
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function unduh($o = 0)
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_print())))
                {
                $data['input'] = $_POST;
		$data['pamong_ttd'] = $this->pamong_model->get_data($_POST['pamong_ttd']);
		$data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
  	        $data['desa'] = $this->config_model->get_data();
                $data['main'] = $this->pamong_model->list_data();
		$this->load->view('home/pengurus_excel', $data);
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}

	public function urut($id = 0, $arah = 0)
	{
		if (!$this->ion_auth->logged_in() || (in_array('18', gp_update())))
                {
                $this->pamong_model->urut($id, $arah);
		redirect("pengurus");
                }
                else
                {
		  $data['page'] = "errors/html/error_access";
                  $this->load->view('dashboard',$data);
	        }
	}
}
