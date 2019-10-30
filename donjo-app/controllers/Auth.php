<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('form_validation',"email"));
		$this->load->helper(array('html','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->load->config('ion_auth');

		$this->lang->load('auth');
		$this->load->model(array('Users_modal','Users_groups','common_model'));
		
		//Load user group for privallegs
		$this->ion_auth->get_user_group();
                session_start();
                 
	}
	
	// redirect if needed, otherwise display the user list
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
                       // set the flash data error message if there is one
		       $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
          	       
                       redirect('hom_sid', 'refresh');
                       $data['page'] = "home/desa";
                       $header = $this->header_model->get_config();
                       $this->load->view('header',$header);	
	               $this->load->view('dashboard',$data);
                       
		}
	}

	// log the user in
	public function login()
	{
		$data['title'] = $this->lang->line('login_heading');

		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() == true)
		{
			$identity = $this->input->post('identity');	
			$password = $this->input->post('password');

			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
					
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('auth/', 'refresh');
				
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with Admin_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);

			

			$this->session->set_flashdata('message', $this->ion_auth->messages());
                        
		        $header = $this->header_model->get_config();
                        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $header);
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $data);  
                        
		}
	}

	// log the user out
	public function logout()
	{
		$data['title'] = "Logout";


		$this->session->unset_userdata('token');
		$this->session->unset_userdata('userData');
		$this->session->unset_userdata('token_secret');
		$this->session->unset_userdata('status');
		
		// log the user out
		$logout = $this->ion_auth->logout();
		
		// redirect them to the login page
		
		redirect('auth/login');
	}

	// change password
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	// forgot password
	public function forgot_password()
	{
		$this->data['title'] = $this->lang->line('forgot_password_heading');
		
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
			];

			if ($this->config->item('identity', 'ion_auth') != 'email')
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
                        $header = $this->header_model->get_config();
                        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $header);
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data); 
		}
		else
		{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$this->data['title'] = $this->lang->line('reset_password_heading');
		
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = [
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['new_password_confirm'] = [
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['user_id'] = [
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				];
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
                                $header = $this->header_model->get_config();
                                $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $header);
			        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data); 
				
			}
			else
			{
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($identity);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	
	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}

			       
	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}
