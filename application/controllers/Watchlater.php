<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Watchlater extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Videos_model');
    }

    public function index() {
        $this->load->helper('date');
        if ($this->session->userdata('logged_in')){
            $data['title'] = "V-Watch Later";
            $data['trending_active'] = "";
            $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "active";

            //Inspired by https://github.com/RIT-Tool-Time/Soundfall/blob/master/user_guide_src/source/helpers/date_helper.rst
            $time = now('Australia/Victoria');
            $now = unix_to_human($time);
            $data['now'] = $now;

            $watchlater_list_html = "";

            $username = $this->session->userdata('username');
            $videos_array = $this->Videos_model->getWatchLater($username);

            $watchLaterNumber = 0;

            if (empty($videos_array)) {
                $watchlater_list_html .= "No video in watch later currently.";
            } else {
                foreach ($videos_array as $video_array) {
                    $videos = $this->Videos_model->retrieveVideo($video_array['videoID']);

                    foreach ($videos as $video) {

                        $watchlater_list_html .= "<li class='list-group-item w-75'>
                            <a href=".base_url()."Videopage/index/".$video['id']."><video width='100%'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
                            <h4 class='w-100'><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
                            <p>Added at: ".$video_array['time']."</p>
                            <a href='".base_url()."Watchlater/removeWatchLater/".$video['id']."' class='btn btn-danger btn-block col-3'>Remove</a>
                        </li>";

                        $watchLaterNumber += 1;
                    }

                }
            }
            

            $data['watchlater_list_html'] = $watchlater_list_html;
            $data['watchLaterNumber'] = $watchLaterNumber;
            //Load views
            $this->load->view('template/header', $data);
            $this->load->view('watchlater', $data);
            $this->load->view('template/footer');
        } else {
            redirect('Login');
        }
    }

    public function removeWatchLater($videoID) {
        $this->load->model('Videos_model');
        
        $this->Videos_model->removeWatchLater($this->session->userdata('username'), $videoID);

        redirect('Watchlater');
    }
}