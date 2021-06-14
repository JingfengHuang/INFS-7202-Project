<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Favourites extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->model('Videos_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')){
            $data['title'] = "V-Favourites";
            $data['trending_active'] = "";
            $data['subscription_active'] = "";
            $data['favourites_active'] = "active";
            $data['watchlater_active'] = "";

            $favourite_videos_html = "";
            $favouriteVideos = $this->Videos_model->getFavourites($this->session->userdata('username'));

            foreach($favouriteVideos as $favouriteVideo) {
                $videoID = $favouriteVideo['videoID'];

                $videos = $this->Videos_model->retrieveVideo($videoID);

                foreach ($videos as $video) {

                    $author_username = $video['username'];
                    $user_infos = $this->User_model->getProfile($author_username);
        
                    foreach ($user_infos as $user_info) {
                        $user_nickname = $user_info['nickname'];
                    }
        
                    $user_avatar_infos = $this->User_model->getAvatar($author_username);
                    if ($user_avatar_infos == null) {
                        $user_avatar = "icons8-male-user-100.png";
                    } else {
                        foreach ($user_avatar_infos as $user_avatar_info) {
                            $user_avatar = $user_avatar_info['filename'];
                        }
                    }
                    
        
                    $favourite_videos_html .= "<div class='col-3'>
                        <a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
        
                        <h4 class='w-100'><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
                        <div class='row align-items-center w-75'>
                            <img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
                            <h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
                        </div>
                        <p>Upload time: ".$video['time']."</p>
                        <p>".$video['viewedtimes']." views</p>
        
                    </div>";
                }
            }
            
            $data['favourite_videos_html'] = $favourite_videos_html;


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
            $this->load->view('favourites', $data);
            $this->load->view('template/footer');

        } else {
            redirect('Login');
        }
    }
}