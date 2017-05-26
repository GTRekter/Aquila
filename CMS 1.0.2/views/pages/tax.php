<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
         	<div class="col-xs-12">
            	 <h1 class="page-header">
                     Tassazione <small>Riepilogo</small>
                 </h1>
                 <ol class="breadcrumb">
                     <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                     <li class="active">Tassazione</li>
                 </ol>
             </div>
         </div> 
         <div class="row">
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-yellow cl-white">
         	            		<i class="ion ion-ios-briefcase sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Tassazioni</span>
         	            	<span class="data"><?php echo count($tax); ?></span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-yellow cl-white">
         	            		<i class="ion ion-ios-folder sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">CAMPO LIBERO</span>
         	            	<span class="data">#</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
     			<div class="statistics-box">
     		    	<div class="row">
     		        	<div class="col-xs-5">
     		        		<div class="icon-box bg-yellow cl-white">
     		            		<i class="ion ion-ios-folder sz-50"></i>
     		            	</div>
     		            </div>
     		            <div class="col-xs-7 text-box">
     		            	<span class="title">CAMPO LIBERO</span>
     		            	<span class="data">#</span>
     		             </div>
     		        </div>
     		    </div>
     		</div>
         	<div class="col-lg-3 col-md-6">
     			<div class="statistics-box">
     		    	<div class="row">
     		        	<div class="col-xs-5">
     		        		<div class="icon-box bg-yellow cl-white">
     		            		<i class="ion ion-ios-folder sz-50"></i>
     		            	</div>
     		            </div>
     		            <div class="col-xs-7 text-box">
     		            	<span class="title">CAMPO LIBERO</span>
     		            	<span class="data">#</span>
     		             </div>
     		        </div>
     		    </div>
     		</div>
         </div>
		
		 <form id="tax" method="POST" action="">
         	<div class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-yellow">
					<div class="box-header">
						<h3 class="box-title">Lista Tassazioni</h3>
						<span class="header-action pull-right">
							<i id="delete" class="ion ion-trash-a pointer"></i>
						</span>
						<span class="header-action pull-right">
							<i id="duplicate" class="ion ion-ios-copy pointer"></i>
						</span>
						<span class="header-action pull-right">
							<a id="add" data-toggle="modal" data-target="#addTax" class="pointer">
								<i class="ion ion-plus-circled pointer"></i>
							</a>
						</span>
					</div>

					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped table-condensed table-tax">
						    	<thead>
						        	<tr>
						        		<th></th>
						            	<th>Tassazione</th>
						            	<th>Descrizione</th>
						            	<th>Percentuale</th>
						            </tr>
						        </thead>
						        <?php if($tax) : ?>
							        <?php for ($i = 0; $i < count($tax); $i++) : ?>
								    	<tr>
								    		<td>
								    			<input type="checkbox" name="idTax[]" value="<?php echo $tax[$i]->idTax ?>" />
								    		</td>
								    		<td class="btnModifyTax" data-id="<?php echo $tax[$i]->idTax ?>" data-toggle="modal" data-target="#modifyTax"><?php echo ucwords($tax[$i]->taxName); ?></td>
								    		<td class="btnModifyTax" data-id="<?php echo $tax[$i]->idTax ?>" data-toggle="modal" data-target="#modifyTax"><?php echo ucwords($tax[$i]->taxDescription); ?>
								    		</td>
								    		<td class="btnModifyTax" data-id="<?php echo $tax[$i]->idTax ?>" data-toggle="modal" data-target="#modifyTax"><?php echo ucwords($tax[$i]->taxValue); ?> %
								    		</td>
								    	</tr>
							    	<?php endfor; ?>
							    </table>
						    <?php else : ?>
						        </tbody>
						    </table>
						    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna classe di tassazione presente</p></div>
						    <?php endif; ?>
						</div>
					</div>
				</div>
			</div>	
		</div>
         </form>
		
		<!-- Modal -->
		<div id="addTax" class="modal fade modal-yellow" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Creazione nuova tassazione</h4>
		      		</div>
		      		<form id="add-tax-form">
			      		<div class="modal-body">

							<div class="form-group">
								<label>Nome *</label>
							    <input class="form-control" name="taxName" required="true">
							</div>
							
							<label>Descrizione *</label>
							<div class="form-group">
							    <textarea class="form-control" name="taxDescription" required="true" rows="4"></textarea>
							</div>
							
							<label>Percentuale *</label>
							<div class="form-group input-group">
								<input class="form-control" name="taxValue" required="true" placeholder="NN" type="text">
								<span class="input-group-addon">%</span>
							</div>

			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn btn-yellow">Inserisci</button>
			        		<button type="button" class="btn btn-yellow" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>
		
		<div id="modifyTax" class="modal fade modal-yellow" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Modifica tassazione</h4>
		      		</div>
		      		<form id="modify-tax-form">
			      		<div class="modal-body">
			      			<input type="hidden" name="idTax" />
			      			<div class="form-group">
			      				<label>Nome *</label>
			      			    <input class="form-control" name="taxName" required="true">
			      			</div>
			      			
			      			<label>Descrizione *</label>
			      			<div class="form-group">
			      			    <textarea class="form-control" name="taxDescription" required="true" rows="4"></textarea>
			      			</div>
			      			
			      			<label>Percentuale *</label>
			      			<div class="form-group input-group">
			      				<input class="form-control" name="taxValue" required="true" placeholder="NN" type="text">
			      				<span class="input-group-addon">%</span>
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