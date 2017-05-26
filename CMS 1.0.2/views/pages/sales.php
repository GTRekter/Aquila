<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
         <div class="row">
         	<div class="col-xs-12">
            	 <h1 class="page-header">
                	Sconti <small>Riepilogo</small>
                 </h1>
                 <ol class="breadcrumb">
                     <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                     <li class="active">Sconti</li>
                 </ol>
             </div>
         </div> 
         <div class="row">
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-box-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Sconti</span>
         	           		<span class="data">N.D</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-pricetags-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Media Sconti</span>
         	            	<span class="data">N.D</span>
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
         	            	<span class="title">Box Libero</span>
         	            	<span class="data">N.D</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-social-euro-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Box Libero</span>
         	            	<span class="data">N.D</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         </div>	
		
		 <form id="modifySale" method="POST" action="">
         	<div class="row">
				<div class="col-md-12">
					<div class="box internal-box articles-box brd-blue">
						<div class="box-header">
							<h3 class="box-title">Lista Sconti</h3>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy pointer"></i>
							</span>
							<span class="header-action pull-right">
								<a href="<?php echo site_url('back/n_sale'); ?>">
									<i class="ion ion-plus-circled"></i>
								</a>
							</span>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-sales">
							    	<thead>
							        	<tr>
							        		<th></th>
							            	<th>Sconto</th>
							            </tr>
							        </thead>
							        <tbody>
							        	<?php if($sales) : ?>
									        <?php for ($i = 0; $i < count($sales); $i++) : ?>
									        	<tr>
									        		<td>
									        			<input type="checkbox" name="idSale[]" value="<?php echo $sales[$i]->idSale; ?>" />
									        		</td>
									        		<td class="lineSaleDetail" data-id="<?php echo $sales[$i]->idSale; ?>">
									        			<?php 
									        				if ($sales[$i]->salePercentage) {
									        					echo $sales[$i]->salePercentage.' %';
									        				} else {
									        					echo $sales[$i]->saleAmount.' €';
									        				} ?>
									        		</td>
									        	</tr>
									        <?php endfor; ?>
								        </tbody>
								    </table>
								<?php else : ?>
										</tbody>
									</table>
								<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuno sconto presente</p></div>
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
         <div id="sale-detail" class="row">
         	<div class="col-md-12">
         		<div class="box internal-box client-box brd-blue">
         			<div class="box-header">
         				<h3 class="box-title">Informazioni Sconto <span id="invoice-order"></span></h3>
         			</div>
         			<div class="box-body">   			
         				<form id="modifySaleDetail" method="post">
		     				<div class="row">
								<div class="col-md-6">
									<label>Percentuale di sconto</label>
									<div class="form-group input-group">
										<input type="text" class="form-control" name="salePercentage" placeholder="NNNN.DD" required="true">
										<span class="input-group-addon">%</span>
									</div>
								</div>
					      		<div class="col-md-6">
					      			<label>Ammontare sconto</label>
					      			<div class="form-group input-group">
					      				<input type="text" class="form-control" name="saleAmount" placeholder="NNNN.DD" required="true">
					      				<span class="input-group-addon">€</span>
					      			</div>
					      		</div>
				      		</div>
				    	 	<div class="row p-b-35">
				    	 		<div class="col-xs-12 p-b-35">
			    	 				<p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente. Per associare/disassociare un prodotto allo sconto è sufficiente cliccare sulla checkbox sul lato sinistro della riga in questione, il sistema aggiornerà automaticamente l'associazione.</p>
			    	 				<p><strong>N.B</strong> Le informazioni relative allo sconto <strong>NON</strong> verrano perse durante il procedimento.</strong></p>
			    	 			</div>
			    	 			<div class="col-xs-12">
			    	 			    <ul class="nav nav-tabs nav-blue">
			 						    <li><a id="newSaleTab" class="pointer"><i class="ion ion-plus"></i></a></li>
			 						</ul>
			 						<div class="tab-content"></div>
			    	 			</div>
				    	 	</div>
				    	 	<div class="row">
		    	 				<div class="col-xs-12">
		    	 					<div class="pull-right">
			    	 					<button type="reset" class="btn btn-default">Cancella</button>
			    	 					<button type="submit" class="btn btn-default btn-blue">Salva</button>
		    	 					</div>
		    	 				</div>
		    	 			</div>
    		    	 	</form>
         			</div>
         		</div>
         	</div>	
         </div>	
	</div>
</div>