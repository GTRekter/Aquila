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
			<form class="courierForm" method="" action="">
				<div class="col-md-12">
					<div class="box box-yellow">
						<div class="box-header">
					  		<h3 class="box-title">Informazioni Corriere</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<input type="hidden" name="idCourier" value="<?php echo $courier->idCourier; ?>" />
									<div class="form-group">
										<label data-toggle="tooltip" data-placement="top" title="Caratteri permessi: lettere e spazi. Il nome dello spedizioniere sarà mostrato durante il checkout.">Nome *</label>
										<input type="text" class="form-control" name="courierName" required="true" value="<?php echo $courier->courierName; ?>">
									</div>
									<div class="form-group">
										<label data-toggle="tooltip" data-placement="top" title="Il tempo stimato per la consegna sarà mostrato durante il checkout.">Tempo di transito *</label>
										<input type="text"  class="form-control" name="courierTime" required="true" value="<?php echo  $courier->courierTime; ?>">
									</div>
									<div class="form-group">
										<label>URL di tracking</label>
										<input type="text"  class="form-control" name="courierTracking" value="<?php echo $courier->courierTracking; ?>">
									</div>
									<div class="form-group">
										<label>Regime Fiscale</label>
										<select class="form-control" name="idTax">
											<?php foreach ($taxs as $tax) : ?>
												<option value="<?php echo $tax->idTax; ?>" <?php echo ($tax->idTax == $courier->idTax ? 'selected="true"' : ''); ?>>
													<?php echo ucwords($tax->taxName); ?> (<?php echo $tax->taxValue; ?>%)
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<label>Lunghezza massima imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">mm</span>
										<input type="text" class="form-control" name="courierMaxLength" required="true" value="<?php echo $courier->courierMaxLength; ?>">
									</div>
											    
									<label>Larghezza massima imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">mm</span>
										<input type="text" class="form-control" name="courierMaxWidth" required="true" value="<?php echo $courier->courierMaxWidth; ?>">
									</div>
											    
									<label>Spessore massimo imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">mm</span>
									    <input type="text" class="form-control" name="courierMaxDepth" required="true" value="<?php echo $courier->courierMaxDepth; ?>">
									</div>
											    
									<label>Peso massimo imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">g</span>
										<input type="text" class="form-control" name="courierMaxWeight" required="true" value="<?php echo $courier->courierMaxWeight; ?>">
									 </div>
							 	 </div>
						 	 </div>
					    </div>
						<div class="box-header">
							<h3 class="box-title">Destinazioni</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Nazioni disponibili</label>
									    <div class="row">
									    <?php if($countries) : ?>
									    	<?php foreach ($countries as $country) : ?>	
									    		<div class="col-md-4 col-sm-6">
									    		<?php if($country->idCourier == $courier->idCourier) : ?> 
										    		<input type="checkbox" value="<?php echo $country->idCountry; ?>" name="idCountry[]" 
										    		 checked="true"> <?php echo $country->countryName; ?>
										    	<? endif; ?>
										    	<?php if($country->idCourier != $courier->idCourier && $country->idCourier != null) : ?> 
										    		<input type="checkbox" value="<?php echo $country->idCountry; ?>" name="idCountry[]" 
										    		 disabled="true"> <span style="text-decoration: line-through;"><?php echo $country->countryName; ?></span>
										    	<? endif; ?>
										    	<?php if($country->idCourier == null) : ?> 
										    		<input type="checkbox" value="<?php echo $country->idCountry; ?>" name="idCountry[]"> 
										    		<?php echo $country->countryName; ?>
										    	<? endif; ?>
										    	</div>
										    <?php endforeach; ?>	
										<?php else : ?>
											<div class="col-md-12">
												<p>Tutte le nazioni sono state associate ad un corriere</p>
											</div>
										<?php endif; ?>
									    </div>
									</div>
								</div>
							</div>
						</div>
					    <div class="box-footer">
					     	<button type="submit" class="btn btn-default">Modifica Corriere</button>
					     </div>
					</div>
				</div>
			</form>
			
			<form class="rangeCourier" method="" action="">
				<div class="col-md-12">
					<div class="box box-yellow">
						<div class="box-header">
								<h3 class="box-title">Destinazioni e costi</h3>
						</div>
						<div class="box-body">
							<input type="hidden" name="courierID" value="<?php echo $courier->idCourier; ?>" />
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Spedizione gratuita</label>
										<select class="form-control" name="courierFreeShipping">
											<option value="0">Disattivo</option>
											<option value="1">Attiva</option>
										</select>
									</div>
									<label>Prezzo</label>
									<div class="form-group input-group">
										<span class="input-group-addon">€</span>
										<input type="text" class="form-control" name="rangePrice" required="true">
									</div>
								</div>
								<div class="col-md-6">
									<label>Sarà applicato quando il peso è maggiore di</label>
									<div class="form-group input-group">
										<input type="text" name="rangeStart" class="form-control" value="" />
										<span class="input-group-addon">gr.</span>
									</div>
									<label>Sarà applicato quando il peso è minore di </label>
									<div class="form-group input-group">
										<input type="text" name="rangeEnd" class="form-control" value="" />
										<span class="input-group-addon">gr.</span>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">		
							<button type="submit" class="btn btn-default">Salva intervallo</button>
							<button type="button" id="btnNewRangeCourier" class="btn btn-default" disabled="true">Crea nuova intervallo</button>
						</div>
					</div>
				</div>
			</form>
			
			<form class="removeRange" method="" action="">
				<div class="col-md-12">
					<div class="box box-yellow">
						<div class="box-header">
							<h3 class="box-title">Riepilogo range di prezzo</h3>
						</div>
						<div class="box-body">
							<div id="ranges" class="row"></div>
						</div>
						<div class="box-footer">		
							<button type="submit" class="btn btn-default">Cancella intervallo</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		
		
		<script type="text/javascript">
		$(window).load( function() {
			loadRanges( $("input[name='courierID']").val() );
		});
		// DISABILITARE TEXTBOX E RESETTARNE I VALORI
		$("select[name='courierFreeShipping']").change(function(){
			if( $(this).val() == 1 )
			{
				$("input[name='rangePrice']").prop("disabled", true);
				$("input[name='rangePrice']").val('');
				$("input[name='rangePrice']").parent().removeClass('has-error');
				$("input[name='rangePrice']").parent().removeClass('has-success');
			} else {
				$("input[name='rangePrice']").prop("disabled", false);
			}
		});
		// MODIFICARE NUOVO CORRIERE
		$('form.courierForm').on('submit', function (e) {
			e.preventDefault();
			
			$.ajax({
			    type: 'post',
			    url: "<?php echo base_url('index.php?/Back/u_CRR_Courier/') ?>",
			    data: $('form').serialize(),
			    
			    success: function (result) {
			    	$("input[name='courierID']").val(result);
			    	alert('Corriere modificato con successo');
			    }
			});
		});
		// CREARE NUOVO RANGE DI PREZZO
		$('form.rangeCourier').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('index.php?/Back/c_CRR_Range/') ?>",
				data: $('form').serialize(),
				    
				success: function(result) {
				    alert('SUCCESSO');
				    $("form.rangeCourier button[type='submit']").prop('disabled', true);
				    $("#btnNewRangeCourier").prop('disabled', false);
				    loadRanges( $("input[name='courierID']").val() );
				},
				error: function() {
				    alert('Error 805: ' + result);
				}
			});	
		});
		// CANCELLA INTERVALLO DI PREZZO
		$('form.removeRange').on('submit', function (e) {
			e.preventDefault();
			
			$.ajax({
		    	type: 'post',
		        url: '<?php echo base_url(); ?>/index.php/back/d_CRR_Range',
		        data: $('form.removeRange').serialize(),
		        dataType: 'json',
		        success: function (result) {
					$.each(result, function(id) {
						console.log(result[id]);
						var child = document.getElementById(result[id]);
						var parent = document.getElementById("ranges");
						
						parent.removeChild(child);
					});
					alert('Intervallo di prezzo eliminato');
		        }
		    });
		});
		// AZZERARE VALORI NUOVO RANGE DU PREZZO
		$("#btnNewRangeCourier").click(function() {
			$("input[name='rangeFirst']").val('');
			$("input[name='rangeSecond']").val('');
			$("input[name='courierPrice']").val('');
			$("form.rangeCourier button[type='submit']").prop('disabled', false);
			$("#btnNewRangeCourier").prop('disabled', true);
		});
		// CONTROLLO PREZZI 
		$("input[name='rangeFirst']").change(function() {
			if( checkFirst() == true ) {
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			} else {
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
				$("form.rangeCourier input[type='submit']").prop('disabled', true);
			}
		});
		$("input[name='rangeSecond']").change(function() {
			if( checkSecond() == true ) {
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			} else {
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
				$("form.rangeCourier input[type='submit']").prop('disabled', true);
			}
		});
		// CONTROLLO PREZZO
		$("input[name='rangePrice']").keyup(function(){
			if ( checkPrice() == true ) 
			{	
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			} else {
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
			}
		});
		
		// UPLOAD RANGES
		function loadRanges(idR) {
			$("#ranges").empty();

			$.ajax({
				type: 'post',
			    url: '<?php echo base_url(); ?>/index.php?/back/r_CRR_Ranges_byCourier',
			    data: {idCourier: idR},
			    dataType: 'json',
				
			    success: function(result) {
			    	$.each(result, function(id) {
				    	$("#ranges").append('<div id="' + result[id].idRange + '" class="col-xs-6 col-lg-3"><b>Da</b>: ' + result[id].rangeStart + ' <b>A</b>: ' + result[id].rangeEnd + '<br> <b>Prezzo</b>: ' + result[id].rangePrice + ' €<br><input type="checkbox" name="idRanges[]" value=' + result[id].idRange + ' /> Cancella</div>'); 
			    	});
			    },
			    error: function(error) {
			    	alert('ERROR');
			    	console.log(error);
			    }
			}); 
		}	
		// CONTROLLO VALORE PRIMA TEXTBOX
		function checkFirst() {
			var result = true;
			if( $("input[name='rangeFirst']").val() >= $("input[name='rangeSecond']").val() && $("input[name='rangeSecond']").val() != '') {
				alert('Controllo 1: valore 1 maggire o uguale di valore 2');
				result = false;
			}
			var pattern = new RegExp('^[0-9]*$');
			if(!pattern.test($("input[name='rangeFirst']").val()))
			{
				alert('Controllo 1: inseriti dei caratteri');
				result = false;
			}
			return result;
		}
		function checkSecond() {
			var result = true;
			if( $("input[name='rangeFirst']").val() >= $("input[name='rangeSecond']").val() && $("input[name='rangeFirst']").val() != '') {
				alert('Controllo 2: valore 1 >= valore 2');
				result = false;
			}
			var pattern = new RegExp('^[0-9]*$');
			if(!pattern.test($("input[name='rangeSecond']").val()))
			{
				alert('Controllo 2: inseriti dei caratteri');
				result = false;
			}
			return result;
		}
		// CONTROLLO Prezzo
		function checkPrice() {
			var price = $("input[name='rangePrice']").val();
			var pattern = new RegExp('^[0-9]+([,.][0-9]{1,2})?$');

			if(!pattern.test(price))
		  	{
		    	price = "";
		    	return false;
		  	}else{
		    	return true;
		  	}
		}
		</script>
		
		<script>
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})
		</script>
		
	</div>
</div>
