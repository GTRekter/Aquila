<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Ordini <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Ordini</li>
		        </ol>
		    </div>
		</div>	
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="fa fa-truck sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Ordini</span>
			            	<span class="data"><?php echo $total_orders; ?></span>
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
			            	<span class="title">Media Introiti</span>
			            	<span class="data"><?php echo $average_order_amount ?> €</span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="ion ion-ios-cart sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Carrello Maggiore</span>
			            	<span class="data"><?php echo $major_order; ?> €</span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="fa fa-credit-card sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Pagamento</span>
			            	<span class="data"><?php echo $major_payment_method; ?></span>
			             </div>
			        </div>
			    </div>
			</div>
		</div>
         
        <div id="orders" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-blue">
					<div class="box-header">
						<h3 class="box-title">Lista Ordini</h3>
					</div>
					<div class="box-body">
						<p>In questa sezione sarà possibile prendere visione di tutti gli ordini eseguiti dai clienti. La presenza di un ordine comporta l'avvenuto pagamento di quest'ultimo.  le specifiche di tutti i prodotti. Le specifiche sono valori che valgono per tutte le combinazioni del prodotto e rimangono fissi per tutte le combinazioni.</p>
						<div class="table-responsive">
							<table class="table table-hover table-striped table-condensed">
						    	<thead>
						        	<tr>
						        		<th>Riferimento</th>
							        	<th>Cliente</th>
						            	<th>Consegna</th>
						            	<th>Totale</th>
						            	<th>Stato Ordine</th>
						                <th>Verifica Paypal</th>
						                <th>Data di Creazione</th>
						            </tr>
						        </thead>
						        <tbody>
						        <?php if($orders) : ?>
						        	<?php foreach($orders as $order) : ?>
										<tr>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php echo $order->idPaypal; ?>
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php 
													if ($order->idClient != NULL) {
														echo ucfirst(substr($order->clientName, 0, 1)); ?>.<?php echo ucfirst($order->clientSurname);
													} else {
														echo 'N.D';
													}
												?>
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php echo $order->shippingCountryName; ?> 
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php echo $order->orderAmount + $order->shippingAmount; ?> €
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php 
													switch ($order->orderStatus) {
														case 0: echo 'In attesa di spedizione'; break;
														case 1: echo 'Completato'; break;
													} 
												?>
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php 
													if ($order->verificationPaypal == 1) {
														echo 'Verificato'; break;
													} else {
														echo 'Non Verificato';
													}
												?>
											</td>
											<td class="lineOrderDetail" data-id="<?php echo $order->idOrder; ?>">
												<?php echo $order->createdOn; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<?php else : ?>
								</tbody>
							</table>
							<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun ordine presente</p></div>
							<?php endif; ?>
					    </div>
				    </div>
				    <div class="box-footer pagination">
				    	<ul>
				    		<?php echo $pagination ?>
				    	</ul>
				    </div>
				</div>	
			</div>	
		</div>
        
        <div id="order-detail" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-blue">
					<div class="box-header">
						<h3 class="box-title">Informazioni Ordine n° <span id="id-order"></span></h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<p>In questa sezione sono visualizzate tutte le informazioni relative all'ordine. Per modificare lo stato dell'ordine è sufficiente selezionare il nuovo stato e cliccare su "Aggiorna". Il cliente Riceverà automaticamente una mail per avvisarlo che il prodotto è stato spedito.</p>
							</div>
						</div>
						<ul class="nav nav-tabs nav-blue" role="tablist">
						    <li role="presentation" class="active">
						    	<a href="#order-general-informations" aria-controls="home" role="tab" data-toggle="tab">Informazioni Generali</a>
						    </li>
						    <li role="presentation">
						    	<a href="#order-shipping-informations" aria-controls="profile" role="tab" data-toggle="tab">Informazioni Acquirente</a>
						    </li>
						    <li role="presentation">
						    	<a href="#order-products" aria-controls="profile" role="tab" data-toggle="tab">Informazioni Prodotti</a>
						    </li>
						</ul>
						<div class="tab-content">
						    <div role="tabpanel" class="tab-pane active" id="order-general-informations"></div>
						    <div role="tabpanel" class="tab-pane" id="order-shipping-informations"></div>
						    <div role="tabpanel" class="tab-pane" id="order-products">
						    	<table class="table table-hover table-striped table-condensed">
						    		<thead>
						    	    	<tr>
						    	    		<th>Immagine</th>
						    	        	<th>Nome Prodotto</th>
						    	        	<th>Prezzo</th>
						    	        	<th>Qty</th>
						    	        	<th>Prezzo Totale</th>
						    	        </tr>
						    	    </thead>
						    	    <tbody>
						    	    </tbody>
						    	</table>
						    </div>
						</div>
					</div>
				</div>
			</div>	
        </div>
	</div>
</div>
