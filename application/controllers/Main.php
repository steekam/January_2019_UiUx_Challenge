<?php defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller{

    function index(){
        $this->load->view('index');
    }

    function signup_process(){
        $data = array(
            'user' => array(
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'phone_number' => '+254'.$this->input->post('phone_number'),
                'password' => $this->input->post('pass'),
            ),
            'auth' => array(
                'phone_number' => '+254'.$this->input->post('phone_number'),
                'auth_token' => generate_auth_token(4)
            )
        );

        // insert records to db
        if($this->main_model->add($data)){
            //create a session with userdata
            $this->session->set_userdata($data['user']);
            
            //send message with token
            $msg = 'Your authorization token is '.$data['auth_token'];
            if(send_msg($data['phone_number'],$msg)){
                $res = array(
                    'type' => 'success',
                    'msg' => 'Authorization code sent to the phone number provided'
                );
            }
        }
        else {
            $res = array(
                'type' => 'warning',
                'msg' => 'Something wrong happened. Please try again later'
            );
        }
        echo json_encode($res);
    }

    function validate_auth_token(){
        echo json_encode($this->main_model->verify($this->session->userdata('phone_number'),$this->input->post('auth_token')));
    }
}