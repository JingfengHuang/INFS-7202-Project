<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Following extends CI_Controller {

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
            $data['subscription_active'] = "active";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";
            
            $follow_list_html = "";
            $username = $this->session->userdata('username');
            $videos_array = $this->Videos_model->getVideoUpdate($username);

            foreach ($videos_array as $video) {
                $following_user_username = $video['username'];

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

                $follow_list_html .= "<li class='list-group-item w-100'>
                    <div class='row align-items-center pl-4 mr-0 w-100'>
                        <div class='row align-items-center w-50 pl-0 mr-0'>
                            <img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$following_user_avatar_filename.">
                            <h6 class='text-nowrap ml-5 w-25'>".$following_user_nickname."</h6>
                        </div>

                        <p class='w-auto'>Upload time: ".$video['time']."</p>
                    </div>

                    <a href=".base_url()."Videopage/index/".$video['id']."><video width='100%'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
                    <h4 class='w-100'><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				    <p>".$video['viewedtimes']." views</p>
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
            $this->load->view('following', $data);
            $this->load->view('template/footer');
        } else {
            redirect('Login');
        }
        
    }
}