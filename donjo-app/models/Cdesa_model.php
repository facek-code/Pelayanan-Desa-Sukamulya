<?php
class Cdesa_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function autocomplete($cari='')
	{
		$sql = "SELECT
					pemilik_luar AS nik
				FROM
					data_persil
				WHERE pemilik_luar LIKE '%$cari%'
				UNION
				SELECT
					p.nama AS nik
				FROM
					data_persil u
				LEFT JOIN tweb_penduduk p ON
					u.id_pend = p.id
				WHERE p.nama LIKE '%$cari%'";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$str = autocomplete_data_ke_str($data);
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (u.nama LIKE '$kw' OR p.pemilik_luar like '$kw' OR y.c_desa LIKE '$kw')";
			return $search_sql;
			}
		}

	private function main_sql()
	{
		$sql = " FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
			 	WHERE 1 ";
		return $sql;
	}

	private function filtered_sql($kat='', $mana=0)
	{
		$sql = $this->main_sql();
		if ($kat == "jenis")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.persil_jenis_id=".$mana.") ";
			}
		}
		elseif($kat == "peruntukan")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.persil_peruntukan_id=".$mana.") ";
			}
		}
				elseif($kat == "kelas")
		{
			if ($mana > 0)
			{
				$sql .= " AND (p.kelas=".$mana.") ";
			}
		}
		$sql .= $this->search_sql();
		return $sql;
	}

	public function paging($kat='', $mana=0, $p=1)
	{
		$sql = "SELECT COUNT(*) AS jml".$this->filtered_sql($kat, $mana);
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function main_sql_c_desa()
	{
		$sql = " FROM cdesa c
				LEFT JOIN mutasi_cdesa m ON m.id_cdesa_masuk = c.id
				LEFT JOIN persil p ON p.id = m.id_persil	
				LEFT JOIN cdesa_penduduk cu ON cu.id_cdesa = c.id
				LEFT JOIN tweb_penduduk u ON u.id = cu.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_wilayah
				LEFT JOIN ref_persil_kelas k ON k.id = p.kelas
				WHERE 1  ";
		return $sql;
	}

	public function paging_c_desa($kat='', $mana=0, $p=1)
	{
		
		$sql = "SELECT COUNT(*) AS jml ".$this->main_sql_c_desa().$this->search_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	public function list_c_desa($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];		
		$strSQL = "SELECT c.id, c.*, m.id_cdesa_masuk, k.kode, u.nik AS nik, cu.id_pend, p.id_wilayah, u.nama as namapemilik, COUNT(m.id_cdesa_masuk) AS jumlah,
			p.`lokasi`, w.rt, w.rw, w.dusun, c.created_at as tanggal_daftar,
			SUM(IF(k.kode LIke '%S%', m.luas, 0)) as basah,
			SUM(IF(k.kode LIke '%D%', m.luas, 0)) as kering
		".$this->main_sql_c_desa().$this->search_sql()." 
		GROUP by c.nomor";

		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
			}
			$j++;
		}

		// $persil = $this->list_c_desa_persil($kat, $mana, $offset, $per_page);
		// $luar = $this->list_c_desa_persil_luar($kat, $mana, $offset, $per_page);
		// $data = array_merge($data, $persil, $luar);
		return $data;
	}

	private function list_c_desa_persil($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];	
		$strSQL = "SELECT p.`id` AS id_persil, p.`id_c_desa` as c_desa, u.nik AS nik, p.`id_pend`, p.id_wilayah, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,COUNT(p.id_c_desa) AS jumlah, p.`lokasi`, w.rt, w.rw, w.dusun, p.rdate as tanggal_daftar, SUM(IF(x.`kode`LIke '%S%', p.`luas`,0)) as basah, SUM(IF(x.`kode`LIke '%D%', p.`luas`,0)) as kering 
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		LEFT JOIN ref_persil_kelas x ON x.id = p.kelas 
		WHERE p.`id_c_desa` = 0 AND p.`id_pend` IS NOT NULL
		GROUP by p.id_pend";
		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
			}
			$j++;
		}
		return $data;
	}

	private function list_c_desa_persil_luar($kat='', $mana=0, $offset, $per_page)
	{
		$data = [];	
		$strSQL = "SELECT p.`id` AS id_persil, p.`id_c_desa` as c_desa, u.nik AS nik, p.`id_pend`, p.`id_clusterdesa`, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`, p.`lokasi`, w.rt, w.rw, w.dusun, p.rdate as tanggal_daftar FROM data_persil p LEFT JOIN tweb_penduduk u ON u.id = p.id_pend LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster LEFT JOIN ref_persil_kelas x ON x.id = p.kelas WHERE p.`id_c_desa` = 0 AND p.`id_pend` IS NULL ";
		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
				$data[$i]['jumlah'] = 1; 
			}
			$j++;

		}

		return $data;
	}

	public function get_persil($id_bidang)
	{
		$data = $this->db->select('p.*')
			->from('mutasi_cdesa m')
			->join('persil p', 'm.id_persil = p.id', 'left')
			->where('m.id', $id_bidang)
			->get()
			->row_array();
		return $data;
	}

	public function get_bidang($id_bidang)
	{
		$data = $this->db->select('m.*, c.nomor as cdesa_keluar')
			->from('mutasi_cdesa m')
			->join('cdesa c', 'c.id = m.id_cdesa_keluar', 'left')
			->where('m.id', $id_bidang)
			->get('')
			->row_array();
		return $data;
	}

	// public function get_persil($id)
	// {
	// 	$data = false;
	// 	$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, x.`tipe`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.`pajak`,  p.pemilik_luar, p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
	// 		FROM `data_persil` p
	// 			LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
	// 			LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
	// 			LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
	// 			LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
	// 		 WHERE p.id = ".$id;
	// 	$query = $this->db->query($strSQL);
	// 	if ($query->num_rows()>0)
	// 	{
	// 		$data = $query->row_array();
	// 	}

	// 	if ($data['jenis_pemilik'] == 2)
	// 	{
	// 		$data['namapemilik'] = $data['pemilik_luar'];
	// 		$data['nik'] = "-";
	// 	}
	// 	return $data;
	// }

	public function get_cdesa($id)
	{
		$data = $this->db->where('id', $id)
			->get('cdesa')
			->row_array();
		return $data;
	}

	public function get_c_desa_persil($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` AS id, p.`id_pend`, u.nik AS nik, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,w.rt, w.rw, w.dusun
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		WHERE p.id = ".$id ;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	public function get_c_desa_id_pend($id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` AS id, p.`id_pend`, u.nik AS nik, p.`jenis_pemilik`, u.`nama` as namapemilik, p.pemilik_luar, p.`alamat_luar`,w.rt, w.rw, w.dusun
		FROM data_persil p 
		LEFT JOIN tweb_penduduk u ON u.id = p.id_pend 
		LEFT JOIN tweb_wil_clusterdesa w ON w.id = u.id_cluster 
		WHERE p.id_pend = ".$id ;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}
		return $data;
	}

	public function list_detail_c_desa($mode, $id)
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, p.`id_clusterdesa`, p.`luas`, 
			p.`kelas`, p.`pajak`,  p.pemilik_luar,
			p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa ";

		$strSQL .=	$this->list_detail_c_desa_mode($mode, $id);

		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		return $data;
	}

	private function list_detail_c_desa_mode($mode, $id)
	{
		if ($mode === 'id_pend') 
		{
		  $sql =  "WHERE p.id_pend = ".$id;			
		}
		elseif($mode === 'persil') 
		{
			$sql = "WHERE p.id = ".$id;
		}
		else
		{
			$sql = "WHERE p.id_c_desa = ".$id;
		}

		return $sql;
	}

	public function list_persil($kat='', $mana=0, $offset, $per_page)
	{
		$strSQL = "SELECT p.`id` as id, u.nik as nik, y.`c_desa`,p.`nama` as nama, p.`jenis_pemilik`, p.`nama` as nopersil, p.`persil_jenis_id`, p.`id_clusterdesa`, p.`luas`, p.`kelas`, p.pemilik_luar,
			p.rdate as tanggal_daftar,p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun".$this->filtered_sql($kat, $mana);
		$strSQL .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
		}
		else
		{
			$_SESSION["pesan"]= $strSQL;
		}

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['pemilik_luar'];
				$data[$i]['nik'] = "-";
			}
			$j++;
		}
		return $data;
	}

	public function simpan_cdesa()
	{
		$data = array();
		$data['nomor'] = $this->input->post('c_desa');
		$data['nama_kepemilikan'] = $this->input->post('nama_kepemilikan');
		$data['jenis_pemilik'] = $this->input->post('jenis_pemilik');
		if ($id_cdesa = $this->input->post('id'))
		{
			$data['updated_by'] = $this->session->user;
			$this->db->where('id', $id_cdesa)
				->update('cdesa', $data);
		}
		else
		{
			$data['created_by'] = $this->session->user;
			$data['updated_by'] = $this->session->user;
			$this->db->insert('cdesa', $data);
			$id_cdesa = $this->db->insert_id();
		}

		if ($this->input->post('jenis_pemilik') == 1) 
			$this->simpan_pemilik($id_cdesa, $this->input->post('id_pend'));
		return $id_cdesa;
	}

	private function simpan_pemilik($id_cdesa, $id_pend)
	{
		// Hapus pemilik lama
		$this->db->where('id_cdesa', $id_cdesa)
			->delete('cdesa_penduduk');
		// Tambahkan pemiliki baru
		$data = array();
		$data['id_cdesa'] = $id_cdesa;
		$data['id_pend'] = $id_pend;
		$this->db->insert('cdesa_penduduk', $data);
	}

	private function simpan_persil($post)
	{
		$data = array();
		$data['kelas'] = $post['kelas'];
		$data['id_wilayah'] = $post['id_wilayah'];
		$id_persil = $post['id_persil'] ?: $this->get_persil_by_nomor($post['no_persil']);
		if ($id_persil)
		{
			$this->db->where('id', $id_persil)
				->update('persil', $data);
		}
		else
		{
			$data['nomor'] = $post['no_persil'];
			$this->db->insert('persil', $data);
			$id_persil = 	$this->db->insert_id();		
		}
		return $id_persil;
 	}

 	private function get_persil_by_nomor($nomor)
 	{
 		$id = $this->db->select('id')
 			->where('nomor', $nomor)
 			->get('persil')->row()->id;
 		return $id;
 	}

	public function simpan_mutasi($id_cdesa, $id_bidang, $post)
	{
		$data = array();
		$data['id_persil'] = $this->simpan_persil($post);
		$data['id_cdesa_masuk'] = $id_cdesa;
		$data['jenis_bidang_persil'] = $post['jenis_bidang_persil'];
		$data['no_bidang_persil'] = $post['no_bidang_persil'];
		$data['peruntukan'] = $post['peruntukan'];
		$data['no_objek_pajak'] = $post['no_objek_pajak'];
		$data['no_sppt_pbb'] = $post['no_sppt_pbb'];

		$data['tanggal_mutasi'] = tgl_indo_in($post['tanggal_mutasi']);
		$data['jenis_mutasi'] = $post['jenis_mutasi'];
		$data['luas'] = $post['luas'];
		$data['id_cdesa_keluar'] = $post['id_cdesa_keluar'] || NULL;
		$data['keterangan'] = strip_tags($post['keterangan']);

		if ($id_bidang)
			$outp = $this->db->where('id', $id_bidang)->update('mutasi_cdesa', $data);
		else
			$outp = $this->db->insert('mutasi_cdesa', $data);
		// if ($_POST["id"] > 0)
		// {
		// 	$data_mutasi['id_persil'] = $_POST["id_persil"];
		// 	$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
		// 	$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
		// 	$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
		// 	$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
		// 	$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
		// 	$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);

		// 	$outp = $this->db->where('id', $_POST['id'])->update('data_persil_mutasi', $data_mutasi);
		// }
		// else
		// {
		// 	if ($_POST["id_persil"] > 0)
		// 	{
		// 		$data_mutasi['id_persil'] = $_POST["id_persil"];
		// 		$data_mutasi['jenis_mutasi'] = strip_tags($_POST["jenis_mutasi"]);
		// 		$data_mutasi['tanggalmutasi'] = tgl_indo_in($_POST["tanggalmutasi"]);
		// 		$data_mutasi['sebabmutasi'] = strip_tags($_POST["sebabmutasi"]);
		// 		$data_mutasi['luasmutasi'] = strip_tags($_POST["luasmutasi"]);
		// 		$data_mutasi['no_c_desa'] = strip_tags($_POST["no_c_desa"]);
		// 		$data_mutasi['keterangan'] = strip_tags($_POST["ket"]);
		// 		$outp = $this->db->insert('data_persil_mutasi', $data_mutasi);
		// 	}
		// }
		if ($outp)
			{
				$_SESSION["success"] = 1;
				$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
				$data["hasil"] = true;
				$data["id"]= $_POST["id_persil"];
				$data['jenis'] = $_POST["jenis"];
			}
		return $data;
	}

	// public function simpan_c_desa()
	// {
	// 	$data = array();
	// 	if ($_POST['id_persil'] > 0)
	// 	{
	// 		$datac['c_desa'] = ltrim($_POST['c_desa'], '0');
	// 		$query = $this->db->get_where('data_persil_c_desa', array('c_desa' => $datac['c_desa']));
	// 		if ($query->num_rows() <= 0)
	// 		{
	// 			$outp = $this->db->insert('data_persil_c_desa', $datac);
	// 			$data['id_c_desa'] = $this->db->insert_id();
	// 		}
	// 		else
	// 		{
	// 			$data['id_c_desa'] = $this->db->result()->id;
	// 		}
	// 		$outp = $this->db->where('id', $_POST['id_persil'])->update('data_persil', $data);
	// 	}
	// 	elseif ($_POST['id_pend'] > 0)
	// 	{
	// 		$datac['id_pend'] =$_POST['id_pend'];
	// 		$datac['c_desa'] = ltrim($_POST['c_desa'], '0');
	// 		$outp = $this->db->insert('data_persil_c_desa', $datac);
	// 		$data['id_c_desa'] = $this->db->insert_id();
	// 		$outp = $this->db->where('id_pend', $_POST['id_pend'])->update('data_persil', $data);
	// 	}
	// 	else
	// 	{
	// 		$data['c_desa'] = ltrim($_POST['c_desa'], '0');
	// 		$outp = $this->db->where('id', $_POST['id_c_desa'])->update('data_persil_c_desa', $data);
	// 	}

	// 	if ($outp)
	// 	{
	// 		$_SESSION["success"] = 1;
	// 		$_SESSION["pesan"] = "Data Persil telah DISIMPAN";
	// 		$hasil = true;
	// 	}
	// 	else
	// 	{
	// 		$_SESSION["success"] = -1;
	// 		$_SESSION["pesan"] = "Gagal Menyimpan data";
	// 	}
	// }

	public function hapus_cdesa($id)
	{
		$outp = $this->db->where('id', $id)
			->delete('cdesa');
		status_sukses($outp);
	}

	public function hapus_persil($id)
	{
		$strSQL = "DELETE FROM `data_persil` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Gagal menghapus data persil";
		}
	}

	public function list_dusunrwrt()
	{
		$strSQL = "SELECT `id`,`rt`,`rw`,`dusun` FROM `tweb_wil_clusterdesa` WHERE (`rt`>0) ORDER BY `dusun`";
		$query = $this->db->query($strSQL);
		return $query->result_array();
	}

	public function get_pemilik($id_cdesa)
	{
		$this->db->select('p.id, p.nik, p.nama, k.no_kk, w.rt, w.rw, w.dusun')
			->select('CONCAT("RT ", rt, " / RW ", rw, " - ", dusun) as alamat')
			->from('cdesa c')
			->join('cdesa_penduduk cp', 'c.id = cp.id_cdesa', 'left')
			->join('tweb_penduduk p', 'p.id = cp.id_pend', 'left')
			->join('tweb_keluarga k','k.id = p.id_kk', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left')
			->where('c.id', $id_cdesa);
		$data = $this->db->get()->row_array();
		return $data;
	}

	public function get_list_bidang($id_cdesa)
	{
		$this->db
			->select('m.*, p.nomor, rk.kode as kelas_tanah, dp.nama as peruntukan, dj.nama as jenis_persil')
			->select('CONCAT("RT ", rt, " / RW ", rw, " - ", dusun) as lokasi')
			->from('mutasi_cdesa m')
			->join('cdesa c', 'c.id = m.id_cdesa_masuk', 'left')
			->join('persil p', 'p.id = m.id_persil', 'left')
			->join('data_persil_peruntukan dp', 'm.peruntukan = dp.id', 'left')
			->join('data_persil_jenis dj', 'm.jenis_bidang_persil = dj.id', 'left')
			->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_wilayah', 'left')
			->where('m.id_cdesa_masuk', $id_cdesa);
		$data = $this->db->get()->result_array();
		return $data;
	}

	public function get_penduduk($id, $nik=false)
	{
		$this->db->select('p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun')
			->from('tweb_penduduk p')
			->join('tweb_keluarga k','k.id = p.id_kk', 'left')
			->join('tweb_wil_clusterdesa w', 'w.id = p.id_cluster', 'left');
		if ($nik)
			$this->db->where('p.nik', $id);
		else
			$this->db->where('p.id', $id);
		$data = $this->db->get()->row_array();
		return $data;
	}

	public function list_penduduk()
	{
		$strSQL = "SELECT p.nik,p.nama,k.no_kk,w.rt,w.rw,w.dusun FROM tweb_penduduk p
			LEFT JOIN tweb_keluarga k ON k.id = p.id_kk
			LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_cluster
			WHERE 1 ORDER BY nama";
		$query = $this->db->query($strSQL);
		$data = "";
		$data = $query->result_array();
		if ($query->num_rows() > 0)
		{
			$j = 0;
			for ($i=0; $i<count($data); $i++)
			{
				if ($data[$i]['nik'] != "")
				{
					$data1[$j]['id']=$data[$i]['nik'];
					$data1[$j]['nik']=$data[$i]['nik'];
					$data1[$j]['nama']=strtoupper($data[$i]['nama'])." [NIK: ".$data[$i]['nik']."] / [NO KK: ".$data[$i]["no_kk"]."]";
					$data1[$j]['info']= "RT/RW ". $data[$i]['rt']."/".$data[$i]['rw']." - ".strtoupper($data[$i]['dusun']);
					$j++;
				}
			}
			$hasil2 = $data1;
		}
		else
		{
			$hasil2 = false;
		}
		return $hasil2;
	}

	public function list_persil_peruntukan()
	{
		$data = $this->db->order_by('nama')
			->get('data_persil_peruntukan')
			->result_array();
		$result = array_combine(array_column($data, 'id'), $data);
		return $result;
	}

	public function get_persil_peruntukan($id=0)
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_peruntukan WHERE id=".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}

	public function update_persil_peruntukan()
	{
		if ($this->input->post('id') == 0)
		{
			$strSQL = "INSERT INTO `data_persil_peruntukan`(`nama`,`ndesc`) VALUES('".fixSQL($this->input->post('nama'))."','".fixSQL($this->input->post('ndesc'))."')";
		}
		else
		{
			$strSQL = "UPDATE `data_persil_peruntukan` SET
			`nama` = '".fixSQL($this->input->post('nama'))."',
			`ndesc` = '".fixSQL($this->input->post('ndesc'))."'
			 WHERE id = ".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$data["transaksi"] = true;
			$data["pesan"] = "Data Peruntukan Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}
		else
		{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}

	public function hapus_peruntukan($id)
	{
		$strSQL = "DELETE FROM `data_persil_peruntukan` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Peruntukan Tanah telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}

	public function list_persil_jenis()
	{
		$data = $this->db->order_by('nama')
			->get('data_persil_jenis')
			->result_array();
		$result = array_combine(array_column($data, 'id'), $data);
		return $result;
	}

	public function get_persil_jenis($id=0)
	{
		$data = false;
		$strSQL = "SELECT id,nama,ndesc FROM data_persil_jenis WHERE id = ".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}

	public function update_persil_jenis()
	{
		if ($this->input->post('id') == 0)
		{
			$strSQL = "INSERT INTO `data_persil_jenis`(`nama`,`ndesc`) VALUES('".strtoupper(fixSQL($this->input->post('nama')))."','".fixSQL($this->input->post('ndesc'))."')";
		}
		else
		{
			$strSQL = "UPDATE `data_persil_jenis` SET
			`nama`='".strtoupper(fixSQL($this->input->post('nama')))."',
			`ndesc`='".fixSQL($this->input->post('ndesc'))."'
			 WHERE id=".$this->input->post('id');
		}

		$data["db"] = $strSQL;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$data["transaksi"] = true;
			$data["pesan"] = "Data Jenis Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Tanah ".fixSQL($this->input->post('nama'))." telah disimpan/diperbarui";
		}
		else
		{
			$data["transaksi"] = false;
			$data["pesan"] = "ERROR ".$strSQL;
		}
		return $data;
	}

	public function hapus_jenis($id)
	{
		$strSQL = "DELETE FROM `data_persil_jenis` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Jenis Tanah telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
		}
	}

	public function list_persil_kelas($table='')
	{
		if($table)
		{	$data =$this->db->order_by('kode') 
						->get_where('ref_persil_kelas', array('tipe' => $table))
						->result_array();
			$data = array_combine(array_column($data, 'id'), $data);
		}
		else
		{
			$data = $this->db->order_by('kode')
			->get('ref_persil_kelas')
			->result_array();
			$data = array_combine(array_column($data, 'id'), $data);
		}
		
		return $data;
	}

	public function list_persil_jenis_mutasi($table='')
	{
		$data = $this->db->order_by('id')
		->get('ref_persil_jenis_mutasi')
		->result_array();
		$data = array_combine(array_column($data, 'id'), $data);
		
		return $data;
	}

	public function list_persil_mutasi($id=0)
	{
		$this->db->select('m.*, p.nama as sebabmutasi')
			->from('data_persil_mutasi m')
			->join('ref_persil_mutasi p','m.sebabmutasi = p.id', 'left')
			->where('m.id_persil',$id);
		$data = $this->db->get()->result_array();

		$data = array_combine(array_column($data, 'id'), $data);		
		return $data;
	}

	public function get_persil_mutasi($id=0)
	{
		$this->db->select('m.*, p.nama, c.c_desa')
			->from('data_persil_mutasi m')
			->join('data_persil p','m.id = p.id', 'left')
			->join('data_persil_c_desa c','p.id_c_desa = c.id', 'left')
			->where('m.id', $id);
		$data = $this->db->get()->row_array();

		$data['tanggalmutasi'] = tgl_indo_out($data['tanggalmutasi']);		
		return $data;
	}

	public function hapus_mutasi($id)
	{
		$strSQL = "DELETE FROM `data_persil_mutasi` WHERE id = ".$id;
		$hasil = $this->db->query($strSQL);
		if ($hasil)
		{
			$_SESSION["success"] = 1;
			$_SESSION["pesan"] = "Data Persil telah dihapus";
		}
		else
		{
			$_SESSION["success"] = -1;
			$_SESSION["pesan"] = "Gagal menghapus data persil";
		}
	}

	public function get_persil_kelas($id=0)
	{
		$data = false;
		$strSQL = "SELECT id, kode, tipe, ndesc FROM ref_persil_kelas WHERE id = ".$id;
		$query = $this->db->query($strSQL);
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data[$id] = $query->row_array();
		}
		return $data;
	}

	public function impor_persil()
	{
		$this->load->library('Spreadsheet_Excel_Reader');
		$data = new Spreadsheet_Excel_Reader($_FILES['persil']['tmp_name']);

		$sheet = 0;
		$baris = $data->rowcount($sheet_index = $sheet);
		$kolom = $data->colcount($sheet_index = $sheet);

		for ($i=2; $i<=$baris; $i++)
		{
			$nik = $data->val($i, 2, $sheet);
			$upd['id_pend'] = $this->db->select('id')->
						where('nik', $nik)->
						get('tweb_penduduk')->row()->id;
			$upd['nama'] = $data->val($i, 3, $sheet);
			$upd['persil_jenis_id'] = $data->val($i, 4, $sheet);
			$upd['id_clusterdesa'] = $data->val($i, 5, $sheet);
			$upd['luas'] = $data->val($i, 6, $sheet);
			$upd['kelas'] = $data->val($i, 7, $sheet);
			$upd['no_sppt_pbb'] = $data->val($i, 8, $sheet);
			$upd['persil_peruntukan_id'] = $data->val($i, 9, $sheet);
			$outp = $this->db->insert('data_persil',$upd);
		}

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_c_cetak($id, $tipe='')
	{
		$data = false;
		$strSQL = "SELECT p.`id` as id, u.`nik` as nik, y.`c_desa`, p.`jenis_pemilik` as jenis_pemilik, p.`nama` as nopersil, p.id_pend, p.`id_c_desa`, p.`persil_jenis_id`, kelas, x.`kode`, p.`id_clusterdesa`, p.`luas`, 
			p.`kelas`, p.`pajak`,  p.pemilik_luar,
			p.`no_sppt_pbb`, p.`lokasi`, p.`persil_peruntukan_id`, u.nama as namapemilik, w.rt, w.rw, w.dusun,alamat_luar, m.jenis_mutasi, m.tanggalmutasi, rm.nama as sebabmutasi, m.luasmutasi, m.no_c_desa, m.keterangan
			FROM `data_persil` p
				LEFT JOIN tweb_penduduk u ON u.id = p.id_pend
				LEFT JOIN tweb_wil_clusterdesa w ON w.id = p.id_clusterdesa
				LEFT JOIN ref_persil_kelas x ON x.id = p.kelas
				LEFT JOIN data_persil_c_desa y ON y.id = p.id_c_desa
				LEFT JOIN data_persil_mutasi m ON m.id_persil = p.id
				LEFT JOIN ref_persil_mutasi rm ON m.sebabmutasi = rm.id

			 WHERE p.id_c_desa = ".$id." AND x.kode LIKE '%".$tipe."%'";
		$query = $this->db->query($strSQL);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}

		if ($data['jenis_pemilik'] == 2)
		{
			$data['namapemilik'] = $data['pemilik_luar'];
			$data['nik'] = "-";
		}
		$hasil=[];
		$count= count($data)-1;
		for ($x = 0; $x <= $count; $x++)
		{
			$hasil[]= $data[$x];
			if( $data[$x]['id']!= $data[$x+1]['id'])
				$hasil[]=[];
		}
		return $hasil;
	}
}
?>