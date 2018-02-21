<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2018 <a href="https://">silverhyuk</a>.</strong> All rights
    reserved.
    <?php
    if($this->session->flashdata('message')){
        ?>
        <script>
            toastr.options = {
                "showDuration": 300,
                "hideDuration": 1000,
                "timeOut": 3000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.success('<?=$this->session->flashdata('message')?>');
        </script>
        <?php
    }
    ?>
</footer>