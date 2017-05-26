<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		News <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">News</li>
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
			            	<span class="title">Articoli</span>
			            	<span class="data"><?php echo $total_articles; ?></span>
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
			
        <form id="modifyArticle" method="POST" action=""> 
	        <div class="row">
				<div class="col-md-12">
					<div class="box internal-box articles-box brd-red">
						<div class="box-header">
							<h3 class="box-title">Lista News</h3>
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
								<a class="addProduct" href="<?php echo site_url('back/n_article'); ?>"> <i class="ion ion-plus-circled"></i></a>
							</span>
						</div>
						
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-articles">
							    	<thead>
							        	<tr>
							        		<th></th>
							        		<th>Immagine</th>
							        		<th>Titolo</th>
								        	<th>Descrizione</th>
								        	<th>Categoria</th>
							                <th>Creazione</th>
							            </tr>
							        </thead>
							        <?php if($articles) : ?>
							        	<?php foreach ($articles as $article) : ?>
									    	<tr>
									    		<td>
									    			<input type="checkbox" name="idArticle[]" value="<?php echo $article->idArticle; ?>" />
									    		</td>
									    		<td class="lineArticleDetail" data-id="<?php echo $article->idArticle ?>">
								    				<?php 
								    					$isPresent = false; 
								    					if($photos) {
									    					foreach ($photos as $photo) {
									    						if ($article->idArticle == $photo->idArticle && $isPresent == false && $photo->isCover == true) {
									    							echo '<img src="'.$this->config->item('resources_dynamic_img').'/news/extra_small/'.$photo->photoName.'" alt="'.ucfirst($article->articleName).'">';
									    							$isPresent = true; 
									    						}
									    					}
								    					}
								    					if ($isPresent == false) {
								    						echo '<img src="'.$this->config->item('resources_dynamic_img').'/news/extra_small/default.jpg" alt="'.ucfirst($article->articleName).'">';
								    					}
								    				?>
									    		</td>
									    		<td class="lineArticleDetail" data-id="<?php echo $article->idArticle ?>">
									    			<?php echo substr(ucfirst($article->articleName),0,100); ?>
									    		</td>
									    		<td class="lineArticleDetail" data-id="<?php echo $article->idArticle ?>">
									    			<?php echo substr(ucfirst($article->articleDescription),0,150).' ...'; ?>
									    		</td>
									    		<td class="lineArticleDetail" data-id="<?php echo $article->idArticle ?>">
									    			<?php echo ucfirst($article->articlesCategoryName); ?>
									    		</td>
									    		<td class="lineArticleDetail" data-id="<?php echo $article->idArticle ?>">
									    			<?php echo $article->createdOn; ?>
									    		</td>
									    	</tr>
							    		<?php endforeach; ?>
							    	</table>
							    <?php else : ?>
							        </tbody>
							    </table>
							    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun articolo presente</p></div>
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
        <div id="article-detail" class="row">
        	<div class="col-md-12">
        		<div class="box internal-box brd-red">
        			<div class="box-header">
        				<h3 class="box-title">Informazioni News <span id="invoice-order"></span></h3>
        			</div>
        			<form id="article-detail-form" method="post" action="">
        				<div class="box-body">
        					<div class="row">
        						<div class="col-xs-12">
        							<p>In questa sezione sono visualizzate tutte le informazioni relative all'articolo selezionata. Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione del prodotto. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
        						</div>
        						
        						<div class="col-xs-12">
        							<ul class="nav nav-tabs nav-red" role="tablist"></ul>
        							<div class="tab-content"></div>
        							<div class="row p-t-25">
        								<div class="col-xs-12">
	        								<label>Categoria Padre</label>
	    									<div class="panel panel-control">
	    										<div class="panel-body">
	    											<div class="form-group">
		    											<?php foreach ($categories as $category) : ?>
		    												<div class="radio">
		    												    <label>
		    												        <input name="idArticlesCategory" value="<?php echo $category->idArticlesCategory; ?>" type="radio"><?php echo $category->articlesCategoryName; ?>
		    												    </label>
		    												</div>
		    											<?php endforeach; ?>
	    											</div>
	    										</div>
	    									</div>	
        								</div>
	        							<div class="col-xs-12">
	        								<div class="form-group">
	        								    <label>Galleria Immagini</label>
	        								    <div class="input-group">
	        								        <span class="input-group-btn">
	        								            <span class="btn btn-default btn-file">
	        								                Sfoglia 
	        								                <input type="hidden" name="idArticle" />
	        								                <input type="file" name="files[]" />
	        								            </span>
	        								        </span>
	        								        <input type="text" class="form-control bg-white" disabled="true">
	        								    </div>
	        								    <p class="help-block">Formato richiesto PNG oppure JPEG</p>
	        								</div>
        								</div>
        								<div class="gallery"></div>
        							</div>
        						</div>
        					</div>
        				</div>
        				<div class="box-footer">
        					<div class="row">
        						<div class="col-xs-12 text-right">
        				    		<button type="reset" class="btn btn-default">Cancella</button>
        				    		<button type="submit" class="btn btn-red">Salva</button>
        						</div>
        					</div>
        				</div>
        			</form>
        		</div>
        	</div>	
        </div>
        <div id="article-search" class="row">
        	<div class="col-md-12">
        		<div class="box internal-box articles-box brd-red">
        			<div class="box-header">
        				<h3 class="box-title">Ricerca Articolo </h3>
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
        						<p>In questa sezione è possibile ricercare uno specifico articolo, in base ad un campo specifico, selezionabile nell'apposito menu a tendina, una volta trovata la corrispondenza è possibile modificare quest'ultimo cliccando su di esso.</p>
        					</div>
        					
        					<form id="article-search-form" method="post" action="">								
        						<div class="col-sm-3 col-sm-offset-2 p-b-35">
        							<label class="visible-xs">Campo di verifica</label>
        							<select class="form-control" name="column-search">
        								<option value="articleName">Titolo</option>
        								<option value="articleDescription">Descrizione</option>
        								<option value="categoryName">Categoria</option>
        							</select>
        						</div>
        						<div class="col-sm-3 p-b-35">
        							<label class="visible-xs">Valore</label>
        							<input class="form-control" type="text" name="value-search" />
        						</div>
        						<div class="col-sm-2 p-b-35">
        							<button type="submit" class="btn btn-red">Ricerca</button>
        						</div>
        					</form>
        					
        					<form id="modifyArticle-search" method="post" action=""></form>
        				</div>
        			</div>
        			
        		</div>
        	</div>	
        </div>
		
	</div>
</div>
