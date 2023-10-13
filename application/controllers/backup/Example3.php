<?php 

class Example3 extends CI_Controller{
    var $table = 'peoples';
	public function __construct(){
		parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Datatables', 'datatables');

		$this->load->model('My_model', 'example3');
	}
	

    public function get_id(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            // $where = ['id' => $id];
            // $data = $this->datatables->table($this->table)->get_id($where);
            // $data = $this->example3->get_id($id);
            // $data = $this->datatables->table('peoples')->select('id, name, email, address')->where(['id' => $id]);
            // echo json_encode($data);


        }else{
            redirect('table/example3');
        }
    }
    
    public function save(){
        if($this->input->is_ajax_request()){
            $this->form_validation->set_rules('name','name','required');
            $this->form_validation->set_rules('email','email','required|valid_email');
            $this->form_validation->set_rules('address','address','required');
        
                if($this->form_validation->run() != false){
        
                    $data = $this->input->post();
        
                    $id = $this->input->post('id', true);
        
                    // For Update
                    if(!empty($id)){
                        $this->datatables->table($this->table)->update($data, ['id' => $id]);
                        $status = 'Data berhasil diubah!';
                    }
                    // For Insert
                    else{
                        $this->datatables->table($this->table)->insert($data);
                        $status = 'Data berhasil ditambahkan!';
                    }
        
                    echo json_encode(["status" => $status]);
                }else{
                    // $error = [ 
                    //     'name' => $this->form_validation->error('name'),
                    //     'email' => $this->form_validation->error('email'),
                    //     'address' => $this->form_validation->error('address'),
                    // ];
                    $error = validation_errors();
                    echo json_encode(["error" => $error]);

                }
        
            }else{
            redirect('table/example3');
            }
        }




    public function delete(){
        if($this->input->is_ajax_request()){

            // $this->example3->delete($this->input->post('id', true));
            $this->datatables->table($this->table)->delete(['id' => $this->input->post('id', true)]);
            echo json_encode(["status" => 'Data berhasil dihapus!']);
        }else{
            redirect('table/example3');
        }
    }

    
}

?>