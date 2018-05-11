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
        $("#menu_01").addClass('active');
        $("#menu_01").addClass('menu-open');
        $("#menu_01_02").addClass('active');

        $('#delete_btn').on('click', function(){
            if(confirm('정말로 삭제 하시겠습니까?')){
                location.href = '<?=site_url('/board/delete').'?table='.$table.'&board_id='.$board_id ?>';
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
        <li class="active">view</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $views -> subject;?></h3>
            <div class="no-margin pull-right text-muted">
                <?php echo $views -> reg_date;?>
            </div>
        </div>
        <div class="box-body">
            <?php echo $views -> user_name;?>
            <div class="no-margin pull-right text-muted">
                [<?php echo $views -> hits;?>]
            </div>
            <table cellspacing="0" cellpadding="0" class="table table-striped">
                <tr>
                    <th colspan="2">
                        <?php echo $views -> contents;?>
                    </th>
                </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="no-margin pull-right">
                <a href="<?=site_url('/board/lists').'?table='.$table.'&per_page='.$per_page ?>" class="btn  btn-sm btn-primary">목록 </a>
                <?php if($views -> user_id === $this->session->userdata('user_id')){ ?>
                    <a href="<?=site_url('/board/modify').'?table='.$table.'&board_id='.$board_id ?>" class="btn btn-sm btn-warning"> 수정 </a>
                    <button id="delete_btn" class="btn btn-sm btn-warning"> 삭제 </button>
                <?php } ?>
            </div>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->