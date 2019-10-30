<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Install_rbac extends CI_Migration {
	private $tables;

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();

		$this->load->config('ion_auth', TRUE);
		$this->tables = $this->config->item('tables', 'ion_auth');
	}

	public function up() {
		// Drop table 'groups' if it exists
		$this->dbforge->drop_table($this->tables['groups'], TRUE);

		// Table structure for table 'groups'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'name' => [
				'type'       => 'VARCHAR',
				'constraint' => '20',
			],
			'description' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->tables['groups']);

		// Dumping data for table 'groups'
		$data = [
			[
				'name'        => 'admin',
				'description' => 'Administrator'
			],
			[
				'name'        => 'operator',
				'description' => 'Operator'
			],
[
				'name'        => 'redaksi',
				'description' => 'Redaksi'
			],
			[
				'name'        => 'kontributor',
				'description' => 'Kontributor'
			]
		];
		$this->db->insert_batch($this->tables['groups'], $data);

		// Drop table 'users' if it exists
		$this->dbforge->drop_table($this->tables['users'], TRUE);

		// Table structure for table 'users'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'ip_address' => [
				'type'       => 'VARCHAR',
				'constraint' => '45'
			],
			'username' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
			],
			'password' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
			],
			'email' => [
				'type'       => 'VARCHAR',
				'constraint' => '254',
				'unique' => TRUE
			],
			'activation_selector' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE,
				'unique' => TRUE
			],
			'activation_code' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'forgotten_password_selector' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE,
				'unique' => TRUE
			],
			'forgotten_password_code' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'forgotten_password_time' => [
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			],
			'remember_selector' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE,
                'unique' => TRUE
			],
			'remember_code' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'created_on' => [
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
			],
			'last_login' => [
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			],
			'active' => [
				'type'       => 'TINYINT',
				'constraint' => '1',
				'unsigned'   => TRUE,
				'null'       => TRUE
			],
			'first_name' => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'null'       => TRUE
			],
			'last_name' => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'null'       => TRUE
			],
			'company' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null'       => TRUE
			],
			'phone' => [
				'type'       => 'VARCHAR',
				'constraint' => '20',
				'null'       => TRUE
			],
			'user_img' => [
				'type' => 'TEXT',
				'null' => TRUE,

			],
			'id_grup' => [
				'type' => 'INT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'null' => TRUE,

			]

		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->tables['users']);

		// Dumping data for table 'users'
		$data = [
			'ip_address'              => '127.0.0.1',
			'username'                => 'adminrbac',
			'password'                => '$2y$12$VCCzq5PRAqu35pCZvB9OTu2zFeWojWzzi8CDod4IJgPftkZKc4bDi',
			'email'                   => 'adminrbac@opendesa.id',
			'activation_code'         => '',
			'forgotten_password_code' => NULL,
			'created_on'              => '1268889823',
			'last_login'              => '1268889823',
			'active'                  => '1',
			'first_name'              => 'admin',
			'last_name'               => 'rbac',
			'company'                 => 'opendesa',
			'phone'                   => '0',
			'user_img'                => 'kuser.png',
			'id_grup'                 => '1',
		];
		$this->db->insert($this->tables['users'], $data);


		// Drop table 'users_groups' if it exists
		$this->dbforge->drop_table($this->tables['users_groups'], TRUE);

		// Table structure for table 'users_groups'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'user_id' => [
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE
			],
			'group_id' => [
				'type'       => 'MEDIUMINT',
				'constraint' => '8',
				'unsigned'   => TRUE
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->tables['users_groups']);

		// Dumping data for table 'users_groups'
		$data = [
			[
				'user_id'  => '1',
				'group_id' => '1',
			]
			
		];
		$this->db->insert_batch($this->tables['users_groups'], $data);


		// Drop table 'login_attempts' if it exists
		$this->dbforge->drop_table($this->tables['login_attempts'], TRUE);
		
		// Table structure for table 'login_attempts'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'MEDIUMINT',
				'constraint'     => '8',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'ip_address' => [
				'type'       => 'VARCHAR',
				'constraint' => '45'
			],
			'login' => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null'       => TRUE
			],
			'time' => [
				'type'       => 'INT',
				'constraint' => '11',
				'unsigned'   => TRUE,
				'null'       => TRUE
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->tables['login_attempts']);

		// Drop table 'group_perm' if it exists
		$this->dbforge->drop_table($this->tables['group_perm'], TRUE);

		// Table structure for table 'group_perm'
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'group_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => FALSE,

			),
			'perm_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => TRUE,

			),
			'create_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => TRUE,


			),
			'update_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => TRUE,


			),
			'delete_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => TRUE,


			),
			'print_id' => array(
				'type' => 'MEDIUMINT',
				'constraint' => 8,
				'unsigned' => TRUE,
				'null' => TRUE,

			)
		));
		$this->dbforge->add_key("id",true);
		$this->dbforge->create_table($this->tables['group_perm']);

		// Menambahkan Template Hak Akses     
		$query = "
			INSERT INTO `group_perm` (`id`, `group_id`, `perm_id`, `create_id`, `update_id`, `delete_id`, `print_id`) VALUES
			(1, 1, 1, 0, 0, 0, 0),
			(2, 1, 200, 0, 0, 0, 0),
			(3, 1, 2, 0, 0, 0, 0),
			(4, 1, 3, 0, 0, 0, 0),
			(5, 1, 4, 0, 0, 0, 0),
			(6, 1, 15, 0, 0, 0, 0),
			(7, 1, 5, 0, 0, 0, 0),
			(8, 1, 201, 0, 0, 0, 0),
			(9, 1, 6, 0, 0, 0, 0),
			(10, 1, 7, 0, 0, 0, 0),
			(11, 1, 9, 0, 0, 0, 0),
			(12, 1, 10, 0, 0, 0, 0),
			(13, 1, 11, 0, 0, 0, 0),
			(14, 1, 13, 0, 0, 0, 0),
			(15, 1, 14, 0, 0, 0, 0),
			(16, 1, 17, 17, 17, 17, 17),
			(17, 1, 20, 20, 20, 20, 20),
			(18, 1, 18, 18, 18, 18, 18),
			(19, 1, 21, 21, 21, 21, 21),
			(20, 1, 22, 22, 22, 22, 22),
			(21, 1, 23, 23, 23, 23, 23),
			(22, 1, 24, 24, 24, 24, 24),
			(23, 1, 25, 25, 25, 25, 25),
			(24, 1, 26, 26, 26, 26, 26),
			(25, 1, 27, 27, 27, 27, 27),
			(26, 1, 28, 28, 28, 28, 28),
			(27, 1, 29, 29, 29, 29, 29),
			(28, 1, 30, 30, 30, 30, 30),
			(29, 1, 31, 31, 31, 31, 31),
			(30, 1, 32, 32, 32, 32, 32),
			(31, 1, 33, 33, 33, 33, 33),
			(32, 1, 57, 57, 57, 57, 57),
			(33, 1, 58, 58, 58, 58, 58),
			(34, 1, 59, 59, 59, 59, 59),
			(35, 1, 60, 60, 60, 60, 60),
			(36, 1, 61, 61, 61, 61, 61),
			(37, 1, 63, 63, 63, 63, 63),
			(38, 1, 67, 67, 67, 67, 67),
			(39, 1, 68, 68, 68, 68, 68),
			(40, 1, 69, 69, 69, 69, 69),
			(41, 1, 70, 70, 70, 70, 70),
			(42, 1, 71, 71, 71, 71, 71),
			(43, 1, 72, 72, 72, 72, 72),
			(44, 1, 73, 73, 73, 73, 73),
			(45, 1, 202, 202, 202, 202, 202),
			(46, 1, 203, 203, 203, 203, 203),
			(47, 1, 205, 205, 205, 205, 205),
			(48, 1, 206, 206, 206, 206, 206),
			(49, 1, 62, 62, 62, 62, 62),
			(50, 1, 8, 8, 8, 8, 8),
			(51, 1, 39, 39, 39, 39, 39),
			(52, 1, 40, 40, 40, 40, 40),
			(53, 1, 41, 41, 41, 41, 41),
			(54, 1, 42, 42, 42, 42, 42),
			(55, 1, 43, 43, 43, 43, 43),
			(56, 1, 44, 44, 44, 44, 44),
			(57, 1, 204, 204, 204, 204, 204),
			(58, 1, 45, 45, 45, 45, 45),
			(59, 1, 46, 46, 46, 46, 46),
			(60, 1, 47, 47, 47, 47, 47),
			(61, 1, 48, 48, 48, 48, 48),
			(62, 1, 49, 49, 49, 49, 49),
			(63, 1, 50, 50, 50, 50, 50),
			(64, 1, 51, 51, 51, 51, 51),
			(65, 1, 52, 52, 52, 52, 52),
			(66, 1, 53, 53, 53, 53, 53),
			(67, 1, 54, 54, 54, 54, 54),
			(68, 1, 64, 64, 64, 64, 64),
			(69, 1, 55, 55, 55, 55, 55),
			(70, 1, 56, 56, 56, 56, 56),
			(71, 2, 1, 0, 0, 0, 0),
			(72, 2, 200, 0, 0, 0, 0),
			(73, 2, 2, 0, 0, 0, 0),
			(74, 2, 3, 0, 0, 0, 0),
			(75, 2, 4, 0, 0, 0, 0),
			(76, 2, 15, 0, 0, 0, 0),
			(77, 2, 5, 0, 0, 0, 0),
			(78, 2, 201, 0, 0, 0, 0),
			(79, 2, 6, 0, 0, 0, 0),
			(80, 2, 7, 0, 0, 0, 0),
			(81, 2, 9, 0, 0, 0, 0),
			(82, 2, 10, 0, 0, 0, 0),
			(83, 2, 11, 0, 0, 0, 0),
			(84, 2, 13, 0, 0, 0, 0),
			(85, 2, 14, 0, 0, 0, 0),
			(86, 2, 17, 17, 17, 17, 17),
			(87, 2, 20, 20, 20, 20, 20),
			(88, 2, 18, 18, 18, 18, 18),
			(89, 2, 21, 21, 21, 21, 21),
			(90, 2, 22, 22, 22, 22, 22),
			(91, 2, 23, 23, 23, 23, 23),
			(92, 2, 24, 24, 24, 24, 24),
			(93, 2, 25, 25, 25, 25, 25),
			(94, 2, 26, 26, 26, 26, 26),
			(95, 2, 27, 27, 27, 27, 27),
			(96, 2, 28, 28, 28, 28, 28),
			(97, 2, 29, 29, 29, 29, 29),
			(98, 2, 30, 30, 30, 30, 30),
			(99, 2, 31, 31, 31, 31, 31),
			(100, 2, 32, 32, 32, 32, 32),
			(101, 2, 33, 33, 33, 33, 33),
			(102, 2, 57, 57, 57, 57, 57),
			(103, 2, 58, 58, 58, 58, 58),
			(104, 2, 59, 59, 59, 59, 59),
			(105, 2, 60, 60, 60, 60, 60),
			(106, 2, 61, 61, 61, 61, 61),
			(107, 2, 63, 63, 63, 63, 63),
			(108, 2, 67, 67, 67, 67, 67),
			(109, 2, 68, 68, 68, 68, 68),
			(110, 2, 69, 69, 69, 69, 69),
			(111, 2, 70, 70, 70, 70, 70),
			(112, 2, 71, 71, 71, 71, 71),
			(113, 2, 72, 72, 72, 72, 72),
			(114, 2, 73, 73, 73, 73, 73),
			(115, 2, 202, 202, 202, 202, 202),
			(116, 2, 203, 203, 203, 203, 203),
			(117, 2, 205, 205, 205, 205, 205),
			(118, 2, 206, 206, 206, 206, 206),
			(119, 2, 62, 62, 62, 62, 62),
			(120, 2, 8, 8, 8, 8, 8),
			(121, 2, 39, 39, 39, 39, 39),
			(122, 2, 40, 40, 40, 40, 40),
			(123, 2, 41, 41, 41, 41, 41),
			(124, 2, 42, 42, 42, 42, 42),
			(125, 2, 47, 47, 47, 47, 47),
			(126, 2, 48, 48, 48, 48, 48),
			(127, 2, 49, 49, 49, 49, 49),
			(128, 2, 50, 50, 50, 50, 50),
			(129, 2, 51, 51, 51, 51, 51),
			(130, 2, 52, 52, 52, 52, 52),
			(131, 2, 53, 53, 53, 53, 53),
			(132, 2, 54, 54, 54, 54, 54),
			(133, 2, 64, 64, 64, 64, 64),
			(134, 2, 55, 55, 55, 55, 55),
			(135, 2, 56, 56, 56, 56, 56),
			(136, 3, 13, 0, 0, 0, 0),
			(137, 3, 47, 47, 47, 47, 47),
			(138, 3, 48, 48, 48, 48, 48),
			(139, 3, 49, 49, 49, 49, 49),
			(140, 3, 50, 50, 50, 50, 50),
			(141, 3, 51, 51, 51, 51, 51),
			(142, 3, 52, 52, 52, 52, 52),
			(143, 3, 53, 53, 53, 53, 53),
			(144, 3, 54, 54, 54, 54, 54),
			(145, 3, 64, 64, 64, 64, 64),
			(146, 4, 13, 0, 0, 0, 0),
			(147, 4, 47, 47, 47, 47, 47),
			(148, 4, 48, 48, 48, 48, 48),
			(149, 4, 49, 49, 49, 49, 49),
			(150, 4, 50, 50, 50, 50, 50),
			(151, 4, 51, 51, 51, 51, 51),
			(152, 4, 52, 52, 52, 52, 52),
			(153, 4, 53, 53, 53, 53, 53),
			(154, 4, 54, 54, 54, 54, 54),
			(155, 4, 64, 64, 64, 64, 64);

				";

		$this->db->query($query);


	       // Ubah no urut menu karena ada penambahan menu 'Group / Hak Akses' di table 'setting_modul'
	       $this->db->where('id', '11')->update('setting_modul', array('url' => ''));
	       $this->db->where('id', '44')->update('setting_modul', array('url' => 'users'));
	       $this->db->where('id', '45')->update('setting_modul', array('urut' => '5'));
	       $this->db->where('id', '46')->update('setting_modul', array('urut' => '6'));

	     
	      // Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
			$data = array();
			$data[] = array(
				'id'=>'204',
				'modul'=>'Group / Hak Akses',
				'url'=>'user_groups',
		                'aktif'=>'1',
		                'ikon'=>'fa-users',
		                'urut'=>'4',
		                'level'=>'1',
		                'hidden'=>'0',
				'ikon_kecil'=>'',
				'parent'=>11);
	      // Menambahkan sub menu 'Bantuan' ke table 'setting_modul'
			$data[] = array(
				'id'=>'205',
				'modul'=>'Bantuan',
				'url'=>'program_bantuan/clear',
		                'aktif'=>'1',
		                'ikon'=>'fa-heart',
		                'urut'=>'1',
		                'level'=>'1',
		                'hidden'=>'0',
				'ikon_kecil'=>'',
				'parent'=>6);
	      // Menambahkan sub menu 'Pertanahan' ke table 'setting_modul'
			$data[] = array(
				'id'=>'206',
				'modul'=>'Pertanahan',
				'url'=>'data_persil/clear',
		                'aktif'=>'1',
		                'ikon'=>'fa-map-signs',
		                'urut'=>'1',
		                'level'=>'1',
		                'hidden'=>'0',
				'ikon_kecil'=>'',
				'parent'=>7);
			foreach ($data as $modul)
			{
				$sql = $this->db->insert_string('setting_modul', $modul);
				$sql .= " ON DUPLICATE KEY UPDATE
						id = VALUES(id),
		                                modul = VALUES(modul),
		                                url = VALUES(url),
		                                aktif = VALUES(aktif),
		                                ikon = VALUES(ikon),
						urut = VALUES(urut),
						level = VALUES(level),
		                                hidden = VALUES(hidden),
						ikon_kecil = VALUES(ikon_kecil),
						parent = VALUES(parent)";
				$this->db->query($sql);
			}

	     
		// Salin Data Pengguna yg sudah ada di table 'user' ke table 'users'
		$query = "  
		INSERT INTO `users` (`ip_address`, `created_on`, `username`, `password`, `email`, `last_login`, `active`, `first_name`, `company`, `phone`, `user_img`, `id_grup`) SELECT '127.0.0.1', CAST(user.last_login AS DATE), `username`, `password`, CONCAT(user.username, '@opensid.id'), CAST(user.last_login AS DATE), `active`, `nama`, `company`, `phone`, `foto`, `id_grup` FROM `user`;
	      ";

		$this->db->query($query);

		// Menambahkan Data Pengguna yg sudah ada ke dalam Group
	       	$query = "
		INSERT INTO `users_groups` (`user_id`, `group_id`) SELECT `id`, `id_grup` FROM `users` WHERE id > 1; 
	       ";

		$this->db->query($query);

		
	}

	public function down() {
		$this->dbforge->drop_table($this->tables['users'], TRUE);
		$this->dbforge->drop_table($this->tables['groups'], TRUE);
		$this->dbforge->drop_table($this->tables['users_groups'], TRUE);
		$this->dbforge->drop_table($this->tables['login_attempts'], TRUE);
		$this->dbforge->drop_table($this->tables['group_perm'], TRUE);
		
	}
}
