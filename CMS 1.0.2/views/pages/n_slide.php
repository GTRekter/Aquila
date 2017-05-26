<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">        
        <div class="row">
        	<div class="col-xs-12">
           		<h1 class="page-header">
               		Slide <small> Creazione</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                    <li><a href="<?php echo site_url('back/pages') ?>">Slides </a></li>
                    <li class="active">Creazione Slide</li>
                </ol>
            </div>
        </div> 
              
        <form id="addSlide">
	        <div class="row">
	        	<div class="col-md-12">
		        	<div class="box internal-box brd-red">
			        	<div class="box-header">
			        		<h3 class="box-title">Informazioni Slide</h3>
			        	</div>
			        	<div class="box-body">
			    	 		<div class="p-b-35">
	    		    	 		<p>Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing&#153; al momento della creazione della slide. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
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
			    	 				    <input class="form-control" name="slideName" required="true">
			    	 				</div>
			    	 				<label>Descrizione *</label>
			    	 				<div class="form-group">
			    	 					 <textarea class="form-control" name="slideDescription" rows="8" required="true"></textarea>
			    	 				</div>
			    	 			</div>
			    	 		</div>
			    	 		<div class="row p-t-25">
			    	 			<div class="col-xs-12">
			    	 				<label>Galleria Immagini</label>
			    	 				<div class="input-group">
		    	 					    <span class="input-group-btn">
		    	 					        <span class="btn btn-default btn-file">
		    	 					            Sfoglia 
		    	 					            <input type="file" name="files" multiple="true"/>
		    	 					        </span>
		    	 					    </span>
		    	 					    <input type="text" name="coverName" class="form-control bg-white" disabled="true">  
		    	 					</div>
		    	 					<p class="help-block">Formato richiesto PNG o JPEG</p>
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
		        	</div>
		      	</div>
	      	</div> 
      	</form>		
	</div>
</div>