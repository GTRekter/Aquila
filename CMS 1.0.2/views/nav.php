<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<body>
	<div id="wrapper">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url('back/index'); ?>">
                	<img class="img-responsive" src="<?php echo $this->config->item('resources_img'); ?>/logo/favicon-16x16.png" />
                	WebEvolution <span class="text-version text-muted"> 1.1.0</span>
                </a>
            </div>
            <ul class="nav navbar-right top-nav">
            	<li class="dropdown notifications-dropdown">
            		<a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
            			<i class="fa fa-bell-o"></i> <span class="label count-notifications"></span>
            		</a>
            		<ul class="dropdown-menu">
            			<li class="header">Ci sono <span class="count-notifications"></span> notifiche</li>
            			<li>
            				<ul class="notifications" style="overflow: hidden; width: 100%; height: 200px;">
            					<li>
				                    <a href="<?php echo site_url('back/clients'); ?>">
				                        <i class="fa fa-users"></i> 5 Clienti registrati oggi
				                    </a>
			                    </li>
			                    <li>
                                    <a href="<?php echo site_url('back/orders'); ?>">
                                        <i class="fa fa-shopping-cart"></i> 5 Ordini creati oggi
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('back/products'); ?>">
                                        <i class="fa fa-book"></i> 25 Prodotti inseriti oggi
                                    </a>
                                </li>
            				</ul>
            			</li>
            		</ul>
            	</li>
				<li class="dropdown users-dropdown">
					<a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-users"></i> <span class="label count-online-users"></span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">Ci sono <span class="count-online-users"></span> utenti attivi</li>
						<li>
							<div style="position: relative; overflow: hidden; width: auto; height: 200px;" class="slimScrollDiv">
								<ul class="online-users" style="overflow: hidden; width: 100%; height: 200px;"></ul>
							</div>
						</li>
					</ul>
				</li>
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    	<i class="flaticon-user65"></i> <?php echo $this->session->accessName; ?> <b class="caret"></b>
                    </a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="http://placehold.it/50/f9f9f9/fff&text=<?php echo substr($this->session->accessName,0,1); ?>" class="img-circle" alt="User Image">
							<p><?php echo $this->session->accessName; ?> - Amministratore
								<small>Registrato da Feb. 2016</small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="#" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="push-right text-right">
								<a href="<?php echo site_url('back/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
	                <li class="nav-green"><a href="<?php echo site_url('back'); ?>"> <i class="flaticon-piegraph"></i> Dashboard</a></li>
                    <li class="header hidden-xs">Negozio</li>
                    <li class="nav-blue">
                    	<a href="javascript:;" data-toggle="collapse" data-target="#nav_products">
                    		<i class="flaticon-read1"></i>  Prodotti </a>
                    	<ul id="nav_products" class="collapse">	
                    		<li><a href="<?php echo site_url('back/categories'); ?>"></i> Categorie</a></li>
                    		<li><a href="<?php echo site_url('back/attributes'); ?>"> Attributi</a></li>
                    		<li><a href="<?php echo site_url('back/features'); ?>"> Caratteristiche</a></li>
                    		<li><a href="<?php echo site_url('back/manufacturers'); ?>"></i> Produttori</a></li>
                    		<li><a href="<?php echo site_url('back/products'); ?>"></i> Prodotti</a></li>
                    	</ul>
                    </li>
                    <li class="nav-blue">
                        <a href="<?php echo site_url('back/sales'); ?>"> Sconti </a>
                    </li>
                    <li class="nav-blue">
                        <a href="<?php echo site_url('back/orders'); ?>"> Ordini </a>
                    </li>
                    <li class="nav-blue">
                        <a href="<?php echo site_url('back/clients'); ?>"> Clienti </a>
                    </li>
					<li class="header hidden-xs">Personalizzazione</li>
					<li class="nav-red">
					    <a href="<?php echo site_url('back/banners'); ?>"> Banner </a>
					</li>
					<li class="nav-red">
					    <a href="<?php echo site_url('back/slides'); ?>"> Slides </a>
					</li>
					<li class="nav-red">
					    <a href="javascript:;" data-toggle="collapse" data-target="#nav_articles">
					    	<i class="flaticon-read1"></i>  News </a>
					    <ul id="nav_articles" class="collapse">
					    	<li><a href="<?php echo site_url('back/articles_categories'); ?>"> Categorie </a></li>
					    	<li><a href="<?php echo site_url('back/articles'); ?>"> News </a></li>
					    </ul>
					</li>
					<li class="nav-red">
					    <a href="javascript:;" data-toggle="collapse" data-target="#socialNetwork">
					    	<i class="flaticon-twitter42"></i> Social Network 
					    </a>
					    <ul id="socialNetwork" class="collapse">
					        <li><a href="https://www.facebook.com/Latteria-Sociale-Beduzzo-Inferiore-1671775039732463/" target="_blank">Facebook</a></li>
					        <li><a href="https://twitter.com/Fashion_Lux_IT" target="_blank">Twitter</a></li>
					        <li><a href="https://plus.google.com/u/0/b/118306613824077094539/118306613824077094539" target="_blank">Google Plus</a></li>
					    </ul>
					</li>
					<li class="nav-red">
					    <a href="<?php echo site_url('back/pages'); ?>">
					    	<i class="flaticon-cloud79"></i>  Pagine Personalizzate 
					    </a>
					</li>
					<li class="header hidden-xs">Funzionalità</li>
					<li class="nav-yellow">
					    <a href="<?php echo site_url('back/export'); ?>">Esportazione</a>
					</li>
					<li class="nav-yellow">
						<a href="<?php echo site_url('back/tax'); ?>">Tassazioni</a>
					</li>
					<li class="nav-yellow">
					    <a data-target="#settings" data-toggle="collapse" href="javascript:;" class="collapsed" aria-expanded="false">Impostazioni</a>
					    <ul class="collapse" id="settings" aria-expanded="false">
					    	<li>
					    	    <a href="<?php echo site_url('back/settings'); ?>">Generali</a>
					    	</li>
					    	<li>
					    	    <a href="<?php echo site_url('back/countries'); ?>">Nazioni</a>
					    	</li>
					        <li>
					            <a href="<?php echo site_url('back/currencies'); ?>">Valute</a>
					        </li>
					        <li>
					            <a href="<?php echo site_url('back/couriers'); ?>">Corrieri</a>
					        </li>
					    </ul>
					</li>
					<li class="header hidden-xs">Marketplace</li>
					<li class="nav-pink">
						<a href="javascript:;" data-toggle="collapse" data-target="#nav_ebay">eBay</a>
						<ul id="nav_ebay" class="collapse">	
							<li><a href="<?php echo site_url('back/ebay_settings'); ?>"></i> Impostazioni</a></li>
							<li><a href="<?php echo site_url('back/ebay_synchronization'); ?>">Sincronizzazione</a></li>
						</ul>
					</li>
                </ul>
            </div>
        </nav>