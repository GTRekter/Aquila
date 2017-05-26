<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Pagine Personalizzate <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Pagine Personalizzate</li>
		        </ol>
		    </div>
		</div> 	

		<form id="modifyPage" method="POST" action="">
		    <div class="row">
				<div class="col-md-12">
					<div class="box internal-box custom-pages-box brd-red">
						<div class="box-header">
							<h3 class="box-title">Lista Pagine<span class="badge"><?php echo count($pages); ?></span></h3>
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
								<a class="addPage" href="<?php echo site_url('back/n_page'); ?>"> 
									<i class="ion ion-plus-circled"></i>
								</a>
							</span>
						</div>
						
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-page">
							    	<thead>
							        	<tr>
							        		<th></th>
							            	<th>Titolo</th>
							            	<th>Testo</th>
							                <th>Data Creazione</th>
							            </tr>
							        </thead>
							        <tbody>
							        	
								        <?php if($pages) : ?>
								        	<?php foreach ($pages as $page) : ?>
										    	<tr>
										    		<td>
										    			<input type="checkbox" name="idPage[]" value="<?php echo $page->idPage; ?>" 
										    			<?php 
										    			if($page->idPage == 1 || $page->idPage == 2) {
										    				echo ' disabled="true" ';
										    			} 
										    			?>/>
										    		</td>
										    		<td class="linePageDetail" data-id="<?php echo $page->idPage; ?>">
										    			<?php echo ucfirst($page->pageName); ?>
										    		</td>
										        	<td class="linePageDetail" data-id="<?php echo $page->idPage; ?>">
										        		<?php echo substr(strip_tags($page->pageDescription),0,100).'...'; ?>
										        	</td>
										        	<td class="linePageDetail" data-id="<?php echo $page->idPage; ?>">
										        		<?php echo $page->createdOn; ?>
										        	</td>
										    	</tr>
								    		<?php endforeach; ?>
							    		</tbody>
							    	<?php else : ?>
							    	    </tbody>
							    	</table>
							    	<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna pagina personalizzata presente</p></div>
							    	<?php endif; ?>
							    </table>
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
		<div id="page-detail" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-red">
					<div class="box-header">
						<h3 class="box-title">Informazioni Pagina <span id="invoice-order"></span></h3>
					</div>
					<form method="post" action="<?php echo base_url('back/u_STN_Page/') ?>">
						<div class="box-body">
							<div class="row">
								<div class="col-xs-12">
									<p>In questa sezione sono visualizzate tutte le informazioni relative alla pagina selezionata. Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione del prodotto. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
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
						    		<button type="submit" class="btn btn-red">Salva</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>	
		</div>	
		<div id="page-search" class="row">
			<div class="col-md-12">
				<div class="box internal-box custom-pages-box brd-red">
					<div class="box-header">
						<h3 class="box-title">Ricerca Pagina </h3>
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
								<p>In questa sezione è possibile ricercare una specifica pagina, in base ad un campo specifico, selezionabile nell'apposito menu a tendina, una volta trovata la corrispondenza è possibile modificare quest'ultimo cliccando su di esso.</p>
							</div>
							
							<form id="page-search-form" method="post" action="">								
								<div class="col-sm-3 col-sm-offset-2 p-b-35">
									<label class="visible-xs">Campo di verifica</label>
									<select class="form-control" name="column-search">
										<option value="pageName">Titolo</option>
										<option value="pageDescription">Descrizione</option>
									</select>
								</div>
								<div class="col-sm-3 p-b-35">
									<label class="visible-xs">Valore</label>
									<input class="form-control" type="text" name="value-search" />
								</div>
								<div class="col-sm-2 p-b-35">
									<button type="submit" class="btn btn-default btn-red">Ricerca</button>
								</div>
							</form>
							
							<form id="modifyPage-search" method="post" action=""></form>
						</div>
					</div>
					
				</div>
			</div>	
		</div>
	</div>
</div>
