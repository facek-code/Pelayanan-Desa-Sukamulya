<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_siskeudes extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('web_dokumen_model');
		$this->modul_ini = 201;
		$this->submodul = 204;
		$this->kat = 4;
	}

	public function clear($kat=1)
	{
		unset($_SESSION['cari']);
		unset($_SESSION['filter']);
		redirect($this->controller);
	}

	public function index($p=1, $o=0)
	{
		$data['p'] = $p;
		$data['o'] = $o;

		$list_session = array('cari', 'filter');
		foreach ($list_session as $session)
		{
			$data[$session] = $this->session->userdata($session) ?: '';
		}

		if (isset($_POST['per_page']))
			$_SESSION['per_page']=$_POST['per_page'];
		$data['per_page'] = $_SESSION['per_page'];

		$data['paging'] = $this->web_dokumen_model->paging($this->kat, $p, $o);
		$data['main'] = $this->web_dokumen_model->list_data($this->kat, $o, $data['paging']->offset, $data['paging']->per_page);
		$data['keyword'] = $this->web_dokumen_model->autocomplete($this->kat);

		$header = $this->header_model->get_data();
		$nav['act_sub'] = $this->submodul;
		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('Laporan_siskeudes/table', $data);
		$this->load->view('footer');
	}

	public function form($kat=1, $p=1, $o=0, $id='')
	{
		$data['p'] = $p;
		$data['o'] = $o;

		if ($id)
		{
			$data['dokumen'] = $this->web_dokumen_model->get_dokumen($id);
			$data['form_action'] = site_url("{$this->controller}/update/$id/$p/$o");
		}
		else
		{
			$data['dokumen'] = null;
			$data['form_action'] = site_url("{$this->controller}/insert");
		}
		$header = $this->header_model->get_data();
		$nav['act_sub'] = $this->submodul;

		$this->load->view('header', $header);
		$this->load->view('nav',$nav);
		$this->load->view('Laporan_siskeudes/form', $data);
		$this->load->view('footer');
	}

	public function search()
	{
		$cari = $this->input->post('cari');
		$kat = $this->input->post('kategori');
		if ($cari != '')
			$_SESSION['cari']=$cari;
		else unset($_SESSION['cari']);
		redirect($this->controller);
	}

	public function filter()
	{
		$filter = $this->input->post('filter');
		$kat = $this->input->post('kategori');
		if ($filter != 0)
			$_SESSION['filter']=$filter;
		else unset($_SESSION['filter']);
		redirect($this->controller);
	}

	public function insert()
	{
		$_SESSION['success'] = 1;
		$outp = $this->web_dokumen_model->insert();
		if (!$outp) $_SESSION['success'] = -1;
		redirect($this->controller);
	}

	public function update($id='', $p=1, $o=0)
	{
		$_SESSION['success'] = 1;
		$kategori = $this->input->post('kategori');
		if (!empty($kategori))
			$kat = $this->input->post('kategori');
		$outp = $this->web_dokumen_model->update($id);
		if (!$outp) $_SESSION['success'] = -1;
		redirect("$this->controller/$p/$o");
	}

	public function delete($p=1, $o=0, $id='')
	{
		$this->redirect_hak_akses('h', "dokumen_sekretariat/index/$kat/$p/$o");
		$_SESSION['success'] = 1;
		$this->web_dokumen_model->delete($id);
		redirect("$this->controller/$p/$o");
	}

	public function delete_all($p=1, $o=0)
	{
		$this->redirect_hak_akses('h', "dokumen_sekretariat/index/$kat/$p/$o");
		$_SESSION['success'] = 1;
		$this->web_dokumen_model->delete_all();
		redirect("dokumen_sekretariat/index/$kat/$p/$o");
	}

	public function dokumen_lock($id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 1);
		redirect("$this->controller/$p/$o");
	}

	public function dokumen_unlock($id='')
	{
		$this->web_dokumen_model->dokumen_lock($id, 2);
		redirect("$this->controller/$p/$o");
	}

	public function dialog_cetak($kat=1)
	{
		redirect("dokumen/dialog_cetak/$kat");
	}

	public function dialog_excel($kat=1)
	{
		redirect("dokumen/dialog_excel/$kat");
	}
}
