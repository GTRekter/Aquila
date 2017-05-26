<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
        <div class="row">
        	<div class="col-xs-12">
           		<h1 class="page-header">
               		News <small>Creazione</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                    <li><a href="<?php echo site_url('back/articles') ?>"> News </a></li>
                    <li class="active">Creazione News</li>
                </ol>
            </div>
        </div> 	
        <form id="addArticle" method="post" action="">
	        <div class="row">
	        	<div class="col-md-12">
		        	<div class="box internal-box brd-red">
			        	<div class="box-header">
			        		<h3 class="box-title">Informazioni News</h3>
			        	</div>
			        	<div class="box-body">
			        		<?php if ($categories) : ?>
				    	 		<div class="p-b-35">
		    		    	 		<p>Inserisci le informazioni relative alla news in lingua italiana e clicca sul pulsante di salvataggio per crearla. Al momento della creazione le informazioni inserite verranno automaticamente tradotto nelle lingue indicate nelle <a href="<?php echo site_url('back/settings'); ?>">impostazioni</a>.</p>
				    	 		</div>
				    	 		<ul class="nav nav-tabs nav-red" role="tablist">
				    	 			<li role="presentation" class="active">
				    	 				<a href="#italian" role="tab" data-toggle="tab">IT</a>
				    	 			</li>
				    	 		</ul>
				    	 		<div class="tab-content">
				    	 			<div role="tabpanel" class="tab-pane active" id="italian">
				    	 				<div class="form-group">
				    	 					<label>Titolo *</label>
				    	 				    <input class="form-control" name="articleName" required="true">
				    	 				</div>
				    	 				<label>Descrizione *</label>
				    	 				<div class="form-group">
				    	 					 <textarea class="form-control" name="articleDescription" rows="8" required="true"></textarea>
				    	 				</div>
				    	 			</div>
				    	 		</div>
				    	 		<div class="row p-t-25">
				    	 			<div class="col-xs-12">
				    	 				<label>Categoria Padre</label>
				    	 				<div class="panel panel-control">
				    	 					<div class="panel-body">
				    	 						<div class="form-group">
				    	 							<?php foreach ($categories as $category) : ?>
				    	 								<div class="radio">
				    	 								    <label>
				    	 								        <input name="idArticlesCategory" value="<?php echo $category->idArticlesCategory; ?>" checked="" type="radio"><?php echo $category->articlesCategoryName; ?>
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
				    	 				                <input type="file" name="files[]" multiple/>
				    	 				            </span>
				    	 				        </span>
				    	 				        <input type="text" class="form-control bg-white" disabled="true">
				    	 				    </div>
				    	 				    <p class="help-block">Formato richiesto PNG oppure JPEG</p>
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
			    	 		<?php else : ?>
			    	 			<p class="text-warning"><strong>ATTENZIONE:</strong> Per procedere con la creazione dell'articolo è necessario aver creato almeno una categoria a cui associalo.</p>
			    	 			<p class="text-warning">Creane almeno uno e riprova ad eseguire l'operazione.</p>
			    	 		</div>
			    	 		<?php endif; ?>
		        	</div>
		      	</div>
	      	</div> 
      	</form>
	</div>
</div>