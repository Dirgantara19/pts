<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Users extends MY_Controller
{

	protected $table;
	protected $table_ion_auth;


	public function __construct()
	{
		parent::__construct();
		if (!$this->input->is_ajax_request()) {
			redirect('admin/users');
		}
		$this->load->model('Crud_model', 'crud');

		$this->table_ion_auth  = $this->config->item('tables', 'ion_auth');
		$this->table = $this->table_ion_auth['users'];
		$this->table_relasi1 = $this->table_ion_auth['users_mapel_kelas'];
		$this->table_relasi2 = $this->table_ion_auth['raport'];

		$this->load->model('M_Users');
	}



	public function ajax_list()
	{
		echo $this->M_Users->ajax_list();
	}

	public function get_id()
	{
		$id = $this->input->post('id');
		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result();
		$groups_array = array();
		foreach ($groups as $group) {
			$groups_array[] = $group->name;
		}

		$data = [
			'user' => $user,
			'groups' => $groups_array
		];
		echo json_encode($data);
	}

	public function save()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$password = $this->input->post('password');

			if (empty($id)) {
				$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
				$this->form_validation->set_rules('nip_or_nik', 'NIP/NIK', 'trim|is_unique[users.nip_or_nik]');
			} else {
				$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('nip_or_nik', 'NIP/NIK', 'trim|is_unique[users.nip_or_nik]');
			}

			if ($this->form_validation->run() == true) {

				$data = array(
					'full_name' => ucwords($this->input->post('full_name'), '-'),
					'nip_or_nik'       => $this->input->post('nip_or_nik'),
					'ip_address' => $this->input->ip_address()
				);

				if (!empty($id)) {
					if ($password) {
						$data['password'] = $password;
					}
					$this->ion_auth->update($id, $data);
					$status = ['success' => 'Success: Data update!'];
				} else {
					$nip_or_nik = strtolower($this->input->post('nip_or_nik'));

					$identity = $nip_or_nik;
					$additional_data = array(
						'full_name' => ucwords($this->input->post('full_name'), '-'),
						'nip_or_nik'       => $this->input->post('nip_or_nik'),
						'img'       => 'gambar.png',
					);
					$this->ion_auth->register($identity, $password, $nip_or_nik, $additional_data);
					$status = ['success' => 'Success: Data insert!'];
				}
			} else {
				$errors = validation_errors();
				$status = ['errors' => $errors];
			}
			echo json_encode($status);
		} else {
			redirect('admin/users');
		}
	}


	public function import()
	{
		$file = $_FILES['fileusers'];

		if (isset($file['name']) && !empty($file['name']) && $file['error'] === 0) {
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

			if ($ext !== 'xls' && $ext !== 'xlsx') {
				$response = ['error' => 'Error: Invalid file format .' . $ext . '. Only .xls or .xlsx format is supported.'];
			} else {

				$spreadsheet = new Spreadsheet();

				if ('xls' == $ext) {
					$reader = IOFactory::createReader('Xls');
				} else if ('xlsx' == $ext) {
					$reader = IOFactory::createReader('Xlsx');
				} else {
					$reader = IOFactory::createReader('Csv');
				}
				$spreadsheet = $reader->load($file['tmp_name']);
				$sheetdata = $spreadsheet->getActiveSheet()->toArray();

				$defaultcol = 6;
				$nullcell = [];
				$col_eliminate = [];
				$data = [];

				foreach ($sheetdata as $index => $cell) {
					$cellcount = count($cell);
					foreach ($cell as $colIndex => $cellValue) {
						if ($colIndex >= $defaultcol) {
							$columnLetter = chr($colIndex + 65);

							$col_eliminate[] = ['column' => $columnLetter, 'row' => $index + 1];
						} else if (is_null($cellValue)) {
							$columnLetter = chr($colIndex + 65);
							$nullcell[] = ['column' => $columnLetter, 'row' => $index + 1];
						}
					}

					while (count($cell) < $defaultcol) {
						$cell[] = null;
					}
					$sheetdata[$index] = $cell;
				}
				if ($cellcount > $defaultcol) {

					$response = [
						'error' => 'Error: Data is not in correct format.',
						'type' => 1, 'problem' => 'Ensure that the data has a maximum of ' . $defaultcol . ' columns.',
						'solution' => $col_eliminate
					];
				} else if ($cellcount < $defaultcol) {

					$col_diff = $defaultcol - $cellcount;

					$col_fit = [];
					for ($i = 0; $i < $col_diff; $i++) {
						$columnLetter = chr($cellcount + $i + 65);
						$col_fit[] = ['column' => $columnLetter];
					}

					$response = [
						'error' => 'Error: Data is not in correct format.',
						'type' => 3, 'problem' => 'Ensure that the data has a minimum of' . $defaultcol . 'columns.',
						'solution' => $col_fit
					];
				} else {
					if (!empty($nullcell)) {
						$response = [
							'error' => 'Error: Data contains null value.',
							'type' => 2, 'problem' => 'Data null found',
							'solution' => $nullcell
						];
					} else {
						for ($i = 1; $i < $cellcount; $i++) {
							$password = $sheetdata[$i][2];
							$full_name = $sheetdata[$i][3];
							$nip_or_nik = $sheetdata[$i][4];

							$data[] = array(
								'identity'     => $nip_or_nik,
								'password'     => $password,
								'full_name' => $full_name,
								'nip_or_nik'       => $nip_or_nik,
							);
						}

						if (!empty($data)) {

							$record = $this->ion_auth->register_batch($data);
							if ($record['error']) {
								$response = ['error' => 'Error: ' . $record['error']];
							} else {
								$response = ['success' => 'Success: Data record!.'];
							}
						} else {
							$response = ['error' => 'Error: Data is empty.'];
						}
					}
				}
			}
		} else {
			$response = ['error' => 'Error: File not uploaded.'];
		}

		echo json_encode($response);
	}


	public function activate()
	{

		$id = $this->input->post('id');

		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			$response = ['success' => false, 'message' => validation_errors()];
		} else {
			if ($id) {
				$this->ion_auth->activate($id);
				$response = ['success' => true, 'message' => $this->ion_auth->messages()];
			} else {
				$response = ['success' => false, 'message' => $this->ion_auth->errors()];
			}
		}
		echo json_encode($response);
	}


	public function deactivate()
	{

		$id = $this->input->post('id');

		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			$response = ['success' => false, 'message' => validation_errors()];
		} else {
			if ($id) {
				$this->ion_auth->deactivate($id);
				$response = ['success' => true, 'message' => $this->ion_auth->messages()];
			} else {
				$response = ['success' => false, 'message' => $this->ion_auth->errors()];
			}
		}
		echo json_encode($response);
	}



	// delete the user
	public function delete()
	{
		$id = $this->input->post('id');

		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			$response = ['success' => false, 'message' => validation_errors()];
		} else {
			if ($id) {
				$this->db->trans_start();

				$this->db->where('user_id', $id);
				$this->db->delete($this->table_relasi2);

				$this->db->where('user_id', $id);
				$this->db->delete($this->table_relasi1);

				$this->ion_auth->delete_user($id);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response = ['success' => false, 'message' => 'Error: Data not deleted due to a transaction error!'];
				} else {
					$response = ['success' => true, 'message' => $this->ion_auth->messages()];
				}
			} else {
				$response = ['success' => false, 'message' => $this->ion_auth->errors()];
			}
		}

		echo json_encode($response);
	}

	public function bulk_delete()
	{
		$array_id = $this->input->post('array_id');

		$this->db->trans_start();

		$response = ['error' => 'Error: Data not deleted!'];

		if (!empty($array_id)) {
			$this->db->where_in('user_id', $array_id);
			$this->db->delete($this->table_relasi2);

			$this->db->where_in('user_id', $array_id);
			$this->db->delete($this->table_relasi1);

			$this->db->where_in('id', $array_id);
			$this->db->delete($this->table);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = ['error' => 'Error: Data not deleted due to a transaction error!'];
		} else {
			$response = ['success' => 'Success: Data deleted!'];
		}

		echo json_encode($response);
	}






	public function export()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
			redirect('auth/login/admin', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->load->dbutil();

			$query    = 'SELECT username, email, full_name, title, nip, nik FROM users';
			$category = 'users';

			$this->backend_tools_model->export_csv($query, $category);
		}
	}

	public function insert()
	{
		if (!$this->ion_auth->logged_in() or !$this->ion_auth->is_admin()) {
			redirect('auth/login/admin', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			return show_error('You must be an administrator to view this page.');
		} else {
			$this->load->dbutil();

			$query    = 'SELECT username, email, full_name, company, phone FROM dp_auth_users';
			$category = 'users';

			$this->backend_tools_model->insert_csv($query, $category);
		}
	}
}