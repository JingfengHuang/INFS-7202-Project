<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Achievement extends CI_Controller {

	public function __construct() {
        parent:: __construct();
        $this->load->library('session');
    }

    public function index() {
        $this->load->model('Videos_model');
        $this->load->model('User_model');

        $this->load->library('image_lib');

        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        } else {
            //Header data
            $data['title'] = "V-My achievements";
            $data['trending_active'] = "";
		    $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";

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


            //Welcome
            $welcome = "Thank you very much for being our user for such a long time, ";
            $username = $this->session->userdata('username');
            $user_infos = $this->User_model->getProfile($username);
            foreach ($user_infos as $user_info) {
                $nickname = $user_info['nickname'];
            }
            $welcome .= $nickname;
            $welcome .= "! During your time with you, you have:";

            //Comment achievement
            $commentCounter = 0;
            $allComments = $this->Videos_model->getUserComments($username);
            foreach ($allComments as $comment) {
                $commentCounter += 1;
            }
            if ($commentCounter < 10) {
                $commentTitle = "Dumb";
            } else {
                $commentTitle = "Talkaholic";
            }
            $commentMoreThan = $this->Videos_model->getCommentPercentage($username, $commentCounter);

            //Favourite achievement
            $favouriteCounter = 0;
            $allFavourites = $this->Videos_model->getFavourites($username);
            foreach ($allFavourites as $favourite) {
                $favouriteCounter += 1;
            }
            if ($favouriteCounter < 10) {
                $favouriteTitle = "Cold";
            } else {
                $favouriteTitle = "Hot";
            }
            $favouriteMoreThan = $this->Videos_model->getFavouritePercentage($username, $favouriteCounter);

            //Follow achievement
            $followCounter = 0;
            $followings = $this->User_model->getFollowing($username);
            foreach ($followings as $follwoing) {
                $followCounter += 1;
            }
            if ($followCounter < 10) {
                $followTitle = "Pawn";
            } else {
                $followTitle = "Head";
            }
            $followMoreThan = $this->User_model->getFollowPercengate($username, $followCounter);

            //Like achievement
            $likeCounter = 0;
            $likes = $this->Videos_model->getUserLikes($username);
            foreach($likes as $like) {
                $likeCounter += 1;
            }
            if ($likeCounter < 10) {
                $likeTitle = "Stingy";
            } else {
                $likeTitle = "Generous";
            }
            $likeMoreThan = $this->Videos_model->getLikePercentage($username, $likeCounter);


            //Session data
            $this->session->set_userdata('nickname', $nickname);
            $this->session->set_userdata('commentCounter', $commentCounter);
            $this->session->set_userdata('favouriteCounter', $favouriteCounter);
            $this->session->set_userdata('followCounter', $followCounter);
            $this->session->set_userdata('likeCounter', $likeCounter);

            //Transfer data
            $data['welcome'] = $welcome;

            $data['commentCounter'] = $commentCounter;
            $data['commentTitle'] = $commentTitle;
            $data['commentUserPercentage'] = $commentMoreThan;

            $data['favouriteCounter'] = $favouriteCounter;
            $data['favouriteTitle'] = $favouriteTitle;
            $data['favouritePercentage'] = $favouriteMoreThan;

            $data['followCounter'] = $followCounter;
            $data['followTitle'] = $followTitle;
            $data['followPercentage'] = $followMoreThan;

            $data['likeCounter'] = $likeCounter;
            $data['likeTitle'] = $likeTitle;
            $data['likePercentage'] = $likeMoreThan;

            if ($this->session->has_userdata('isError')) {
                $data['error'] = "<div class=\"alert alert-danger\" role=\"alert\"> Unable to upload file!!</div>";
            } else {
                $data['error'] = "";
            }
            //Load views
            $this->load->view('template/header', $data);
            $this->load->view('achievement', $data);
            $this->load->view('template/footer', $data);

            //Unset userdata
            $this->session->unset_userdata('isError');
        }
    }

    public function makeAchievement() {
        $this->load->model('Videos_model');
        $this->load->model('User_model');

        $this->load->library('image_lib');

        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        } else {
            $config['upload_path'] = './uploads/watermark/';
            $config['allowed_types'] = 'jpg|jpeg|png|svg';
            $config['max_size'] = 0;
            $config['max_width'] = 0;
            $config['max_height'] = 0;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('background')) {
                //If image upload in folder, set also this value in "$image_data".
                $image_data = $this->upload->data();
                $text .= "@".$this->session->userdata('nickname');
                $text .= "\nI left ".$this->session->userdata('commentCounter')." comments, have ".$this->session->userdata('favouriteCounter')." favourite videos, following ".$this->session->userdata('followCounter')." people, and give ".$this->session->userdata('likeCounter')." likes to videos.";
                $text .= "\nEnjoy your day in V!https://infs3202-cb8e706b.uqcloud.net/demo/";
                $data = $this->water_marking($image_data, $text);
                $this->download($data['watermark_image_path']);

                $this->session->unset_userdata('nickname');
                $this->session->unset_userdata('commentCounter');
                $this->session->unset_userdata('favouriteCounter');
                $this->session->unset_userdata('followCounter');
                $this->session->unset_userdata('likeCounter');
                $this->session->unset_userdata('isError');
            } else {
                $this->session->set_userdata('isError', true);
            }
            redirect('Achievement');
        }
    }

    // Water Mark Manipulation.
    public function water_marking($image_data, $text) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['wm_text'] = $text;
        $config['wm_type'] = 'text';
        $config['wm_font_size'] = '10';
        $config['wm_font_color'] = 'ffffff';
        $config['wm_font_path'] = './system/fonts/texb.ttf';
        $config['wm_hor_alignment'] = 'center';
        $config['wm_vrt_alignment'] = 'middle';
        $config['new_image'] = './uploads/watermark/watermark_' . $image_data['file_name'];

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['watermark_image'] = substr($src, 2);
        $data['watermark_image'] = base_url() . $data['watermark_image'];
        $data['watermark_image_path'] = $src;
        // Call watermark function in image library.
        $this->image_lib->watermark();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }

    function download($filepath) {

        $this->load->library('zip');
        // pass second argument as FALSE if want to ignore preceding directories
        $this->zip->read_file($filepath);

        // prompt user to download the zip file
        $this->zip->download('images.zip');
    }
}