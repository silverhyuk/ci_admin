<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_controller
{
    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
    }
    function index(){
        $this->load->view('test/csrf');
    }
    public function test()
    { /* post 로 전송된 text 필드의 값을 출력 */
        echo "테스트값 : " . $this->input->post('text', true); /* 토큰값 갱신 설정($config['csrf_regenerate'])이 TRUE 일경우, 요청시마다, 토큰값을 | 갱신을 해주어야 하기때문에 아래와 같이 레이아웃을 이용하여 데이터에 토큰값을 생성해준다. */
        echo "<span id='token' data-value=" . $this->security->get_csrf_hash() . "></span>";
    }
}
