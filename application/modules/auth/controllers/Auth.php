<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Theme
		$this->data['theme'] = (!empty($this->config->item('dp_theme_auth'))) ? $this->config->item('dp_theme_auth') : 'default';
		$this->data['theme_url'] = base_url($this->config->item('dp_theme_auth_url'));

		// Title
		$this->data['title'] = (!empty($this->config->item('dp_title'))) ? $this->config->item('dp_title') : 'Raport';

		$this->data['title_toastr'] = 'System Information';
	}

	public function render()
	{
		// Gestion title and subtitle
		if (!isset($this->data['subtitle'])) {
			$this->data['separate'] = NULL;
			$this->data['subtitle'] = NULL;
		} else {
			$this->data['separate'] = ' | ';
		}

		$this->data['pagetitle'] = $this->data['title'] . $this->data['separate'] . $this->data['subtitle'];

		// Include content
		$this->data['content'] = $this->parser->parse($this->data['page_content'], $this->data, TRUE);

		// Render template
		$this->data['data'] = &$this->data;
		$this->parser->parse('auth/_theme/template', $this->data);
	}

	public function index()
	{
		// if ($this->ion_auth->logout()) {
		redirect('auth/login', 'refresh');
		// }
	}


	public function login()
	{
		$this->data['subtitle'] = $this->lang->line('login_heading');

		if (!$this->ion_auth->logged_in()) {
			$this->form_validation->set_rules('identity', 'lang:login_identity_label', 'required');
			$this->form_validation->set_rules('password', 'lang:login_password_label', 'required');
			$this->form_validation->set_rules('remember', 'lang:login_remember_label', 'integer');

			if ($this->form_validation->run() == TRUE) {
				$remember = (bool) $this->input->post('remember');

				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
					if ($this->ion_auth->is_programmer()) {
						redirect('backend', 'refresh');
					} else if ($this->ion_auth->is_admin()) {
						$this->sweetalert->setToastNew('success', $this->ion_auth->messages(), $this->data['title_toastr']);
						redirect('admin', 'refresh');
					} else {
						$this->sweetalert->setToastNew('success', $this->ion_auth->messages(), $this->data['title_toastr']);
						redirect('guru', 'refresh');
					}
				} else {
					$this->sweetalert->setToastNew('error', $this->ion_auth->errors(), $this->data['title_toastr']);
					redirect('auth/login', 'refresh');
				}
			} else {
				$error = [
					'identity' => $this->form_validation->error('identity'),
					'password' => $this->form_validation->error('password'),
				];

				if (!empty($error['identity']) || !empty($error['password'])) {
					$errorMessages = [];

					foreach ($error as $errorMessage) {
						if (!empty($errorMessage)) {
							$errorMessages[] = $errorMessage;
						}
					}

					$this->sweetalert->setToastNew('error', $errorMessages, $this->data['title_toastr']);
				}



				$this->data['identity'] = array(
					'type'  => 'text',
					'name'  => 'identity',
					'id'    => 'identity',
					'value' => $this->form_validation->set_value('identity')
				);
				$this->data['password'] = array(
					'type' => 'password',
					'name' => 'password',
					'id'   => 'password'
				);
				$this->data['remember'] = array(
					'type'    => 'checkbox',
					'name'    => 'remember',
					'id'      => 'remember',
					'value'   => '1',
					'class'   => 'checkbox',
					'checked' => $this->form_validation->set_checkbox('remember', '1')
				);

				$this->data['page_content'] = 'auth/login';
				$this->render();
			}
		} else {
			if ($this->ion_auth->is_programmer()) {

				redirect('backend', 'refresh');
			} else if ($this->ion_auth->is_admin()) {

				redirect('admin', 'refresh');
			} else {

				redirect('guru', 'refresh');
			}
		}
	}


	public function logout()
	{
		$this->ion_auth->logout();



		$this->sweetalert->setToastNew('success', $this->ion_auth->messages(), $this->data['title_toastr']);
		redirect('/');
	}


	// public function forgot_password()
	// {
	// 	$this->data['subtitle'] = $this->lang->line('forgot_password_heading');

	// 	if ($this->config->item('identity', 'ion_auth') != 'email') {
	// 		$this->form_validation->set_rules('identity', 'lang:forgot_password_identity_label', 'required');
	// 	} else {
	// 		$this->form_validation->set_rules('identity', 'lang:forgot_password_validation_email_label', 'required|valid_email');
	// 	}

	// 	if ($this->form_validation->run() == FALSE) {
	// 		$this->data['type'] = $this->config->item('identity', 'ion_auth');

	// 		$this->data['identity'] = array(
	// 			'name' => 'identity',
	// 			'id'   => 'identity'
	// 		);

	// 		if ($this->config->item('identity', 'ion_auth') != 'email') {
	// 			$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
	// 		} else {
	// 			$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
	// 		}

	// 		$error = [
	// 			'identity' => $this->form_validation->error('identity'),
	// 		];

	// 		if (!empty($error['identity'])) {
	// 			$errorMessages = [];

	// 			foreach ($error as $errorMessage) {
	// 				if (!empty($errorMessage)) {
	// 					$errorMessages[] = $errorMessage;
	// 				}
	// 			}

	// 			$this->sweetalert->setToastNew('error', $errorMessages, $this->data['title_toastr']);
	// 		}

	// 		$this->data['page_content'] = 'auth/forgot_password';
	// 		$this->render();
	// 	} else {
	// 		// Retrieve identity columns and check for their existence
	// 		$identity_admin1 = $this->config->item('identity_admin1', 'ion_auth');
	// 		$identity_admin2 = $this->config->item('identity_admin2', 'ion_auth');
	// 		$identity_teacher = $this->config->item('identity_teacher', 'ion_auth');

	// 		$identity_col_admin_1 = $this->ion_auth->where($identity_admin1, $this->input->post('identity'))->users()->row();
	// 		$identity_col_admin_2 = $this->ion_auth->where($identity_admin2, $this->input->post('identity'))->users()->row();
	// 		$identity_col_teacher = $this->ion_auth->where($identity_teacher, $this->input->post('identity'))->users()->row();

	// 		if (empty($identity_col_admin_1) && empty($identity_col_admin_2) && empty($identity_col_teacher)) {
	// 			if ($this->config->item('identity', 'ion_auth') != 'email') {
	// 				$this->ion_auth->set_error('forgot_password_identity_not_found');
	// 			} else {
	// 				$this->ion_auth->set_error('forgot_password_email_not_found');
	// 			}

	// 			$this->sweetalert->setToastNew('error', $this->ion_auth->messages(), $this->data['title_toastr']);
	// 			redirect('auth/forgot_password', 'refresh');
	// 		} else {
	// 			// Try to send forgotten password emails
	// 			$successful = false;

	// 			if (!empty($identity_col_admin_1)) {
	// 				$successful = $this->ion_auth->forgotten_password($identity_col_admin_1->{$identity_admin1});
	// 			} elseif (!empty($identity_col_admin_2)) {
	// 				$successful = $this->ion_auth->forgotten_password($identity_col_admin_2->{$identity_admin2});
	// 			} elseif (!empty($identity_col_teacher)) {
	// 				$successful = $this->ion_auth->forgotten_password($identity_col_teacher->{$identity_teacher});
	// 			}

	// 			// Check if email sending was successful
	// 			if ($successful) {
	// 				$this->sweetalert->setToastNew('success', $this->ion_auth->messages(), $this->data['title_toastr']);
	// 				redirect('auth/login', 'refresh');
	// 			} else {
	// 				$this->sweetalert->setToastNew('error', $this->ion_auth->errors(), $this->data['title_toastr']);
	// 				redirect('auth/forgot_password', 'refresh');
	// 			}
	// 		}
	// 	}
	// }
}