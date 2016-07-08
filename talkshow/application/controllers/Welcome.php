<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->library("Mdb");
		var_dump($this->session->userid);
		//$this->Mdb->select_db($db);
		//$result=$this->mdb->insert("topics",array("topic"=>"java","issub"=>0,"ctime"=>time()));
		//print_r($result);
		$result=$this->mdb->get('users'); // returns all results
		echo "<pre>";
		print_r($result->result());
	}
        
        
        public function newgroups() {
            $this->mdb->insert('topics',array('topic'=>'java','opened'=>true));
        }


	public function delete($table)
	{
		$this->mdb->delete($table);
	}
}
