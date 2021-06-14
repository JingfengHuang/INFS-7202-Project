<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Inspired by CodeIgniter Documentation https://codeigniter.com/userguide3/index.html
//Inspired by Bootstrap Docs https://getbootstrap.com/docs/5.0/getting-started/introduction/
//Inspired by PHP Manual https://www.php.net/manual/en/index.php
class Email extends CI_Controller {

    public function send_email($email){
        $this->load->helper('string');
        $this->load->helper('url');

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
        $this->email->subject('NoReply -- Please confirm your account registration');

        $message = "Click the following link to confirm your account registration on V: ";

        $this->email->message($message);
        $this->email->send();

    }

}