<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends MY_controller {
    function __construct()
    {       
        parent::__construct();
    }
    function index(){
        $this->register_view();
    }
    function register_view(){
        $this->load->view('register_v');
    }

    function reg_process(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', '이메일 주소', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('username', '이름', 'required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('nickname', '닉네임', 'required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('password', '비밀번호', 'required|min_length[6]|max_length[30]|matches[re_password]');
        $this->form_validation->set_rules('re_password', '비밀번호 확인', 'required');

        if($this->form_validation->run() === false){
            $this->session->set_flashdata('message', '회원가입에 실패했습니다.');
            $this->load->view('register_v');
        } else {
            if(!function_exists('password_hash')){
                $this->load->helper('password');
            }
            $hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

            $this->load->model('user_m');
            $this->user_m->add(array(
                'username'=>$this->input->post('username'),
                'email'=>$this->input->post('email'),
                'password'=>$hash,
                'nickname'=>$this->input->post('nickname')
            ));

            $this->session->set_flashdata('message', '회원가입에 성공했습니다.');
            $this->load->helper('url');
            redirect('/auth/login');
        }
    }
}