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
		

		$data = '';

		$this->load->view("modules/form", $data);
	}
}
