<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Modules extends Secure_Controller
{

    public function __construct()
    {
        parent::__construct('modules');
    }
    public function index()
    {
        $data['table_headers'] = $this->xss_clean(get_module_manage_table_headers());

        $this->load->view('modules/manage', $data);
    }
    public function search()
    {
        $search = $this->input->get('search');
        $limit  = $this->input->get('limit');
        $offset = $this->input->get('offset');
        $sort   = $this->input->get('sort');
        $order  = $this->input->get('order');

        $modules = $this->Module->search($search, $limit, $offset, $sort, $order);

        $total_rows = $this->Module->get_found_rows();

        $data_rows = array();
        $id = 1;
        foreach ($modules->result() as $module) {
            $data_rows[] = $this->xss_clean(get_module_data_row($module, $id));
            $id++;
        }


        echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
    }
    public function view($module_id = -1)
    {

        if ($module_id != -1) {
            $module_info = $this->Module->get_info($module_id);
            // echo json_encode($module_info->module_id);
            // die();
            $data['module_id'] = $module_info->module_id;
            $data['name_lang_key'] = $module_info->name_lang_key;
            $data['desc_lang_key'] = $module_info->desc_lang_key;
            $data['sort'] = $module_info->sort;
            $data['status'] = $module_info->status;
        }

        $this->load->view("modules/form", $data);
    }
    public function save($module_id = -1)
    {
        // echo json_encode($this->input->post('sort'));
        // die();
        $module_data = array(
            'module_id' => $this->input->post('module_id'),
            'name_lang_key' => $this->input->post('name_lang_key'),
            'desc_lang_key' => $this->input->post('desc_lang_key'),
            'sort' => $this->input->post('sort') == '' ? 99 : $this->input->post('sort'),
            'status' => $this->input->post('status') == NULL ? 0 : $this->input->post('status')
        );
        if ($this->Module->save($module_data, $module_id)) {
            $module_data = $this->xss_clean($module_data);
            if ($module_id == -1) {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('modules_successful_adding') . ' ' .
                    $module_data['module_id'], 'id' => $module_data['name_lang_key']));
            } else //Existing giftcard
            {
                echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('modules_successful_updating') . ' ' .
                    $module_data['module_id'], 'id' => $module_data));
            }
        } else //failure
        {
            $module_data = $this->xss_clean($module_data);

            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('modules_error_adding_updating') . ' ' .
                $module_data['module_id'], 'id' => -1));
        }
    }
    public function change_status($module_id)
    {
        $module_info = $this->Module->get_info($module_id);
        $module_status = $module_info->status;
        if ($module_status == 1) {
            $status = 0;
        }else{
            $status = 1;
        }
        $module_data = array(
            'status' => $status 
        );
        if ($this->Module->change_status($module_id, $module_data )) {
                // echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('modules_successful_updating') . ' ' .
                //         $module_info['module_id'], 'id' => $module_info));
                $this->load->helper('url');
                redirect('modules', 'refresh');
        }else{
            // echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('modules_error_updating') . ' ' .
            //     $module_info['module_id'], 'id' => -1));
            $this->load->helper('url');
                redirect('modules', 'refresh');
        }
    }
}
