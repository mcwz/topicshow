<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	/*注册用户*/
	public function signup()
	{
		if(($email=$this->input->post("email")) && ($password=$this->input->post("password")) && ($nikename=$this->input->post("nikename")))
		{

			$results_nums = $this->mdb->where(array("email"=>$email))->get('users')->num_rows();
			if($results_nums<1)
			{
				if($this->mdb->insert('users',array('email'=>$email,'password'=>password_hash($password, PASSWORD_DEFAULT),'nikename'=>$nikename)))
				{
					$last_id = $this->mdb->insert_id();
					$newdata = array(
					    'userid'  => $last_id.'',
					    'email'     => $this->input->post("email"),
					    'logged_in' => TRUE,
					    'nikename'=>$this->input->post("nikename")
					);

					$this->session->set_userdata($newdata);

					redirect('/home/talk');
				}
			}
			else
			{
				echo "user exists,<a href='javascript:history.go(-1)'>click go back</a>";
			}

		}
		else
		{
			$this->load->view("common/header",['page_title'=>'注册','type'=>'signup']);
			$this->load->view("users/signup");
			$this->load->view("common/footer");

		}
	}


	public function login()
	{
		if(($email=$this->input->post("email")) && ($password=$this->input->post("password")))
		{

			$results = $this->mdb->where(array("email"=>$email))->get('users');

			if($results->num_rows()==1)
			{
				$results=$results->result();
				$loginUser=$results[0];
				
				if (password_verify($password, $loginUser->password)) {
				    $newdata = array(
					    'userid'  => $loginUser->_id.'',
					    'email'     => $loginUser->email,
					    'logged_in' => true,
					    'nikename'=>$loginUser->nikename
					);

					$this->session->set_userdata($newdata);

					redirect('/home/talk');
				} else {
				    echo 'Invalid password,<a href="javascript:history.go(-1)"">click go back</a>';
				}
			}
			else
			{
				echo "user not exists,<a href='javascript:history.go(-1)'>click go back</a>";
			}

		}
		else
		{
			$this->load->view("common/header",['page_title'=>'登录','type'=>'signin']);
			$this->load->view("users/signup");
			$this->load->view("common/footer");

		}
	}
}