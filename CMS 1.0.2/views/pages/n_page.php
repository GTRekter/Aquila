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
		            <li><a href="<?php echo site_url('back/pages') ?>">Pagine Personalizzate </a></li>
		            <li class="active">Creazione Pagina Personalizzata</li>
		        </ol>
		    </div>
		</div> 
         
        <form id="addPage" method="post" action="">
	        <div class="row">
	        	<div class="col-md-12">
		        	<div class="box internal-box brd-red">
			        	<div class="box-header">
			        		<h3 class="box-title">Informazioni Pagina</h3>
			        	</div>
			        	<div class="box-body">
			    	 		<div class="p-b-35">
	    		    	 		<p>Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione del prodotto. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
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
			    	 				    <input class="form-control" name="pageName" required="true">
			    	 				</div>
			    	 				<label>Corpo della pagina *</label>
			    	 				<div class="form-group">
			    	 					 <textarea class="wysihtml5 form-control" name="pageDescription" rows="16" required="true"></textarea>
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
		        	</div>
		      	</div>
	      	</div> 
      	</form>
		
	</div>
</div>