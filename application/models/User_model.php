<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
 class User_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    //Salt
	function generateSalt($max = 64) {
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
		$i = 0;
		$salt = "";
		while ($i < $max) {
			$salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
			$i++;
	    }
		return $salt;
    }
    
    //Token
	function generateToken($max = 8) {
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$i = 0;
		$token = "";
		while ($i < $max) {
			$token .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
			$i++;
	    }
		return $token;
	}

    //login/signup model
    public function login($username, $password){
        $password = $this->encryption->decrypt($password);
        $this->db->where('username', $username);
        $results = $this->db->get('users');
        $results_array = $results->result_array();
        if ($results->num_rows() == 1) {
            foreach ($results_array as $result_array) {
                if ($result_array['verified'] == 1) {
                    $password_hash = $result_array['password'];
                    return password_verify($password, $password_hash);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

    }

    public function check_signup($username, $password, $email) {
        $password = $this->encryption->decrypt($password);
        if ($username == null || $username == "" || $password == null || $password == "" || $email == null || $email == "") {
            return false;
        }

        $this->db->where('username', $username);

        $result = $this->db->get('users');

        if($result->num_rows() == 0){
            $this->db->where('email', $email);

            $result = $this->db->get('users');

            if($result->num_rows() == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function signup($username, $password, $email) {

        $password = $this->encryption->decrypt($password);
        $password = password_hash($password, PASSWORD_BCRYPT);

        $data = array(
            'username' => $this->input->post('username'),
            'password' => $password,
            'nickname' => $this->input->post('nickname'),
            'email' => $email,
            'self_intro' => "This user is too lazy to write a short self introduction.",
            'verified' => 0
        );

        $this->db->insert('users', $data);
    }

    public function setVerified($username) {
        $this->db->where('username', $username);
        $this->db->set('verified', 1);

        $this->db->update('users');
    }

    //Social media
    public function setTwitter($username, $twitter) {
        $this->db->where('username', $username);
        $this->db->set('twitterAccountName', $twitter);

        $this->db->update('users');
    }


    //profile model
    public function getProfile($username) {
        $query = $this->db->get_where('users', array('username' => $username));

        return $query->result_array();
    }

    public function getProfileByEmail($email) {
        $query = $this->db->get_where('users', array('email' => $email));

        return $query;
    }

    public function updateProfile($username) {

        if ($this->input->post('password') === $this->input->post('confirm_password')){
            $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            $this->db->where('username', $username);
            $this->db->set('nickname', $this->input->post('nickname'));
            $this->db->set('password', $password);
            $this->db->set('twitterAccountName', $this->input->post('twitter'));

            if ($this->input->post('self_intro') == "") {
                $this->db->set('self_intro', "This user is too lazy to write a short self introduction.");
            } else {
                $this->db->set('self_intro', $this->input->post('self_intro'));
            }
            $this->db->update('users');

            return true;
        } else {
            return false;
        }
    }


    //Avatar model
    public function getAvatar($username) {
        $query = $this->db->get_where('avatar', array('username' => $username));

        return $query->result_array();
    }

    public function uploadAvatar($username, $filename){

        if (empty($this->getAvatar($username))) {
            $data = array(
                'username' => $username,
                'filename' => $filename
            );

            $this->db->insert('avatar', $data);
        } else {
            $this->db->where('username', $username);
            $this->db->set('filename', $filename);

            $this->db->update('avatar');
        }

    }


    //Follow model
    public function getFollowing($username) {
        $query = $this->db->get_where('follows', array('user' => $username));

        return $query->result_array();
    }

    public function getFollowPercengate($username, $followCounter) {
        $allUserNumber = $this->db->count_all('users');

        $userMoreThanFollowCounter = $this->db->query('SELECT COUNT(*) FROM `follows` GROUP BY user HAVING COUNT(*) > '.$followCounter)->num_rows();

        return round((($allUserNumber - $userMoreThanFollowCounter) / $allUserNumber) * 100, 2);
    }

    public function follow($username, $follow) {
        $data = array(
            'user' => $username,
            'following' => $follow
        );

        $this->db->insert('follows', $data);
    }

    public function unfollow($username, $follow) {
        $this->db->delete('follows', array('user' => $username, 'following' => $follow));
    }

    public function isFollowing($username, $follow) {
        $query = $this->db->get_where('follows', array('user' => $username, 'following' => $follow));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getFollowers($username) {
        //$username = "'" + strval($username) + "'";
        //$query = $this->db->query('SELECT COUNT(`user`) FROM `follows` WHERE `following` = '.$username);

        $this->db->where('following', $username);

        return $this->db->count_all_results('follows');
    }

    public function myFollowers($username) {
        $query = $this->db->get_where('follows', array('following' => $username));

        return $query->result_array();
    }

    //reset password model
    public function canResetPassword($username, $email) {
        $query = $this->db->get_where('users', array('username' => $username, 'email' => $email));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function resetPassword($username) {
        $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $this->db->where('username', $username);
        $this->db->set('password', $password);

        $this->db->update('users');
    }

}
?>
