<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function talk($topic = "") {
        if ($topic == "") {
            redirect("home/talk/java");
        }
        if (!$this->session->userid) {
            redirect('users/login');
        }
        $results = $this->mdb->where(['topic' => $topic])->get('topics');
        if ($results->num_rows() > 0) {
            $this->load->view("common/header", ['page_title' => $topic . '话题聊']);
            $this->load->view("home/topic", ['topic' => $topic]);
            $this->load->view("common/footer");
        } else {
            $this->load->view("common/header", ['page_title' => '没有找到此话题']);
            $this->load->view("home/topic", ['topic' => false]);
            $this->load->view("common/footer");
        }
    }

    public function time() {
        echo time();
    }

}
