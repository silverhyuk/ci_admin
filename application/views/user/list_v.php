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
        $("#write_form_btn").on(
            "click",
            function(){
                //alert("pop");
                $("#writeForm").validate().resetForm();
                $("#writeForm").prop("action","<?=site_url('/user/write')?>");
                $("#writeForm")[0].reset();

                $("#writeFormTitle").text('사용자 등록');
                $('#write_submit_btn').text("등록");
                $('#writeContainer').modal();
            }
        );
        $("#write_submit_btn").on('click', function(e) {
            submitAjax();
        });
        $( "#writeForm" ).validate( {
            rules: {
                user_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                nick_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 30
                },
                re_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
                role_id: "required"
            },
            messages: {
                user_name: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters",
                    maxlength: "Your username must consist of max 30 characters"
                },
                nick_name: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters",
                    maxlength: "Your username must consist of max 30 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                re_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email: "Please enter a valid email address",
                role_id: "Please accept our policy"
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

    function user_search_enter(form) {
        var keycode = window.event.keyCode;
        if (keycode == 13){
            $("#search_btn").click();
        }
    }
    function submitAjax(){
        $("#writeForm").submit();
    }
    function modifyForm(userSeqId){
        $("#writeForm").validate().resetForm();
        $.ajax({
            type: "GET",
            url: "<?=site_url('/user/view')?>",
            dataType:"json",
            cache: false,
            data: "user_id=" + userSeqId,
            success: function(data){
                $("#writeForm").prop("action","<?=site_url('/user/modify')?>");
                $("#writeForm")[0].reset();
                var result = data.result;
                if($("#writeForm input[name='user_id']").length == 0){
                    $("#writeForm").prepend("<input type=\"hidden\" name=\"user_id\" />");
                }
                $("#writeForm input[name='user_id']").val(userSeqId);
                $("#writeForm input[name='user_name']").val(result.user_name);
                $("#writeForm input[name='nick_name']").val(result.nick_name);
                $("#writeForm input[name='email']").val(result.email);
                $("#writeForm input[name='<?=$token_name?>']").val('<?=$token_hash?>');

                /* $("#writeForm select[name='roleId'] option:eq("+result.roleId+")").attr("selected", "selected"); */
                $("#writeForm select[name='role_id']").val(result.role_id);
            },
            error: function(request,status,error)
            {
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            complete: function(){

            }
        });
        $('#writeFormTitle').text("사용자 수정");
        $('#write_submit_btn').text("수정");
        $('#writeContainer').modal();
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
                        <button id="write_form_btn" class="btn btn-primary"><i class="fa fa-save"></i>  등록</button>
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
                                <td><button  class="btn btn-default" onclick="javascript:modifyForm(<?php echo $lt->user_id; ?>);"><i class="fa fa-edit"></i></button></td>
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
            <form name="writeForm" id="writeForm" action="<?=site_url('/user/write')?>" method="post" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id="writeFormTitle">사용자 등록</span></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>"/>
                    <div class="form-group has-feedback">
                        <label>이름</label>
                        <input type="text" id="user_name" name="user_name" class="form-control" placeholder="이름">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>닉네임</label>
                        <input type="text" id="nick_name" name="nick_name" class="form-control" placeholder="닉네임">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>이메일</label>
                        <input type="email" id="email" name="email"  class="form-control" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>비밀번호</label>
                        <input type="password" id="password" name="password"  class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>비밀번호 재확인</label>
                        <input type="password" id="re_password" name="re_password" class="form-control" placeholder="Retype password">
                        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <label>권한선택</label>
                        <select class="form-control" name='role_id'>
                            <option value=''>선택하세요</option>
                            <option value='1'>ADMIN(슈퍼어드민)</option>
                            <option value='2'>MANAGER(관리자)</option>
                            <option value='3'>USER(사용자)</option>
                        </select>
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