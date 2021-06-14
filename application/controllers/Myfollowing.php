<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Myfollowing extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Videos_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')){
            $data['title'] = "V-Following";
            $data['trending_active'] = "";
            $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";
            
            $follow_list_html = "";
            $username = $this->session->userdata('username');
            $follows = $this->User_model->getFollowing($username);
            foreach ($follows as $follow) {
                $following_user_username = $follow['following'];

                $following_user_profiles = $this->User_model->getProfile($following_user_username);
                foreach ($following_user_profiles as $following_user_profile) {
                    $following_user_nickname = $following_user_profile['nickname'];
                }

                $following_user_avatars = $this->User_model->getAvatar($following_user_username);
                if ($following_user_avatars == null) {
                    $following_user_avatar_filename = "icons8-male-user-100.png";
                } else {
                    foreach ($following_user_avatars as $following_user_avatar) {
                        $following_user_avatar_filename = $following_user_avatar['filename'];
                    }
                }
                
                $followOrUnfollow = "unfollow";
                $button_style = "btn-danger";
                $button_status = "";
                $follow_list_html .= "<li class='list-group-item w-100'>
                    <div class='row align-items-center w-100 pl-0 mr-0'>
                        <img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$following_user_avatar_filename.">
                        <h6 class='text-nowrap ml-5 w-50'>".$following_user_nickname."</h6>

                        <a href='".base_url()."Myfollowing/".$followOrUnfollow."/".$following_user_username."' class='btn ".$button_style." btn-block w-auto".$button_status."'>Unfollow</a>

                    </div>
                </li>";
            }

            $data['follow_list_html'] = $follow_list_html;

            //Watch later number
            $videos_array = $this->Videos_model->getWatchLater($this->session->userdata('username'));

            $watchLaterNumber = 0;

            if (!empty($videos_array)) {
                foreach ($videos_array as $video_array) {
                    $videos = $this->Videos_model->retrieveVideo($video_array['videoID']);

                    foreach ($videos as $video) {
                        $watchLaterNumber += 1;
                    }

                }
            }

            $data['watchLaterNumber'] = $watchLaterNumber;


            //Load views
            $this->load->view('template/header', $data);
            $this->load->view('myfollowing', $data);
            $this->load->view('template/footer');
        } else {
            redirect('Login');
        }
        
    }

    public function unfollow($follow) {
        $this->load->model("User_model");
        $username = $this->session->userdata['username'];
        $this->User_model->unfollow($username, $follow);

        redirect('Myfollowing');
    }
}