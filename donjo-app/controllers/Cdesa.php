<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Cdesa extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('config_model');
		$this->load->model('data_persil_model');
		$this->load->model('cdesa_model');
		$this->load->model('penduduk_model');
		$this->load->model('referensi_model');
		$this->modul_ini = 7;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		$_SESSION['per_page'] = 20;
		redirect($this->controller);
	}

	public function autocomplete()
	{
		$data = $this->data_persil_model->autocomplete($this->input->post('cari'));
		echo json_encode($data);
	}

	public function search(){
		$_SESSION['cari'] = $this->input->post('cari');
		if ($_SESSION['cari'] == '') unset($_SESSION['cari']);
		redirect('cdesa');
	}

	public function index($page=1, $o=0)
	{
		$header = $this->header_model->get_data();
		$this->tab_ini = 12;
		$header['minsidebar'] = 1;

		$data['cari'] = isset($_SESSION['cari']) ? $_SESSION['cari'] : '';
		$_SESSION['per_page'] = $_POST['per_page'] ?: null;
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging']  = $this->cdesa_model->paging_c_desa($kat, $mana, $page);
		$data['keyword'] = $this->data_persil_model->autocomplete();
		$data["desa"] = $this->config_model->get_data();
		$data["cdesa"] = $this->cdesa_model->list_c_desa($kat, $mana, $data['paging']->offset, $data['paging']->per_page);
		$data["persil_peruntukan"] = $this->data_persil_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->data_persil_model->list_persil_jenis();
		$data["persil_kelas"] = $this->data_persil_model->list_persil_kelas();

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/c_desa', $data);
		$this->load->view('footer');
	}

	public function import()
	{
		$data['form_action'] = site_url("data_persil/import_proses");
		$this->load->view('data_persil/import', $data);
	}

	public function rincian($id)
	{
		$header = $this->header_model->get_data();
		$data = array();
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id);
		$data['pemilik'] = $this->cdesa_model->get_pemilik($id);
		$data['bidang'] = $this->cdesa_model->get_list_bidang($id);
		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('data_persil/rincian', $data);
		$this->load->view('footer');
	}

	public function create($mode=0, $id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = empty($mode) ? 10 : 12;

		$post = $this->input->post();
		$data = array();
		$data["mode"] = $mode;
		$data["penduduk"] = $this->data_persil_model->list_penduduk();
		if ($mode === 'edit')
		{ 
			$data['cdesa'] = $this->cdesa_model->get_cdesa($id);
			$this->ubah_pemilik($id, $data, $post);
		}
		else
		{
			switch ($post['jenis_pemilik']) 
			{
				case '1':
					# Pemilik desa
					if (!empty($post['nik']))
					{
						$data['pemilik'] = $this->data_persil_model->get_penduduk($post['nik'], $nik=true);
					}
					break;
				case '2':
					# Pemilik luar desa
					$data['cdesa']['jenis_pemilik'] = 2;
					break;
			}
		}

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/create', $data);
		$this->load->view('footer');
	}

	private function ubah_pemilik($id, &$data, $post)
	{
		$jenis_pemilik_baru = $post['jenis_pemilik'] ?: 0;

		switch ($jenis_pemilik_baru) 
		{
			case '0':
				// Buka form ubah pertama kali
				if ($data['cdesa']['jenis_pemilik'] == 1)
				{
					$data['pemilik'] = $this->cdesa_model->get_pemilik($id);					
				}
				break;
			case '1':
				// Ubah atau ambil pemilik desa
				$data['pemilik'] = $this->cdesa_model->get_pemilik($id);
				if ($post['nik'] and $$data['pemilik']['nik'] != $post['nik'])
				{
					$data['pemilik'] = $this->data_persil_model->get_penduduk($post['nik'], $nik=true);
				}
				$data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
				break;
			case '2':
				// Ubah pemilik luar
				$data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
				break;
		}
	}

	public function simpan_cdesa($page=1)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('c_desa','Nomor Surat C-DESA','required|trim|numeric');
		$this->form_validation->set_rules('c_desa', 'Username', 'callback_cek_nomor');

		if ($this->form_validation->run() != false)
		{
			$header = $this->header_model->get_data();
			$header['minsidebar'] = 1;
			$this->load->view('header', $header);

			$id_cdesa = $this->cdesa_model->simpan_cdesa();
			if ($this->input->post('id')) redirect("cdesa");
			else redirect("cdesa/create_bidang/$id_cdesa");
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["error_msg"] = trim(strip_tags(validation_errors()));
			$jenis_pemilik = $this->input->post('jenis_pemilik');
			$id	= $this->input->post('id');
			if ($jenis_pemilik == 1) 
			{
				if ($id)
					redirect("cdesa/create/edit/".$id);
				else
					redirect("cdesa/create");
			}
			else
			{
				if ($id)
					redirect("cdesa/create/edit/".$id);
				else
					redirect("cdesa/create");
			}
		}
	}

	public function create_bidang($id_cdesa, $id_bidang='')
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');

		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;

		if ($id_bidang)
		{ 
			$data["persil"] = $this->cdesa_model->get_persil($id_bidang);
			$data["bidang"] = $this->cdesa_model->get_bidang($id_bidang);
		}
		$data['cdesa'] = $this->cdesa_model->get_cdesa($id_cdesa);
		$data['pemilik'] = $this->cdesa_model->get_pemilik($id_cdesa);

		$data["persil_lokasi"] = $this->data_persil_model->list_dusunrwrt();
		$data["persil_peruntukan"] = $this->cdesa_model->list_persil_peruntukan();
		$data["persil_jenis"] = $this->cdesa_model->list_persil_jenis();
		$data["persil_kelas"] = $this->referensi_model->list_by_id('ref_persil_kelas');
		$data["persil_sebab_mutasi"] = $this->referensi_model->list_by_id('ref_persil_mutasi');

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/create_bidang', $data);
		$this->load->view('footer');
	}

	public function simpan_bidang($id_cdesa, $id_bidang='')
	{
		$post = $this->input->post();
		$data = $this->cdesa_model->simpan_mutasi($id_cdesa, $id_bidang, $this->input->post());
		redirect("cdesa/rincian/$id_cdesa");
	}

	public function hapus_bidang($cdesa, $id_bidang)
	{
		$this->db->where('id', $id_bidang)
			->delete('mutasi_cdesa');
		redirect("cdesa/rincian/$cdesa");
	}

	public function cek_nomor($nomor)
	{
		$id_cdesa = $this->input->post('id');
		if ($id_cdesa) $this->db->where('id <>', $id_cdesa);
		$ada = $this->db
			->group_start()
				->where('nomor', $nomor)
				->or_where('nama_kepemilikan', $this->input->post('nama_kepemilikan'))
			->group_end()
			->get('cdesa')->num_rows();

		if ($ada)
		{
			$this->form_validation->set_message('cek_nomor', 'Nomor C-Desa atau Nama Kepemilikan itu sudah ada');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
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

	public function hapus($id)
	{
		$this->redirect_hak_akses('h', "cdesa");
		$this->cdesa_model->hapus_cdesa($id);
		redirect("cdesa");
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

}

?>
