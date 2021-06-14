<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Trending extends CI_Controller {

	public function index()
	{
		//$this->output->cache(5);
		$this->load->model('Videos_model');
		$this->load->model('User_model');
		$this->load->helper('date');

		//Inspired by https://github.com/RIT-Tool-Time/Soundfall/blob/master/user_guide_src/source/helpers/date_helper.rst
		$time = now('Australia/Victoria');
		$now = unix_to_human($time);
		$data['now'] = $now;

		$data['title'] = "V-Trending";

		$data['trending_active'] = "active";
		$data['subscription_active'] = "";
		$data['favourites_active'] = "";
		$data['watchlater_active'] = "";

		$MostPopVideos = $this->Videos_model->retrieveMostPopularVideos();
		$data['MostPopVideos'] = $MostPopVideos;

		foreach($MostPopVideos as $MostPopVideo) {
			$pop_user_username = $MostPopVideo['username'];
		}

		$pop_user_infos = $this->User_model->getProfile($pop_user_username);
		foreach ($pop_user_infos as $pop_user_info) {
			$pop_user_nickname = $pop_user_info['nickname'];
			$data['pop_user_nickname'] = $pop_user_nickname;
		}

		$pop_user_avatar_infos = $this->User_model->getAvatar($pop_user_username);
		foreach ($pop_user_avatar_infos as $pop_user_avatar_info) {
			$pop_user_avatar = $pop_user_avatar_info['filename'];
			$data['pop_user_avatar'] = $pop_user_avatar;
		}
		
		//Trending videos
		$videos = $this->Videos_model->retrieveTrendingVideos();

        $trending_videos_html = "";
        foreach($videos as $video) {

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
			

			$trending_videos_html .= "<div class='col-3'>
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
		
		$data['trending_videos_html'] = $trending_videos_html;

		//Funny videos
		$videos = $this->Videos_model->retrieveCategoryVideos('Fun');

        $funny_videos_html = "";
        foreach($videos as $video) {
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

			$funny_videos_html .= "<div class='col-md-3'>
				<a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
				<h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				<div class='row align-items-center w-75'>
					<img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
					<h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
				</div>
                <p>Upload time: ".$video['time']."</p>
                <p>".$video['viewedtimes']." views</p>
            </div>";
		}
		
		$data['funny_videos_html'] = $funny_videos_html;

		//Life videos
		$videos = $this->Videos_model->retrieveCategoryVideos('Life');

        $life_videos_html = "";
        foreach($videos as $video) {
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

			$life_videos_html .= "<div class='col-md-3'>
				<a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
				<h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				<div class='row align-items-center w-75'>
					<img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
					<h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
				</div>
                <p>Upload time: ".$video['time']."</p>
                <p>".$video['viewedtimes']." views</p>
            </div>";
		}
		
		$data['life_videos_html'] = $life_videos_html;

		//Game videos
		$videos = $this->Videos_model->retrieveCategoryVideos('Game');

        $game_videos_html = "";
        foreach($videos as $video) {
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

			$game_videos_html .= "<div class='col-md-3'>
				<a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
				<h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				<div class='row align-items-center w-75'>
					<img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
					<h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
				</div>
                <p>Upload time: ".$video['time']."</p>
                <p>".$video['viewedtimes']." views</p>
            </div>";
		}
		
		$data['game_videos_html'] = $game_videos_html;

		//Webtoon videos
		$videos = $this->Videos_model->retrieveCategoryVideos('Webtoon');

        $webtoon_videos_html = "";
        foreach($videos as $video) {
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

			$webtoon_videos_html .= "<div class='col-md-3'>
				<a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
				<h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				<div class='row align-items-center w-75'>
					<img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
					<h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
				</div>
                <p>Upload time: ".$video['time']."</p>
                <p>".$video['viewedtimes']." views</p>
            </div>";
		}
		
		$data['webtoon_videos_html'] = $webtoon_videos_html;

		//fashion videos
		$videos = $this->Videos_model->retrieveCategoryVideos('Fashion');

        $fashion_videos_html = "";
        foreach($videos as $video) {
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

			$fashion_videos_html .= "<div class='col-md-3'>
				<a href=".base_url()."Videopage/index/".$video['id']."><video width='100%' class='rounded border-light shadow-sm'><source src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/videos/".$video['filename']." type=".$video['filetype']."></video></a>
				<h4><a class='text-decoration-none' href=".base_url()."Videopage/index/".$video['id'].">".$video['videotitle']."</a></h4>
				<div class='row align-items-center w-75'>
					<img class='rounded-circle w-25 img-thumbnail' src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_".$user_avatar.">
					<h6 class='text-nowrap ml-1'>".$user_nickname."</h6>
				</div>
                <p>Upload time: ".$video['time']."</p>
                <p>".$video['viewedtimes']." views</p>
            </div>";
		}
		
		$data['fashion_videos_html'] = $fashion_videos_html;

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
		
		//Load views
		$this->load->view('template/header', $data);
		$this->load->view('trending', $data);
		$this->load->view('template/footer');

	}

}