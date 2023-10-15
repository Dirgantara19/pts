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
				if (validation_errors()) {
					$errors = $this->form_validation->error_array();

					foreach ($errors as $field => $message) {
						$error = [
							$field => $message,
						];

						$this->sweetalert->setToastNew('error', $error, $this->data['title_toastr']);
					}

					$error = [];
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


	public function forgot_password()
	{
		$this->data['subtitle'] = $this->lang->line('forgot_password_heading');

		if ($this->config->item('identity', 'ion_auth') != 'email') {
			$this->form_validation->set_rules('identity', 'lang:forgot_password_identity_label', 'required');
		} else {
			$this->form_validation->set_rules('identity', 'lang:forgot_password_validation_email_label', 'required|valid_email');
		}

		if ($this->form_validation->run() == FALSE) {
			$this->data['type'] = $this->config->item('identity', 'ion_auth');

			$this->data['identity'] = array(
				'name' => 'identity',
				'id'   => 'identity'
			);

			if ($this->config->item('identity', 'ion_auth') != 'email') {
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['page_content'] = 'auth/forgot_password';
			$this->render();
		} else {
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity)) {
				if ($this->config->item('identity', 'ion_auth') != 'email') {
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				} else {
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->sweetalert->setToastNew('error', $this->ion_auth->messages(), $this->data['title_toastr']);
				redirect('auth/forgot_password', 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten) {
				$this->sweetalert->setToastNew('success', $this->ion_auth->messages(), $this->data['title_toastr'],);
				redirect('auth/login', 'refresh');
			} else {
				$this->sweetalert->setToastNew('error', $this->ion_auth->messages(), $this->data['title_toastr']);
				redirect('auth/forgot_password', 'refresh');
			}
		}
	}
}
