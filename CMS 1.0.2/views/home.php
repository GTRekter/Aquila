<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
        	<div class="col-lg-12">
            	<h1 class="page-header">
                	Dashboard <small>Statistiche Generali</small>
                 </h1>
             </div>
         </div>       
		 <div class="row">
         	<div class="col-lg-3 col-md-6">
            	<div class="statistics-box">
                	<div class="row">
                    	<div class="col-xs-5">
                    		<div class="icon-box bg-green cl-white">
                        		<i class="ion ion-ios-box-outline sz-50"></i>
                        	</div>
                        </div>
                        <div class="col-xs-7 text-box">
                        	<span class="title">Prodotti</span>
                        	<span class="data"><?php echo count($products) ?></span>
                         </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
            	<div class="statistics-box">
                	<div class="row">
                    	<div class="col-xs-5">
                    		<div class="icon-box bg-green cl-white">
                        		<i class="ion ion-ios-cart-outline sz-50"></i>
                        	</div>
                        </div>
                        <div class="col-xs-7 text-box">
                        	<span class="title">Ordini</span>
                        	<span class="data"> <?php echo count($orders); ?></span>
                         </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
            	<div class="statistics-box">
                	<div class="row">
                    	<div class="col-xs-5">
                    		<div class="icon-box bg-green cl-white">
                        		<i class="ion ion-ios-people-outline sz-50"></i>
                        	</div>
                        </div>
                        <div class="col-xs-7 text-box">
                        	<span class="title">Clienti</span>
                        	<span class="data"><?php echo count($clients); ?></span>
                         </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
            	<div class="statistics-box">
                	<div class="row">
                    	<div class="col-xs-5">
                    		<div class="icon-box bg-green cl-white">
                        		<i class="ion ion-ios-pricetag-outline sz-50"></i>
                        	</div>
                        </div>
                        <div class="col-xs-7 text-box">
                        	<span class="title">Articoli</span>
                        	<span class="data"><?php echo count($articles); ?></span>
                         </div>
                    </div>
                </div>
            </div>
         </div>
		 
		 <div class="row">
			<div class="col-xs-12">
				<div class="box internal-box brd-green">
					<div class="box-header with-border">
						<h3 class="box-title">Rapporto annuale</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-action="collapse"><i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-action="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<p class="text-center">
								    <strong>Ordini/Clienti: 1 Gen, <?php echo date("Y"); ?> - 30 Dec, <?php echo date("Y"); ?></strong>
								</p>
								<div class="chart">
								    <canvas id="salesChart" style="height: 250px;"></canvas>
								</div>
							</div>
							<div class="col-md-4">
								<p class="text-center">
							    	<strong>Altre informazioni</strong>
							    </p>
								<div class="progress-group">
									<?php 
										$convertions = array();
										if (count($orders) != 0 && count($carts) != 0) {
											$_temp = (100/(count($orders) + count($carts)))*count($orders);
										} else {	
											$_temp = 0;
										}
										array_push($convertions,$_temp);
									?>
							    	<span class="progress-text">Conversioni Carrelli/Ordini</span>
							        <span class="progress-number"><b><?php echo round($convertions[0],0); ?></b>/100</span>
									<div class="progress">
										
							        	<div class="progress-bar bg-green" style="width: <?php echo $convertions[0]; ?>%"></div>
							        </div>
							    </div>
							    <div class="progress-group">
							    	<span class="progress-text">Campo libero</span>
							        <span class="progress-number"><b>20</b>/100</span>
							    	<div class="progress">
							        	<div class="progress-bar bg-green" style="width: 20%"></div>
							        </div>
							    </div>
							    <div class="progress-group">
							    	<span class="progress-text">Campo libero</span>
							        <span class="progress-number"><b>30</b>/100</span>
							    	<div class="progress">
							        	<div class="progress-bar bg-green" style="width: 30%"></div>
							        </div>
							    </div>
							    <div class="progress-group">
							    	<span class="progress-text">Campo libero</span>
							        <span class="progress-number"><b>40</b>/100</span>
							    	<div class="progress">
							        	<div class="progress-bar bg-green" style="width: 40%"></div>
							        </div>
							    </div>

							</div>
						</div>
					</div>
				</div>
			</div>
		 </div>
		
		
		 <div class="row">
            <div class="col-lg-4">
            	<div class="box internal-box brd-green">
            		<div class="box-header with-border">
            			<h3 class="box-title">Chat Pubblica</h3>
            			<div class="box-tools pull-right">
            				<button type="button" class="btn btn-box-tool" data-action="collapse"><i class="fa fa-minus"></i></button>
            				<button type="button" class="btn btn-box-tool" data-action="remove"><i class="fa fa-times"></i></button>
            			</div>
            		</div>
            		<div class="box-body">
            			<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;">
            					<div class="box-body chat" id="chat-box" style="overflow: scroll; width: auto; height: 250px;"></div>
            				</div>
            			<form id="formMessage" action="">
            			    <div class="input-group">
            			    	<input type="text" name="messageText" class="form-control" placeholder="Scrivi il messaggio..." required />
            			        <div class="input-group-btn">
            			            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
            			        </div>
            			    </div>
            			</form>
            		</div>
            	</div>
            </div>
            
            <div class="col-lg-8">
            	<div class="box internal-box brd-green">
            		<div class="box-header with-border">
            			<h3 class="box-title">Agenda</h3>
            			<div class="box-tools pull-right">
            				<button type="button" class="btn btn-box-tool" data-action="collapse"><i class="fa fa-minus"></i></button>
            				<button type="button" class="btn btn-box-tool" data-action="remove"><i class="fa fa-times"></i></button>
            			</div>
            		</div>
            		<div class="box-body">
            			<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;">
        					<ul class="todo-list ui-sortable" style="overflow: scroll; width: auto; height: 250px;"></ul>
        				</div>
        				<form id="formCommitment" action="">
        				    <div class="input-group">
        				    	<input type="text" name="commitmentText" class="form-control" maxlength="30" placeholder="Scrivi l'impegno..." required />
        				        <div class="input-group-btn">
        				            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
        				        </div>
        				    </div>
        				</form>
            		</div>
            	</div>
            </div>
         </div>

		 <div class="row">	
            <div class="col-lg-12">
            	<div class="box internal-box brd-green">
            		<div class="box-header with-border">
            			<h3 class="box-title">Ultimi Prodotti Inseriti </h3>
            			<div class="box-tools pull-right">
            				<button type="button" class="btn btn-box-tool" data-action="collapse"><i class="fa fa-minus"></i></button>
            				<button type="button" class="btn btn-box-tool" data-action="remove"><i class="fa fa-times"></i></button>
            			</div>
            		</div>
            		<div class="box-body">
            			<div class="table-responsive">
            				<table class="table table-hover table-striped table-condensed table-product">
            					<thead>
            						<tr>
            							<th></th>
            					    	<th>Immagine</th>
            					    	<th>Titolo</th>
            					    	<th>Produttore</th>
            					        <th>Codici</th>
            					        <th>Categoria</th>
            					        <th>Prezzo</th>
            					        <th>Sconto</th>
            					    </tr>
            					</thead>
            					<tbody>
            					<?php count($products) > 4 ? $nProduct = 5 : $nProduct = count($products); ?>
            				    <?php if($products) : ?>
            				    	<?php for ($i = 0; $i < $nProduct; $i++) : ?>
            					    	<tr>
            					    		<td>
            					    			<input type="checkbox" name="idProduct[]" value="<?php echo $products[$i]->idProduct; ?>" />
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            					    			<img src="<?php echo $this->config->item('resources_img'); ?>/products/extra_small/<?php echo $products[$i]->photoName; ?>" alt="<?php echo ucfirst($products[$i]->productName); ?>">
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            					    			<?php echo ucfirst($products[$i]->productName); ?>
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            					    			<?php echo ucfirst($products[$i]->manufacturerName); ?>
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            						    		<?php 
            						    			if($products[$i]->productSKU != null) { echo 'SKU'; }
            						    			if($products[$i]->productEAN != null) { echo 'EAN-13 '; } 
            						    			if($products[$i]->productEAN == null && $products[$i]->productSKU == null) { echo 'Nessun codice presente'; } 
            						    		?>  
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            					    			<?php echo $products[$i]->categoryName; ?>
            					    		</td>
            					    		<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">
            					    			<?php echo $products[$i]->productPrice; ?> €
            					    		</td>
            					        	<td href="<?php echo site_url('back/product/'.$products[$i]->idProduct); ?>" class="pointer">N.D
            					        	</td>
            					    	</tr>
            					    	
            						<?php endfor; ?>
            					</tbody>
            				</table>
            				<?php else : ?>
            				    </tbody>
            				</table>
            				<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun prodotto presente</p></div>
            				<?php endif; ?>
            			</div>
            		</div>
            	</div>
            </div>
         </div>
	</div>
</div>