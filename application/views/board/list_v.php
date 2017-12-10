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
                    <h3 class="box-title">Responsive Hover Table</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
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
                                <th scope="row"><?php echo $lt->board_id; ?></th>
                                <td><a rel="external"
                                       href="/bbs/<?php echo $this->uri->segment(1); ?>/view/<?php echo $this->uri->segment(3); ?>/<?php echo $lt->board_id; ?>"> <?php echo $lt->subject; ?></a>
                                </td>
                                <td><?php echo $lt->user_name; ?></td>
                                <td><?php echo $lt->hits; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_date)); ?>">
                                        <?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_date)); ?>
                                    </time>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th colspan="5"><?php echo $pagination;?></th>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<!-- /.content -->