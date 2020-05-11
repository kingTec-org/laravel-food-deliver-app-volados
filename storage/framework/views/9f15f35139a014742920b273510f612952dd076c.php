<?php echo $__env->make('admin.common.auth.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<body class="off-canvas-sidebar login-page">
    <!-- Navbar -->
    <?php echo $__env->make('admin.common.auth.navbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- End Navbar -->
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" style="background-image: url('<?php echo e(asset('admin_assets/img/login.jpg')); ?>'); background-size: cover; background-position: top center;">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="container">
                <div class="col-12 col-md-7 col-lg-5 ml-auto mr-auto">
                    <?php echo Form::open(['url'=>route('admin.authenticate'),'class'=>'form']); ?>

                    <div class="card card-login">
                        <div class="card-header card-header-rose text-center">
                            <h4 class="card-title">Log in</h4>
                        </div>
                        <div class="card-body">
                            <!-- <p class="card-description text-center">Or Be Classical</p> -->
                            <span class="bmd-form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">
                                                face
                                            </i>
                                        </span>
                                    </div>
                                    <div class="input-field flex-grow-1">
                                        <?php echo Form::text('user_name','',['class'=>'form-control mt-0','placeholder'=>'User Name']); ?>

                                        <span class="text-danger d-block mt-1">
                                            <?php echo e($errors->first('user_name')); ?>

                                        </span>
                                    </div>
                                </div>
                            </span>
                            <span class="bmd-form-group">
                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">
                                                lock_outline
                                            </i>
                                        </span>
                                    </div>
                                    <div class="input-field flex-grow-1">
                                    <?php echo Form::input('password','password','',['class'=>'form-control mt-0','placeholder'=>'password']); ?>

                                        <span class="text-danger">
                                            <?php echo e($errors->first('password')); ?>

                                        </span>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="card-footer justify-content-center">
                            <?php echo Form::submit('submit',['class'=>'btn btn-rose btn-lg']); ?>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $__env->make('admin.common.auth.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('admin.common.auth.foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
