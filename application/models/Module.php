<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module class
 */

class Module extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);

		if ($query->num_rows() == 1) {
			$row = $query->row();

			return $this->lang->line($row->name_lang_key);
		}

		return $this->lang->line('error_unknown');
	}

	public function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);

		if ($query->num_rows() == 1) {
			$row = $query->row();

			return $this->lang->line($row->desc_lang_key);
		}

		return $this->lang->line('error_unknown');
	}

	public function get_all_permissions()
	{
		$this->db->from('permissions');

		return $this->db->get();
	}

	public function get_all_subpermissions()
	{
		$this->db->from('permissions');
		$this->db->join('modules AS modules', 'modules.module_id = permissions.module_id');
		// can't quote the parameters correctly when using different operators..
		$this->db->where('modules.module_id != ', 'permission_id', FALSE);

		return $this->db->get();
	}

	public function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by('sort', 'asc');
		return $this->db->get();
	}

	public function get_allowed_home_modules($person_id)
	{
		$menus = array('home', 'both');
		$this->db->from('modules');
		$this->db->join('permissions', 'permissions.permission_id = modules.module_id');
		$this->db->join('grants', 'permissions.permission_id = grants.permission_id');
		$this->db->where('person_id', $person_id);
		$this->db->where_in('menu_group', $menus);
		$this->db->where('sort !=', 0);
		$this->db->order_by('sort', 'asc');
		return $this->db->get();
	}

	public function get_allowed_office_modules($person_id)
	{
		$menus = array('office', 'both');
		$this->db->from('modules');
		$this->db->join('permissions', 'permissions.permission_id = modules.module_id');
		$this->db->join('grants', 'permissions.permission_id = grants.permission_id');
		$this->db->where('person_id', $person_id);
		$this->db->where_in('menu_group', $menus);
		$this->db->where('sort !=', 0);
		$this->db->order_by('sort', 'asc');
		return $this->db->get();
	}

	/**
	 * This method is used to set the show the office navigation icon on the home page
	 * which happens when the sort value is greater than zero
	 */
	public function set_show_office_group($show_office_group)
	{
		if ($show_office_group) {
			$sort = 999;
		} else {
			$sort = 0;
		}

		$modules_data = array(
			'sort' => $sort
		);
		$this->db->where('module_id', 'office');
		$this->db->update('modules', $modules_data);
	}

	/**
	 * This method is used to show the office navigation icon on the home page
	 * which happens when the sort value is greater than zero
	 */
	public function get_show_office_group()
	{
		$this->db->select('sort');
		$this->db->from('grants');
		$this->db->where('module_id', 'office');
		$this->db->from('modules');
		return $this->db->get()->row()->sort;
	}
	/**get_found_rows
	 * This method is used to show all the modules
	 */
	public function search($search, $rows = 0, $limit_from = 0, $sort = 'status', $order = 'asc', $count_only = FALSE)
	{
		if ($count_only == TRUE) {
			$this->db->select('COUNT(*) as count');
			$this->db->from('modules');

			return $this->db->get()->row()->count;
		}
		$this->db->select('*');
		$this->db->from('modules');
		// get_found_rows case


		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}
		return $this->db->get();
	}
	public function get_found_rows()
	{
		return $this->search('', 0, 0, 'status', 'asc', TRUE);
	}
	/*
	Inserts or updates a giftcard
	*/
	public function save($module_data, $module_id = '')
	{
		if ( $module_id != '') {
			$this->db->where('module_id', $module_id);
			return $this->db->update('modules', $module_data);
		}else{
			if ($this->db->insert('modules', $module_data)) {

				return true;
			}
			return false;
		}
		
		
		// if(!$giftcard_id || !$this->exists($giftcard_id))
		// {
		// 	if($this->db->insert('giftcards', $giftcard_data))
		// 	{
		// 		$giftcard_data['giftcard_number'] = $this->db->insert_id();
		// 		$giftcard_data['giftcard_id'] = $this->db->insert_id();

		// 		return TRUE;
		// 	}

		// 	return FALSE;
		// }

		// $this->db->where('giftcard_id', $giftcard_id);

		// return $this->db->update('giftcards', $giftcard_data);
	}
	public function get_info($module_id)
	{
		$this->db->select('*');
		$this->db->from('modules');
		$this->db->where('module_id', $module_id);
		return $this->db->get()->row();
	}
}
