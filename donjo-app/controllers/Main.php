<?php
class Main extends CI_Controller
{
	function index(){
		if (isset($_SESSION['siteman']) AND $_SESSION['siteman'] == 1){
			$this->load->model('user_model');
			$grup	= $this->user_model->sesi_grup($_SESSION['sesi']);
			switch($grup){
				case 1: redirect('hom_desa'); break;
				case 2: redirect('hom_desa'); break;
				case 3: redirect('web'); break;
				case 4: redirect('web'); break;
				default: {
					if ($this->setting->offline_mode > 0) {
						redirect('siteman');

					} else {
						redirect('first');
					}
				}
			}

		// Jika offline_mode aktif, tidak perlu menampilkan halaman website
		} else if ($this->setting->offline_mode > 0) {
			redirect('siteman');

		} else {
			redirect('first');
		}

	}
}
