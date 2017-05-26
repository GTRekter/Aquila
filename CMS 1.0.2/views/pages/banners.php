<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Banner <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Banner</li>
		        </ol>
		    </div>
		</div> 
			
         
        <div id="modifyBanner" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-red">
					<div class="box-header">
						<h3 class="box-title">Lista Banner</h3>
					</div>
					
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped table-condensed table-banner">
						    	<thead>
						        	<tr>
						        		<th>Immagine</th>
						        		<th>Titolo</th>
							        	<th>Descrizione</th>
							        	<th>Dimensioni</th>
						            </tr>
						        </thead>
						        <?php if($banners) : ?>
						        	<?php foreach ($banners as $banner) : ?>
								    	<tr>
								    		<td class="lineBannerDetail" data-id="<?php echo $banner->idBanner; ?>">
								    			<img src="<?php echo $this->config->item('resources_dynamic_img') ?>/banners/<?php echo $banner->photoName; ?>" alt="<?php echo ucfirst($banner->bannerName); ?>">
								    		</td>
								    		<td class="lineBannerDetail" data-id="<?php echo $banner->idBanner; ?>">
								    			<?php echo ucfirst($banner->bannerName); ?>
								    		</td>
								    		<td class="lineBannerDetail" data-id="<?php echo $banner->idBanner; ?>">
								    			<?php echo ucfirst($banner->bannerDescription); ?>
								    		</td>
								    		<td class="lineBannerDetail" data-id="<?php echo $banner->idBanner; ?>">
								    			<?php echo $banner->photoWidth.' x '.$banner->photoHeight; ?>
								    		</td>
								    	</tr>
						    		<?php endforeach; ?>
						    	</table>
						    <?php else : ?>
						        </tbody>
						    </table>
						    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun banner presente</p></div>
						    <?php endif; ?>
					    </div>
				    </div>
				</div>	
			</div>	
		</div>

		<div id="banner-detail" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-red">
					<div class="box-header">
						<h3 class="box-title">Informazioni Banner <span id="invoice-order"></span></h3>
					</div>
					<form id="banner-detail-form" method="post" action="">
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<p>In questa sezione sono visualizzate tutte le informazioni relative al banner selezionato. Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione del banner. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
								</div>
								
								<div class="col-xs-12">
									<ul class="nav nav-tabs nav-red" role="tablist"></ul>
									<div class="tab-content"></div>
								</div>
								<div class="col-xs-12 p-t-25">
									<div class="row">	
										<div class="col-md-4">
											<div class="gallery"></div>
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<label>URL di destinazione</label>
												<input type="text" class="form-control" name="bannerURL" placeholder="http://"/>
											</div>
											
											<div class="form-group">
											    <label>Galleria Immagini</label>
											    <div class="input-group">
											        <span class="input-group-btn">
											            <span class="btn btn-default btn-file">
											                Sfoglia 
											                <input name="idBanner" type="hidden">
											                <input name="files[]" type="file">
											            </span>
											        </span>
											        <input type="text" class="form-control bg-white" disabled="true">
											    </div>
											    <p class="help-block">Formato richiesto PNG oppure JPEG</p>
											</div>
											
										</div>
									</div>    
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
						    		<button type="reset" class="btn btn-default">Cancella</button>
						    		<button type="submit" class="btn btn-default btn-red">Salva</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
</div>
