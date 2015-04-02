<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?=implode(' - ',$this->title)?></title>
	<?php echo link_tag(static_url('css/bootstrap.css')); ?>
	<?php echo link_tag(static_url('css/layout.css')); ?>
	<?php echo link_tag(static_url('css/components.css')); ?>
    <?php echo link_tag(static_url('css/bootstrap-datetimepicker.min.css')); ?>
    <?php echo link_tag(static_url('css/main.css')); ?>
    <?php echo link_tag(static_url('css/jquery.fancybox.css')); ?>
    <?php echo link_tag(static_url('css/jquery-ui.css')); ?>


	<meta property="qc:admins" content="1524167566671621135056375" />
	<?php echo link_script(static_url('js/jquery-1.11.1.min.js')); ?>
	
	<?php echo link_script(static_url('js/jquery-ui.min.js')); ?>
	<?php echo link_script(static_url('js/bootstrap.min.js')); ?>
	<?php echo link_script(static_url('js/bootstrap-datetimepicker.js')); ?>
	<?php echo link_script(static_url('js/bootstrap-datetimepicker.zh-CN.js')); ?>

	<?php echo link_script(static_url('js/jquery.tablesorter.js')); ?>
	<?php echo link_script(static_url('js/jquery.powertip.js')); ?>
	<?php echo link_script(static_url('js/jquery.blockUI.js')); ?>
	<?php echo link_script(static_url('js/jquery.validate.js')); ?>
	<?php echo link_script(static_url('js/jquery.flot.js')); ?>
	<?php echo link_script(static_url('js/jquery.flot.pie.js')); ?>
    <?php echo link_script(static_url('js/lib.js')); ?>
    <?php echo link_script(static_url('js/main.js')); ?>
    <?php echo link_script(static_url('js/ajaxfileupload.js')); ?>
    <?php echo link_script(static_url('js/jquery.fancybox.js')); ?>

    <script type="text/javascript">
    	var base_url = "<?php print base_url(); ?>";
    	var req_url_template = "<?php echo site_url('{ctrller}/{action}') ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
