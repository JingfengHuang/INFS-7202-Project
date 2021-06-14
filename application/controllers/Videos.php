<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Videos extends CI_Controller {

	public function __construct() {
        parent:: __construct();
        $this->load->library('session');
    }

    public function index() {
        //$this->output->cache(5);
        $this->load->model('Videos_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        } else {
            $data['title'] = "V-My videos";
            $data['uploadStatus'] = $this->session->userdata("uploadStatus");
            $data['trending_active'] = "";
		    $data['subscription_active'] = "";
            $data['favourites_active'] = "";
            $data['watchlater_active'] = "";

            $myVideos = $this->Videos_model->retrieveMyVideos($this->session->userdata('username'));


            $videos = "";
            foreach($myVideos as $myVideo) {
                $liked_times = 0;
                $likes_infos = $this->Videos_model->getLikes($myVideo['id']);
                foreach ($likes_infos as $likes_info) {
                    $liked_times += 1;
                }

                $videos .= "<div class='col-md-4 mb-3'>
                    <a href=".base_url()."Videopage/index/".$myVideo['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$myVideo['filename']." type=".$myVideo['filetype']."></video></a>
                    <h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$myVideo['id'].">Video title: ".$myVideo['videotitle']."</a></h4>
                    <p>Description: ".$myVideo['description']."</p>
                    <p>Upload time: ".$myVideo['time']."</p>
                    <p>Viewed: ".$myVideo['viewedtimes']."</p>
                    <div class=''>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-hand-thumbs-up-fill mr-1' viewBox='0 0 16 16'>
                            <path d='M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z'/>
                        </svg>
                        <span>
                            ".$liked_times."
                        </span>
                    </div>
                    <a href='".base_url()."Videos/delete/".$myVideo['id']."' class='btn btn-block btn-danger'>Delete</a>
                </div>";
        }

        $data['videos'] = $videos;

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
        $this->load->view('videos', $data);
        $this->load->view('template/footer', $data);

        $this->session->set_userdata("uploadStatus", "");
        }
    }

    public function upload() {

        $this->load->model('Videos_model');

        $config['upload_path'] = './uploads/videos';
		$config['allowed_types'] = 'mp4|mov|wmv|flv|avi|mkv';
		$config['max_size'] = 0;
		$config['max_width'] = 0;
		$config['max_height'] = 0;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('uploadVideo')) {
            $this->Videos_model->upload($this->upload->data('file_name'), $this->upload->data('file_type'), $this->input->post('videotitle'), $this->input->post('videocategory'), $this->input->post('description'), $this->session->userdata('username'));
            $this->session->set_userdata("uploadStatus", "<div class=\"alert alert-success\" role=\"alert\">Video has been uploaded.</div>");
        } else {
            $this->session->set_userdata("uploadStatus", "<div class=\"alert alert-danger\" role=\"alert\">Failed to upload video!!</div>");
        }

        redirect('Videos');
    }

    public function delete($videoID) {
        $this->load->model('Videos_model');
        $this->Videos_model->deleteVideo($videoID);
        $this->session->set_userdata("uploadStatus", "<div class=\"alert alert-success\" role=\"alert\">Video has been deleted.</div>");
        redirect('Videos');
    }

}