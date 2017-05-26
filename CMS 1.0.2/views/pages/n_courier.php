<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
         	<div class="col-sm-12">
         		<div class="page-header">
         			<h1>
         				Corrieri <small>Creazione nuovo corriere</small>
         			 </h1>
         			 <ol class="breadcrumb">
         			     <li>
         			     	<i class="fa fa-dashboard"></i> Dashboard
         			     </li>
         			     <li class="active">
         			      	<i class="fa fa-dashboard"></i> Impostazioni
         			      </li>
         			 </ol>
         		</div>
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
									<div class="form-group">
										<label>Nome *</label>
										<input type="text" class="form-control" name="courierName" required="true">
									</div>
									<div class="form-group">
										<label>Tempo di transito *</label>
										<input type="text"  class="form-control" name="courierTime" required="true" >
									</div>
									<div class="form-group">
										<label>URL di tracking</label>
										<input type="text"  class="form-control" name="courierTracking" >
									</div>
									<div class="form-group">
										<label>Regime Fiscale</label>
										<select class="form-control" name="idTax">
											<?php foreach ($taxs as $tax) : ?>
												<option value="<?php echo $tax->idTax; ?>">
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
										<input type="text" class="form-control" name="courierMaxLength" required="true">
									</div>
											    
									<label>Larghezza massima imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">mm</span>
										<input type="text" class="form-control" name="courierMaxWidth" required="true">
									</div>
											    
									<label>Spessore massimo imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">mm</span>
									    <input type="text" class="form-control" name="courierMaxDepth" required="true">
									</div>
											    
									<label>Peso massimo imballaggio *</label>
									<div class="form-group input-group">
										<span class="input-group-addon">g</span>
										<input type="text" class="form-control" name="courierMaxWeight" required="true">
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
										    		<input type="checkbox" value="<?php echo $country->idCountry; ?>" name="idCountry[]"> <?php echo $country->countryName; ?>
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
					     	<button type="submit" class="btn btn-warning">Inserisci</button>
					     </div>
					</div>
				</div>
			</form>
			
			<form class="rangeCourier" method="" action="">
				<div class="col-md-12">
					<div class="box box-yellow">
						<div class="box-header">
							<h3 class="box-title">Ranges dei costi di spedizione</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<input type="hidden" name="courierID" value="" />
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
							<button type="submit" class="btn btn-default" disabled="true">Salva intervallo</button>
							<button type="button" id="btnNewRangeCourier" class="btn btn-default" disabled="true">Crea nuova intervallo</button>
						</div>
					</div>
				</div>
			</form>
			
			<div class="col-md-12">
				<div class="box box-yellow">
					<div class="box-header">
						<h3 class="box-title">Riepilogo range di prezzo</h3>
					</div>
					<div class="box-body">
						<div id="ranges" class="row"></div>
					</div>
				</div>
			</div>
			
		</div>
		
		
		
		<script type="text/javascript">
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
		// CREARE NUOVO CORRIERE
		$('form.courierForm').on('submit', function (e) {
			e.preventDefault();
			
			$.ajax({
			    type: 'post',
			    url: "<?php echo base_url('index.php?/Back/c_CRR_Courier/') ?>",
			    data: $('form').serialize(),
			    
			    success: function (result) {
			    	$("input[name='courierID']").val(result);
			    	$("form.rangeCourier button[type='submit']").prop('disabled', false);
			    	$("form.courierForm button[type='submit']").prop('disabled', true);
			    	alert('Corriere creato con successo');
			    },
			    error: function(result) {
			         alert('Error 107: ' + result);
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
				    $("form.rangeCourier button[type='submit']").prop('disabled', true);
				    $("#btnNewRangeCourier").prop('disabled', false);
				    loadRanges( $("input[name='courierID']").val() );
				},
				error: function(result) {
				     alert('Error 805: ' + result);
				}
			});	
		});
		// AZZERARE VALORI NUOVO RANGE DU PREZZO
		$("#btnNewRangeCourier").click(function() {
			$("input[name='rangeStart']").val('');
			$("input[name='rangeEnd']").val('');
			$("input[name='rangePrice']").val('');
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
		function loadRanges(idC) {
			$("#ranges").empty();

			$.ajax({
				type: 'post',
			    url: '<?php echo base_url(); ?>/index.php?/back/r_CRR_Ranges_byCourier',
			    data: {idCourier: idC},
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
