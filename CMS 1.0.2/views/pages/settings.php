<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Impostazioni <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Impostazioni</li>
		        </ol>
		    </div>
		</div> 

         <div class="row">
         	<form id="modifySettings" method="post" action="">	
				<div class="col-md-12">
				
					<div class="box internal-box brd-yellow">
						<div class="box-header">
							<h3 class="box-title">Informazioni Negozio</h3>
						</div>
						<div class="box-body">
					        <div class="row">
						        <div class="col-md-6">
									<div class="form-group">
										<label>Nome Negozio </label>
										<input type="text" class="form-control" name="shopName" value="<?php echo ucwords($settings->shopName); ?>">
									</div>
									<div class="form-group">
										<label>Indirizzo Negozio </label>
										<input type="text" class="form-control" name="shopAddress" value="<?php echo ucwords($settings->shopAddress); ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Telefono Negozio </label>
										<input type="text" class="form-control" name="shopPhone" value="<?php echo ucwords($settings->shopPhone); ?>">
									</div>
									<div class="form-group">
										<label>Email Negozio </label>
										<input type="text" class="form-control" name="shopEmail" value="<?php echo ucwords($settings->shopEmail); ?>">
									</div>
								</div>
							</div>
				        </div>
				        <div class="box-header">
				        	<h3 class="box-title">Traduttore Bing</h3>
				        </div>
				        <div class="box-body">
				            <div class="row">
				                <div class="col-md-6">
				        			<div class="form-group">
				        				<label>ID cliente </label>
				        				<input type="text" class="form-control" name="googleClientID" value="<?php echo ucwords($settings->googleClientID); ?>">
				        			</div>
				        			<div class="form-group">
				        				<label>Codice segreto del cliente</label>
				        				<input type="text" class="form-control" name="googleClientSecret" value="<?php echo ucwords($settings->googleClientSecret); ?>">
				        			</div>
				        			<div class="form-group">
				        				<label>URL di autenticazione</label>
				        				<input type="text" class="form-control" name="googleAuthURL" value="<?php echo ucwords($settings->googleAuthURL); ?>">
				        			</div>
				        		</div>
				        		<div class="col-md-6">
				        			<?php
					        			$languages = explode( ',', $settings->shopLanguages );
					        			array_unshift($languages, "it");
				        			?>
				        			<div class="row">
				        				<div class="col-sm-4">
						        			<div class="form-group">
						        				<label>Lingue</label>
						        			    	<div class="checkbox">
						        					<label>
						        			            <input type="checkbox" name="shopLanguages[]" value="ar" <?php echo (in_array("ar", $languages) == true ? 'checked="true"' : '') ?>>Arabo
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			        <label>
						        			            <input type="checkbox" name="shopLanguages[]" value="ca" <?php echo (in_array("ca", $languages) == true ? 'checked="true"' : '') ?>>Catalano
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			    	<label>
						        			            <input type="checkbox" name="shopLanguages[]" value="cs" <?php echo (in_array("cs", $languages) == true ? 'checked="true"' : '') ?>>Ceco
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			        <label>
						        			            <input type="checkbox" name="shopLanguages[]" value="zh-CN" <?php echo (in_array("zh-CN", $languages) == true ? 'checked="true"' : '') ?>>Cinese
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			        <label>
						        			            <input type="checkbox" name="shopLanguages[]" value="ko" <?php echo (in_array("ko", $languages) == true ? 'checked="true"' : '') ?>>Coreano
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			        <label>
						        			            <input type="checkbox" name="shopLanguages[]" value="iw" <?php echo (in_array("iw", $languages) == true ? 'checked="true"' : '') ?>>Ebraico
						        			        </label>
						        			    </div>
						        			    <div class="checkbox">
						        			        <label>
						        			            <input type="checkbox" name="shopLanguages[]" value="et" <?php echo (in_array("et", $languages) == true ? 'checked="true"' : '') ?>>Estone
						        			        </label>
						        			    </div>
						        			</div>
				        				</div>
				        				<div class="col-sm-4">
				        					<div class="form-group">
				        						<label class="hidden-xs">&nbsp;</label>
				        					    
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="fr" <?php echo (in_array("fr", $languages) == true ? 'checked="true"' : '') ?>>Francese
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					    	<label>
				        					            <input type="checkbox" name="shopLanguages[]" value="ja" <?php echo (in_array("ja", $languages) == true ? 'checked="true"' : '') ?>>Giapponese
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="el" <?php echo (in_array("el", $languages) == true ? 'checked="true"' : '') ?>>Greco
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="en" <?php echo (in_array("en", $languages) == true ? 'checked="true"' : '') ?>>Inglese
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" checked="true" disabled="true" value="it">Italiano
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="id" <?php echo (in_array("id", $languages) == true ? 'checked="true"' : '') ?>>Indonesiano
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="ms" <?php echo (in_array("ms", $languages) == true ? 'checked="true"' : '') ?>>Malese
				        					        </label>
				        					    </div>
				        					</div>
				        				</div>
				        				<div class="col-sm-4">
				        					<div class="form-group">
				        						<label class="hidden-xs">&nbsp;</label>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="nl" <?php echo (in_array("nl", $languages) == true ? 'checked="true"' : '') ?>>Olandese
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="pl" <?php echo (in_array("pl", $languages) == true ? 'checked="true"' : '') ?>>Polacco
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="pt" <?php echo (in_array("pt", $languages) == true ? 'checked="true"' : '') ?>>Portoghese
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="ru" <?php echo (in_array("ru", $languages) == true ? 'checked="true"' : '') ?>>Russo
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="es" <?php echo (in_array("es", $languages) == true ? 'checked="true"' : '') ?>>Spagnolo
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="de" <?php echo (in_array("des", $languages) == true ? 'checked="true"' : '') ?>>Tedesco
				        					        </label>
				        					    </div>
				        					    <div class="checkbox">
				        					        <label>
				        					            <input type="checkbox" name="shopLanguages[]" value="tr" <?php echo (in_array("tr", $languages) == true ? 'checked="true"' : '') ?>>Turco
				        					        </label>
				        					    </div>
				        					</div>
				        				</div>
				        			</div>
				        		</div>
				        	</div>
				        </div>
				        <div class="box-footer">
				        	<div class="row">
				        		<div class="col-xs-12">
						        	<div class="pull-right">
						        		<button type="submit" class="btn btn-yellow">Salva le impostazioni</button>
						        	</div>
					        	</div>
				        	</div>
				        </div>
				    </div>
				</div>
         	</form>
		</div>
	</div>
</div>
