<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backend extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			// Load model
			$this->load->model(array('backend_tools_model'));

			// Load langage
			$this->form_validation->set_error_delimiters($this->config->item('error_prefix'), $this->config->item('error_suffix'));

			$this->load->helper('number');

			$this->load->dbutil();

			$this->lang->load(array('backend'));
			// Theme
			$this->data['theme']     = (!empty($this->config->item('dp_theme_backend'))) ? $this->config->item('dp_theme_backend') : 'default';
			$this->data['theme_url'] = base_url($this->config->item('dp_theme_backend_url'));

			// Title
			$this->data['title'] = 'Backend';

			// Common language
			$this->data['lang_dashboard']          = $this->lang->line('dashboard');
			$this->data['lang_security_groups']    = $this->lang->line('security_groups');
			$this->data['lang_users']              = $this->lang->line('users');
			$this->data['lang_maintenance']        = $this->lang->line('maintenance');
			$this->data['lang_list']               = $this->lang->line('list');
			$this->data['lang_actions']            = $this->lang->line('actions');
			$this->data['lang_help']               = $this->lang->line('help');
			$this->data['lang_edit']               = $this->lang->line('edit');
			$this->data['lang_export_list']        = $this->lang->line('export_list');
			$this->data['lang_import_list']        = $this->lang->line('import_list');
			$this->data['lang_add_user']           = $this->lang->line('add_user');
			$this->data['lang_email']              = $this->lang->line('email');
			$this->data['lang_group']              = $this->lang->line('group');
			$this->data['lang_group_name']         = $this->lang->line('group_name');
			$this->data['lang_status']             = $this->lang->line('status');
			$this->data['lang_active']             = $this->lang->line('active');
			$this->data['lang_inactive']           = $this->lang->line('inactive');
			$this->data['lang_see']                = $this->lang->line('see');
			$this->data['lang_file']               = $this->lang->line('file');
			$this->data['lang_full_name']         = $this->lang->line('full_name');
			$this->data['lang_nip']       		= $this->lang->line('nip');
			$this->data['lang_nik']              = $this->lang->line('nik');
			// $this->data['lang_title']       = $this->lang->line('title');
			$this->data['lang_password']           = $this->lang->line('password');
			$this->data['lang_password_confirm']   = $this->lang->line('password_confirm');
			$this->data['lang_password_if_change'] = $this->lang->line('password_if_change');
			$this->data['lang_cancel']             = $this->lang->line('cancel');
			$this->data['lang_create']             = $this->lang->line('create');
			$this->data['lang_save']               = $this->lang->line('save');
			$this->data['lang_add_group']          = $this->lang->line('add_group');
			$this->data['lang_name']               = $this->lang->line('name');
			$this->data['lang_color']              = $this->lang->line('color');
			$this->data['lang_description']        = $this->lang->line('description');
			$this->data['lang_delete']             = $this->lang->line('delete');
			$this->data['lang_import']             = $this->lang->line('import');
			$this->data['lang_yes']                = $this->lang->line('yes');
			$this->data['lang_no']                 = $this->lang->line('no');
		}
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

		// Error management
		$this->data['result_flashdata'] = $this->backend_tools_model->display_flashdata('item');

		// Include nav header
		$this->data['nav_header'] = $this->parser->parse('backend/_theme/nav_header', $this->data, TRUE);

		// Include navside
		$this->data['nav_side'] = $this->parser->parse('backend/_theme/nav_side', $this->data, TRUE);

		// Include content
		$this->data['content'] = $this->parser->parse($this->data['page_content'], $this->data, TRUE);

		// Render template
		$this->data['data'] = $this->data;
		$this->parser->parse('backend/_theme/template', $this->data);
	}

	// Dashboard

	public function dashboard()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('/', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$count_user  = $this->db->count_all($this->config->item('tables', 'ion_auth')['users']);
			$count_group = $this->db->count_all($this->config->item('tables', 'ion_auth')['groups']);

			if ($count_user >= 1) {
				$this->data['lang_user_plural'] = plural($this->lang->line('user'));
			} else {
				$this->data['lang_user_plural'] = $this->lang->line('user');
			}

			if ($count_group >= 1) {
				$this->data['lang_group_plural'] = plural($this->lang->line('group'));
			} else {
				$this->data['lang_group_plural'] = $this->lang->line('group');
			}

			$this->data['nbr_user']     = $count_user;
			$this->data['nbr_group']    = $count_group;
			$this->data['subtitle']     = $this->lang->line('dashboard');
			$this->data['page_content'] = 'backend/dashboard';

			$this->render();
		}
	}

	// Groups
	public function groups()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->data['groups'] = $this->ion_auth->groups()->result();
			// IN TEST
			//$this->data['color'] = $this->db->select('id_groups, color')->get('dp_auth_groups_color');
			//$this->data['color'] = $this->db->get('dp_auth_groups_color');

			$this->data['count_groups']   = $this->db->count_all($this->config->item('tables', 'ion_auth')['groups']);
			$this->data['subtitle']       = $this->lang->line('security_groups');
			$this->data['page_content']   = 'backend/groups/index';

			$this->render();
		}
	}


	public function add_groups()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->form_validation->set_rules('group_name', 'lang:group_name', 'required|alpha_dash');

			if ($this->form_validation->run() == TRUE) {
				$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));

				if ($new_group_id) {
					$this->session->set_flashdata('message', $this->ion_auth->messages());

					redirect('backend/groups', 'refresh');
				}
			} else {
				$this->data['group_name'] = array(
					'type'  => 'text',
					'name'  => 'group_name',
					'id'    => 'group_name',
					'value' => $this->form_validation->set_value('group_name'),
					'class' => 'form-control'
				);
				$this->data['description'] = array(
					'type'  => 'text',
					'name'  => 'description',
					'id'    => 'description',
					'value' => $this->form_validation->set_value('description'),
					'class' => 'form-control'
				);

				$this->data['message']      = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->data['subtitle']     = $this->lang->line('create_group_title');
				$this->data['page_content'] = 'backend/groups/add';

				$this->render();
			}
		}
	}


	public function edit_groups($id)
	{
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_programmer() && !($this->ion_auth->group()->row()->id == $id))) {
			redirect('auth/login', 'refresh');
		} else {
			$this->form_validation->set_rules('group_name', 'lang:group_name', 'required|alpha_dash');

			$group = $this->ion_auth->group($id)->row();

			if (isset($_POST) && !empty($_POST)) {
				if ($this->form_validation->run() == TRUE) {
					$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

					if ($group_update) {
						$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
					}

					redirect('backend/groups', 'refresh');
				}
			}

			// $this->data['group']   = $group;

			$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

			$this->data['group_name'] = array(
				'type'    => 'text',
				'name'    => 'group_name',
				'id'      => 'group_name',
				'value'   => $this->form_validation->set_value('group_name', $group->name),
				'class'   => 'form-control',
				$readonly => $readonly
			);
			$this->data['group_description'] = array(
				'type'  => 'text',
				'name'  => 'group_description',
				'id'    => 'group_description',
				'value' => $this->form_validation->set_value('group_description', $group->description),
				'class' => 'form-control'
			);

			$this->data['message']      = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['subtitle']     = 'Group edit';
			$this->data['page_content'] = 'backend/groups/edit';

			$this->render();
		}
	}


	public function import_groups()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->data['message']      = NULL;
			$this->data['subtitle']     = 'Import';
			$this->data['page_content'] = 'backend/groups/import';

			$this->render();
		}
	}


	public function import_process_groups()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->load->library('import_csv');

			$config['upload_path']   = './uploads/';
			$config['allowed_types'] = 'csv|txt';
			$config['max_size']      = '1000';

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file')) {
				$file_data = $this->upload->data();

				$file_path           = './uploads/' . $file_data['file_name'];
				$column_headers      = array('name', 'description');
				$detect_line_endings = TRUE;
				$initial_line        = 0;
				$delimiter           = ';';

				if ($this->import_csv->get_array($file_path)) {
					$csv_array = $this->import_csv->get_array($file_path, $column_headers, $detect_line_endings, $initial_line, $delimiter);

					foreach ($csv_array as $row) {
						$insert_data = array(
							'name'        => $row['name'],
							'description' => $row['description']
						);

						$this->backend_tools_model->insert_csv('dp_auth_groups', $insert_data);
					}

					unlink($file_path);

					$this->session->set_flashdata('item', array('message' => 'Csv Data Imported Succesfully', 'class' => 'success'));

					redirect('backend/groups', 'refresh');
				} else {
					$this->data['message'] = "Error occured";
				}
			} else {
				$this->data['message'] = $this->upload->display_errors();
			}

			$this->data['subtitle']     = 'Import';
			$this->data['page_content'] = 'backend/groups/import';

			$this->render();
		}
	}


	public function export_groups()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login/backend', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->load->dbutil();

			$query    = 'SELECT name, description FROM dp_auth_groups';
			$category = 'security_groups';

			$this->backend_tools_model->export_csv($query, $category);
		}
	}

	// Maintenance

	public function maintenance()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login/backend', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->data['db_platform']    = $this->db->platform();
			$this->data['db_version']     = $this->db->version();

			$this->data['apache_version'] = $this->backend_tools_model->server_version();

			$this->data['php_version']    = phpversion();
			$this->data['zend_version']   = zend_version();
			$this->data['disk_freespace'] = byte_format($this->backend_tools_model->disk_freespace());
			$this->data['memory_free']    = byte_format($this->backend_tools_model->memory_free());

			$this->data['control_table'] = $this->backend_tools_model->control_table();

			$this->data['subtitle']     = $this->lang->line('maintenance');
			$this->data['page_content'] = 'backend/maintenance/index';

			$this->render();
		}
	}


	public function backup()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login/backend', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->load->helper('download');

			$filename = 'backup_' . date('Ymd_His') . '.zip';

			$prefs = array(
				'tables'             => array(),
				'ignore'             => array(),
				'format'             => 'zip',
				'filename'           => $filename,
				'add_drop'           => TRUE,
				'add_insert'         => TRUE,
				'newline'            => "\r\n",
				'foreign_key_checks' => TRUE
			);

			$backup = $this->dbutil->backup($prefs);

			force_download($filename, $backup);
		}
	}


	public function backup_table()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login/backend', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$query    = 'SELECT * FROM dp_auth_groups';
			$category = 'security_groups';

			$this->backend_tools_model->export_csv($query, $category);
		}
	}

	// Users

	public function users()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->data['users'] = $this->ion_auth->users()->result();


			foreach ($this->data['users'] as $k => $user) {
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			$this->data['count_users']  = $this->db->count_all($this->config->item('tables', 'ion_auth')['users']);
			$this->data['subtitle']     = $this->lang->line('users');
			$this->data['page_content'] = 'backend/users/index';

			$this->render();
		}
	}


	public function add_users()
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			redirect('auth/login', 'refresh');
		} else {
			$table_ion_auth  = $this->config->item('tables', 'ion_auth');
			$identity_column = $this->config->item('identity', 'ion_auth');

			$this->data['identity_column'] = $identity_column;

			$this->form_validation->set_rules('full_name', 'lang:full_name', 'trim|required');

			if ($identity_column == 'id') {
				$this->form_validation->set_rules('email', 'lang:email', 'required|valid_email|is_unique[' . $table_ion_auth['users'] . '.email]');
			}

			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'lang:password_confirm', 'required');

			if ($this->form_validation->run() === TRUE) {
				$email    = strtolower($this->input->post('email'));
				$identity = ($identity_column == 'id') ? $email : strtolower($this->input->post('identity'));
				$password = $this->input->post('password');

				$additional_data = array(
					'full_name' => ucwords($this->input->post('full_name'), '-'),
				);
			}

			if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());

				redirect('backend/users', 'refresh');
			} else {
				$this->data['full_name'] = array(
					'type'  => 'text',
					'name'  => 'full_name',
					'id'    => 'full_name',
					'value' => $this->form_validation->set_value('full_name'),
					'class' => 'form-control'
				);
				$this->data['identity'] = array(
					'type'  => 'text',
					'name'  => 'identity',
					'id'    => 'identity',
					'value' => $this->form_validation->set_value('identity'),
					'class' => 'form-control'
				);
				$this->data['email'] = array(
					'type'  => 'email',
					'name'  => 'email',
					'id'    => 'email',
					'value' => $this->form_validation->set_value('email'),
					'class' => 'form-control'
				);
				$this->data['password'] = array(
					'type'  => 'password',
					'name'  => 'password',
					'id'    => 'password',
					'value' => $this->form_validation->set_value('password'),
					'class' => 'form-control'
				);
				$this->data['password_confirm'] = array(
					'type'  => 'password',
					'name'  => 'password_confirm',
					'id'    => 'password_confirm',
					'value' => $this->form_validation->set_value('password_confirm'),
					'class' => 'form-control'
				);

				$this->data['message']      = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->data['subtitle']     = $this->lang->line('create_user_heading');
				$this->data['page_content'] = 'backend/users/add';

				$this->render();
			}
		}
	}


	public function activate_user($id = NULL, $code = FALSE)
	{
		$id = (int) $id;

		if ($code !== FALSE) {
			$activation = $this->ion_auth->activate($id, $code);
		} else if ($this->ion_auth->is_programmer()) {
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation) {
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('backend/users', 'refresh');
		} else {
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect('auth/forgot_password', 'refresh');
		}
	}

	// deactivate the user
	public function deactivate_user($id = NULL)
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();

			$user = $this->ion_auth->user($id)->row();

			$this->data['lang_deactivate_user_confirm'] = $this->lang->line('deactivate_user_confirm');
			$this->data['id']           = $user->id;
			$this->data['username']     = $user->username;
			$this->data['subtitle']     = 'Deactivate user';
			$this->data['page_content'] = 'backend/users/deactivate';

			$this->render();
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_programmer()) {
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('backend/users', 'refresh');
		}
	}


	public function edit_user($id)
	{
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_programmer() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth/login', 'refresh');
		} else {
			$user          = $this->ion_auth->user($id)->row();
			$groups        = $this->ion_auth->groups()->result_array();
			$currentGroups = $this->ion_auth->get_users_groups($id)->result();
			$table_ion_auth  = $this->config->item('tables', 'ion_auth');


			// validate form input
			$this->form_validation->set_rules('full_name', 'lang:edit_user_validation_fullname_label', 'trim|required');
			$this->form_validation->set_rules('email', 'lang:edit_user_validation_email_label', 'required|is_unique[users.email]|valid_email');


			if (isset($_POST) && !empty($_POST)) {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// update the password if it was posted
				if ($this->input->post('password')) {
					$this->form_validation->set_rules('password', 'lang:edit_user_validation_password_label', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
					$this->form_validation->set_rules('password_confirm', 'lang:edit_user_validation_password_confirm_label', 'required');
				}

				if ($this->form_validation->run() === TRUE) {
					$data = array(
						'full_name' => ucwords($this->input->post('full_name'), '-'),
						'email' => $this->input->post('email'),
					);

					// update the password if it was posted
					if ($this->input->post('password')) {
						$data['password'] = $this->input->post('password');
					}

					// Only allow updating groups if user is admin
					if ($this->ion_auth->is_programmer()) {
						//Update the groups user belongs to
						$groupData = $this->input->post('groups');

						if (isset($groupData) && !empty($groupData)) {
							$this->ion_auth->remove_from_group('', $id);

							foreach ($groupData as $grp) {
								$this->ion_auth->add_to_group($grp, $id);
							}
						}
					}

					// check to see if we are updating the user
					if ($this->ion_auth->update($user->id, $data)) {
						// redirect them back to the admin page if admin, or to the base url if non admin
						$this->session->set_flashdata('message', $this->ion_auth->messages());

						if ($this->ion_auth->is_programmer()) {
							redirect('backend/users', 'refresh');
						} else {
							redirect('/', 'refresh');
						}
					} else {
						// redirect them back to the admin page if admin, or to the base url if non admin
						$this->session->set_flashdata('message', $this->ion_auth->errors());

						if ($this->ion_auth->is_programmer()) {
							redirect('backend/users', 'refresh');
						} else {
							redirect('/', 'refresh');
						}
					}
				}
			}

			$this->data['full_name'] = array(
				'type'  => 'text',
				'name'  => 'full_name',
				'id'    => 'full_name',
				'value' => $this->form_validation->set_value('full_name', $user->full_name),
				'class' => 'form-control'
			);

			$this->data['email'] = array(
				'type'  => 'email',
				'name'  => 'email',
				'id'    => 'email',
				'value' => $this->form_validation->set_value('email', $user->email),
				'class' => 'form-control'
			);
			$this->data['password'] = array(
				'type'  => 'password',
				'name'  => 'password',
				'id'    => 'password',
				'class' => 'form-control form-control-warning'
			);
			$this->data['password_confirm'] = array(
				'type'  => 'password',
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'class' => 'form-control form-control-warning'
			);

			$this->data['csrf']          = $this->_get_csrf_nonce();
			$this->data['user_id']       = $user->id;
			$this->data['groups']        = $groups;
			$this->data['currentGroups'] = $currentGroups;
			$this->data['message']       = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['subtitle']      = $this->lang->line('edit_user_heading');
			$this->data['page_content']  = 'backend/users/edit';

			$this->render();
		}
	}

	// Delete the user
	public function delete_user($id = NULL)
	{
		if (!$this->ion_auth->logged_in() && !$this->ion_auth->is_programmer()) {
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->form_validation->set_rules('confirm', $this->lang->line('delete_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('delete_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();

			$user = $this->ion_auth->user($id)->row();

			$this->data['lang_delete_user_confirm'] = $this->lang->line('delete_user_confirm');
			$this->data['id']           = $user->id;
			$this->data['username']     = $user->username;
			$this->data['subtitle']     = 'Delete user';
			$this->data['page_content'] = 'backend/users/delete';

			$this->render();
		} else {
			// do we really want to delete?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_programmer()) {
					$this->ion_auth->delete_user($id);
				}
			}

			// redirect them back to the auth page
			redirect('backend/users', 'refresh');
		}
	}




	public function export_users()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_programmer()) {
			redirect('auth/login/backend', 'refresh');
		} elseif (!$this->ion_auth->is_programmer()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->load->dbutil();

			$query    = 'SELECT username, email, first_name, last_name, company, phone FROM dp_auth_users';
			$category = 'users';

			$this->backend_tools_model->export_csv($query, $category);
		}
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');

		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);

		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}


	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));

		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
