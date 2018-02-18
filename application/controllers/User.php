<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:44
 */

class User extends MY_Controller {
    function __construct() {
        parent::__construct();
        parent::_require_admin();
        $this -> load -> database();
        $this -> load -> model('user_m');
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
        $config['base_url'] = site_url('/user/lists');
        // 게시물 전체 개수
        $totalCount = $this->user_m->getUserTotalCount();
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
        $data['list'] = $this->user_m->getUserList( $start, $limit);
        $data['per_page'] = $per_page;
        $data['last_num'] = $totalCount - $per_page;

        $this->load->template('user/list_v', $data);
    }

    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기

        $table = $this->input->get('table');
        $user_id = $this->input->get('user_id');
        $per_page = $this->input->get('per_page');
        if($per_page === false){
            $per_page = 0;
        }
        $data['views'] = $this -> user_m -> get_view($table, $user_id);
        $data['table'] = $table;
        $data['user_id'] = $user_id;
        $data['per_page'] = $per_page;

        // view 호출
        $this -> load -> template('user/view_v', $data);
    }

    /**
     * 게시물 수정
     */
    function modify() {
        //echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $this->input->post() ) {
            $this -> load -> helper('alert');
            $table = $this->input->post('table');
            $user_id = $this->input->post('user_id');
            //$per_page = $this->input->get('per_page');

            if ( $this->input->post('subject', TRUE)===FALSE AND $this->input->post('contents', TRUE)===FALSE) {
                alert('비정상적인 접근입니다.', site_url('/user/lists').'?table='.$table);

                exit;
            }
            $modify_data = array(
                'table' => $table,
                'user_id' => $user_id,
                'subject' => $this->input->post('subject', TRUE),
                'contents' => $this->input->post('contents', TRUE)
            );
            $result = $this->user_m->modify_user($modify_data);
            if ( $result ) {
                alert('수정되었습니다.', site_url('/user/lists').'?table='.$table);
                exit;
            } else {
                alert('다시 수정해 주세요.', site_url('/user/lists').'?table='.$table.'&user_id='.$user_id);
                exit;
            }
        } else {
            $table = $this->input->get('table');
            $user_id = $this->input->get('user_id');
            $data['views'] = $this->user_m->get_view($table, $user_id);
            $data['table'] = $table;
            $data['user_id'] = $user_id;
            $this->load->template('user/modify_v', $data);
        }
    }
    /**
     * 게시물 수정
     */
    function write() {
        //echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $this->input->post() && $this->session->userdata('is_login') === TRUE ) {
            //폼 검증 라이브러리 로드
            $this->load->library('form_validation');
            //폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('email', '이메일 주소', 'required|valid_email|is_unique[ci_user.email]');
            $this->form_validation->set_rules('user_name', '이름', 'required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('nick_name', '닉네임', 'required|min_length[3]|max_length[20]|is_unique[ci_user.nick_name]');
            $this->form_validation->set_rules('password', '비밀번호', 'required|min_length[6]|max_length[30]|matches[re_password]');
            $this->form_validation->set_rules('re_password', '비밀번호 확인', 'required');

            if($this->form_validation->run() === false){
                $this->session->set_flashdata('message', '등록에 실패했습니다.');
                echo json_encode (array('result'=>'F')) ;
            } else {
                if(!function_exists('password_hash')){
                    $this->load->helper('password');
                }
                $hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

                $this->load->model('user_m');
                $this->user_m->add(array(
                    'user_name'=>$this->input->post('user_name'),
                    'email'=>$this->input->post('email'),
                    'password'=>$hash,
                    'nick_name'=>$this->input->post('nick_name'),
                    'role_id'=>$this->input->post('role_id'),
                ));

                $this->session->set_flashdata('message', '등록에 성공했습니다.');
                echo json_encode (array('result'=>'S')) ;
            }
        } else {
            echo json_encode (array('result'=>'F')) ;
        }
    }
    /**
     * 게시물 삭제
     */
    function delete()
    {
        //경고창 헬퍼 로딩
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if( @$this->session->userdata('is_login') == TRUE ){
            //삭제하려는 글의 작성자가 본인인지 검증
            $table = $this->input->get('table');
            $user_id = $this->input->get('user_id');

            $writer_id = $this->user_m->writer_check($table, $user_id);

            if( $writer_id->user_id != $this->session->userdata('nick_name') ){
                alert('본인이 작성한 글이 아닙니다.', site_url('/user/view') . '?table=' . $table . '&user_id='.$user_id);
                exit;
            }
            //게시물 번호에 해당하는 게시물 삭제
            $return = $this->user_m->delete_content($table, $user_id);

            //게시물 목록으로 돌아가기
            if ( $return ){
                //삭제가 성공한 경우
                alert('삭제되었습니다.', site_url('/user/lists') . '?table=' . $table);
            }else{
                //삭제가 실패한 경
                alert('삭제 실패하였습니다.', site_url('/user/view') . '?table=' . $table . '&user_id='.$user_id);
            }
        }else{
            alert('로그인후 삭제하세요', site_url('/auth/login'));
            exit;
        }
    }
}