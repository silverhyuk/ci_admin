<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */

?>
<script>
    $(document).ready(function () {
        // Menu
        $("#side-menu > .sidebar-menu > li").removeClass('active');
        $("#side-menu > .sidebar-menu > li").removeClass('menu-open');
        $("#side-menu > .treeview-menu > li").removeClass('active');
        $("#menu_02").addClass('active');
        $("#menu_02").addClass('menu-open');
        $("#menu_02_01").addClass('active');

        $("#search_btn").click(function() {
            if ($("#q").val() == '') {
                //alert("검색어를 입력하세요!");
                //return false;
                var url ="<?=site_url('/user/lists').'?page=1' ?>" ;
                location.href=url;
            } else {
                var url ="<?=site_url('/user/lists').'?search_word=' ?>" + $("#q").val() + "&page=1";
                //$("#search_word").val($("#q").val());
                //$("#bd_search").attr('action', url).submit();
                location.href=url;
            }
        });
    });

    function user_search_enter(form) {
        var keycode = window.event.keyCode;
        if (keycode == 13){
            $("#search_btn").click();
        }

    }
</script>

<form id="bd_search" method="post">
    <input type="hidden" name="search_word"  id="search_word" >
</form>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        사용자 관리
        <small>user</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">user</a></li>
        <li class="active">list</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <!-- /.row -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <!--<h3 class="box-title">Table</h3>-->
                    <div class="pull-left">
                        <a href="<?=site_url('/user/write')?>" class="btn btn-primary"><i class="fa fa-save"></i>  등록</a>
                    </div>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" id="q" onkeypress="user_search_enter(document.q);">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default" id="search_btn" ><i class="fa fa-search"></i></button>
                                </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">아이디</th>
                            <th scope="col">이름</th>
                            <th scope="col">권한</th>
                            <th scope="col">작성일</th>
                            <th scope="col">-</th>
                        </tr>
                        <?php
                        foreach ($list as $lt) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $last_num--; ?></th>
                                <td><?php echo $lt->nick_name; ?></td>
                                <td><?php echo $lt->user_name; ?></td>
                                <td><?php echo $lt->role_name; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>">
                                        <?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>
                                    </time>
                                </td>
                                <td><button  class="btn btn-default"><i class="fa fa-edit"></i></button></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="text-center">
                         <?php echo $pagination;?>
                    </div>

                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<!-- /.content -->