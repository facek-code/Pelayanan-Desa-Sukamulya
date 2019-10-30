<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate_rbac extends CI_Controller {

    public function __construct() {

        parent::__construct();

         $this->load->library('migration');

    }

    public function index() {

        if (!$this->migration->current()) {

            show_error($this->migration->error_string());

        } else {

            echo 'Proses Migrasi ke RBAC selesai!';
            redirect('auth/login');

        }

    }

}
