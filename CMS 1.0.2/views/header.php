<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Web Evolution - Administration Panel</title>
	    <meta name="author" content="Web Evolution">
	    <meta name="description" content="Back-Office and dashboard made by Web Evolution.">
	    <meta property="image" content="<?php echo $this->config->item('resources_img'); ?>/logo/logo.png">
        <meta property="og:image" content="<?php echo $this->config->item('resources_img'); ?>/logo/logo.png">
        <meta property="site_name" content="Web Evolution - Administration Panel">
        <meta property="description" content="Back-Office and dashboard made by Web Evolution.">

        <!-- FAVICON -->
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->config->item('resources_img'); ?>/logo/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo $this->config->item('resources_img'); ?>/logo/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo $this->config->item('resources_img'); ?>/logo/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo $this->config->item('resources_img'); ?>/logo/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo $this->config->item('resources_img'); ?>/logo/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo $this->config->item('resources_img'); ?>/logo/manifest.json">
        <link rel="mask-icon" href="<?php echo $this->config->item('resources_img'); ?>/logo/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="<?php echo $this->config->item('resources_img'); ?>/logo/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="<?php echo $this->config->item('resources_img'); ?>/logo/mstile-144x144.png">
        <meta name="msapplication-config" content="<?php echo $this->config->item('resources_img'); ?>/logo/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
        <!-- END FAVICON -->
        
	    <link href="<?php echo $this->config->item('resources_css'); ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
	    <link href="<?php echo $this->config->item('resources_css'); ?>/font-awesome.min.css" rel="stylesheet" type="text/css">
	    <link href="<?php echo $this->config->item('resources_css'); ?>/ionicons.min.css" rel="stylesheet" type="text/css">
	    <?php if ($page == 'products') : ?>
	    	<link href="<?php echo $this->config->item('resources_css'); ?>/plugin/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">	
	    <?php endif; ?>  
	    <?php if ($page == 'pages' || $page == 'n_page') : ?>
	    	<link rel="stylesheet" href="<?php echo $this->config->item('resources_css'); ?>/plugin/bootstrap3-wysihtml5.min.css" type="text/css">	
	    <?php endif; ?>
	    <?php if ($page == 'sales' || $page == 'n_sale') : ?>
	    	<link rel="stylesheet" href="<?php echo $this->config->item('resources_css'); ?>/plugin/bootstrap-datepicker.min.css" type="text/css">
	    <?php endif; ?>
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
	    <link href="<?php echo $this->config->item('resources_css'); ?>/webevo-cms.css" rel="stylesheet">
	    <!-- jQuery -->
	    <script src="<?php echo $this->config->item('resources_js'); ?>/jQuery-2.2.3.min.js"></script>
	</head>
