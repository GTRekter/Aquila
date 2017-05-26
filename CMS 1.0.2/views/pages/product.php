<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

        <div class="row">
        	<div class="col-xs-12">
           		<h1 class="page-header">
               		Prodotto <small>Modifica</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                    <li><a href="<?php echo site_url('back/products') ?>">Prodotti </a></li>
                    <li class="active">Modifica Prodotto</li>
                </ol>
            </div>
        </div>
         
        <div class="row">
        	<div class="col-md-12">
	        	<div class="box internal-box box-blue">
		        	<div class="box-header">
		        		<h3 class="box-title">Informazioni Generali</h3>
		        	</div>
		        	<div class="box-body">
		        		<div class="tabbable">
		        		     <ul class="nav nav-blue nav-tabs">
		        		     	 <li class="active">
		        		     	 	<a href="#modify_tab_general" data-toggle="tab">
		        		     	 		<i class="fa fa-cogs"></i><span class="hidden-xs"> Informazioni Generali</span>
		        		     	 	</a>
		        		     	 </li>
		        		     	 <li>
		        		     	   	<a href="#modify_tab_features" data-toggle="tab">
		        		     	   		<i class="fa fa-tags"></i><span class="hidden-xs"> Caratteristiche</span>
		        		     	   	</a>
		        		     	 </li>
		        		         <li>
		        		         	<a href="#modify_tab_combinations" data-toggle="tab">
		        		         		<i class="fa fa-cubes"></i><span class="hidden-xs"> Combinazioni</span>
		        		         	</a>
		        		         </li>
		        		         <li>
		        		         	<a href="#modify_tab_images" data-toggle="tab">
		        		         		<i class="fa fa-picture-o"></i><span class="hidden-xs"> Immagini</span>
		        		         	</a>
		        		         </li>
		        		     </ul>
		        		     
		        		     <div class="tab-content">
		        		    	 <div class="tab-pane active" id="modify_tab_general">
		        		    	 	<form id="modify_product_form">
		        		    	 		<input type="hidden" name="idProduct" value="<?php echo $product->idProduct; ?>" />
		        		    	 		<div class="p-b-35">
			        		    	 		<p>Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione del prodotto. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
		        		    	 		</div>
		        		    	 		
		        		    	 		<ul class="nav nav-blue nav-tabs" role="tablist">
		        		    	 			<?php for ($i = 0; $i < count($languages); $i++) : ?>
		        		    	 				<li role="presentation" class="<?php echo ($i==0 ? 'active' : ''); ?>">
		        		    	 					<input type="hidden" name="languages[]" value="<?php echo $languages[$i]; ?>" />
		        		    	 					<a href="#<?php echo $languages[$i]; ?>" role="tab" data-toggle="tab"><?php echo strtoupper($languages[$i]); ?></a>
		        		    	 				</li>
		        		    	 			<?php endfor; ?>
		        		    	 		</ul>
		        		    	 		<div class="tab-content">
		        		    	 			<?php for ($i = 0; $i < count($languages); $i++) : ?>
		        		    	 				<div role="tabpanel" class="tab-pane <?php echo ($i==0 ? 'active' : ''); ?>" id="<?php echo $languages[$i]; ?>">
		        		    	 					<?php foreach ($product_translations as $product_translation) : ?>
		        		    	 						<?php if ($product_translation->language == $languages[$i]) : ?>
		        		    	 							<div class="form-group">
		        		    	 								<label>Titolo *</label>
		        		    	 							    <input class="form-control" name="productName[]" required="true" value="<?php echo $product_translation->productName; ?>">
		        		    	 							</div>
		        		    	 							<div class="form-group">
		        		    	 								<label>Descrizione *</label>
		        		    	 							   <textarea class="form-control" name="productDescription[]" rows="7" required="true"><?php echo $product_translation->productDescription; ?></textarea>
		        		    	 							</div>
		        		    	 						<?php endif; ?>
		        		    	 					<?php endforeach; ?>
		        		    	 				</div>
		        		    	 			<?php endfor; ?>
		        		    	 		</div>
		        		    	 		
			        					<div class="row p-t-25">
			        		      			<div class="col-md-12">
			        		      				<div class="panel panel-default">
			        		      					<div class="panel-body">
			        		      						<div class="form-group">
			        		      							<label>Categoria Padre</label>
			        		      							<ul id="cat-modify" class="nav nav-list"></ul>
			        		      						</div>
			        		      					</div>
			        		      				</div>
			        		      			</div>
			        						<div class="col-md-6">
			        							<div class="row">
			        								<div class="col-sm-12">
			        									<label>Codice EAN-13</label>
			        								</div>
			        								<div class="col-sm-8">
			        									<div class="form-group">
			        									    <input class="form-control" name="productEAN" value="<?php echo $product->productEAN; ?>">
			        									</div>
			        								</div>
			        								<div class="col-sm-4">
			        									<button type="button" class="btn btn-blue full-width" name="checkEAN">Controlla EAN</button>
			        								</div>
			        							</div>
			        						</div>
			        			      		<div class="col-md-6">
			        			      			<div class="row">
			        			      				<div class="col-sm-12">
			        			      					<label>Codice SKU</label>
			        			      				</div>
			        			      				<div class="col-sm-8">
			        			      					<div class="form-group">
			        			      					    <input class="form-control" name="productSKU" value="<?php echo $product->productSKU; ?>">
			        			      					</div>
			        			      				</div>
			        			      				<div class="col-sm-4">
			        			      					<button type="button" class="btn btn-blue full-width" name="checkSKU">Controlla SKU</button>
			        			      				</div>
			        			      			</div>
			        			      		</div>
			        			      		<div class="col-md-6">
		        			      				<div class="form-group">
		        			      					<label>Classe di tassazione</label>
		        			      					<select class="form-control" <?php if(!$tax){echo 'disabled';} ?> name="idTax">
		        			      						<?php for ($i = 0; $i < count($tax); $i++) : ?>
		        			      							<option value="<?php echo $tax[$i]->idTax; ?>" data-percentage="<?php echo $tax[$i]->taxValue; ?>" <?php echo ($tax[$i]->idTax == $product->idTax ? 'selected' : ''); ?>>
		        			      								<?php echo ucwords($tax[$i]->taxName).' ( '.$tax[$i]->taxValue.'% )'; ?>
		        			      							</option>
		        			      						<?php endfor; ?>
		        			      				 	</select>
		        			      				</div>
		        			      			</div>
		        			      			<div class="col-md-6">
		        			      				<div class="row">
		        			      					<div class="col-md-6">
		        			      						<label>Prezzo netto</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="productPrice" required="true" placeholder="NNNN.DD" value="<?php echo $product->productPrice; ?>">
		        			      							<span class="input-group-addon">€</span>
		        			      						</div>
		        			      					</div>
		        			      					<div class="col-md-6">
		        			      						<label>Prezzo lordo</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="grossPrice" required="true" placeholder="NNNN.DD">
		        			      							<span class="input-group-addon">€</span>
		        			      						</div>
		        			      					</div>
		        			      				</div>
		        			      			</div>
		        			      			<div class="col-md-6">
		        			      				<div class="row">
		        			      					<div class="col-md-6">
		        			      						<label>Larghezza imballaggio *</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="productLenght" required="true" value="<?php echo $product->productLenght; ?>">
		        			      							<span class="input-group-addon">cm</span>
		        			      						</div>
		        			      					</div>	
		        			      					<div class="col-md-6">
		        			      						<label>Lunghezza imballaggio *</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="productWidth" required="true" value="<?php echo $product->productWidth; ?>">
		        			      							<span class="input-group-addon">cm</span>
		        			      						</div>
		        			      					</div>		
		        			      				</div>
		        			      			</div>
		        			      			<div class="col-md-6">
		        			      				<div class="row">
		        			      					<div class="col-md-6">
		        			      						<label>Altezza imballaggio *</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="productHeight" required="true" value="<?php echo $product->productHeight; ?>">
		        			      							<span class="input-group-addon">cm</span>
		        			      						</div>
		        			      					</div>
		        			      					<div class="col-md-6">
		        			      						<label>Peso prodotto *</label>
		        			      						<div class="form-group input-group">
		        			      							<input type="text" class="form-control" name="productWeight" required="true" value="<?php echo $product->productWeight; ?>">
		        			      							<span class="input-group-addon">gr</span>
		        			      						</div>
		        			      					</div>		
		        			      				</div>
		        			      			</div>
			        			      		
			        			      		
			        			      		<div class="col-md-6">
			        			      			<div class="form-group">
			        			      				<label>Produttore</label>
			        			      				<select class="form-control" <?php if(! $manufacturers){echo 'disabled';} ?> name="idManufacturer">
			        			      					<?php if ($manufacturers) : ?>
			        			      						<?php foreach ($manufacturers as $manufacturer) : ?>
			        			      							<option value="<?php echo $manufacturer->idManufacturer; ?>" <?php echo ($manufacturer->idManufacturer == $product->idManufacturer ? 'selected' : ''); ?>>
			        			      								<?php echo ucwords($manufacturer->manufacturerName); ?>
			        			      							</option>
			        			      						<?php endforeach; ?>
			        			      					<?php endif; ?>
			        			      			 	</select>
			        			      			</div>
			        			      		</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-6">
														<label>Visibilità eCommerce </label>
														<div class="switch-button">
															<input type="checkbox" name="isEcommerce" value="1" <?php echo($product->isEcommerce == 1 ? 'checked' : ''); ?>/>
															<div class="switch-light">
																<span class="light btn-blue <?php echo($product->isEcommerce == 1 ? 'active' : ''); ?>"></span>
																<span class="pointer <?php echo($product->isEcommerce == 0 ? 'active' : ''); ?>" data-value="0">Off</span>
																<span class="pointer <?php echo($product->isEcommerce == 1 ? 'active' : ''); ?>" data-value="1">On</span>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<label>Visibilità Marketplace </label>
														<div class="switch-button">
															<input type="checkbox" name="isMarketplace" value="1" <?php echo($product->isMarketplace == 1 ? 'checked' : ''); ?>/>
															<div class="switch-light">
																<span class="light btn-blue <?php echo($product->isMarketplace == 1 ? 'active' : ''); ?>"></span>
																<span class="pointer <?php echo($product->isMarketplace == 0 ? 'active' : ''); ?>" data-value="0">Off</span>
																<span class="pointer <?php echo($product->isMarketplace == 1 ? 'active' : ''); ?>" data-value="1">On</span>
															</div>
														</div>
													</div>
												</div>
											</div>	
			        		      		</div>
			        		      		<div class="row pull-right">
			        		      			<div class="col-xs-12">
			        		      				<button type="reset" class="btn btn-default">Cancella</button>
			        		      				<button type="submit" class="btn btn-default btn-blue">Salva</button>
			        		      			</div>
			        		      		</div>
		        		    	 	</form>
		        		    	 </div>	  
		        		    	 <div class="tab-pane" id="modify_tab_features">
		        		    	 	 <form id="modify_feature_form">
		        		    	 	 	<div class="row">
		        		    	 	 		<div class="col-xs-12 p-b-35">
		        		    	 				<p>Le caratteristiche sono campi opzionali, che permettono di associare una valore a tutte le combinazioni di quel determinato prodotto. Per inserire uno o più caratteristiche vai all'apposita sezione <a href="<?php echo site_url('back/features'); ?>">Caratteristiche</a>. Per aggiungere una caratteristica clicca sull'apposito pulsante "Aggiungi una caratteristica" sul fondo della pagina.</p>
		        		    	 				<p><strong>N.B</strong> Le informazioni relative al prodotto <strong>NON</strong> verrano perse durante il procedimento.</strong></p>
		        		    	 			</div>
		        		    	 			<div class="col-xs-12">
		        		    	 				<input type="hidden" name="idProduct" />
		        		    	 				<table id="table-features" class="table table-hover table-striped table-condensed">
		        		    	 					<thead>
		        		    	 						<tr>
		        		    	 							<th>Caratteristica</th>
		        		    	 							<th>Valore associato</th>
		        		    	 							<th></th>
		        		    	 						</tr>
		        		    	 					</thead>
		        		    	 					<tbody></tbody>
		        		    	 				</table>
		        		    	 			</div>
		        		    	 	 	</div>
		        		    	 	 	<div class="row pull-right">
		        		    	 			<div class="col-xs-12">
		        		    	 				<button type="button" id="btn_add_feature" class="btn btn-default">Aggiungi caratteristica</button>
		        		    	 				<button type="submit" class="btn btn-default btn-blue">Salva</button>
		        		    	 			</div>
		        		    	 		</div>
		        		    	 	</form>
		        		    	 </div>  
		        		    	     
		        		         <div class="tab-pane" id="modify_tab_combinations">
		        		         	<form id="modify_combination_form">
		        		         		<input type="hidden" name="idProduct" value="<?php echo $product->idProduct; ?>" />
		        		         		<div class="row">
		        		         			<div class="col-xs-12 p-b-35">
		        		         				<p>Inserisci gli attributi delle varie combinazioni del prodotto, esse dovranno necessariamente essere composte dagli stessi campi per la sincronizzazione sui Marketplace e dovranno avere almeno un attributo.</p>
		        		         			</div>
		        		         			<div class="col-xs-12">
		        		         				<label>Attributi</label>
												<div class="form-group">
													<?php for ($i = 0; $i < count($attributes); $i++) : ?>
														<label class="checkbox-inline">
															<input type="checkbox" name="feature[]"
															data-featureName="<?php echo $attributes[$i]->featureName; ?>" value="<?php echo $attributes[$i]->idFeature; ?>" /><?php echo $attributes[$i]->featureName; ?>
														</label>
													<?php endfor; ?>
					                            </div>
		        		         			</div>
		        		         			<div class="col-xs-12">
		        		         				<label>Valori</label>
		        		         				<div class="row">
		        		         					<div class="col-xs-12">
		        		         						<div id="list-values"></div>
		        		         					</div>
		        		         				</div>
		        		         			</div>
		        		         			<div class="col-xs-12 table-responsive">
		        		         				<label>Combinazioni</label>
		        		         				<table id="table-combinations" class="table table-hover table-striped table-bordered">
		        		         			    	<thead>
		        		         			    		<tr></tr>
		        		         			    	</thead>
		        		         			        <tbody class="values">
		        		         			        </tbody>
		        		         				</table>
		        		         			</div>
		        		          		</div>
		        		         		
		        		         		<div class="row brd-top-light combinations"></div>
		        		         		        	
		        		         		<div class="row pull-right">
		        		         			<div class="col-xs-12">
		        		         				<button type="button" id="btn_generate_combinations" class="btn btn-default">Genera Combinazioni</button>
		        		         				<button type="submit" class="btn btn-default btn-blue">Salva</button>
		        		         			</div>
		        		         		</div>    	
		        		         	</form>
		        		         </div>
		        		         <div class="tab-pane" id="modify_tab_images">
		        		         	<form id="modify_images_form">
		        		         		<input type="hidden" name="idProduct" value="<?php echo $product->idProduct; ?>" />
		        			         	<div class="row">
		        			         		<div class="col-xs-12 p-b-35">
		        			         			<p>Seleziona una o più immagini che rappresentano il prodotto inserito. Le immagini devono essere in formato JPEG o PNG. Una volta inserite, per impostare l'immagine principale del prodotto sarà necessario cliccare sul bottone con la voce <b>Immagine Principale</b> e salvare.</p>
		        			         		</div>
		        			         		<div class="col-xs-12">
	        			         				<div class="input-group">
	        			         				    <span class="input-group-btn">
	        			         				        <span class="btn btn-default btn-file">
	        			         				            Sfoglia 
	        			         				            <input type="hidden" name="idProduct" value="" />
	        			         				            <input type="file" name="files[]" multiple="true"/>
	        			         				        </span>
	        			         				    </span>
	        			         				    <input type="text" name="coverName" class="form-control bg-white" disabled="true">  
	        			         				</div>
	        			         				<p class="help-block">Formato richiesto PNG o JPEG</p>
	        			         			</div>
		        			         	</div>
		        			         	<div class="row gallery">
		        			         		<?php if($photos) : ?>
		        			         			<?php foreach ($photos as $photo) : ?>
		        			         				<div id="<?php echo $photo->idPhoto ?>" class="col-xs-6 col-lg-4">
		        			         					<div class="img-thumbnail" style="width: 100%;">
		        			         						<img style="margin-top: 10px; height: 150px; margin: 0 auto;" src="<?php echo $this->config->item('resources_dynamic_img').'/products/medium/'.$photo->photoName ?>" class="img-responsive">
		        			         					</div>
		        			         					<input type="radio" name="idPhotoCover" value="<?php echo $photo->idPhoto; ?>" <?php echo ($photo->isCover!=0 ? 'checked' : ''); ?>/> Principale &nbsp;<input type="checkbox" name="idPhoto[]" value="<?php echo $photo->idPhoto; ?>"/> Cancella<br/>
		        			         				</div>
		        			         			<?php endforeach; ?>
		        			         		<?php endif; ?>
		        			         	</div>
		        			         	<div class="row pull-right">
		        			         		<div class="col-xs-12">
		        			         			<button type="reset" class="btn btn-default">Cancella</button>
		        			         			<button type="submit" class="btn btn-default btn-blue">Salva</button>
		        			         		</div>
		        			         	</div>
		        			         </form>
		        		         </div>
		        		     </div>
		        		 </div>
		        	</div>
	        	</div>
        	</div>
      	</div> 
		
	</div>
</div>