<?php

class Policy extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Policy_model');    
    }

    public function index() {
        if (!$this->session->userdata('company_id')) {
            redirect(base_url().'company', 'refresh');
        }


        $data = [];
        $data['type_list'] = $this->Policy_model->get_all_types();
        $this->loadView('add', $data);
    }

    public function add() {
        $policy = array(
            'name' => $this->input->post('name'),
            'company_id' => $this->session->userdata('company_id'),
            'policy_type_id' => $this->input->post('type_id'),
            //'c_id' => $this->input->post('c_id'),
            'inv_per_year' => $this->input->post('inv_per_year'),
            'term' => $this->input->post('term'),
            'expected_return' => $this->input->post('expected_return'),
            'min_age' => $this->input->post('min_age'),
            'description' => $this->input->post('description')
        );

        $name_check = $this->Policy_model->name_check($policy['name']);
        if ($name_check) {
            $this->Policy_model->insert_policy($policy);
            $this->session->set_flashdata('success_msg', 'Policy added successfully.');
            redirect(base_url().'policy/policy_list', 'refresh');
        } else {
            $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
            redirect(base_url().'policy', 'refresh');
        }
    }

    public function policy_list(){
        $data['company_id'] = $this->session->userdata('company_id');
        if (!$this->session->userdata('company_id')) {
            redirect(base_url().'company', 'refresh');
        }

        $data['policies'] = $this->Policy_model->list_company_policy($data['company_id']);
        $this->loadView('list', $data);
    }

    public function editPolicy(){
        $data = [];
        $id = $this->uri->segment('3');
        $data['get_policy'] = $this->Policy_model->get_policy($id);
        $data['type_list'] = $this->Policy_model->get_all_types();
        $this->loadView('add', $data);
    }

    public function updatePolicy(){
        $policy = array(
            'name' => $this->input->post('name'),
            'company_id' => $this->session->userdata('company_id'),
            'policy_type_id' => $this->input->post('type_id'),
            'inv_per_year' => $this->input->post('inv_per_year'),
            'term' => $this->input->post('term'),
            'expected_return' => $this->input->post('expected_return'),
            'min_age' => $this->input->post('min_age'),
            'max_age' => $this->input->post('max_age'),
            'description' => $this->input->post('description')
        );

        $updates = $this->Policy_model->update_policy($policy,$this->input->post('id'));
        
        if ($updates) {
            
            $this->session->set_flashdata('success_msg', 'Policy updated successfully.');
            redirect(base_url().'policy/policy_list', 'refresh');
        } else {
            $this->session->set_flashdata('error_msg', 'Error occured. Try again.');
            redirect(base_url().'policy', 'refresh');
        }
    }

    public function deletePolicy(){
        $id = $this->uri->segment('3');
        $deletestatus = $this->Policy_model->deletePolicy($id);
        if ($deletestatus) {
            $this->session->set_flashdata('success_msg', 'Policy deleted successfully.');
            redirect(base_url().'policy/policy_list', 'refresh');
        } else {
            $this->session->set_flashdata('error_msg', 'Error occured in deletion. Try again.');
            redirect(base_url().'policy', 'refresh');
        }

    }
    public function policyType(){
        $id = $this->uri->segment('3');
        $data['products'] = $this->Policy_model->get_policy_by_type($id);
        $data['type'] = $this->Policy_model->get_type($id);
        $this->loadView('type_products', $data);
        

    }

    public function loadView($page_name, $data) {
        $this->load->view('template/header');
        $this->load->view('policy/' . $page_name, $data);
        $this->load->view('template/footer');
    }
}
?>