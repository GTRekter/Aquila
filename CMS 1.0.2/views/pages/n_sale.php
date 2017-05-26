<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
        <div class="row">
        	<div class="col-xs-12">
           		<h1 class="page-header">
               		Sconto <small>Creazione</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                    <li><a href="<?php echo site_url('back/sales') ?>">Sconti </a></li>
                    <li class="active">Creazione Sconto</li>
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
        		     	 <form id="newSale" method="post">
    		    	 		<div class="p-b-35">
        		    	 		<p>Inserisci le informazioni dello sconto e clicca sul pulsante di salvataggio per crearlo. E' possibile inserire lo sconto sia in cifre che in percentuali, ma non entrambe. Una volta che verrà compilato uno dei due campi l'altro verrà automaticamente disabilitato. </p>
    		    	 		</div>
        					<div class="row">
        						<div class="col-md-6">
        							<label>Percentuale di sconto</label>
    								<div class="form-group input-group">
    									<input type="text" class="form-control" name="salePercentage" placeholder="NNNN.DD" required="true">
    									<span class="input-group-addon">%</span>
    								</div>
        						</div>
        			      		<div class="col-md-6">
        			      			<label>Ammontare sconto</label>
        			      			<div class="form-group input-group">
        			      				<input type="text" class="form-control" name="saleAmount" placeholder="NNNN.DD" required="true">
        			      				<span class="input-group-addon">€</span>
        			      			</div>
        			      		</div>
        		      		</div>
        		    	 	<div class="row p-b-35">
        		    	 		<div class="col-xs-12 p-b-35">
    		    	 				<p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente. Per associare/disassociare un prodotto allo sconto è sufficiente cliccare sulla checkbox sul lato sinistro della riga in questione, il sistema aggiornerà automaticamente l'associazione.</p>
    		    	 				<p><strong>N.B</strong> Le informazioni relative allo sconto <strong>NON</strong> verrano perse durante il procedimento.</strong></p>
    		    	 			</div>
    		    	 			<div class="col-xs-12">
    		    	 			    <ul class="nav nav-tabs nav-blue">
	    	 						    <li class="active"><a class="pointer" data-target="#tabSale_1" data-toggle="tab">Associazione n°1</a></li>
	    	 						    <li><a id="newSaleTab" class="pointer"><i class="ion ion-plus"></i></a></li>
	    	 						</ul>
	    	 						<div class="tab-content">
	    	 						    <div class="tab-pane active" id="tabSale_1">
	    	 						    	<p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente e nel caso si voglia, programmare quest&#39;ultimo. Nel caso in cui non venga inserita alcuna datazione, lo sconto sarà fisso.</p>
	    	 						    	<div class="row p-b-20">
	    	 						    		<div class="col-xs-12">
		    	 						    		<div class="input-group input-daterange" data-provide="datepicker">
		    	 						    			<span class="input-group-addon"> DA </span>
		    	 						    		    <input type="text" class="form-control" name="saleStart">
		    	 						    		    <span class="input-group-addon"> A </span>
		    	 						    		    <input type="text" class="form-control" name="saleEnd">
		    	 						    		</div>
	    	 						    		</div>
	    	 						    	</div>
	    	 						    	<div class="row">
	    	 						    		<div class="col-xs-12">
    	 						    				<div class="table-responsive">
    	 						    					<table class="table table-hover table-striped table-bordered table-product">
    	 						    				    	<thead>
    	 						    				        	<tr>
    	 						    				        		<th></th>
    	 						    					        	<th>Immagine</th>
    	 						    				            	<th>Titolo</th>
    	 						    				            	<th>Produttore</th>
    	 						    				            	<th>Codice</th>
    	 						    				                <th>Categoria</th>
    	 						    				                <th>Prezzo</th>
    	 						    				            </tr>
    	 						    				        </thead>
    	 						    				        <tbody>
    	 						    						</tbody>
    	 						    					</table>
    	 						    				</div>
    	 						    			</div>
    	 						    			<div class="col-xs-12 pagination">
    	 						    				<ul></ul>
    	 						    			</div> 
    	 						    		</div>
	    	 						    </div>
	    	 						</div>
    		    	 			</div>
        		    	 	</div>
        		    	 	<div class="row">
		    	 				<div class="col-xs-12">
		    	 					<div class="pull-right">
    		    	 					<button type="reset" class="btn btn-default">Cancella</button>
    		    	 					<button type="submit" class="btn btn-default btn-blue">Salva</button>
		    	 					</div>
		    	 				</div>
		    	 			</div>
        		    	 </form>
		        	</div>
	        	</div>
        	</div>
      	</div> 
	</div>
</div>