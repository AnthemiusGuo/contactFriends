<?php include_once('common/header.php');
?>
<body class="page-header">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?=site_url()?>">
                    <img src="<?=static_url('images/logo.png')?>" alt="logo" class="logo-default" width="150px">
                </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="glyphicon glyphicon-bell"></span>
                        <span class="badge badge-default">
                        7 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold">12 pending</span> notifications</h3>
                                <a href="extra_profile.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                                    <li>
                                        <a href="javascript:;">
                                        <span class="time">just now</span>
                                        <span class="details">
                                        <span class="label label-sm label-icon label-success">
                                        <i class="fa fa-plus"></i>
                                        </span>
                                        New user registered. </span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END NOTIFICATION DROPDOWN -->
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="glyphicon glyphicon-envelope"></span>
                        <span class="badge badge-default">
                        4 </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3>You have <span class="bold">7 New</span> Messages</h3>
                                <a href="page_inbox.html">查看所有消息</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 275px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                                    <li>
                                        <a href="inbox.html?a=view">
                                        <span class="photo">
                                        <img src="../../assets/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">
                                        </span>
                                        <span class="subject">
                                        <span class="from">
                                        Lisa Wong </span>
                                        <span class="time">Just Now </span>
                                        </span>
                                        <span class="message">
                                        Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END INBOX DROPDOWN -->
                    <!-- BEGIN TODO DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <span class="badge badge-default">
                        3 </span>
                        </a>
                        <ul class="dropdown-menu extended tasks">
                            <li class="external">
                                <h3>You have <span class="bold">12 pending</span> tasks</h3>
                                <a href="page_todo.html">view all</a>
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 275px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                                    <li>
                                        <a href="javascript:;">
                                        <span class="task">
                                        <span class="desc">New release v1.2 </span>
                                        <span class="percent">30%</span>
                                        </span>
                                        <span class="progress">
                                        <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">40% Complete</span></span>
                                        </span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END TODO DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?=static_url('images/avartar/1_s.jpg')?>">
                        <span class="username username-hide-on-mobile">
                        <?=$this->userInfo->field_list['name']->gen_show_value()?> </span>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="extra_profile.html">
                                <span class="glyphicon glyphicon-user"></span>我的信息</a>
                            </li>
                            <li>
                                <a href="page_calendar.html">
                                <span class="glyphicon glyphicon-calendar"></span>我的日程</a>
                            </li>
                            <li>
                                <a href="inbox.html">
                                <span class="glyphicon glyphicon-envelope"></span>我的信箱
                                </a>
                            </li>
                            <li>
                                <a href="page_todo.html">
                                <span class="glyphicon glyphicon-list"></span>我的任务
                                </a>
                            </li>
                            <li class="divider">
                            </li>
                            <li>
                                <a href="extra_lock.html">
                                <span class="glyphicon glyphicon-lock"></span>锁屏
                                </a>
                            </li>
                            <li>
                                <a href="login.html">
                                <span class="glyphicon glyphicon-log-out"></span>退出
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-quick-sidebar-toggler">
                        <a href="javascript:;" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                        </a>
                    </li>
                    <!-- END QUICK SIDEBAR TOGGLER -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul id="nav-sidebar" class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <?php
                foreach ($this->menus as $menu_name=>$menu_info):
                ?>
                    <li class="main-nav <?php echo ($this->controller_name==$menu_name)?"active open":"" ?>" id="nav-side-title-<?php echo $menu_name;?>">
                        <a href="#" onclick="nav_sidebar_collapse('<?php echo $menu_name;?>')">
                        <span class="glyphicon <?php echo $menu_info["icon"]?>"></span>
                        <span class="title"><?php echo $menu_info['name'];?></span>
                        <span class="showing_icon glyphicon glyphicon-chevron-down pull-right <?php echo ($this->controller_name==$menu_name)?"show":"hidden" ?>"></span>
                        <?php echo ($this->controller_name==$menu_name)?'<span class="selected"></span>':"" ?>
                        </a>

                        <ul class="nav sub-nav <?php echo ($this->controller_name==$menu_name)?"show":"hidden" ?>" id="nav-side-list-<?php echo $menu_name;?>">
                            <?php
                            foreach ($menu_info["menu_array"] as $sub_menu_name=>$sub_menu_info):
                            ?>
                            <li class="<?php echo ($this->controller_name==$menu_name && $sub_menu_name==$this->method_name)?'active':'' ?>">
                                <a href="<?php echo ("href"==$sub_menu_info['method'])?$sub_menu_info['href']:'javascript:void(0);' ?>" <?php echo ("onclick"==$sub_menu_info['method'])?'onclick="'.$sub_menu_info['onclick'].'"':'' ?> >
                                <span class="glyphicon <?php echo ($this->controller_name==$menu_name && $sub_menu_name==$this->method_name)?'glyphicon-circle-arrow-right':'glyphicon-chevron-right' ?>"></span>
                                <?php echo $sub_menu_info['name'] ?></a>
                                </li>
                            <?
                            endforeach;
                            ?>
                        </ul>
                    </li>
                <?
                endforeach;
                ?>

            </ul>
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content" style="min-height:1227px">
        <?php echo $contents; ?>
        </div>
    </div>
    <!-- END CONTENT -->
<script>
jQuery(document).ready(function() {
    $(".table-paged").quickPager({pageSize:10,holder:'#main_pager',struct:'tbody'});
    $(".tablesorter").tablesorter();
    $('.tooltips').powerTip({offset:20});
});
</script>
<!-- END JAVASCRIPTS -->
<?php include_once('common/footer.php')?>
