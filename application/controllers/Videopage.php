<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Videopage extends CI_Controller {

	public function index($video_id = NULL){

        //$this->output->cache(5);
        $this->load->model("Videos_model");
        $this->load->model('User_model');

        if ($video_id == NULL) {
            redirect('trending');
        } else {
            $this->session->set_userdata('video_id', $video_id);

            //Video player
            $video_html = "";

            $videos = $this->Videos_model->retrieveVideo($video_id);

            foreach ($videos as $video) {
                $this->Videos_model->increaseViewAccount($video['id']);

                $data['video'] = $video;
                $data['title'] = "V-".$video['videotitle'];

                $author_username = $video['username'];
                $author_infos = $this->User_model->getProfile($author_username);
                foreach ($author_infos as $author_info) {
                    $author_nickname = $author_info['nickname'];

                    if ($author_info['twitterAccountName'] != null) {
                        $author_twitter = $author_info['twitterAccountName'];
                    } else {
                        $author_twitter = null;
                    }
                }

                $author_avatars = $this->User_model->getAvatar($author_username);
                if ($author_avatars == null) {
                    $author_avatar_filename = "icons8-male-user-100.png";
                } else {
                    foreach ($author_avatars as $author_avatar) {
                        $author_avatar_filename = $author_avatar['filename'];
                    }
                }

                //follow button
                if ($this->session->userdata('logged_in')) {
                    if ($author_username == $this->session->userdata['username']) {
                        $followOrUnfollow = "follow";
                        $follow_button = "Myself";
                        $button_status = "disabled";
                        $button_style = "btn-secondary";
                    } else {
                        if ($this->User_model->isFollowing($this->session->userdata('username'), $author_username)) {
                            $followOrUnfollow = "unfollow";
                            $follow_button = "Unfollow";
                            $button_status = "";
                            $button_style = "btn-danger";
                        } else {
                            $followOrUnfollow = "follow";
                            $follow_button = "Follow";
                            $button_status = "";
                            $button_style = "btn-primary";
                        }
                    }
                } else {
                    $followOrUnfollow = "follow";
                    $follow_button = "Login to Follow";
                    $button_status = "disabled";
                    $button_style = "btn-warning";
                }

                //add to favourite button
                if ($this->session->userdata('logged_in')) {
                    if ($author_username == $this->session->userdata['username']) {
                        $addOrDelete = "addToFavourites";
                        $add_to_favourite_button_style = "btn-secondary";
                        $add_to_favourite_button_status = "disabled";
                        $add_to_favourite_button = "My video";
                    } else {
                        if ($this->Videos_model->isFavourites($this->session->userdata('username'), $video['id'])) {
                            $addOrDelete = "deleteFromFavourites";
                            $add_to_favourite_button_style = "btn-danger";
                            $add_to_favourite_button_status = "";
                            $add_to_favourite_button = "Delete from favourites";
                        } else {
                            $addOrDelete = "addToFavourites";
                            $add_to_favourite_button_style = "btn-primary";
                            $add_to_favourite_button_status = "";
                            $add_to_favourite_button = "Add to favourites";
                        }
                    }
                } else {
                    $addOrDelete = "addToFavourites";
                    $add_to_favourite_button_style = "btn-warning";
                    $add_to_favourite_button_status = "disabled";
                    $add_to_favourite_button = "Login and add to favourites";
                }
                
                //like button
                if ($this->session->userdata('logged_in')) {
                    if ($this->Videos_model->isLike($this->session->userdata('username'), $video['id'])) {
                        $like_button_status = 'disabled';
                    } else {
                        $like_button_status = '';
                    }
                } else {
                    $like_button_status = 'disabled';
                }

                $liked_times = 0;
                $likes_infos = $this->Videos_model->getLikes($video['id']);
                foreach ($likes_infos as $likes_info) {
                    $liked_times += 1;
                }

                //Watch later button
                if ($this->session->userdata('logged_in')) {
                    if ($this->Videos_model->isWatchLater($this->session->userdata('username'), $video['id'])) {
                        $watchLaterButtonStyle = "btn-secondary";
                        $watchLaterButtonStatus = "disabled";
                        $watchLaterButtonText = "Already in watch later";
                    } else {
                        $watchLaterButtonStyle = "btn-primary";
                        $watchLaterButtonStatus = "";
                        $watchLaterButtonText = "Add to watch later";
                    }
                } else {
                    $watchLaterButtonStyle = "btn-warning";
                    $watchLaterButtonStatus = "disabled";
                    $watchLaterButtonText = "Login and add to watch later";
                }

                $video_html .= "<div class='col-md-8 offset-2'>
                    <video width='100%'  class='rounded border-light shadow-sm' controls><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video>
                    <div class='row align-items-center w-100 pt-2 ml-0'>
                        <div class='row align-items-center w-25 pl-3'>
                            <img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$author_avatar_filename.">
                            <h6 class='text-nowrap text-center w-25'>".$author_nickname."</h6>
                        </div>

                        <form class='w-25'>
                            <a href='".base_url()."Videopage/like/".$video['id']."' class='btn btn-block d-flex align-items-center ".$like_button_status."'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-hand-thumbs-up-fill mr-1' viewBox='0 0 16 16'>
                                    <path d='M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z'/>
                                </svg>
                                <span>
                                    ".$liked_times."
                                </span>
                            </a>
                        </form>

                        <form class='w-50'>
                            <a href='".base_url()."Videopage/".$followOrUnfollow."/".$author_username."' class='btn ".$button_style." btn-block col-3 offset-9 ".$button_status."'>".$follow_button."</a>
                        </form>

                    </div>";

                if ($author_twitter != null) {
                    $video_html .= "<a href='https://twitter.com/".$author_twitter."' data-size='large' class='twitter-follow-button' data-show-count='false'>Follow</a><script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>";
                }
                   
                $video_html .= "
                    <a class='twitter-share-button' href='https://twitter.com/intent/tweet' data-size='large'>Tweet</a><script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>
                    <p>Description: ".$video['description']."</p>
                    <p>Upload time: ".$video['time']."</p>
                    <p>Viewed: ".$video['viewedtimes']."</p>
                    <a href='".base_url()."Videopage/".$addOrDelete."/".$video['id']."' class='btn ".$add_to_favourite_button_style." btn-block col-3 ".$add_to_favourite_button_status."'>".$add_to_favourite_button."</a>
                    <a href='".base_url()."Videopage/addWatchLater/".$video['id']."' class='btn ".$watchLaterButtonStyle." btn-block col-3 ".$watchLaterButtonStatus."'>".$watchLaterButtonText."</a>
                </div>";
            }

            //Video comments
            $comment_html = "";

            $comments = $this->Videos_model->getComments($video_id);
            foreach ($comments as $comment) {
                if ($comment['isanonymous'] == 1) {
                    $user_nickname = "Anonymous";
                    $avatar_filename = "icons8-male-user-100.png";
                } else {
                    $user = $comment['user'];
                    $user_infos = $this->User_model->getProfile($user);
                    foreach ($user_infos as $user_info) {
                        $user_nickname = $user_info['nickname'];
                    }

                    $user_avatars = $this->User_model->getAvatar($user);
                    if ($user_avatars == null) {
                        $avatar_filename = "icons8-male-user-100.png";
                    } else {
                        foreach ($user_avatars as $user_avatar) {
                            $avatar_filename = $user_avatar['filename'];
                        }
                    }
                }
                

                $comment_html .= "<div class='card border-light shadow-sm'>
                        <div class='row align-items-center justify-content-center w-100 pt-2 pl-1 pr-1 ml-0'>
                            <div class='column align-items-center justify-content-center w-25'>
                                <img class='rounded-circle img-thumbnail w-25' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$avatar_filename.">
                                <h6 class='text-nowrap w-25 text-center'>".$user_nickname."</h6>
                            </div>

                            <div class='column align-items-center w-75'>
                                <div>".$comment['text']."</div>
                                <p class='text-right text-muted pr-1'>".$comment['time']."</p>
                            </div>
                        </div>
                </div>";
            }

            $data['trending_active'] = "";
            $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";
            $data['video_html'] = $video_html;
            $data['comment_html'] = $comment_html;

            if ($this->session->userdata('logged_in')) {
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
            } else {
                $data['watchLaterNumber'] = "";
            }
            
            $this->load->view('template/header', $data);
            $this->load->view('videopage', $data);
            $this->load->view('template/footer', $data);
        }

    }

    public function comment() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model("Videos_model");
            $commenttext = $this->input->post('commenttext');
            $isAnonymous = $this->input->post('anonymous');
            $videoID = $this->session->userdata['video_id'];
            $username = $this->session->userdata['username'];

            if ($isAnonymous) {
                $this->Videos_model->comment($username, $videoID, $commenttext, 1);
            } else {
                $this->Videos_model->comment($username, $videoID, $commenttext, 0);
            }

            redirect('Videopage/index/'.$this->session->userdata['video_id']);
        } else {
            redirect('Login');
        }

    }

    public function follow($follow) {
        $this->load->model("User_model");
        $username = $this->session->userdata('username');
        $this->User_model->follow($username, $follow);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }

    public function unfollow($follow) {
        $this->load->model("User_model");
        $username = $this->session->userdata('username');
        $this->User_model->unfollow($username, $follow);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }

    public function addToFavourites($videoID) {
        $this->load->model('Videos_model');
        $username = $this->session->userdata('username');

        $this->Videos_model->addToFavourites($username, $videoID);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }

    public function deleteFromFavourites($videoID) {
        $this->load->model('Videos_model');
        $username = $this->session->userdata('username');

        $this->Videos_model->deleteFromFavourites($username, $videoID);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }

    public function like($videoID) {
        $this->load->model('Videos_model');

        $this->Videos_model->like($this->session->userdata('username'), $videoID);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }

    public function addWatchLater($videoID) {
        $this->load->model('Videos_model');

        $this->Videos_model->addToWatchLater($this->session->userdata('username'), $videoID);

        redirect('Videopage/index/'.$this->session->userdata['video_id']);
    }
}