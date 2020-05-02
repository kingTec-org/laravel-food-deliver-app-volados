<body class="" ng-app="App">
    <div class="wrapper">
        <div class="sidebar" data-color="rose" data-background-color="black" data-image="<?php echo e(asset('admin_assets/img/sidebar-1.jpg')); ?>">
            <div class="logo">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="simple-text logo-normal" title="<?php echo e(site_setting('site_name')); ?>">
                   <img src="<?php echo e(site_setting('1','1')); ?>">
               </a>
           </div>
           <div class="sidebar-wrapper">
            <div class="user">
                <div class="photo">
                    <img src="<?php echo e(asset('admin_assets/img/faces/avatar.jpg')); ?>" />
                </div>
                <div class="user-info">
                    <a  href="<?php echo e(route('admin.edit_admin',['id' => auth()->guard('admin')->user()->id])); ?>" class="username">
                        <span>
                            <?php echo e(auth()->guard('admin')->user()->username); ?>

                        </span>
                    </a>
                    <div class="collapse d-none" id="collapseExample">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="sidebar-mini"> MP
                                    </span>
                                    <span class="sidebar-normal"> My Profile
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="sidebar-mini"> EP
                                    </span>
                                    <span class="sidebar-normal"> Edit Profile
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="sidebar-mini"> S
                                    </span>
                                    <span class="sidebar-normal"> Settings
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav">
                <?php $__currentLoopData = side_navigation(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navigation_name => $navigation_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item <?php echo e(($navigation_value['active']) ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e($navigation_value['route']); ?>">
                        <i class="material-icons"><?php echo e($navigation_value['icon']); ?>

                        </i>
                        <p> 
                        <?php echo e($navigation_value['name']); ?>

                        </p>
                    </a>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute fixed-top">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                            <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert
                            </i>
                            <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list
                            </i>
                        </button>
                    </div>
                    <a class="navbar-brand" href="#pablo"><?php echo e(@$form_name); ?>

                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation
                    </span>
                    <span class="navbar-toggler-icon icon-bar">
                    </span>
                    <span class="navbar-toggler-icon icon-bar">
                    </span>
                    <span class="navbar-toggler-icon icon-bar">
                    </span>
                </button>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#pablo">
                                <i class="material-icons">notifications
                                </i>
                                <span class="notification">5
                                </span>
                                <p>
                                    <span class="d-lg-none d-md-block">Account
                                    </span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#pablo" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">person
                                </i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="<?php echo e(route('admin.logout')); ?>">
                                Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flash-container">
                <?php if(Session::has('message')): ?>
                <div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
                    <a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

                </div>
                <?php endif; ?>
            </div>
        </nav>
        <!-- End Navbar -->

