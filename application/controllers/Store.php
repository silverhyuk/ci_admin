<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * store: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Store extends MY_Controller {
    function __construct() {

        parent::__construct();
        parent::_require_admin();
        $this -> load -> database();
        $this -> load -> model('store_m');
        $this -> load -> helper(array('url','date'));
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     */
    public function index() {
        $this -> lists();
    }
    /**
     * 목록 불러오기
     */
    public function lists()
    {

        /*페이지 네이션 설정*/
        $this->load->library('pagination');
        // 페이징 주소
        $config['base_url'] = site_url('/store/lists');
        // 게시물 전체 개수
        $totalCount = $this->store_m->getStoreTotalCount();
        $config['total_rows'] = $totalCount;
        // 한 페이지에 표시할 게시물 수
        $config['per_page'] = 20;
        // 페이지 번호가 위치한 세그먼트
        $config['page_query_string'] = TRUE;


        //페이징 디자인 변경
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin ">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '&laquo;&laquo;';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;&raquo;';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';


        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $per_page = $this->input->get('per_page');
        if(empty($this->input->get('per_page')) === true){
            $per_page = 0;
        }
        if ($per_page > 1) {
            $start = (($per_page / $config['per_page'])) * $config['per_page'];
        } else {
            $start = 0;
        }
        $limit = $config['per_page'];
        $data['list'] = $this->store_m->getStoreList( $start, $limit);
        $data['storeTypeList'] = $this->store_m->getStoreTypeList();
         $data['statusList'] = $this->store_m->getStatusList();
        $data['per_page'] = $per_page;
        $data['last_num'] = $totalCount - $per_page;

        $this->load->template('store/list_v', $data);
    }

    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기

        $store_id = $this->input->get('store_id');
        log_message('debug', '$store_id : '. $store_id);
        $data = $this -> store_m -> getView($store_id);
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
            $this->form_validation->set_rules('store_type_id', '구분', 'required');
            $this->form_validation->set_rules('status_id', '상태', 'required');
            $this->form_validation->set_rules('store_name', '이름', 'required|max_length[60]');
            $this->form_validation->set_rules('store_tel', '전화번호', 'required|max_length[13]');

            if($this->form_validation->run() === false){
                $this->session->set_flashdata('message', '수정에 실패했습니다.');
                echo json_encode (array('result'=>'F')) ;
            } else {
                $modify_data = array(
                    'store_id' => $this->input->post('store_id', TRUE),
                    'store_type_id' => $this->input->post('store_type_id', TRUE),
                    'status_id' => $this->input->post('status_id', TRUE),
                    'store_name' => $this->input->post('store_name', TRUE),
                    'store_tel' => $this->input->post('store_tel', TRUE),
                    'reg_id' => $this->session->userdata('user_id')
                );
                $result = $this->store_m->modifyStore($modify_data);

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
            $this->form_validation->set_rules('store_type_id', '구분', 'required');
            $this->form_validation->set_rules('status_id', '상태', 'required');
            $this->form_validation->set_rules('store_name', '이름', 'required|max_length[60]');
            $this->form_validation->set_rules('store_tel', '전화번호', 'required|max_length[13]');

            if($this->form_validation->run() === false){
                $this->session->set_flashdata('message', '등록에 실패했습니다.');
                echo json_encode (array('result'=>'F')) ;
            } else {
                $this->store_m->add(array(
                    'store_type_id' => $this->input->post('store_type_id', TRUE),
                    'status_id' => $this->input->post('status_id', TRUE),
                    'store_name' => $this->input->post('store_name', TRUE),
                    'store_tel' => $this->input->post('store_tel', TRUE),
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