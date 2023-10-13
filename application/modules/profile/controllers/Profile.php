<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            redirect('admin/profile');
        }
        $this->load->model('Crud_model', 'crud');
    }

    public function user_info()
    {
        $id = $this->data['user_info']['id'];
        $data = $this->ion_auth->user($id)->row();
        echo json_encode($data);
    }

    public function save()
    {
        $id = $this->data['user_info']['id'];
        $user = $this->ion_auth->user($id)->row();
        $password = $this->input->post('password');

        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('nip_or_nik', 'NIP/NIK', 'required|trim|numeric|is_unique[users.nip_or_nik]');

        if ($this->form_validation->run() == false) {
            $status = ['errors' => validation_errors()];
        } else {
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'nip_or_nik' => $this->input->post('nip_or_nik'),
            );

            if (!empty($id)) {
                if ($password) {
                    $data['password'] = $password;
                }

                if ($this->input->post('defaultimg')) {
                    $data['img'] = $this->input->post('defaultimg');
                }

                if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] == 0) {
                    $upload_path = './assets/default/img/profile/';
                    $allowed_types = array('gif', 'jpg', 'jpeg', 'png', 'svg');
                    $max_width = 500;
                    $max_height = 500;
                    $max_size = 4048;

                    $file_name = $_FILES['userfile']['name'];
                    $file_tmp = $_FILES['userfile']['tmp_name'];
                    $file_type = $_FILES['userfile']['type'];
                    $file_size = $_FILES['userfile']['size'];

                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $file_ext = strtolower($file_ext);

                    if (in_array($file_ext, $allowed_types) && $file_size <= $max_size) {
                        list($width, $height) = getimagesize($file_tmp);

                        if ($width <= $max_width && $height <= $max_height) {
                            if ($user->img != 'gambar.png') {
                                unlink($upload_path . '/' . $user->img);
                            }

                            $new_file_name = uniqid() . '.' . $file_ext;
                            $destination = $upload_path . $new_file_name;

                            if (move_uploaded_file($file_tmp, $destination)) {
                                $data['img'] = $new_file_name;
                            } else {
                                $status = ['errors' => error_get_last()];
                            }
                        } else {
                            $status = ['errors' => 'Error: Image size is too large.'];
                        }
                    } else {
                        $status = ['errors' => 'Error: File type is not allowed or file size is too large.'];
                    }
                }

                if (!isset($status['errors'])) {
                    $this->ion_auth->update($id, $data);
                    $status = ['success' => 'Success: Data updated!'];
                }
            } else {
                $status = ['errors' => 'Error: No id data found'];
            }
        }

        echo json_encode($status);
    }


    public function default_profile()
    {

        $id = $this->data['user_info']['id'];
        $user = $this->ion_auth->user($id)->row();
        $data['img'] = $this->input->post('img');
        if ($user->img != 'gambar.png') {
            $direcory_file = './assets/default/img/profile/';
            unlink($direcory_file . '/' . $user->img);
            $this->ion_auth->update($id, $data);
            $status = ['success' => 'Success: Data updated!'];
        } else {
            $status = ['errors' => 'Error: Already using default profile image!'];
        }

        echo json_encode($status);
    }
}
