<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email_model extends CI_Model
{

    function send($subject, $to_email, $message)
    {

        $data_email = $this->db->get('email_sender')->result();
        $smtp_host = '';
        $smtp_port = '';
        $smtp_user = '';
        $smtp_pass = '';
        foreach ($data_email as $de) {
            $smtp_host = $de->smtp_host;
            $smtp_port = $de->smtp_port;
            $smtp_user = $de->email;
            $smtp_pass = $de->password;
        }

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $smtp_host;
        $config['smtp_port'] = $smtp_port;
        $config['smtp_user'] = $smtp_user;
        $config['smtp_pass'] = $smtp_pass;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($smtp_user);
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        //Send mail 
        $this->email->send();
        return $this->email->print_debugger();
    }
}

/* End of file Email_model.php */
