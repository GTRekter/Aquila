<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Prodotti <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Prodotti</li>
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
			            	<span class="title">Prodotti</span>
			            	<span class="data"><?php echo $total_products ?></span>
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
			            	<span class="title">Prodotti Disattivati</span>
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
			            	<span class="title">Prezzo Medio</span>
			            	<span class="data"><?php echo $average_product_price; ?> €</span>
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
			            	<span class="title">BLOCCO LIBERO</span>
			            	<span class="data">#</span>
			             </div>
			        </div>
			    </div>
			</div>
		</div>	
		<form id="modifyProduct" method="POST" action="">
	        <div class="row">
				<div class="col-md-12">
					<div class="box internal-box product-box brd-blue">
						<div class="box-header">
							<h3 class="box-title">Lista Prodotti<span class="badge"><?php echo count($products); ?></span></h3>
							<span class="header-action pull-right">
								<i id="search" class="ion ion-search pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy pointer"></i>
							</span>
							<span class="header-action pull-right">
								<a href="<?php echo site_url('back/n_product'); ?>">
									<i class="ion ion-plus-circled"></i>
								</a>
							</span>
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
								        <?php if($products) : ?>
								        	<?php foreach ($products as $product) : ?>
										    	<tr>
										    		<td>
										    			<input type="checkbox" name="idProduct[]" value="<?php echo $product->idProduct; ?>" />
										    		</td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>">
										    			<img src="<?php echo $this->config->item('resources_img') ?>/products/extra_small/<?php echo $product->photoName; ?>" alt="<?php echo ucfirst($product->productName); ?>">
										    		</td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>"><?php echo ucfirst($product->productName); ?></td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>"><?php echo ucfirst($product->manufacturerName); ?></td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>">
										    		<?php 
										    			if($product->productSKU != null) { echo 'SKU'; }
										    			if($product->productEAN != null) { echo 'EAN-13 '; } 
										    			if($product->productEAN == null && $product->productSKU == null) { echo 'Nessun codice presente'; } 
										    		?> 
										    		</td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>"><?php echo $product->categoryName; ?></td>
										    		<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>"><?php echo $product->productPrice; ?> €</td>
										        	<td href="<?php echo site_url('back/product/'.$product->idProduct); ?>">N.D</td>
										        	
										    	</tr>
								    		<?php endforeach; ?>
								     </tbody>
								 </table>
								 <?php else : ?>
								     </tbody>
								 </table>
								 <div class="col-xs-12 p-t-10 p-b-10">
								 	<p class="text-center text-muted">Nessun prodotto presente</p>
								 </div>
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
		<div id="product-search" class="row">
			<div class="col-md-12">
				<div class="box internal-box product-box brd-blue">
					<div class="box-header">
						<h3 class="box-title">Ricerca Prodotto </h3>
						<span class="header-action pull-right">
							<i id="delete-search" class="ion ion-trash-a pointer"></i>
						</span>
						<span class="header-action pull-right">
							<i id="duplicate-search" class="ion ion-ios-copy pointer"></i>
						</span>
					</div>
					
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12 p-b-35">
								<p>In questa sezione è possibile ricercare uno specifico prodotto, in base ad un campo specifico, selezionabile nell'apposito menu a tendina, una volta trovata la corrispondenza è possibile modificare quest'ultimo cliccando su di esso.</p>
							</div>
							
							<form id="product-search-form" method="post" action="">								
								<div class="col-sm-3 col-sm-offset-2 p-b-35">
									<label class="visible-xs">Campo di verifica</label>
									<select class="form-control" name="column-search">
										<option value="productName">Nome</option>
										<option value="manufacturerName">Produttore</option>
										<option value="categoryName">Categoria</option>
										<option value="productDiscount">Sconto</option>
									</select>
								</div>
								<div class="col-sm-3 p-b-35">
									<label class="visible-xs">Valore</label>
									<input class="form-control" type="text" name="value-search" />
								</div>
								<div class="col-sm-2 p-b-35">
									<button type="submit" class="btn btn-default btn-blue">Ricerca</button>
								</div>
							</form>
							
							<form id="modifyProduct-search" method="post" action=""></form>
						</div>
					</div>
					
				</div>
			</div>	
		</div>
	</div>
</div>