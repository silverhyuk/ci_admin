<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_controller {
    function __construct()
    {       
        parent::__construct();
    }
    function login(){
        $this->load->view('login_v', array('returnURL'=>$this->input->get('returnURL')));
    }

    function logout(){
    	$this->session->sess_destroy();
    	$this->load->helper('url');
    	redirect('/');
    }


    function authentication(){
    	$this->load->model('user_m');
        $user = $this->user_m->getByEmail(array('email'=>$this->input->post('email')));
        if(!function_exists('password_hash')){
            $this->load->helper('password');
        }
    	if(
    		$this->input->post('email') == $user->email && 
            password_verify($this->input->post('password'), $user->password)
    	) {
    		$this->session->set_userdata('is_login', true);
            $this->session->set_flashdata('message', '로그인에 성공 했습니다.');

    		$this->load->helper('url');
            $returnURL = $this->input->get('returnURL');
            if($returnURL === false){
                $returnURL = '/';
            }
            redirect($returnURL);
    	} else {
    		$this->session->set_flashdata('message', '로그인에 실패 했습니다.');
    		$this->load->helper('url');
    		redirect('/auth/login');
    	}
    }
}