<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Findpassword extends CI_Controller {

	public function __construct() {
        parent:: __construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Videos_model');
    }

    public function index() {
        if ($this->session->has_userdata('find_password_error')) {
            $data['error']= $this->session->userdata('find_password_error');
        } else {
            $data['error'] = "";
        }

        if ($this->session->has_userdata('reset_password_username')) {
            $data['username_value'] = $this->session->userdata('reset_password_username');
            $data['form_status'] = 'disabled';
        } else {
            $data['username_value'] = null;
            $data['form_status'] = '';
        }

        if ($this->session->has_userdata('reset_password_email')) {
            $data['email_value'] = $this->session->userdata('reset_password_email');
            $data['form_status'] = 'disabled';
        } else {
            $data['email_value'] = null;
            $data['form_status'] = '';
        }

        $data['title'] = "V-Reset password";
		$data['trending_active'] = "";
		$data['subscription_active'] = "";
        $data['favourites_active'] = "";
        $data['watchlater_active'] = "";

        $data['watchLaterNumber'] = "";
        
        $this->load->view('template/header', $data);
        $this->load->view('findpassword', $data);
        $this->load->view('template/footer', $data);

        $this->session->unset_userdata('find_password_error');
    }

    public function check_and_send_email() {
        $username = $this->input->post('username');
        $email = $this->input->post('email');

        if ($this->User_model->canResetPassword($username, $email)) {
            $token = $this->User_model->generateToken();
            $this->session->set_userdata('verificationCode', $token);
            $this->send_verification($username, $email, $token);
            $this->session->set_userdata('reset_password_username', $username);
            $this->session->set_userdata('reset_password_email', $email);
            $this->session->set_userdata("find_password_error", "<div class=\"alert alert-success\" role=\"alert\"> Verification code has been sent to your email box. Please check it as soon as possible.</div> ");
        } else {
            $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">Username not found or incorrect email address</div> ");
        }

        redirect('Findpassword');
    }

    public function verify($token = null) {
        if ($token != null) {
            if ($this->session->has_userdata('reset_password_username')) {
                if ($this->session->has_userdata('reset_password_email')) {
                    if ($this->session->has_userdata('verificationCode')) {
                        $password = $this->input->post('password');
                        $confirm_password = $this->input->post('confirm_password');
                        $verificationCode = $this->input->post('verificationCode');
                
                        if ($verificationCode == $token) {
                            $this->form_validation->set_rules('password', 'Password', 'callback_password_strength_check');
                            if ($this->form_validation->run() == FALSE) {
                                $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">Password must have at least 8 length, and must contain at least 1 uppercase, 1 lowercase, and 1 number!!</div> ");
                            } else {
                                if ($password != $confirm_password) {
                                    $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">Please make sure your new password is correct.</div> ");
                                } else {
                                    $this->User_model->resetPassword($this->session->userdata('reset_password_username'));
                                    $this->session->unset_userdata('reset_password_username');
                                    $this->session->unset_userdata('reset_password_email');  
                                    $this->session->unset_userdata('verificationCode');
                                    $this->session->set_userdata("find_password_error", "<div class=\"alert alert-success\" role=\"alert\">You can now login with your new password</div> ");          
                                }
                            }          
                        } else {
                            $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">Incorrect verification code, please fill the username and email address and request verification code again!!</div> ");
                            $this->session->unset_userdata('reset_password_username');
                            $this->session->unset_userdata('reset_password_email');
                            $this->session->unset_userdata('verificationCode');
                        }
                    } else {
                        $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">You have to request verification code before resetting password!!</div> ");
                    }
                } else {
                    $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">You have to request verification code before resetting password!!</div> ");
                }
            } else {
                $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">You have to request verification code before resetting password!!</div> ");
            }
        } else {
            $this->session->set_userdata("find_password_error", "<div class=\"alert alert-danger\" role=\"alert\">You have to request verification code before resetting password!!</div> ");
        }


        redirect('Findpassword');
    }

    public function send_verification($username, $email, $verificationCode){

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

        $message = "You account requested reset password on V. Your verification code is: ".$verificationCode.". If you do not send request, please login and change password as soon as possible.";

        $this->email->message($message);
		$this->email->send();

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