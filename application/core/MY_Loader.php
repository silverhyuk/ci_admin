<?php
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:40
 */

class MY_Loader extends CI_Loader{
    /**
     * @param $content_name
     * @param array $vars
     * @param bool $return
     */
    public function template($content_name, $vars=array(), $return=FALSE){
        $layout_header = isset($vars['layout_header']) ? $vars['layout_header'] : 'layout/layout_header';
        $layout_side_menu = isset($vars['layout_side_menu']) ? $vars['layout_side_menu'] : 'layout/layout_side_menu';
        $layout_footer = isset($vars['layout_footer']) ? $vars['layout_footer'] : 'layout/layout_footer';


        $param['layout_header'] = $layout_header;
        $param['layout_side_menu'] = $layout_side_menu;
        $param['layout_footer'] = $layout_footer;
        $param['content_name'] = $content_name;
        $param['vars'] = $vars;

        $this->view('layout/layout_body', $param);
    }
}