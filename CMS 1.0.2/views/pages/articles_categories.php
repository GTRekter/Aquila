<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div id="page-wrapper">
	<div class="container-fluid">
         <div class="row">
         	<div class="col-xs-12">
            		<h1 class="page-header">
                		Categorie <small>Riepilogo</small>
                 </h1>
                 <ol class="breadcrumb">
                     <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                     <li class="active">Categorie</li>
                 </ol>
             </div>
         </div> 
         <div class="row">
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-red cl-white">
         	            		<i class="ion ion-ios-box-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Categorie</span>
         	            	<span class="data"><?php echo count($categories); ?></span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-red cl-white">
         	            		<i class="ion ion-ios-pricetags-outline sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Media Articoli</span>
         	            	<span class="data"><?php echo $average_articles ?></span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         	<div class="col-lg-3 col-md-6">
         		<div class="statistics-box">
         	    	<div class="row">
         	        	<div class="col-xs-5">
         	        		<div class="icon-box bg-red cl-white">
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
         	        		<div class="icon-box bg-red cl-white">
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
		
		 <form id="modifyCategory">
         	<div class="row">
				<div class="col-md-12">
					<div class="box internal-box articles-box brd-red">
						<div class="box-header">
							<h3 class="box-title">Lista Categorie</h3>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="add" data-action="add" class="ion ion-plus-circled pointer"></i>
							</span>
						</div>
	
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-categories">
							    	<thead>
							        	<tr>
							        		<th></th>
							            	<th>Categoria</th>
							            	<th>Descrizione</th>
							            </tr>
							        </thead>
							        <?php if($categories) : ?>
							        	<?php foreach ($categories as $category) : ?>
									    	<tr>
									    		<td>
									    			<input type="checkbox" name="idArticlesCategory[]" value="<?php echo $category->idArticlesCategory; ?>" />
									    		</td>
									    		<td class="lineCategoryDetail" data-action="update" data-id="<?php echo $category->idArticlesCategory; ?>">
									    			<?php echo ucfirst($category->articlesCategoryName); ?>
									    		</td>
									    		<td class="lineCategoryDetail" data-action="update" data-id="<?php echo $category->idArticlesCategory; ?>">
									    			<?php echo ucfirst($category->articlesCategoryDescription); ?>
									    		</td>
									    	</tr>
							    		<?php endforeach; ?>
							    	</table>
							    <?php else : ?>
							        </tbody>
							    </table>
							    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna categoria presente</p></div>
							    <?php endif; ?>
							</div>
						</div>
					</div>
				</div>	
			</div>
         </form>
		 <div id="add-articles-category" class="row">
		 	<div class="col-md-12">
		 		<div class="box internal-box brd-red">
		 			<div class="box-header">
		 				<h3 class="box-title">Creazione Categoria</h3>
		 			</div>
		 			<form id="add_images_form">
		 				<div class="box-body">
		 					<div class="row">
		 						<div class="col-xs-12">
		 							<div class="p-b-35">
	 									<p>Inserisci le informazioni relative alla categoria in lingua italiana e clicca sul pulsante di salvataggio per crearla. Al momento della creazione le informazioni inserite verranno automaticamente tradotto nelle lingue indicate nelle <a href="<?php echo site_url('back/settings'); ?>">impostazioni</a>.</p>
	 								</div>
	 								<ul class="nav nav-tabs nav-red" role="tablist">
	 									<li role="presentation" class="active">
	 										<a href="#italian" role="tab" data-toggle="tab">IT</a>
	 									</li>
	 								</ul>
	 								<div class="tab-content">
	 									<div role="tabpanel" class="tab-pane active" id="italian">
	 										<div class="form-group">
	 											<label>Titolo Categoria *</label>
	 										    <input class="form-control" name="articlesCategoryName" required="true">
	 										</div>
	 										<label>Descrizione Categoria *</label>
	 										<div class="form-group">
	 											 <textarea class="form-control" name="articlesCategoryDescription" rows="8" required="true"></textarea>
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
         <div id="category-detail" class="row">
         	<div class="col-md-12">
         		<div class="box internal-box brd-red">
         			<div class="box-header">
         				<h3 class="box-title">Informazioni Categoria</h3>
         			</div>
         			<form id="modify_articles_category_form">
         				<div class="box-body">
         					<div class="row">
         						<div class="col-xs-12">
         							<p>In questa sezione sono visualizzate tutte le informazioni relative alla categoria selezionata. Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione della categoria. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
         						</div>
         						<div class="col-xs-12">
         							<ul class="nav nav-tabs nav-red" role="tablist"></ul>
         							<div class="tab-content"></div>
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