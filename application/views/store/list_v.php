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

        $("#search_btn").click(function() {
            if ($("#q").val() == '') {
                //alert("검색어를 입력하세요!");
                //return false;
                var url ="<?=site_url('/store/lists').'?page=1' ?>" ;
                location.href=url;
            } else {
                var url ="<?=site_url('/store/lists').'?search_word=' ?>" + $("#q").val() + "&page=1";
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
                $("#writeForm").prop("action","<?=site_url('/store/write')?>");
                $("#writeForm")[0].reset();

                $("#writeFormTitle").text('상점 등록');
                $('#write_submit_btn').text("등록");
                $('#writeContainer').modal();
            }
        );
        $("#write_submit_btn").on('click', function(e) {
            submitAjax();
        });
        $( "#writeForm" ).validate( {
            rules: {
                store_name: {
                    required: true
                },
                store_type_id: {
                    required: true
                },
                status_id: {
                    required: true
                },
                store_tel: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }

            },
            messages: {
                store_name: {
                    required: "Please enter a store name"
                },
                store_type_id: {
                    required: "Please select a store type"
                },
                status_id: {
                    required: "Please select a store status"
                },
                store_tel: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long",
                    maxlength: "Your password must be max 20 characters long"
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

    function store_search_enter(form) {
        var keycode = window.event.keyCode;
        if (keycode == 13){
            $("#search_btn").click();
        }
    }
    function submitAjax(){
        $("#writeForm").submit();
    }
    function modifyForm(storeSeqId){
        $("#writeForm").validate().resetForm();
        $.ajax({
            type: "GET",
            url: "<?=site_url('/store/view')?>",
            dataType:"json",
            cache: false,
            data: "store_id=" + storeSeqId,
            success: function(data){
                $("#writeForm").prop("action","<?=site_url('/store/modify')?>");
                $("#writeForm")[0].reset();
                var result = data.result;
                if($("#writeForm input[name='store_id']").length == 0){
                    $("#writeForm").prepend("<input type=\"hidden\" name=\"store_id\" />");
                }
                $("#writeForm input[name='store_id']").val(storeSeqId);
                $("#writeForm input[name='store_name']").val(result.store_name);
                $("#writeForm input[name='store_tel']").val(result.store_tel);
                $("#writeForm select[name='status_id']").val(result.status_id);
                $("#writeForm select[name='store_type_id']").val(result.store_type_id);
                $("#writeForm input[name='<?=$token_name?>']").val('<?=$token_hash?>');
            },
            error: function(request,status,error)
            {
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            },
            complete: function(){

            }
        });
        $('#writeFormTitle').text("상점 수정");
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
        상점 관리
        <small>store</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">store</a></li>
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
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search" id="q" onkeypress="store_search_enter(document.q);">
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
                            <th scope="col">상점이름</th>
                            <th scope="col">상점분류</th>
                            <th scope="col">상태</th>
                            <th scope="col">작성일</th>
                            <th scope="col">-</th>
                        </tr>
                        <?php
                        foreach ($list as $lt) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $last_num--; ?></th>
                                <td><?php echo $lt->store_name; ?></td>
                                <td><?php echo $lt->store_type_name; ?></td>
                                <td><?php echo $lt->status_name; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>">
                                        <?php echo mdate("%Y-%m-%d", human_to_unix($lt->reg_date)); ?>
                                    </time>
                                </td>
                                <td><button  class="btn btn-default" onclick="javascript:modifyForm(<?php echo $lt->store_id; ?>);"><i class="fa fa-edit"></i></button></td>
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
            <form name="writeForm" id="writeForm" action="<?=site_url('/store/write')?>" method="post" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id="writeFormTitle">상점 등록</span></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>"/>
                    <div class="form-group has-feedback">
                        <label>상점이름</label>
                        <input type="text" id="store_name" name="store_name" class="form-control" placeholder="상점이름">
                        <span class="glyphicon glyphicon-store form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>상점전화번호</label>
                        <input type="text" id="store_tel" name="store_tel" class="form-control" placeholder="전화번호">
                        <span class="glyphicon glyphicon-store form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label>상점분류</label>
                        <select class="form-control" id='store_type_id' name='store_type_id'>
                            <option value=''>선택하세요</option>
                            <?php
                            foreach ($storeTypeList as $lt) {
                                ?>
                                <option value='<?php echo $lt->type_id; ?>'><?php echo $lt->type_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group has-feedback">
                        <label>상태</label>
                        <select class="form-control" id='status_id' name='status_id'>
                            <option value=''>선택하세요</option>
                            <?php
                            foreach ($statusList as $lt) {
                                ?>
                                <option value='<?php echo $lt->status_id; ?>'><?php echo $lt->status_name; ?></option>
                                <?php
                            }
                            ?>
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