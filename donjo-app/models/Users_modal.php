<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_modal extends CI_Model 
{
	// get All Users
	public function all_users()
	{
		$this->db->order_by('id','desc');
		$this->db->limit(5);
		$query = $this->db->get('users');
		return $query->result();
	}

	//Count Users
	public function count_users()
	{
		$this->db->select('*');
		$this->db->from('users');
		return $this->db->count_all_results();
	}

	public function recent_users()
	{
		$this->db->where('date', date('Y-m-d'));
		$query = $this->db->get('users');
		return $count = $query->num_rows();
	}

	public function weekly_data()
	{
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('DATE > DATE_SUB(NOW(), INTERVAL 1 WEEK)');
		return $this->db->count_all_results();
	}

	public function get_user_privileges($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.perm_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

       public function get_user_privileges_create($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.create_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_update($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.update_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_delete($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.delete_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

        public function get_user_privileges_print($id)
	{
		$this->db->select('*')
				 ->from('groups')
				 ->join('group_perm', "groups.id = group_perm.group_id")
				 ->join('setting_modul', "setting_modul.id = group_perm.print_id")
				 ->where('group_perm.group_id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}

              
	public function remove_from_privileges($privilege_ids=false, $group_id=false)
	{
		// group id is required
		if(empty($group_id))
		{
			return FALSE;
		}

		// if privilege id(s) are passed remove privilege from the group(s)
		
		if(!is_array($privilege_ids))
		{
			$privilege_ids = array($privilege_ids);
		}

		foreach($privilege_ids as $privilege_id)
		{
			$this->db->select('*')
					 ->from('group_perm')
					 ->join('groups', "groups.id = group_perm.group_id")
					 ->join('setting_modul', "setting_modul.id = group_perm.perm_id")
					 ->where('group_perm.group_id',$group_id);
			$this->db->delete(); 	
		}

		return TRUE;
	}

        public function get_group_users($group)
	{
		$this->db->select('email')
		         ->from('groups')
		         ->join('users_groups','groups.id = users_groups.group_id')
		         ->join('users','users.id = users_groups.user_id')
		         ->where('groups.id',$group);
		$query = $this->db->get();
		return $query->result();
	}


        function list_data_main()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE parent = 0 ";
                $sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}
		return $data;
	}


       function list_data_sub()
	{
		$sql = "SELECT u.* FROM setting_modul u WHERE parent <> 0 ";
                $sql .= ' ORDER BY urut';
		$query = $this->db->query($sql);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
		}
		return $data;
	}


       public function list_sub_modul($modul_id=1)
	{
		$data	= $this->db->select('*')->where('parent', $modul_id)->order_by('urut')->get('setting_modul')->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['modul'] = str_ireplace('[desa]', ucwords($this->setting->sebutan_desa), $data[$i]['modul']);
		}
		return $data;
	}

        
       public function autocomplete()
	{
		$sql = "SELECT username FROM users UNION SELECT first_name FROM users";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$out = '';
		for ($i=0; $i < count($data); $i++)
		{
			$out .= ",'".$data[$i]['username']."'";
		}
		return '['.strtolower(substr($out, 1)).']';
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$keyword = $_SESSION['cari'];
			$keyword = '%'.$this->db->escape_like_str($keyword).'%';
			$search_sql = " AND (u.username LIKE '$keyword' OR u.first_name LIKE '$keyword')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$filter = $_SESSION['filter'];
			$filter_sql = " AND p.group_id = $filter";
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
		$sql = " FROM users u
                         LEFT JOIN users_groups p ON u.id = p.user_id
                         LEFT JOIN groups m ON p.group_id = m.id
                         WHERE 1 ";
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
				$order_sql = ' ORDER BY u.username';
				break;
			case 2:
				$order_sql = ' ORDER BY u.username DESC';
				break;
			case 3:
				$order_sql = ' ORDER BY u.first_name';
				break;
			case 4:
				$order_sql = ' ORDER BY u.first_name DESC';
				break;
                        case 5:
				$order_sql = ' ORDER BY u.email';
				break;
			case 6:
				$order_sql = ' ORDER BY u.email DESC';
				break;
			case 7:
				$order_sql = ' ORDER BY m.name';
				break;
			case 8:
				$order_sql = ' ORDER BY m.name DESC';
				break;
			default:
				$order_sql = ' ORDER BY u.username';
		}
		// Paging sql
		$paging_sql = ' LIMIT '.$offset.','.$limit;
		// Query utama
		$sql = "SELECT u.*, m.name AS grup " . $this->list_data_sql();
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
