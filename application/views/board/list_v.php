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
        //Menu
        $("#side-menu > .sidebar-menu > li").removeClass('active');
        $("#side-menu > .sidebar-menu > li").removeClass('menu-open');
        $("#side-menu > .treeview-menu > li").removeClass('active');
        $("#menu_01").addClass('active');
        $("#menu_01").addClass('menu-open');
        $("#menu_01_02").addClass('active');

        $("#search_btn").click(function() {
            if ($("#q").val() == '') {
                //alert("검색어를 입력하세요!");
                //return false;
                var url ="<?=site_url('/board/lists').'?table=ci_board&page=1' ?>" ;
                location.href=url;
            } else {
                var url ="<?=site_url('/board/lists').'?table=ci_board&search_word=' ?>" + $("#q").val() + "&page=1";
                //$("#search_word").val($("#q").val());
                //$("#bd_search").attr('action', url).submit();
                location.href=url;
            }
        });
    });

    function board_search_enter(form) {
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
        Board page
        <small>board</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">board</a></li>
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
                    <h3 class="box-title">Table</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" id="q" onkeypress="board_search_enter(document.q);">
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
                            <th scope="col">제목</th>
                            <th scope="col">작성자</th>
                            <th scope="col">조회수</th>
                            <th scope="col">작성일</th>
                        </tr>
                        <?php
                        foreach ($list as $lt) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $last_num--; ?></th>
                                <td><a rel="external"
                                       href="<?=site_url('/board/view').'?board_id='.$lt->board_id.'&table='.$table.'&per_page='.$per_page;?>"> <?php echo $lt->subject; ?></a>
                                </td>
                                <td><?php echo $lt->user_name; ?></td>
                                <td><?php echo $lt->hits; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>">
                                        <?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>
                                    </time>
                                </td>
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
                    <div class="pull-right">
                        <a href="<?=site_url('/board/write').'?table='.$table ?>" class="btn btn-sm btn-primary">쓰기</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<!-- /.content -->