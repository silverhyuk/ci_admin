<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * menu: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Menu extends MY_Controller {
    function __construct() {

        parent::__construct();
        parent::_require_admin();
        $this -> load -> database();
        $this -> load -> model('menu_m');
        $this -> load -> helper(array('url','date'));
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     */
    public function index() {
        $this -> view();
    }
    /**
     * 목록 불러오기
     */


    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기

        $menu_id = $this->input->get('menu_id');
        log_message('debug', '$menu_id : '. $menu_id);
        $data = $this -> menu_m -> getView($menu_id);
        echo json_encode (array('result'=> $data)) ;
    }

    /**
     * 게시물 수정
     */
    function modify() {

        if ( $this->input->post() && $this->session->userdata('is_login') === TRUE ) {
            //폼 검증 라이브러리 로드
            $this->load->library('form_validation');
            //폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('menu_name', '메뉴이름', 'required|max_length[60]');
            $this->form_validation->set_rules('menu_price', '가격', 'required');

            if($this->form_validation->run() === false){
                $this->session->set_flashdata('message', '수정에 실패했습니다.');
                echo json_encode (array('result'=>'F')) ;
            } else {
                $modify_data = array(
                    'menu_id' => $this->input->post('menu_id', TRUE),
                    'menu_name' => $this->input->post('menu_name', TRUE),
                    'menu_price' => $this->input->post('menu_price', TRUE),
                    'reg_id' => $this->session->userdata('user_id')
                );
                $result = $this->menu_m->modifyMenu($modify_data);

                if($result){
                    $this->session->set_flashdata('message', '수정에 성공했습니다.');
                    echo json_encode (array('result'=>'S')) ;
                }else{
                    $this->session->set_flashdata('message', '수정에 실패했습니다.');
                    echo json_encode (array('result'=>'F')) ;
                }

            }
        } else {
            echo json_encode (array('result'=>'F')) ;
        }
    }
    /**
     * 게시물 수정
     */
    function write() {
        if ( $this->input->post() && $this->session->userdata('is_login') === TRUE ) {
            //폼 검증 라이브러리 로드
            $this->load->library('form_validation');
            //폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('menu_name', '메뉴이름', 'required|max_length[60]');
            $this->form_validation->set_rules('menu_price', '가격', 'required');

            if($this->form_validation->run() === false){
                $this->session->set_flashdata('message', '등록에 실패했습니다.');
                echo json_encode (array('result'=>'F')) ;
            } else {
                $this->menu_m->add(array(
                    'menu_name' => $this->input->post('menu_name', TRUE),
                    'menu_price' => $this->input->post('menu_price', TRUE),
                    'store_id' => $this->input->post('store_id', TRUE),
                    'reg_id' => $this->session->userdata('user_id')
                ));

                $this->session->set_flashdata('message', '등록에 성공했습니다.');
                echo json_encode (array('result'=>'S')) ;
            }
        } else {
            echo json_encode (array('result'=>'F')) ;
        }
    }
}