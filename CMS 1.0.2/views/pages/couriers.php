<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Corrieri <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Corrieri</li>
		        </ol>
		    </div>
		</div> 	
		
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-yellow cl-white">
			            		<i class="ion ion-ios-box-outline sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Corrieri</span>
			            	<span class="data">N.D</span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-yellow cl-white">
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
			        		<div class="icon-box bg-yellow cl-white">
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
			        		<div class="icon-box bg-yellow cl-white">
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
         
        <form id="modifyCourier" method="POST" action="">	
	        <div class="row">
	        	<div class="col-md-12">
	        		<div class="box internal-box articles-box brd-yellow">
						<div class="box-header">
							<h3 class="box-title">Lista Corrieri</h3>
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
								<a class="addProduct" href="<?php echo site_url('back/n_courier'); ?>"> <i class="ion ion-plus-circled"></i></a>
							</span>
						</div>
						
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-articles">
							    	<thead>
							        	<tr>
							        		<th></th>
							            	<th>Nome Corriere</th>
							                <th>Ritardo</th>
							                <th>Nazioni</th>
							                <th>Stato</th>
							            </tr>
							        </thead>
							        <?php if($couriers) : ?>
							        	<?php foreach ($couriers as $courier) : ?>
							            	<tr>
							            		<td>
							            			<input type="checkbox" name="idCourier[]" value="<?php echo $courier->idCourier; ?>" />
							            		</td>
							            		<td href="<?php echo site_url('back/courier/'.$courier->idCourier); ?>"><?php echo ucfirst($courier->courierName); ?></td>
							            		<td href="<?php echo site_url('back/courier/'.$courier->idCourier); ?>"><?php echo $courier->courierTime; ?> Ore</td>
							            		<td href="<?php echo site_url('back/courier/'.$courier->idCourier); ?>">
							            		<?php 
							            		if($countries) {
							        	    		foreach ($countries as $country) {
							        	    			echo ($country->idCourier == $courier->idCourier ?  $country->countryName.' | ' : '');
							        	    		}
							            		}; 
							            		?>
							            		</td>
							            		<td href="<?php echo site_url('back/courier/'.$courier->idCourier); ?>"s><?php echo ($courier->courierStatus  ? 'Attivo' : 'Disattivo'); ?></td>
							            	</tr>
							        	<?php endforeach; ?>
									</table>
								<?php else : ?>
								    </tbody>
								</table>
								<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun corriere presente</p></div>
								<?php endif; ?>
							</div>	
						</div>	
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    $('table tr td:nth-child(n+2)').click(function(){
        window.location = $(this).attr('href');
        return false;
    });
});

$(document).on('click','#delete',function(){
	$.ajax({
		type: 'post',
	    url: "<?php echo base_url('index.php/Back/d_ORD_Couriers'); ?>",
	    data: $("#modifyCourier").serialize(),
		dataType: 'json',
		
	    success: function(result) {
	    	alert(result);
	    	location.reload();
	    },
	    error: function(error) {
	    	alert('Errore 631:' + result);
	    }
	});
});
</script>

