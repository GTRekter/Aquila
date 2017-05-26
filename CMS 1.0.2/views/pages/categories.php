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
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-briefcase sz-50"></i>
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
         	        		<div class="icon-box bg-blue cl-white">
         	            		<i class="ion ion-ios-folder sz-50"></i>
         	            	</div>
         	            </div>
         	            <div class="col-xs-7 text-box">
         	            	<span class="title">Sotto-Categorie</span>
         	            	<span class="data"><?php echo $total_subcategories; ?></span>
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
         	            	<span class="title">Media Prodotti</span>
         	            	<span class="data"><?php echo $average_products; ?></span>
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
         	            	<span class="title">Categorie Bloccate</span>
         	            	<span class="data">N.D</span>
         	             </div>
         	        </div>
         	    </div>
         	</div>
         </div>
		 <!-- GENERAL LIST-->
		 <form id="category">
         	<div class="row">
				<div class="col-md-12">
					<div class="box internal-box brd-blue">
						<div class="box-header">
							<h3 class="box-title">Lista Categorie</h3>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy pointer"></i>
							</span>
							<span class="header-action pull-right">
								<a id="add" data-toggle="modal" data-target="#addCategory" class="pointer">
									<i class="ion ion-plus-circled pointer"></i>
								</a>
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
							            	<th>Tipologia</th>
							            </tr>
							        </thead>
							        <?php if($categories) : ?>
							        	<?php for($i = 0; $i < count($categories); $i++) : ?>
									    	<tr>
									    		<td>
									    			<input type="checkbox" name="idCategory[]" value="<?php echo $categories[$i]->idCategory ?>" />
									    		</td>
									    		<td class="btnModifyCategory" data-id="<?php echo $categories[$i]->idCategory ?>" data-toggle="modal" data-target="#modifyCategory"><?php echo ucwords($categories[$i]->categoryName); ?></td>
									    		<td class="btnModifyCategory" data-id="<?php echo $categories[$i]->idCategory ?>" data-toggle="modal" data-target="#modifyCategory"><?php echo ucwords($categories[$i]->categoryDescription); ?>
									    		</td>
									    		<td><?php echo ( $categories[$i]->idParentCategory ? 'Secondaria' : 'Principale' ) ?></td>
									    	</tr>
							    		<?php endfor; ?>
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
		 <!-- MODALS -->
		 <div id="addCategory" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title"><i class="fa fa-tags"></i> Creazione nuova categoria</h4>
		      		</div>
		      		<form id="add-category-form">
			      		<div class="modal-body">
			      			<div class="form-group">
								<ul class="nav nav-tabs nav-blue" role="tablist">
									<li role="presentation" class="active">
										<a href="#italia" role="tab" data-toggle="tab">IT</a>
									</li>
								</ul>
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="italia">
										<label>Categoria Padre</label>
	        		      				<div class="panel panel-control">
	        		      					<div class="panel-body">
	        		      						<div class="form-group">
	        		      							<ul id="cat-create" class="nav nav-list"></ul>
	        		      						</div>
	        		      					</div>
	        		      				</div>
										
										<div class="form-group">
											<label>Nome *</label>
										    <input class="form-control" name="categoryName" required="true">
										</div>
										
										<label>Descrizione *</label>
										<div class="form-group">
										    <textarea class="form-control" name="categoryDescription" required="true" rows="4"></textarea>
										</div>

									</div>
								</div>
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
		 <div id="modifyCategory" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Modifica categoria</h4>
		      		</div>
		      		<form id="modify-category-form">
			      		<div class="modal-body">
			      			<input type="hidden" name="idCategory" />
			      			
			      			<ul id="tablist_category" class="nav nav-tabs nav-blue" role="tablist"></ul>
			      			<div id="tabcontent_category" class="tab-content"></div>
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