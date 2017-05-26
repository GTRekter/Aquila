<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
          	<div class="col-xs-12">
             	<h1 class="page-header">
                 	Valute <small>Riepilogo</small>
                 </h1>
                 <ol class="breadcrumb">
                      <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                      <li class="active">Valute</li>
                 </ol>
              </div>
         </div> 

        <div id="modifyCurrency" class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-yellow">
					<div class="box-header">
						<h3 class="box-title">Lista Valute</h3>
						<span id="refresh" class="header-action pull-right pointer">
							<i class="fa fa-refresh" aria-hidden="true"></i>
						</span>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped table-condensed table-currency">
						    	<thead>
						        	<tr>
						            	<th>Codice</th>
						            	<th>Nome</th>
						                <th>Tasso di cambio</th>
						                <th>Stato</th>
						            </tr>
						        </thead>
						        <tbody>
						        <?php if($currencies) : ?>
						        	<?php foreach ($currencies as $currency) : ?>
								    	<tr>
								    		<td class="lineCurrencyDetail" data-id="<?php echo $currency->idCurrency ?>">
								    			<?php echo strtoupper($currency->currencyCode); ?>
								    		</td>
								    		<td class="lineCurrencyDetail" data-id="<?php echo $currency->idCurrency ?>">
								    			<?php echo ucwords($currency->currencyName); ?>
								    		</td>
								    		<td class="lineCurrencyDetail" data-id="<?php echo $currency->idCurrency ?>">
								    			1 € = <?php echo $currency->currencyValue.' '.$currency->currencySymbol; ?> 
								    		</td>
								        	<td class="lineCurrencyDetail" data-id="<?php echo $currency->idCurrency ?>">
								        		<?php echo $currency->currencyStatus == 1 ? 'Attivo' : 'Non Attivo' ; ?>
								        	</td>
								    	</tr>
						    		<?php endforeach; ?>
						    		</tbody>
						    	</table>
						    <?php else : ?>
						        </tbody>
						    </table>
						    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna valuta presente</p></div>
						    <?php endif; ?>
						</div>	
					</div>
				</div>
			</div>	
		</div>
        
        <div id="currency-detail" class="row">
        	<div class="col-md-12">
        		<div class="box internal-box box-yellow">
        			<div class="box-header">
        				<h3 class="box-title">Informazioni Valuta </h3>
        			</div>
        			<form id="currency-detail-form" method="post" action="">
        				<div class="box-body">
        					<div class="row">
        						<div class="col-xs-12">
        							<p>In questa sezione sono visualizzate tutte le informazioni relative alla valuta selezionata. Il tasso di cambio viene aggiornato tramite API a Yahoo Finance. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
        						</div>
        						<div class="col-xs-12">
        							<div class="row currency-general-informations"></div>
        						</div>
        					</div>
        				</div>
        				<div class="box-footer">
        					<div class="row">
        						<div class="col-xs-12 text-right">
        				    		<button type="reset" class="btn btn-default">Cancella</button>
        				    		<button type="submit" class="btn btn-warning">Salva</button>
        						</div>
        					</div>
        				</div>
        			</form>
        		</div>
        	</div>	
        </div>
		
	</div>
</div>