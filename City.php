<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class City extends CI_Controller 
{

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Common_model');
    }

    function index() 
    {
    	if(!empty($_POST))
    	{
    		$this->form_validation->set_rules('city_name', 'City Name', 'required|trim');
    		
    		if($this->form_validation->run() == TRUE)
    		{
                if($this->input->post('city_id') == "")
                {
                    $field_list = array('city_name' => $this->input->post('city_name'));
                    $result     = $this->Common_model->insert('city',$field_list);
                    if($result)
                    {
                        $msg = $this->session->set_flashdata('res', 'Saved Successfully');
                        redirect(base_url('index.php/City'));
                    }   
                }

                else
                {
                    $field_list = array('city_name' => $this->input->post('city_name'));
                    $where      = array('city_id'   => $this->input->post('city_id'));
                    $result     = $this->Common_model->update('city',$field_list, $where);
                    if($result)
                    {
                        $msg = $this->session->set_flashdata('res', 'Updated Successfully');
                        redirect(base_url('index.php/City'));
                     }  
                }
				
    		}
    	}
    	$viewData['success']    = $this->session->flashdata('res');
    	$field_list 			= array('city_id', 'city_name', 'status');
    	$viewData['tableList']  = $this->Common_model->get_data('city', $field_list);
        $this->load->view('cityform', $viewData);
    }

    public function edit($city_id)
    {
        $field_list             = array('city_id', 'city_name', 'status');
        $where                  = array('city_id' => $city_id);
        $viewData['tableData']  = $this->Common_model->get_data('city', $field_list, $where);
        $viewData['tableList']  = $this->Common_model->get_data('city', $field_list, "");
        $this->load->view('cityform', $viewData);
        
    }

    public function delete($city_id)
    {
        $where   = array('city_id' => $city_id);
        $result  = $this->Common_model->delete('city', $where);
        if($result)
        {
            $msg = $this->session->set_flashdata('res', 'Deleted Successfully');
            redirect(base_url('index.php/City/city_view'));
        }  
    }

    public function city_view()
    { 

        $tmpl = array('table_open' => '<table id="big_table" border="1" cellpadding="2" cellspacing="1" class="mytable table table-bordered">');
        $this->table->set_template($tmpl);
        $this->table->set_heading('City Name', 'Status', 'Actions');
        $this->load->view('city_view_table');
    }

    function datatable()
    {
        $this->datatables->select('city_name, status, city_id')
             ->from('city');
        $this->datatables->edit_column('city_id', '$1', 'get_buttons(city_id)');
        echo $this->datatables->generate();
    }
}
