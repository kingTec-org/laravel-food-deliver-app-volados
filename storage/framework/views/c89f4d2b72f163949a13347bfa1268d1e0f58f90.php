<!doctype html>
 <html  dir="<?php echo e((((Session::get('language')) ? Session::get('language') : $default_language[0]->value) == 'ar') ? 'rtl' : ''); ?>" lang="<?php echo e((Session::get('language')) ? Session::get('language') : $default_language[0]->value); ?>">
<head>
	<title><?php echo e(site_setting('site_name')); ?></title>
	<meta charset="utf-8" time="<?php echo e(date('H:i')); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="<?php echo e(site_setting('1','2')); ?>" type="image/gif" sizes="14x26">
	<link href="<?php echo e(asset('css/animate.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/bootstrap-toggle.min.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/bootstrap-select.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('css/common.css')); ?>" rel="stylesheet">
	<script src=" <?php echo e(url('js/jquery-3.3.1.min.js')); ?>" type="text/javascript"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<style type="text/css">
		main {
			opacity: 0;
		}
		  .ng-cloak {
            display: none;
        }
	</style>
</head>

<body class="<?php echo e(Route::current()->named('store.*') ? 'store-page' : ''); ?> <?php echo e((!isset($exception)) ? (Route::current()->uri() == '/' ? 'home-page' : 'inner-page') : ''); ?> <?php echo e(Route::current()->uri() == 'details/{store_id}' ? 'detail-page' : ''); ?> <?php echo e(auth()->guard('store')->user() ? 'log_dash' : ''); ?>" ng-cloak class="ng-cloak"  ng-app="App">