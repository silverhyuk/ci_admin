<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Blank page
        <small>it all starts here</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $views -> subject;?></h3>
        </div>
        <div class="box-body">
            <table cellspacing="0" cellpadding="0" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">이름: <?php echo $views -> user_name;?></th>
                    <th scope="col">조회수: <?php echo $views -> hits;?></th>
                    <th scope="col">등록일: <?php echo $views -> reg_date;?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th colspan="3">
                        <?php echo $views -> contents;?>
                    </th>
                </tr>
                </tbody>

            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <a href="/bbs/board/lists/<?php echo $this -> uri -> segment(3); ?>/page/<?php echo $this -> uri -> segment(7); ?>" class="btn  btn-sm btn-primary">목록 </a>
            <a href="/bbs/board/modify/<?php echo $this -> uri -> segment(3); ?>/board_id/<?php echo $this -> uri -> segment(4); ?>/page/<?php echo $this -> uri -> segment(7); ?>"
               class="btn btn-sm btn-warning"> 수정 </a>
            <a href="/bbs/board/delete/<?php echo $this -> uri -> segment(3); ?>/board_id/<?php echo $this -> uri -> segment(5); ?>/page/<?php echo $this -> uri -> segment(7); ?>"
               class="btn btn-sm btn-danger"> 삭제 </a>
            <a href="/bbs/board/write/<?php echo $this -> uri -> segment(3); ?>/page/<?php echo $this -> uri -> segment(7); ?>"
               class="btn btn-sm btn-success">쓰기</a>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->