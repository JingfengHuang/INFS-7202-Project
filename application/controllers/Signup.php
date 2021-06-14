<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Signup extends CI_Controller {

	public function __construct() {
        parent:: __construct();
		$this->load->library('session');
		$this->load->library('form_validation');
    }

	public function index() {
		$data['title'] = "V-Sign up";
		$data['trending_active'] = "";
		$data['subscription_active'] = "";
		$data['favourites_active'] = "";
		$data['watchlater_active'] = "";
		$data['form_status'] = "";
		$this->load->helper('form');
		$this->load->helper('url');

		if ($this->session->has_userdata('verification_email_address')) {
			$data['verification_email_address'] = $this->session->userdata('verification_email_address');
		} else {
			$data['verification_email_address'] = "";
		}

		if ($this->session->has_userdata('verify_message')) {
			$data['verify_message'] = $this->session->userdata('verify_message');
		} else {
			$data['verify_message'] = "";
		}

		if ($this->session->has_userdata('verify_status')) {
			if ($this->session->userdata('verify_status')) {
				$data['form_status'] = "disabled";
			}
		}

		$data['watchLaterNumber'] = "";


		$this->load->view('template/header', $data);

		$this->load->view('signup', $data);

		$this->load->view('template/footer');

		$this->session->unset_userdata('verify_message');

	}

	public function check_signup() {
		$this->load->model('User_model');
		$this->load->model('Videos_model');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('encryption');

		$username = $this->input->post('username');
		$nickname = $this->input->post('nickname');
		if ($this->session->has_userdata('verification_email_address')) {
			$email = $this->session->userdata('verification_email_address');
		} else {
			$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Email has not be verified. Please enter your email address and get verification code!!</div> ");
			$this->session->unset_userdata('signup_verficationCode');
			$this->session->unset_userdata('verify_status');
			redirect('Signup');
		}

		$password = $this->input->post('password');
		$confirm_password = $this->input->post('confirm_password');


		$data['title'] = "V-Sign up";
		$data['trending_active'] = "";
		$data['subscription_active'] = "";
		$data['favourites_active'] = "";
		$data['watchlater_active'] = "";
		$data['watchLaterNumber'] = "";
		$this->load->view("template/header", $data);


		if ($this->session->has_userdata('verify_status')) {
			if ($this->session->userdata('verify_status')) {
				if ($password === $confirm_password) {
					if (strpos($username, " ") == false){
						if (strpos($nickname, " ") == false){
							$this->form_validation->set_rules('password', 'Password', 'callback_password_strength_check');
							if ($this->form_validation->run() == FALSE) {
								$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Password must have at least 8 length, and must contain at least 1 uppercase, 1 lowercase, and 1 number.</div> ");
								redirect('Signup');
							} else {
								$password = $this->encryption->encrypt($password);
								if ($this->User_model->check_signup($username, $password, $email)) {
									$this->user_model->signup($username, $password, $email);
									$this->session->unset_userdata('verify_status');
									$this->session->unset_userdata('signup_verficationCode');
									$this->session->unset_userdata('verify_message');
									$this->session->unset_userdata('verification_email_address');
									$this->load->view('signup_success');
									$this->send_email($email);
									$this->User_model->setVerified($username);
								} else {
									$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">This username or email has been registered!!</div> ");
									redirect('Signup');
								}
							}
						} else {
							$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">No space allowed in nickname!!</div> ");
							redirect('Signup');
						}
					} else {
						$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">No space allowed in username!!</div> ");
						redirect('Signup');
					}
				} else {
					$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Please double check your password!!</div> ");
					redirect('Signup');
				}
			} else {
				$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Incorrect verification code!! Please verify your email address first!!.</div> ");
				$this->session->unset_userdata('verify_status');
				$this->session->unset_userdata('verification_email_address');
				redirect('Signup');
			}
		} else {
			$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Please verify the email address first.</div> ");
			redirect('Signup');
		}

	}

	public function send_email($email){

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'mailhub.eait.uq.edu.au',
            'smtp_port' => 25,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE ,
            'starttls'  => true,
            'newline'   => "\r\n"
        );
        
        $this->email->initialize($config);
        $this->email->from('s4569179@student.uq.edu.au', 'Jingfeng Huang');
        $this->email->to($email);
        $this->email->cc($email);
        $this->email->subject('NoReply -- You have successfully registered account');

        $message = "Thank you for registering account on V! Click the following link and login to start exploring! https://infs3202-cb8e706b.uqcloud.net/demo/";

        $this->email->message($message);
		$this->email->send();

	}

	public function send_verification(){
		$this->load->model('User_model');
		$email = $this->input->post('verication_email');

		$results = $this->User_model->getProfileByEmail($email);
		if ($results->num_rows() == 0) {
			$verificationCode = $this->User_model->generateToken();
			$this->session->set_userdata('signup_verficationCode', $verificationCode);
			$this->session->set_userdata('verification_email_address', $email);
	
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'mailhub.eait.uq.edu.au',
				'smtp_port' => 25,
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE ,
				'starttls'  => true,
				'newline'   => "\r\n"
			);
			
			$this->email->initialize($config);
			$this->email->from('s4569179@student.uq.edu.au', 'Jingfeng Huang');
			$this->email->to($email);
			$this->email->cc($email);
			$this->email->subject('NoReply -- V-Verification code');
	
			$message = "You are creating account on V. Your verification code is: ".$verificationCode.". Please input the verification code into the registration form, otherwise your account won't be created. If you do not send request, please login and change password as soon as possible.";
	
			$this->email->message($message);
			$this->email->send();
	
			$this->session->set_userdata('verify_message', "<div class=\"alert alert-info\" role=\"alert\">Verification code has been sent to your email box.</div> ");
		} else {
			$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">This email has been registered!!</div> ");
		}

		redirect('Signup');
	}

	public function canVerify() {
		$this->load->model('User_model');
		$token = $this->input->post('token');

		if ($this->session->has_userdata('signup_verficationCode')) {
			if ($token == $this->session->userdata('signup_verficationCode')) {
				$this->session->set_userdata('verify_status', true);
				$this->session->set_userdata('verify_message', "<div class=\"alert alert-success\" role=\"alert\">You can now directly enter your username, nickname, and password.</div> ");

			} else {
				$this->session->set_userdata('verify_status', false);
				$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">Incorrect verification code!!</div> ");
				$this->session->unset_userdata('signup_verficationCode');
				$this->session->unset_userdata('verification_email_address');
			}
		} else {
			$this->session->set_userdata('verify_message', "<div class=\"alert alert-danger\" role=\"alert\">You have to get verification code before verify it!!</div> ");
			$this->session->unset_userdata('verify_status');
		}

		redirect('Signup');
	}


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
?>