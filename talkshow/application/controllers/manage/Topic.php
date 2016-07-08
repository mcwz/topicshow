<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends CI_Controller {

    public function topiclist() {
        $this->load->view("manage/common/backTop");

        $results = $this->mdb->get('topics');

        $this->load->view("manage/topic/list", ['topics' => $results]);
        $this->load->view("manage/common/backBottom");
    }

    public function update($upid = '') {
        $topic = $this->input->post('topic');
        if ($topic) {
            if ($oid = $this->input->post('id')) {//update
                $data = array("topic" => $topic);
                $this->mdb->update("topics", $data, array("_id" => new \MongoDB\BSON\ObjectID($oid)));
            } else {
                $results = $this->mdb->where(['topic' => $topic])->get('topics');
                if ($results->num_rows() < 1) {
                    $this->mdb->insert('topics', array('topic' => $topic, 'opened' => true, 'ctime' => time()));
                }
            }
            redirect('manage/topic/topiclist');
        } else {
            if ($upid == '') {
                $this->load->view("manage/common/backTop");
                $this->load->view("manage/topic/add");
                $this->load->view("manage/common/backBottom");
            } else {
                $this->load->view("manage/common/backTop");
                $results = $this->mdb->where(['_id' => new \MongoDB\BSON\ObjectID($upid)])->get('topics');
                $this->load->view("manage/topic/update",['update'=>$results]);
                $this->load->view("manage/common/backBottom");
            }
        }
    }

    public function delthis($oid) {
        if ($oid) {
            $this->mdb->delete("topics", array('_id' => new \MongoDB\BSON\ObjectID($oid)));
        }
        redirect('manage/topic/topiclist');
    }

}
