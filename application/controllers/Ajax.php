<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Ajax extends CI_Controller {

    public function fetch() {
        $this->load->model('Videos_model');
        $output = "";
        $keyword = "";
        if ($this->input->get('query')) {
            $keyword = $this->input->get('query');
        }
        $data = $this->Videos_model->fetch_videos($keyword);

        if (!$data == null) {
            echo json_encode ($data->result());
        } else {
            echo "";
        }
    }

}