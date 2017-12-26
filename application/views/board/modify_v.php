<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */
?>
<script>
    $(document).ready(function() {
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
            <h3 class="box-title">게시물 수정</h3>
        </div>
        <div class="box-body">
            <form class="form-horizontal" method="post" action="" id="write_action">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="input01">제목</label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" id="input01" name="subject"
                                   value="<?php echo $views->subject; ?>" />
                        </div>
                        <label class="control-label" for="input02">내용</label>
                        <div class="controls">
                                <textarea class="input-xlarge" id="input02" name="contents" rows="5">
                                    <?php echo $views->contents;?>
                                </textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="write_btn"> 수정 </button>
                            <button class="btn" onclick="history.back();">취소</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <!-- /.box-body -->
        <!--<div class="box-footer">
            Footer
        </div>-->
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->