<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
         	<div class="col-xs-12">
            	 <h1 class="page-header">
                     Produttori <small>Riepilogo</small>
                 </h1>
                 <ol class="breadcrumb">
                     <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                     <li class="active">Produttori</li>
                 </ol>
             </div>
         </div> 
         <div class="row">
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-briefcase sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Produttori</span>
         	            	<span class="data"><?php echo count($manufacturers); ?></span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-folder sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Campo Libero</span>
         	            	<span class="data">#</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-bag sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Campo Libero</span>
         	            	<span class="data">#</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-locked-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Campo Libero</span>
         	            	<span class="data">#</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         </div>
		
		 <form id="manufacturer" method="POST" action="">
         	<div class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-blue">
					<div class="box-header">
						<h3 class="box-title">Lista Produttori</h3>
						<span class="header-action pull-right">
							<i id="delete" class="ion ion-trash-a pointer"></i>
						</span>
						<span class="header-action pull-right">
							<i id="duplicate" class="ion ion-ios-copy pointer"></i>
						</span>
						<span class="header-action pull-right">
							<a id="add" data-toggle="modal" data-target="#addManufacturer" class="pointer">
								<i class="ion ion-plus-circled pointer"></i>
							</a>
						</span>
					</div>

					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped table-condensed table-manufacturers">
						    	<thead>
						        	<tr>
						        		<th></th>
						        		<th>Immagine</th>
						            	<th>Produttore</th>
						            	<th>Descrizione</th>
						            </tr>
						        </thead>
						        <?php if($manufacturers) : ?>
						        	<?php foreach ($manufacturers as $manufacturer) : ?>
								    	<tr>
								    		<td>
								    			<input type="checkbox" name="idManufacturer[]" value="<?php echo $manufacturer->idManufacturer ?>" />
								    		</td>
								    		<td class="btnModifyManufacturer" data-id="<?php echo $manufacturer->idManufacturer ?>" data-toggle="modal" data-target="#modifyManufacturer">
								    			<img src="<?php echo $this->config->item('resources_dynamic_img') ?>/manufacturers/<?php echo $manufacturer->photoName; ?>" alt="<?php echo ucfirst($manufacturer->manufacturerName); ?>">
								    		</td>
								    		<td class="btnModifyManufacturer" data-id="<?php echo $manufacturer->idManufacturer ?>" data-toggle="modal" data-target="#modifyManufacturer"><?php echo ucwords($manufacturer->manufacturerName); ?></td>
								    		<td class="btnModifyManufacturer" data-id="<?php echo $manufacturer->idManufacturer ?>" data-toggle="modal" data-target="#modifyManufacturer"><?php echo ucwords($manufacturer->manufacturerDescription); ?>
								    		</td>
								    	</tr>
						    		<?php endforeach; ?>
						    	</table>
					    	<?php else : ?>
					    	    </tbody>
					    	</table>
					    	<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun produttore presente</p></div>
					    	<?php endif; ?>
						</div>
					</div>
					<div class="box-footer pagination">
						<ul>
							<?php echo $pagination; ?>
						</ul>
					</div>
				</div>
			</div>	
		</div>
         </form>
		
		 <!-- Modal -->
		 <div id="addManufacturer" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title"><i class="fa fa-tags"></i> Creazione nuovo produttore</h4>
		      		</div>
		      		<form id="add-manufacturer-form" method="POST">
			      		<div class="modal-body">
							<div class="form-group">
								<label>Nome *</label>
							    <input class="form-control" name="manufacturerName" required="true">
							</div>
							
							<label>Descrizione *</label>
							<div class="form-group">
							    <textarea class="form-control" name="manufacturerDescription" rows="4"></textarea>
							</div>
	
							<div class="form-group">
							    <label>Galleria Immagini</label>
							    <div class="input-group">
							        <span class="input-group-btn">
							            <span class="btn btn-default btn-file">
							                Sfoglia 
							                <input type="file" name="cover" />
							            </span>
							        </span>
							        <input type="text" name="coverName" class="form-control bg-white" disabled="true">
							    </div>
							    <p class="help-block">Formato richiesto PNG oppure JPEG</p>
							</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn btn-blue">Inserisci</button>
			        		<button type="button" class="btn btn-blue" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>
		 <div id="modifyManufacturer" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Modifica produttore</h4>
		      		</div>
		      		<form id="modify-manufacturer-form" method="POST">
			      		<div class="modal-body">
			      			<input type="hidden" name="idManufacturer" />
			      			<div class="form-group">
								<label>Nome *</label>
							    <input class="form-control" name="manufacturerName" required="true">
							</div>
							
							<label>Descrizione *</label>
							<div class="form-group">
							    <textarea class="form-control" name="manufacturerDescription" required="true" rows="4"></textarea>
							</div>
	
							<div class="form-group">
							    <label>Galleria Immagini</label>
							    <div class="input-group">
							        <span class="input-group-btn">
							            <span class="btn btn-default btn-file">
							                Sfoglia 
							                <input type="file" name="cover" />
							            </span>
							        </span>
							        <input type="text" name="coverName" class="form-control bg-white" disabled="true">
							    </div>
							    <p class="help-block">Formato richiesto PNG oppure JPEG</p>
							</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn btn-blue">Salva</button>
			        		<button type="button" class="btn btn-blue btn-close" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		      		
		    	</div>
			</div>
		</div>	
	</div>
</div>