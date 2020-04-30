<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Data_persil extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('data_persil_model');
		$this->load->model('cdesa_model');
		$this->load->model('penduduk_model');
		$this->controller = 'data_persil';
		$this->modul_ini = 7;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		$_SESSION['per_page'] = 20;
		redirect('data_persil');
	}

	public function autocomplete()
	{
		$data = $this->data_persil_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function search(){
		$_SESSION['cari'] = $this->input->post('cari');
		if ($_SESSION['cari'] == '') unset($_SESSION['cari']);
		redirect('data_persil');
	}

	public function index($page=1, $o=0)
	{
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = 13;

		$data['cari'] = isset($_SESSION['cari']) ? $_SESSION['cari'] : '';
		$_SESSION['per_page'] = $_POST['per_page'] ?: null;
		$data['per_page'] = $_SESSION['per_page'];

		$data["desa"] = $this->config_model->get_data();
		$data['paging']  = $this->data_persil_model->paging($page);
		$data["persil"] = $this->data_persil_model->list_data($data['paging']->offset, $data['paging']->per_page);
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$data['keyword'] = $this->data_persil_model->autocomplete();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/persil', $data);
		$this->load->view('footer');
	}

	// Cek untuk dihapus
	public function persil($kat=0, $mana=0, $page=1, $o=0)
	{
		$header = $this->header_model->get_data();
		$data['kat'] = $kat;
		$data['mana'] = $mana;
		$header['minsidebar'] = 1;
		$this->_set_tab($kat, $mana);
		$this->load->view('header', $header);

		if (isset($_SESSION['cari']))
			$data['cari'] = $_SESSION['cari'];
		else $data['cari'] = '';

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data["desa"] = $this->config_model->get_data();
		$data['paging']  = $this->data_persil_model->paging($kat, $mana, $page);
		$data["persil"] = $this->data_persil_model->list_persil($kat, $mana, $data['paging']->offset, $data['paging']->per_page);
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$data['keyword'] = $this->data_persil_model->autocomplete();
		$nav['act'] = 7;
		$data["title"] = $kat." ".$data["persil_$kat"][$mana]['nama'];
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/persil', $data);
		$this->load->view('footer');
	}

	private function _set_tab($kat='', $mana='')
	{
		switch (true)
		{
			case ($kat === "peruntukan"):
				$this->tab_ini = 3..$mana;
				break;

			case ($kat === "jenis"):
				$this->tab_ini = 2..$mana;
				break;

			default:
				$this->tab_ini = 13;
				break;
		}
	}

	public function import()
	{
		$data['form_action'] = site_url("data_persil/import_proses");
		$this->load->view('data_persil/import', $data);
	}

	public function rincian($id=0)
	{
		$header = $this->header_model->get_data();
		$this->tab_ini = 13;

		$data['persil'] = $this->data_persil_model->get_persil($id);
		$data['bidang'] = $this->data_persil_model->get_list_bidang($id);
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('data_persil/rincian_persil', $data);
		$this->load->view('footer');
	}

	public function form($id)
	{
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = 13;

		$data["persil"] = $this->data_persil_model->get_persil($id);
		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/form_persil', $data);
		$this->load->view('footer');
	}

	public function simpan_persil($page=1)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('no_persil','Nomor Surat Persil','required|trim|numeric');
		$this->form_validation->set_rules('kelas','Kelas Tanah','required|trim|numeric');

		if ($this->form_validation->run() != false)
		{
			$this->data_persil_model->simpan_persil($this->input->post());
			redirect("data_persil");
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["error_msg"] = trim(strip_tags(validation_errors()));
			$id	= $this->input->post('id_persil');
			redirect("data_persil/form/".$id);
		}
	}

	public function persil_jenis($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->load->view('header', $header);
		$nav['act'] = 7;
		$this->tab_ini = 20;
		$this->load->view('nav', $nav);
		$data["id"] = $id;
		if ($this->form_validation->run() === FALSE)
		{
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
			$data["persil_jenis_detail"] = $this->data_persil_model->get_persil_jenis($id);
			$data["hasil"] = false;
			$this->load->view('data_persil/persil_jenis', $data);
		}
		else
		{
			$data["hasil"] = $this->data_persil_model->update_persil_jenis($id);
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
			$data["persil_jenis_detail"] = $this->data_persil_model->get_persil_jenis($id);
			$this->load->view('data_persil/persil_jenis', $data);
		}
		$this->load->view('footer');
	}

	public function hapus_persil_jenis($id){
		$this->redirect_hak_akses('h', "data_persil/persil_jenis");
		$this->data_persil_model->hapus_jenis($id);
		redirect("data_persil/persil_jenis");
	}

	public function persil_peruntukan($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = 30;
		$this->load->view('header', $header);
		$nav['act'] = 7;
		$this->load->view('nav', $nav);
		$data["id"] = $id;
		if ($this->form_validation->run() === FALSE)
		{
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_peruntukan_detail"] = $this->data_persil_model->get_persil_peruntukan($id);
			$data["hasil"] = false;
			$this->load->view('data_persil/persil_peruntukan', $data);
		}
		else
		{
			$data["hasil"] = $this->data_persil_model->update_persil_peruntukan($id);
			$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
			$data["persil_peruntukan_detail"] = $this->data_persil_model->get_persil_peruntukan($id);
			$this->load->view('data_persil/persil_peruntukan', $data);
		}
		$this->load->view('footer');
	}

	public function panduan()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = 15;
		$this->load->view('header', $header);
		$nav['act'] = 7;
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/panduan');
		$this->load->view('footer');
	}

	public function hapus_persil_peruntukan($id)
	{
		$this->redirect_hak_akses('h', "data_persil/persil_peruntukan");
		$this->data_persil_model->hapus_peruntukan($id);
		redirect("data_persil/persil_peruntukan");
	}

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "data_persil");
		$this->data_persil_model->hapus($id);
		redirect("data_persil/clear");
	}

	public function import_proses()
	{
		$this->data_persil_model->impor_persil();
		redirect("data_persil");
	}

	public function cetak_persil($o=0)
	{
		$data['data_persil'] = $this->data_persil_model->list_persil('', $o, 0, 10000);
		$this->load->view('data_persil/persil_print', $data);
	}

	public function cetak($o=0)
	{
		$data['data_persil'] = $this->data_persil_model->list_c_desa('', $o, 0, 10000);
		$this->load->view('data_persil/c_desa_print', $data);
	}

	// Cek untuk dihapus
	public function form_c_desa($id=0)
	{
		$header = $this->header_model->get_data();
		$data['desa'] = $header['desa'];
		$data["persil_detail"] = $this->data_persil_model->get_c_desa($id);
		$data['basah']= $this->data_persil_model->get_c_cetak($id, 'S');
		$data['kering']= $this->data_persil_model->get_c_cetak($id, 'D');
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();
		$this->load->view('data_persil/c_desa_form_print', $data);
	}

	public function excel($mode="", $o=0)
	{
		$data['mode'] = $mode;
		if($mode == 'persil')
			$data['data_persil'] = $this->data_persil_model->list_persil('', $o, 0, 10000);
		else
			$data['data_persil'] = $this->data_persil_model->list_c_desa('', $o, 0, 10000);
			$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$this->load->view('data_persil/persil_excel', $data);
	}

	public function kelasid()
	{
		$data =[];
		$id = $this->input->post('id');
		$kelas = $this->data_persil_model->list_persil_kelas($id);
		foreach ($kelas as $key => $item) {
			$data[] = array('id' => $key, 'kode' => $item[kode], 'ndesc' => $item['ndesc']);
		}
		echo json_encode($data);
	}
}

?>
