<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-09
 * Time: 오후 10:57
 */
?>
<script type="application/javascript">
    $(document).ready(function () {
        $("#side-menu > .sidebar-menu > li").removeClass('active');
        $("#side-menu > .sidebar-menu > li").removeClass('menu-open');
        $("#side-menu > .treeview-menu > li").removeClass('active');
        $("#menu_02").addClass('active');
        $("#menu_02").addClass('menu-open');
        $("#menu_02_02").addClass('active');

        $("#backBtn").click(function() {
            history.back();
        });
    });
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        상점 상세 정보
        <small>store</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">store</a></li>
        <li><a href="<?php echo site_url('/store/lists')?>">list</a></li>
        <li class="active">detail</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $result->store_name ?></h3>

            <div class="box-tools pull-right">
                <button id="backBtn" type="button" class="btn btn-box-tool"  data-toggle="tooltip"
                        title="back">
            <i class="fa fa-fw fa-chevron-circle-left"></i></button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                         title="Collapse">
                     <i class="fa fa-minus"></i></button>
                 <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                         title="Remove">
                     <i class="fa fa-times"></i></button>-->
             </div>
        </div>
        <div class="box-body">
            Start creating your amazing application!
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->