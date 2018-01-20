<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */
?>

<!-- CK Editor -->
<script src="<?=base_url();?>template/bower_components/ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('input02');
        $("#write_btn").click(function() {
            if ($("#input01").val() == '') {
                alert('제목을 입력해 주세요.');
                $("#input01").focus();
                return false;
            } else if ($("#input02").val() == '') {
                alert('내용을 입력해 주세요.');
                $("#input02").focus();
                return false;
            } else {
                $("#write_action").submit();
            }
        });
    });
</script>


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
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">게시물 작성</h3>
        </div>
        <form class="form-horizontal" method="post" action="<?=site_url('/board/write') ?>" id="write_action">
             <div class="box-body">

                <input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>"/>
                <input type="hidden" id="table" name="table"  value="<?=$table; ?>" />
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="input01">제목</label>
                        <div class="controls">
                            <input type="text" class="form-control" id="input01" name="subject"    value="" />
                        </div>
                        <label class="control-label" for="input02">내용</label>
                        <div class="controls">
                                <textarea class="input-xlarge" id="input02" name="contents" rows="5">

                                </textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="box-footer">
                <div class="no-margin pull-right">
                    <button type="button" class="btn btn-primary" id="write_btn"> 작성 </button>
                    <button type="button" class="btn" id="cancel_btn" onclick="history.back();">취소</button>
                </div>
            </div>
        </form>
        <!-- /.box-body -->
        <!--<div class="box-footer">
            Footer
        </div>-->
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->