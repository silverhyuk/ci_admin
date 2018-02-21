<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller {
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
            $sessionUserArray = array(
                'is_login'=> true,
                'user_id'=> $user->user_id,
                'user_name'=> $user->user_name,
                'nick_name'=> $user->nick_name,
                'role_id'=> $user->role_id,
                'role_type'=> $user->role_type,
                'role_name'=> $user->role_name,
                'email' => $user->email
            );
    		$this->session->set_userdata($sessionUserArray);
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