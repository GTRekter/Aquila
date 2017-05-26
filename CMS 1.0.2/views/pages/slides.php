<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">		
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Slides <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Slides</li>
		        </ol>
		    </div>
		</div> 	
		
        <form id="modifySlide" method="POST" action="">  
			<div class="row">
				<div class="col-md-12">
					<div class="box internal-box slides-box brd-red">
						<div class="box-header">
							<h3 class="box-title">Lista Slides</h3>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy pointer"></i>
							</span>
							<span class="header-action pull-right">
								<a class="addSlide" href="<?php echo site_url('back/n_slide'); ?>"> <i class="ion ion-plus-circled"></i></a>
							</span>
						</div>
						
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-slides">
							    	<thead>
							        	<tr>
							        		<th></th>
								        	<th>Immagine</th>
							            	<th>Titolo</th>
							            	<th>Descrizione</th>
							            </tr>
							        </thead>
							        <?php if($slides) : ?>
							        	<?php foreach ($slides as $slide) : ?>
									    	<tr>
									    		<td>
									    			<input type="checkbox" name="idSlide[]" value="<?php echo $slide->idSlide; ?>" />
									    		</td>
									    		<td class="lineSlideDetail" data-id="<?php echo $slide->idSlide ?>">
									    			<img src="<?php echo $this->config->item('resources_dynamic_img') ?>/slides/<?php echo $slide->photoName; ?>" alt="<?php echo ucfirst($slide->slideName); ?>">
									    		</td>
									    		<td class="lineSlideDetail" data-id="<?php echo $slide->idSlide ?>">
									    			<?php echo ucfirst($slide->slideName); ?>
									    		</td>
									    		<td class="lineSlideDetail" data-id="<?php echo $slide->idSlide ?>">
									    			<?php echo ucfirst($slide->slideDescription); ?>
									    		</td>
									    	</tr>
							    		<?php endforeach; ?>
							    	</table>
							    <?php else : ?>
							        </tbody>
							    </table>
							    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna slide presente</p></div>
							    <?php endif; ?>
						    </div>
					    </div>
					</div>	
				</div>	
			</div>
		</form>
		<div id="slide-detail" class="row">
			<div class="col-md-12">
				<div class="box internal-box slides-box brd-red">
					<div class="box-header">
						<h3 class="box-title">Informazioni Slide </h3>
					</div>
					<form id="slide-detail-form" method="post" action="">
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<p>In questa sezione sono visualizzate tutte le informazioni relative alla slide selezionata. Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione della slide. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
								</div>
								
								<div class="col-xs-12">
									<ul class="nav nav-tabs nav-red" role="tablist"></ul>
									<div class="tab-content"></div>
									
									<div class="row p-t-25">	
										<div class="col-md-4">
											<div class="gallery"></div>
										</div>
										<div class="col-md-8">
											<div class="form-group">
											    <label>Galleria Immagini</label>
											    <div class="input-group">
											        <span class="input-group-btn">
											            <span class="btn btn-default btn-file">
											                Sfoglia 
											                <input type="hidden" name="idSlide" />
											                <input type="file" name="files[]" />
											            </span>
											        </span>
											        <input type="text" name="photoName" class="form-control bg-white" disabled="true">
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
