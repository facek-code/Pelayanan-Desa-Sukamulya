<?php
class Cdesa_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->model('data_persil_model');
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
			$sql= " AND (u.nama LIKE '$kw' OR c.nama_pemilik_luar like '$kw' OR c.nama_kepemilikan like '$kw' OR c.nomor LIKE '$kw')";
			return $sql;
			}
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
		$sql .= $this->search_sql();
		return $sql;
	}

	public function paging_c_desa($kat='', $mana=0, $p=1)
	{
		
		$sql = "SELECT COUNT(*) AS jml ".$this->main_sql_c_desa();
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
		$sql = "SELECT c.id, c.*, m.id_cdesa_masuk, k.kode, u.nik AS nik, cu.id_pend, p.id_wilayah, COUNT(m.id_cdesa_masuk) AS jumlah, u.nama as namapemilik,
			p.`lokasi`, w.rt, w.rw, w.dusun, c.created_at as tanggal_daftar,
			SUM(IF(k.kode LIke '%S%', m.luas, 0)) as basah,
			SUM(IF(k.kode LIke '%D%', m.luas, 0)) as kering
		";
		$sql .= $this->main_sql_c_desa();
		$sql .= " GROUP BY c.nomor ";
		$sql .= " LIMIT ".$offset.",".$per_page;
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if (($data[$i]['jenis_pemilik']) == 2)
			{
				$data[$i]['namapemilik'] = $data[$i]['nama_pemilik_luar'];
				$data[$i]['nik'] = "-";
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

	public function get_cdesa($id)
	{
		$data = $this->db->where('id', $id)
			->get('cdesa')
			->row_array();
		return $data;
	}

	public function simpan_cdesa()
	{
		$data = array();
		$data['nomor'] = bilangan_spasi($this->input->post('c_desa'));
		$data['nama_kepemilikan'] = nama($this->input->post('nama_kepemilikan'));
		$data['jenis_pemilik'] = $this->input->post('jenis_pemilik');
		$data['nama_pemilik_luar'] = nama($this->input->post('nama_pemilik_luar'));
		$data['alamat_pemilik_luar'] = strip_tags($this->input->post('alamat_pemilik_luar'));
		if ($id_cdesa = $this->input->post('id'))
		{
			$data_lama = $this->db->where('id', $id_c_desa)
				->get('cdesa')->row_array();
			if ($data['nomor'] == $data_lama['nomor']) unset($data['nomor']);
			if ($data['nama_kepemilikan'] == $data_lama['nama_kepemilikan']) unset($data['nama_kepemilikan']);
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
		{
			$this->simpan_pemilik($id_cdesa, $this->input->post('id_pend'));
		} 
		else
		{
			$this->hapus_pemilik($id_cdesa);			
		}
		return $id_cdesa;
	}

	private function hapus_pemilik($id_cdesa)
	{
		$this->db->where('id_cdesa', $id_cdesa)
			->delete('cdesa_penduduk');
	}

	private function simpan_pemilik($id_cdesa, $id_pend)
	{
		// Hapus pemilik lama
		$this->hapus_pemilik($id_cdesa);
		// Tambahkan pemiliki baru
		$data = array();
		$data['id_cdesa'] = $id_cdesa;
		$data['id_pend'] = $id_pend;
		$this->db->insert('cdesa_penduduk', $data);
	}

	public function simpan_mutasi($id_cdesa, $id_bidang, $post)
	{
		$data = array();
		$data['id_persil'] = $this->data_persil_model->simpan_persil($post);
		$data['id_cdesa_masuk'] = $id_cdesa;
		$data['jenis_bidang_persil'] = $post['jenis_bidang_persil'];
		$data['no_bidang_persil'] = bilangan($post['no_bidang_persil']);
		$data['peruntukan'] = $post['peruntukan'];
		$data['no_objek_pajak'] = strip_tags($post['no_objek_pajak']);
		$data['no_sppt_pbb'] = strip_tags($post['no_sppt_pbb']);

		$data['tanggal_mutasi'] = $post['tanggal_mutasi'] ? tgl_indo_in($post['tanggal_mutasi']) : NULL;
		$data['jenis_mutasi'] = $post['jenis_mutasi'] ?: NULL;
		$data['luas'] = bilangan_titik($post['luas']) ?: NULL;
		$data['id_cdesa_keluar'] = bilangan($post['id_cdesa_keluar']) ?: NULL;
		$data['keterangan'] = strip_tags($post['keterangan']) ?: NULL;

		if ($id_bidang)
			$outp = $this->db->where('id', $id_bidang)->update('mutasi_cdesa', $data);
		else
			$outp = $this->db->insert('mutasi_cdesa', $data);
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

	public function hapus_cdesa($id)
	{
		$outp = $this->db->where('id', $id)
			->delete('cdesa');
		status_sukses($outp);
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
			->select('CONCAT("RT ", rt, " / RW ", rw, " - ", dusun) as lokasi, p.lokasi as alamat')
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

	public function list_persil_peruntukan()
	{
		$data = $this->db->order_by('nama')
			->get('data_persil_peruntukan')
			->result_array();
		$result = array_combine(array_column($data, 'id'), $data);
		return $result;
	}

	public function list_persil_jenis()
	{
		$data = $this->db->order_by('nama')
			->get('data_persil_jenis')
			->result_array();
		$result = array_combine(array_column($data, 'id'), $data);
		return $result;
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