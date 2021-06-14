<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class login extends CI_Controller {

	public function __construct() {
		parent:: __construct();
        $this->load->library('session'); //enable session
    }

	public function index()
	{
		$this->load->library('encryption');
		
		$data['title'] = "V-Login";
		$data['error']= "";
		$data['trending_active'] = "";
		$data['subscription_active'] = "";
		$data['favourites_active'] = "";
		$data['watchlater_active'] = "";

		$data['username'] = "";
		$data['password'] = "";
		$this->load->helper('form');
		$this->load->helper('url');

		$this->load->model('Videos_model');
		$this->load->model('User_model');

		if (!$this->session->userdata('logged_in')){
			if (get_cookie("remember")) {
				$username = get_cookie('username');
				$password = get_cookie('password');
				$data['username'] = $username;
				$data['password'] = $this->encryption->decrypt($password);
				$data['isChecked'] = 'checked';
				$data['watchLaterNumber'] = "";

				$this->load->view('template/header', $data);
				$this->load->view('login', $data);
				
			} else {
				$data['isChecked'] = '';
				$data['watchLaterNumber'] = "";
				$this->load->view('template/header', $data);
				$this->load->view('login', $data);
			}
		} else {
			redirect('Trending');
		}

		$this->load->view('template/footer');
	}


	public function check_login()
	{
		$this->load->model('user_model');
		$this->load->model('Videos_model');
		$this->load->library('encryption');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha','callback_recaptcha');

		$data['trending_active'] = "";
		$data['subscription_active'] = "";
		$data['favourites_active'] = "";
		$data['watchlater_active'] = "";
		$data['error']= "<div class=\"alert alert-danger\" role=\"alert\"> Incorrect username or password!! </div> ";
		$this->load->helper('form');
		$this->load->helper('url');

		$username = $this->input->post('username'); //getting username from login form
		$password = $this->input->post('password'); //getting password from login form
		$password = $this->encryption->encrypt($password);
		$remember = $this->input->post('remember'); //getting remember check box from login form

		if(!$this->session->userdata('logged_in')){	//Check if user already login
			if ($this->form_validation->run() === true) {
				if ( $this->user_model->login($username, $password) )//check username and password
				{
					//Avatar header button
					$this->load->model('User_model');
					$avatar_results = $this->User_model->getAvatar($username);
					if (empty($avatar_results)) {
						$avatarHEADER = '<img class="nav-item rounded-circle img-fluid img-thumbnail border-light shadow" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_icons8-male-user-100.png>';
					} else {
						foreach ($avatar_results as $row) {
							$avatarHEADER = '<img class="nav-item rounded-circle img-fluid img-thumbnail border-light shadow" src=https://infs3202-cb8e706b.uqcloud.net/demo/uploads/avatars/crop_'.$row['filename'].'>';
						}
					}
			
					$user_data = array(
						'username' => $username,
						'password' => $password,
						'logged_in' => true,
						'avatarHEADER' => $avatarHEADER
					);

					if ($remember) {
						echo $remember.'cookies';
						set_cookie('username', $username, '3600');
						set_cookie('password', $password, '3600');
						set_cookie('remember', $remember, '3600');
					} else {
						delete_cookie('username');
						delete_cookie('password');
						delete_cookie('remember');
					}

					$this->session->set_userdata($user_data); //set user status to login in session
					redirect('login');
				} else {
					$data['username'] = "";
					$data['password'] = "";
					$data['title'] = "V-Login";
					delete_cookie('username');
					delete_cookie('password');
					delete_cookie('remember');
					$data['isChecked'] = '';
					$data['watchLaterNumber'] = "";
					$this->load->view('template/header', $data);
					$this->load->view('login', $data);	//if username password incorrect, show error msg and ask user to login
				}
			} else {
				$data['username'] = "";
				$data['password'] = "";
				$data['title'] = "V-Login";
				$data['error']= "<div class=\"alert alert-danger\" role=\"alert\">Please verify you are not rebot!!</div> ";
				delete_cookie('username');
				delete_cookie('password');
				delete_cookie('remember');
				$data['isChecked'] = '';
				$data['watchLaterNumber'] = "";
				$this->load->view('template/header', $data);
				$this->load->view('login', $data);
			}
			
		} else {
			{
				redirect('login');
			}
		$this->load->view('template/footer');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('avatarHEADER'); //delete login status
		$this->session->sess_destroy();
		redirect('login'); // redirect user back to login
	}

	//Inspired by https://www.itsolutionstuff.com/post/php-codeigniter-3-google-recaptcha-form-validation-exampleexample.html
	public function recaptcha($str = '') {
		$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
		$secret = '6Lf2h90aAAAAAM51uZZE6Ch4wfDVzYmmB-9ic3el';
		$userIp=$this->input->ip_address();
   
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;
 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
		curl_close($ch);
		$status= json_decode($output, true);  


		if ($status['success']) {
			return true;
		} else {
			return false;
		}
	}
}
?>
