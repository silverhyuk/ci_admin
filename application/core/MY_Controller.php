<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-24
 * Time: 오후 10:46
 */

class MY_Controller extends CI_Controller {
    function __construct(){
        parent::__construct();
        if($peak = $this->config->item('peak_page_cache')){
            if($peak == current_url()){
                $this->output->cache(5);
            }
        }
        $this->load->database();
        if(!$this->input->is_cli_request()){
            $this->load->library('session');
        }
        $this->load->driver('cache', array('adapter' => 'file'));
        $this->output->enable_profiler(FALSE);
    }
    function _require_login($return_url=null){
        // 로그인이 되어 있지 않다면 로그인 페이지로 리다이렉션
        if(!$this->session->userdata('is_login')){
            $this->load->helper('url');
            redirect('/auth/login?returnURL='.rawurlencode($return_url));
        }
    }

    function _require_admin($return_url=null){
        // 로그인이 되어 있지 않다면 로그인 페이지로 리다이렉션
        if(!$this->session->userdata('is_login')){
            $this->load->helper('url');
            redirect('/auth/login?returnURL='.rawurlencode($return_url));
        }
        $roleType = $this->session->userdata('role_type');
        if($roleType !== 'ADMIN' && $roleType !== 'SUPER_ADMIN'){
            $this->load->helper('url');
            $referred_from = $this->session->userdata('referred_from');
            redirect($referred_from, 'refresh');
        }

    }

}