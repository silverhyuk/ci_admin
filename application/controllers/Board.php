<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class Board extends MY_Controller {
    function __construct() {
        parent::__construct();
        parent::_require_login();
        $this -> load -> database();
        $this -> load -> model('board_m');
        $this -> load -> helper(array('url','date'));
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     * http://127.0.0.1/bbs/board/lists/ci_board/page/5
     * http://127.0.0.1/bbs/board/lists?table=ci_board
     */
    public function index() {

        $this -> lists();
    }
    /**
     * 목록 불러오기
     */
    public function lists()
    {

        // 검색어 초기화
        $search_word = $this->input->get('search_word');
        $table = $this->input->get('table');

        /*페이지 네이션 설정*/
        $this->load->library('pagination');
        // 페이징 주소
        $config['base_url'] = site_url('/board/lists').'?table='.$table ;
        // 게시물 전체 개수
        $config['total_rows'] = $this->board_m->get_list($table, 'count', '', '', $search_word);
        // 한 페이지에 표시할 게시물 수
        $config['per_page'] = 10;
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
        if($this->input->get('per_page') ===false){
            $per_page = 1;
        }
        if ($per_page > 1) {
            $start = (($per_page / $config['per_page'])) * $config['per_page'];
        } else {
            $start = 0;
        }
        $limit = $config['per_page'];
        $data['list'] = $this->board_m->get_list($table, '', $start, $limit, $search_word);
        $data['table'] = $table;
        $data['per_page'] = $per_page;

        $this->load->template('board/list_v', $data);
    }

    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기

        $table = $this->input->get('table');
        $board_id = $this->input->get('board_id');
        $per_page = $this->input->get('per_page');
        if($per_page === false){
            $per_page = 0;
        }
        $data['views'] = $this -> board_m -> get_view($table, $board_id);
        $data['table'] = $table;
        $data['board_id'] = $board_id;
        $data['per_page'] = $per_page;

        // view 호출
        $this -> load -> template('board/view_v', $data);
    }

    /**
     * 게시물 수정
     */
    function modify() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $this->input->post() ) {
            $this -> load -> helper('alert');
            $table = $this->input->post('table');
            $board_id = $this->input->post('board_id');
            //$per_page = $this->input->get('per_page');

            if ( $this->input->post('subject', TRUE)===FALSE AND $this->input->post('contents', TRUE)===FALSE) {
                alert('비정상적인 접근입니다.', site_url('/board/lists').'?table='.$table);

                exit;
            }
            $modify_data = array(
                'table' => $table,
                'board_id' => $board_id,
                'subject' => $this->input->post('subject', TRUE),
                'contents' => $this->input->post('contents', TRUE)
            );
            $result = $this->board_m->modify_board($modify_data);
            if ( $result ) {
                alert('수정되었습니다.', site_url('/board/lists').'?table='.$table);
                exit;
            } else {
                alert('다시 수정해 주세요.', site_url('/board/lists').'?table='.$table.'&board_id='.$board_id);
                exit;
            }
        } else {
            $table = $this->input->get('table');
            $board_id = $this->input->get('board_id');
            $data['views'] = $this->board_m->get_view($table, $board_id);
            $data['table'] = $table;
            $data['board_id'] = $board_id;
            $this->load->template('board/modify_v', $data);
        }
    }
    /**
     * 게시물 수정
     */
    function write() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $this->input->post() && $this->session->userdata('is_login') === TRUE ) {
            //폼 검증 라이브러리 로드
            $this->load->library('form_validation');
            //폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('subject', '제목', 'required');
            $this->form_validation->set_rules('contents', '내용', 'required');

            if ( $this->form_validation->run() == TRUE ) {

                $this->load->helper('alert');
                $table = $this->input->post('table');

                if ($this->input->post('subject', TRUE) === FALSE AND $this->input->post('contents', TRUE) === FALSE) {
                    alert('비정상적인 접근입니다.', site_url('/board/lists') . '?table=' . $table);
                    exit;
                }
                $write_data = array(
                    'table' => $table,
                    'subject' => $this->input->post('subject', TRUE),
                    'contents' => $this->input->post('contents', TRUE),
                    'user_id' => $this->session->userdata('nick_name'),
                    'user_name' => $this->session->userdata('user_name')
                );
                $result = $this->board_m->insert_board($write_data);
                if ($result) {
                    alert('게시물이 작성 되었습니다.', site_url('/board/lists') . '?table=' . $table);
                    exit;
                } else {
                    alert('다시 작성해 주세요.', site_url('/board/lists') . '?table=' . $table);
                    exit;
                }
            }else{
                //쓰기폼 view 호출
                $table = $this->input->get('table');
                $data['table'] = $table;
                $this->load->template('board/write_v');
            }
        } else {
            $table = $this->input->get('table');
            $data['table'] = $table;
            $this->load->template('board/write_v', $data);
        }
    }
}