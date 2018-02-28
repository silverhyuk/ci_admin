<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */
$token_name = $this->security->get_csrf_token_name();
$token_hash = $this->security->get_csrf_hash();
?>
<script>
    $(document).ready(function () {
        // Menu
        $("#side-menu > .sidebar-menu > li").removeClass('active');
        $("#side-menu > .sidebar-menu > li").removeClass('menu-open');
        $("#side-menu > .treeview-menu > li").removeClass('active');
        $("#menu_02").addClass('active');
        $("#menu_02").addClass('menu-open');
        $("#menu_02_02").addClass('active');

        $("#backBtn").click(function() {
            history.back();
        });

        $("#write_form_btn").on(
            "click",
            function(){
                //alert("pop");
                $("#writeForm").validate().resetForm();
                $("#writeForm").prop("action","<?=site_url('/menu/write')?>");
                $("#writeForm")[0].reset();

                $("#writeFormTitle").text('메뉴 등록');
                $('#write_submit_btn').text("등록");
                $('#writeContainer').modal();
            }
        );
        $("#write_submit_btn").on('click', function(e) {
            submitAjax();
        });
        $( "#writeForm" ).validate( {
            rules: {
                menu_name: {
                    required: true
                },
                menu_price: {
                    required: true,
                    minlength:3,
                    maxlength: 10,
                    digits: true
                }

            },
            messages: {
                menu_name: {
                    required: "Please enter a menu name"
                },
                menu_price: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 3 characters long",
                    maxlength: "Your password must be max 10 characters long",
                    digits: "숫자만 입력 가능합니다"
                }
            },
            submitHandler: function() {
                var formData = $("#writeForm").serialize();
                var actionUrl = $("#writeForm").prop("action");
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    dataType:"json",
                    cache: false,
                    data: formData,
                    success: function(data){
                        if(data.result == "S"){
                            window.location.reload();
                        }else if(data.result == "F")  {
                            toastr["error"]("실패했습니다.", "실패");
                        }
                    },
                    error: function(request,status,error)
                    {
                        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                    },
                    complete: function(){
                        window.location.reload();

                    }
                });
            }
        } );
    });

    function menu_search_enter(form) {
        var keycode = window.event.keyCode;
        if (keycode == 13){
            $("#search_btn").click();
        }
    }
    function submitAjax(){
        $("#writeForm").submit();
    }
    function modifyForm(menuSeqId){
        $("#writeForm").validate().resetForm();
        $.ajax({
            type: "GET",
            url: "<?=site_url('/menu/view')?>",
            dataType:"json",
            cache: false,
            data: "menu_id=" + menuSeqId,
            success: function(data){
                $("#writeForm").prop("action","<?=site_url('/menu/modify')?>");
                $("#writeForm")[0].reset();
                var result = data.result;
                if($("#writeForm input[name='menu_id']").length == 0){
                    $("#writeForm").prepend("<input type=\"hidden\" name=\"menu_id\" />");
                }
                $("#writeForm input[name='menu_id']").val(menuSeqId);
                $("#writeForm input[name='menu_name']").val(result.menu_name);
                $("#writeForm input[name='menu_price']").val(result.menu_price);
                $("#writeForm select[name='status_id']").val(result.status_id);
                $("#writeForm select[name='menu_type_id']").val(result.menu_type_id);
                $("#writeForm input[name='<?=$token_name?>']").val('<?=$token_hash?>');
            },
            error: function(request,status,error)
            {
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            complete: function(){

            }
        });
        $('#writeFormTitle').text("메뉴 수정");
        $('#write_submit_btn').text("수정");
        $('#writeContainer').modal();
    }
</script>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $store_info->store_name ?> 
        <small>menu</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">menu</a></li>
        <li><a href="<?php echo site_url('/menu/lists')?>">list</a></li>
        <li class="active">detail</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <button id="backBtn" type="button" class="btn btn-box-tool"  data-toggle="tooltip"
                            title="back">
                        <i class="fa fa-fw fa-chevron-circle-left"></i>뒤로가기</button>
                </div>
                <div class="box-body">
                    <ul>
                        <li>상점명 : <?php echo $store_info->store_name ?></li>
                        <li>전화번호 : <?php echo $store_info->store_tel ?></li>
                        <li>등록일 :  <?php echo mdate("%Y-%m-%d", human_to_unix($store_info->reg_date)); ?></li>
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="pull-left">
                        <button id="write_form_btn" class="btn btn-primary"><i class="fa fa-save"></i>  등록</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">메뉴이름</th>
                            <th scope="col">가격</th>
                            <th scope="col">작성일</th>
                            <th scope="col">-</th>
                        </tr>
                        <?php
                        foreach ($list as $lt) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $last_num--; ?></th>
                                <td><?php echo $lt->menu_name; ?></td>
                                <td><?php echo $lt->menu_price; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>">
                                        <?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>
                                    </time>
                                </td>
                                <td><button  class="btn btn-default" onclick="javascript:modifyForm(<?php echo $lt->menu_id; ?>);"><i class="fa fa-edit"></i></button></td>
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

<div id="writeContainer" class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="writeForm" id="writeForm" action="<?=site_url('/menu/write')?>" method="post" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id="writeFormTitle">메뉴 등록</span></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>"/>
                    <input type="hidden" id="store_id" name="store_id" value="<?= $store_id; ?>"/>
                    <div class="form-group has-feedback">
                        <label>메뉴이름</label>
                        <input type="text" id="menu_name" name="menu_name" class="form-control" placeholder="메뉴이름">
                        <span class="glyphicon glyphicon-menu form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>가격</label>
                        <input type="text" id="menu_price" name="menu_price" class="form-control" placeholder="가격">
                        <span class="glyphicon glyphicon-menu form-control-feedback"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">닫기</button>
                    <button id="write_submit_btn" type="button" class="btn btn-primary">등록</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->