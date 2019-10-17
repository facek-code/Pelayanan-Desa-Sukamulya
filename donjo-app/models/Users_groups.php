<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_groups extends CI_Model 
{

	public function check_group($table,$group_name)
	{
		$this->db->where('name', $group_name);
		$query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
          return TRUE;
        }
        else
        {
          return FALSE;
        }
	}
      
	public function update($id,$data,$table)
	{
		if (empty($id)) return FALSE;
		$this->db->where($id);
		$this->db->update($table,$data);
		return TRUE;
	}


        public function autocomplete()
	{
		$sql = "SELECT name FROM groups UNION SELECT description FROM groups";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$out = '';
		for ($i=0; $i < count($data); $i++)
		{
			$out .= ",'".$data[$i]['name']."'";
		}
		return '['.strtolower(substr($out, 1)).']';
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$keyword = $_SESSION['cari'];
			$keyword = '%'.$this->db->escape_like_str($keyword).'%';
			$search_sql = " AND (u.name LIKE '$keyword' OR u.description LIKE '$keyword')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$filter = $_SESSION['filter'];
			$filter_sql = " AND u.id = $filter";
			return $filter_sql;
		}
	}

	public function paging($page = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $page;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM groups u WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($order = 0, $offset = 0, $limit = 500)
	{
		// Ordering sql
		switch($order)
		{
			case 1 :
				$order_sql = ' ORDER BY u.name';
				break;
			case 2:
				$order_sql = ' ORDER BY u.name DESC';
				break;
			case 3:
				$order_sql = ' ORDER BY u.description';
				break;
			case 4:
				$order_sql = ' ORDER BY u.description DESC';
				break;
                        
			default:
				$order_sql = ' ORDER BY u.name';
		}
		// Paging sql
		$paging_sql = ' LIMIT '.$offset.','.$limit;
		// Query utama
		$sql = "SELECT u.*, u.name AS grup " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		// Formating output
		$j = $offset;
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}


	public function list_group()
	{
		$sql = "SELECT * FROM groups WHERE 1 ";

		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}
        
}

