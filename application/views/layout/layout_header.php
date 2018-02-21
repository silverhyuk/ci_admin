<header class="main-header">
    <!-- Logo -->
    <a href="<?=site_url('/');?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">

                    <!-- 로그인 되었을때 -->
                    <?php
                        if($this->session->userdata('is_login')){
                    ?>

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=base_url();?>template/dist/img/if_bitty_bear_blake_37660.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=$this->session->userdata('nick_name');?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?=base_url();?>template/dist/img/if_bitty_bear_blake_37660.png" class="img-circle" alt="User Image">

                            <p>
                                <?=$this->session->userdata('user_name');?>(<?=$this->session->userdata('role_name');?>)
                                <small><?=$this->session->userdata('email');?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- <li class="user-body">
                             <div class="row">
                                 <div class="col-xs-4 text-center">
                                     <a href="#">Followers</a>
                                 </div>
                                 <div class="col-xs-4 text-center">
                                     <a href="#">Sales</a>
                                 </div>
                                 <div class="col-xs-4 text-center">
                                     <a href="#">Friends</a>
                                 </div>
                             </div>

                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?=site_url();?>auth/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                        <?php
                        } else {
                            ?>
                            <li><a href="<?=site_url('auth/login');?>">로그인</a></li>
                            <li><a href="<?=site_url('register');?>">회원가입</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>