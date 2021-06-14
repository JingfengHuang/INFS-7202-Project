<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Profile extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session');
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        } else{
            $this->load->model('User_model');
            $this->load->model('Videos_model');

            $data['isOne'] = "s";
            $follower_number = $this->User_model->getFollowers($this->session->userdata('username'));

            if ($follower_number == 1) {
                $data['isOne'] = "";
            }
            
            $data['follower_number'] = $follower_number;
        
            //Avatar header button
            $avatar_results = $this->User_model->getAvatar($this->session->userdata('username'));
            if (empty($avatar_results)) {
                $avatarHEADER = '<img class="nav-item rounded-circle img-fluid img-thumbnail border-light shadow" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>';
            } else {
                foreach ($avatar_results as $row) {
                    $avatarHEADER = '<img class="nav-item rounded-circle img-fluid img-thumbnail border-light shadow" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_'.$row['filename'].'>';
                }
            }
            $this->session->set_userdata('avatarHEADER', $avatarHEADER);

            $data['title'] = "V-Profile";
            $data['error'] = $this->session->userdata('editProfile');
            $data['trending_active'] = "";
		    $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";

            $data['AvatarStatus'] = $this->session->userdata('uploadAvatar');

            $data['username'] = "";
            $data['password'] = "";
            $data['confirmPassword'] = "";
            $data['nickname'] = "";
            $data['email'] = "";
            $data['avatarHTML'] = "";
            $data['followers_html'] = "";
            $data['twitterName'] = "";

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

            $this->load->view('template/header', $data);

            $userInfo = $this->User_model->getProfile($this->session->userdata('username'));
            if (!empty($userInfo)) {
                foreach ($userInfo as $row) {
                    $username = $row['username'];
                    $nickname = $row['nickname'];
                    $email = $row['email'];
                    $self_intro = $row['self_intro'];
                    $twitterName = $row['twitterAccountName'];
                }
                $data['username'] = $username;
                $data['nickname'] = $nickname;
                $data['email'] = $email;
                $data['twitterName'] = $twitterName;
            }

            $data['self_intro'] = $self_intro; 

            $results = $this->User_model->getAvatar($this->session->userdata('username'));
            if (empty($results)) {
                $avatarHTML = '<img class="rounded-circle w-25" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>';
                $general_info_avatarHTML = '<img class="rounded-circle w-75 border-light shadow-sm" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>';
            } else {
                foreach ($results as $row) {
                    $avatarHTML = '<img class="rounded-circle w-25" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_'.$row['filename'].'>';
                    $general_info_avatarHTML = '<img class="rounded-circle w-75 border-light shadow-sm" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_'.$row['filename'].'>';
                }
            }
            $data['avatarHTML'] = $avatarHTML;
            $data['general_info_avatarHTML'] = $general_info_avatarHTML;
            
            $followers_html = "";
            $follower_results = $this->User_model->myFollowers($this->session->userdata('username'));
            if (empty($follower_results)) {
                $followers_html .= "<div class='col-md-4 mb-3'>You don't have follower yet</div>";
            } else {
                foreach ($follower_results as $follower) {
                    $follower_nickname = "";
                    $follower_profiles = $this->User_model->getProfile($follower['user']);
                    foreach ($follower_profiles as $follower_profile) {
                        $follower_nickname = $follower_profile['nickname'];
                    }

                    $follower_avatars = $this->User_model->getAvatar($follower['user']);
                    if (empty($follower_avatars)) {
                        $followers_html .= "<div class='col-md-3 mb-3 d-flex flex-column justify-content-center align-items-center'>
                            <img class='rounded-circle w-50 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>
                            <p>".$follower_nickname."</p>
                            <a href='".base_url()."Profile/unfollow/".$follower['user']."' class='btn btn-block btn-danger'>Remove</a>
                        </div>";
                    } else {
                        foreach ($follower_avatars as $follower_avatar) {
                            $followers_html .= "<div class='col-md-3 mb-3 d-flex flex-column justify-content-center align-items-center'>
                                <img class='rounded-circle w-50 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$follower_avatar['filename'].">
                                <p>".$follower_nickname."</p>
                                <a href='".base_url()."Profile/unfollow/".$follower['user']."' class='btn btn-block btn-danger'>Remove</a>
                            </div>";
                        }
                    }
                }
            }

            $data['followers_html'] = $followers_html;

            $this->load->view('profile', $data);

            $this->load->view('template/footer');

            $this->session->set_userdata("editProfile", null);
            $this->session->set_userdata("uploadAvatar", null);
        }
    }

    public function unfollow($userID) {
        $this->load->model('User_model');
        $this->User_model->unfollow($userID, $this->session->userdata('username'));
        redirect('Profile');
    }

    public function editProfile() {
        $this->load->model('User_model');
        $this->load->library('form_validation');

        if (strpos($this->input->post('nickname'), " ") == false){
            $this->form_validation->set_rules('password', 'Password', 'callback_password_strength_check');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_userdata("editProfile", "<div class=\"alert alert-danger\" role=\"alert\">Password must have at least 8 length, and must contain at least 1 uppercase, 1 lowercase, and 1 number.</div>");
                redirect('Profile');
            } else {
                $updateResult = $this->User_model->updateProfile($this->session->userdata('username'));
                
                if ($updateResult) {
                    $this->session->set_userdata("editProfile", "<div class=\"alert alert-success\" role=\"alert\">Change has been saved.</div>");
                } else {
                    $this->session->set_userdata("editProfile", "<div class=\"alert alert-danger\" role=\"alert\">Please check your password!!</div>");
                }
        
                redirect('Profile');
            }
        } else {
            $this->session->set_userdata("editProfile", "<div class=\"alert alert-danger\" role=\"alert\">Space are not allowed in nickname!!</div>");
            redirect('Profile');
        }

    }

    public function uploadAvatar() {
        $this->load->model('User_model');

        $config['upload_path'] = './uploads/avatars/';
		$config['allowed_types'] = 'jpg|jpeg|png|svg';
		$config['max_size'] = 0;
		$config['max_width'] = 0;
		$config['max_height'] = 0;
        $this->load->library('upload', $config);

        
        if (!($this->upload->do_upload('avatar'))) {
            $this->session->set_userdata("uploadAvatar", "<div class=\"alert alert-danger\" role=\"alert\">Failed to upload avatar image!!</div>");

            redirect('Profile');
        } else {
            //Resize avatar
            $this->load->library('image_lib');
            $image_data = $this->upload->data();
            $this->crop($image_data);
            
            $this->session->set_userdata("uploadAvatar", "<div class=\"alert alert-success\" role=\"alert\">Avatar has been saved.</div>");

            $this->User_model->uploadAvatar($this->session->userdata('username'), $this->upload->data('file_name'));
            
            redirect('Profile');
        }
    }

    // Crop Manipulation.
    public function crop($image_data) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['x_axis'] = $this->input->post('x_axis');
        $config['y_axis'] = $this->input->post('y_axis');
        $config['maintain_ratio'] = FALSE;

        if ($image_data["image_width"] >= $image_data['image_height']) {
            $config['width'] = $image_data["image_height"];
        } else {
            $config['height'] = $image_data["image_width"];
        }

        $config['new_image'] = './uploads/avatars/crop_' . $image_data['file_name'];

        //send config array to image_lib's  initialize function
        $this->image_lib->initialize($config);
        $src = $config['new_image'];
        $data['crop_image'] = substr($src, 2);
        $data['crop_image'] = base_url() . $data['crop_image'];
        // Call crop function in image library.
        $this->image_lib->crop();
        // Return new image contains above properties and also store in "upload" folder.
        return $data;
    }

    //change password functions
    //Inspired by https://stackoverflow.com/questions/10752862/password-strength-check-in-php
    public function password_strength_check($password) {
		$result = true;

		if ( !preg_match("#[0-9]+#", $password) ) {
			$result = False;
		}
	
		if ( !preg_match("#[a-z]+#", $password) ) {
			$result = False;
		}
	
		if ( !preg_match("#[A-Z]+#", $password) ) {
			$result = False;
		}

		$password_array = str_split($password);
		$password_length = sizeof($password_array);

		if ($password_length <8 ) {
			$result = False;
		}

		return $result;

	}

}