<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Clienti <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Clienti</li>
		        </ol>
		    </div>
		</div> 
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="ion ion-person-stalker sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Clienti</span>
			            	<span class="data"><?php echo $total_clients ?></span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="fa fa-home sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Privati</span>
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
			            		<i class="fa fa-building-o sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Aziende</span>
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
			            		<i class="ion ion-stats-bars sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Media Ordini</span>
			            	<span class="data">N.D</span>
			             </div>
			        </div>
			    </div>
			</div>
		</div>	
		
        <div class="row" id="clients">
			<div class="col-md-12">
				<div class="box internal-box client-box brd-blue">
					<div class="box-header">
						<h3 class="box-title">Lista Clienti<span class="badge"><?php echo count($clients); ?></span></h3>
						<span class="header-action pull-right">
							<i id="delete" class="ion ion-trash-a pointer"></i>
						</span>
						<span class="header-action pull-right">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="ion ion-plus-circled"></i>
							</a>
							<ul class="dropdown-menu">
								<li><a class="addClient" href="<?php echo site_url('back/n_client/private') ?>"> Privato</a></li>
								<li><a class="addClient" href="<?php echo site_url('back/n_client/company') ?>"> Azienda</a></li>
							</ul>
						</span>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover">
						    	<thead>
						        	<tr>
						            	<th>Nome</th>
						                <th>Cognome</th>
						                <th>Nazionalità</th>
						                <th>Cod. Fiscale</th>
						                <th>Indirizzo Email</th>
						                <th>Data Registrazione</th>
						            </tr>
						        </thead>
						        <tbody>
						        <?php if($clients) : ?>
						        	<?php foreach ($clients as $client) : ?>
								    	<tr>
								    		<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>"><?php echo ucfirst($client->clientName); ?></td>
								    		<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>"><?php echo ucfirst($client->clientSurname); ?></td>
								        	<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>"><?php echo $client->countryName; ?></td>
								        	<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>">
								        	<?php 
								        		if ($client->clientFiscalCode == NULL) {
								        			echo 'Non Disponibile';
								        		} else {
								        			echo $client->clientFiscalCode;
								        		} 
								        	?>
								        	</td>
								        	<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>"><?php echo $client->clientEmail; ?></td>
								        	<td class="lineClientDetail" data-id="<?php echo $client->idClient; ?>"><?php echo $client->createdOn; ?></td>
								    	</tr>
						    		<?php endforeach; ?>
						    	</tbody>
						    </table>
						    <?php else : ?>
					    		</tbody>
					    	</table>
						    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun cliente presente</p></div>
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
        
        <div id="client-detail" class="row">
        	<div class="col-md-12">
        		<div class="box internal-box client-box brd-blue">
        			<div class="box-header">
        				<h3 class="box-title">Informazioni Cliente <span id="invoice-order"></span></h3>
        			</div>
        			<div class="box-body">
        				<div class="row">
        					<div class="col-xs-12">
        						<p>In questa sezione sono visualizzate tutte le informazioni relative al cliente selezionato. Queste informazioni possono essere modificate soltanto dall'utente stesso nell'apposita pagina del front-end relativa al proprio account. </p>
        					</div>
        				</div>
        				<div class="row" id="client-general-informations"></div>
        			</div>
        			<div class="box-header">
        				<h3 class="box-title">Statistiche Cliente <span id="invoice-order"></span></h3>
        			</div>
        			
        			<div id="box-client-charts" class="box-body">
        			</div>
        			
        		</div>
        	</div>	
        </div>
        
	</div>
</div>