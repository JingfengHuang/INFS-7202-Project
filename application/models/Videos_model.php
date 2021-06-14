<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
 class Videos_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    //video upload model
    public function upload($filename, $filetype, $videotitle, $videocategory, $description, $username) {
        $this->load->helper('date');

		$time = now('Australia/Victoria');
        $now = unix_to_human($time);
        
        $data = array (
            'filename' => $filename,
            'filetype' => $filetype,
            'videotitle' => $videotitle,
            'videocategory' => $videocategory,
            'description' => $description,
            'username' => $username,
            'time' => $now,
            'viewedtimes' => 0
        );

        $this->db->insert('videos', $data);
    }


    //video retrieve model
    public function retrieveMyVideos($username) {
        $query = $this->db->get_where('videos', array('username' => $username));

        return $query->result_array();
    }

    public function retrieveTrendingVideos() {
        $this->db->order_by('viewedtimes', 'DESC');
        $this->db->limit(8);
        $query = $this->db->get('videos');

        return $query->result_array();
    }

    public function retrieveCategoryVideos($category) {
        $this->db->order_by('viewedtimes', 'DESC');
        $this->db->limit(8);
        $query = $this->db->get_where('videos', array('videocategory' => $category));

        return $query->result_array();
    }

    public function retrieveMostPopularVideos() {
        $query = $this->db->query('SELECT * FROM `videos` WHERE `viewedtimes` = (SELECT MAX(`viewedtimes`) FROM `videos`)');

        return $query->result_array();
    }

    public function retrieveVideo($id) {
        $query = $this->db->get_where('videos', array('id' => $id));

        return $query->result_array();
    }

    //Delete video model
    public function deleteVideo($id) {
        $this->db->where('videoID', $id);
        $this->db->delete('videolikes');

        $this->db->where('videoID', $id);
        $this->db->delete('videocomments');

        $this->db->where('videoID', $id);
        $this->db->delete('favourites');

        $this->db->where('videoID', $id);
        $this->db->delete('watchlater');

        $this->db->where('id', $id);
        $this->db->delete('videos');
    }

    //video change value model
    public function increaseViewAccount($id) {
        $this->db->set('viewedtimes', 'viewedtimes+1', FALSE);
        $this->db->where('id', $id);
        $this->db->update('videos');
    }

    //ajax model
    function fetch_videos ($keyword) {
        if ($keyword == "") {
            return null;
        } else {
            $this->db->select("*");
            $this->db->from("videos");
            $this->db->like('videotitle', $keyword);
            //$this->db->or_like('videocategory', $keyword);
            $this->db->order_by('viewedtimes', 'DESC');
            return $this->db->get();
        }
    }

    //video comments model
    function comment($username, $videoID, $text, $isAnonymous) {
        $this->load->helper('date');

        //Inspired by https://github.com/RIT-Tool-Time/Soundfall/blob/master/user_guide_src/source/helpers/date_helper.rst
		$time = now('Australia/Victoria');
        $now = unix_to_human($time);
        
        $data = array(
            'user' => $username,
            'videoID' => $videoID,
            'text' => $text,
            'time' => $now,
            'isanonymous' => $isAnonymous
        );

        $this->db->insert('videocomments', $data);
    }

    function getComments($videoID) {
        $query = $this->db->get_where('videocomments', array('videoID' => $videoID));

        return $query->result_array();
    }

    function getUserComments($username) {
        $query = $this->db->get_where('videocomments', array('user' => $username));

        return $query->result_array();
    }

    function getCommentPercentage($username, $commentCounter) {
        $allUserNumber = $this->db->count_all('users');

        $userMoreThanCommentCounter = $this->db->query('SELECT COUNT(*) FROM `videocomments` GROUP BY user HAVING COUNT(*) > '.$commentCounter)->num_rows();

        return round((($allUserNumber - $userMoreThanCommentCounter) / $allUserNumber) * 100, 2);
    }


    //Get video updates
    function getVideoUpdate($username) {
        $query = $this->db->get_where('follows', array('user' => $username));

        $results = $query->result_array();

        $following_videos = array();

        foreach ($results as $result) {
            $query = $this->db->get_where('videos', array('username' => $result['following']));
            $query_results = $query->result_array();
            foreach ($query_results as $query_result){
                array_push($following_videos, $query_result);
            }
        }


        //Inspired by https://gist.github.com/sohelrana820/861c36a511282335ce85
        usort($following_videos, function($firstItem, $secondItem) {
            $timeStamp1 = strtotime($firstItem['time']);
            $timeStamp2 = strtotime($secondItem['time']);
            return $timeStamp2 - $timeStamp1;
        });

        //var_dump($following_videos);

        return $following_videos;

    }

    //favourite videos
    public function addToFavourites($username, $videoID) {
        $data = array(
            'username' => $username,
            'videoID' => $videoID,
        );

        $this->db->insert('favourites', $data);
    }

    public function getFavourites($username) {
        $query = $this->db->get_where('favourites', array('username' => $username));

        return $query->result_array();
    }

    public function isFavourites($username, $videoID) {
        $query = $this->db->get_where('favourites', array('username' => $username, 'videoID' => $videoID));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFromFavourites($username, $videoID) {
        $this->db->delete('favourites', array('username' => $username, 'videoID' => $videoID));
    }

    public function getFavouritePercentage($username, $favouriteCounter) {
        $allUserNumber = $this->db->count_all('users');

        $userMoreThanFavouriteCounter = $this->db->query('SELECT COUNT(*) FROM `favourites` GROUP BY username HAVING COUNT(*) > '.$favouriteCounter)->num_rows();

        return round((($allUserNumber - $userMoreThanFavouriteCounter) / $allUserNumber) * 100, 2);
    }

    //video like model
    public function like($username, $videoID) {
        $data = array(
            'username' => $username,
            'videoID' => $videoID
        );

        $this->db->insert('videolikes', $data);
    }

    public function isLike($username, $videoID) {
        $query = $this->db->get_where('videolikes', array('username' => $username, 'videoID' => $videoID));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function dislike($username, $videoID) {
        $this->db->delete('videolikes', array('username' => $username, 'videoID' => $videoID));
    }

    public function getlikes($videoID) {
        $query = $this->db->get_where('videolikes', array('videoID' => $videoID));

        return $query->result_array();
    }

    public function getUserLikes($username) {
        $query = $this->db->get_where('videolikes', array('username' => $username));

        return $query->result_array();
    }

    public function getLikePercentage($username, $likeCounter) {
        $allUserNumber = $this->db->count_all('users');

        $userMoreThanLikeCounter = $this->db->query('SELECT COUNT(*) FROM `videolikes` GROUP BY username HAVING COUNT(*) > '.$likeCounter)->num_rows();

        return round((($allUserNumber - $userMoreThanLikeCounter) / $allUserNumber) * 100, 2);
    }

    //Watch later model
    public function addToWatchLater($username, $videoID) {
        $this->load->helper('date');

        //Inspired by https://github.com/RIT-Tool-Time/Soundfall/blob/master/user_guide_src/source/helpers/date_helper.rst
		$time = now('Australia/Victoria');
        $now = unix_to_human($time);

        $data = array(
            'username' => $username,
            'videoID' => $videoID,
            'time' => $now
        );

        $this->db->insert('watchlater', $data);
    }

    public function removeWatchLater($username, $videoID) {
        $this->db->delete('watchlater', array('username' => $username, 'videoID' => $videoID));
    }

    public function getWatchLater($username) {
        $query = $this->db->get_where('watchlater', array('username' => $username));

        return $query->result_array();
    }

    public function isWatchLater($username, $videoID) {
        $query = $this->db->get_where('watchlater', array('username' => $username, 'videoID' => $videoID));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}