<?php
	defined('BASEPATH') OR exit('No direct script access allowed'); 
	
	$message = '';
	if (isset($blocked_ip)) {
		$message = '<p class="txt-error">Indirizzo ip bloccato. Contatta l amministrazione a <a href="mailto:info@webevolution.eu">info@webevolution.eu</a></p>';
		$loginDisabled = true;
	} else {
		$loginDisabled = false;
		$remainLogin = 5;
		if (isset($_SESSION['failed_login'])) {
			if ($_SESSION['failed_login'] >= 5) {
				$remainLogin = 0;
				$message = '<p class="txt-error">L&#39;indirizzo email e la password non corrispondono. Contatta l amministrazione a <a href="mailto:info@webevolution.eu">info@webevolution.eu</a></p>';
				$loginDisabled = true;
			} else {
				$remainLogin -= $_SESSION['failed_login'];
				$message = '<p class="txt-error">L&#39;indirizzo email e la password non corrispondono. Tentativi rimasti: '.$remainLogin.'</p>';
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta charset="UTF-8">
    	<title>WE ADMIN | Log in</title>
    	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="<?php echo $this->config->item('resources_css'); ?>/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('resources_css'); ?>/webevo-cms.css" rel="stylesheet">
        <link href="<?php echo $this->config->item('resources_css'); ?>/webevo-cms-responsive.css" rel="stylesheet">
    	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
 	</head>

  	<body class="login-body">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 text-center">
					<h1 class="text-muted login-txt-logo">WebEvolution</h1>
					<p class="text-muted">Version 1.1.0</p>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-3">
					<div class="panel panel-default">
						<div class="panel-body">
							<form role="form" action="<?php echo site_url('front/autenticate'); ?>" method="POST">
								<div class="row p-b-20">
									<div class="col-xs-12">
										<img class="login-logo" src="<?php echo $this->config->item('resources_img'); ?>/logo/logo.png" alt="">
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-user"></i>
												</span> 
												<input type="email" id="email" name="email" class="form-control" placeholder="Email" autofocus="true"/>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-lock"></i>
												</span>
												<input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
											</div>
										</div>
										<?php 
											if (isset($loginDisabled)) {
												echo $message;
											}
										?>
										<div class="form-group">
											<input type="submit" class="btn btn-lg btn-green btn-block" value="Accedi" 
											<?php 
												if ($loginDisabled == true) {
													echo 'disabled="true"';
												}
											?>/>
										</div>
									</div>
								</div>
							</form>
						</div>
		            </div>
				</div>
				<div class="col-xs-12 text-center">
					<p class="text-muted">© WebEvolution™ 2015-2016 - All rights reserved</p>
					<a class="link-social link-facebook" href="https://www.facebook.com/WebEvolutionOfficialPage/"><i class="fa fa-facebook"></i></a>
					<a class="link-social link-twitter" href="https://twitter.com/WebEvolution_E"><i class="fa fa-twitter"></i></a>
					<a class="link-social link-mail" href="mailto:info@webevolution.eu"><i class="fa fa-envelope"></i></a>
				</div>
			</div>
		</div>
    	<script src="<?php echo $this->config->item('resources_js') ?>/jQuery-2.2.3.min.js"></script>
    	<script src="<?php echo $this->config->item('resources_js') ?>/bootstrap.min.js" type="text/javascript"></script>
	</body>
</html>