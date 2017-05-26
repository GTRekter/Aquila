<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
		 	<div class="col-xs-12">
		    	<h1 class="page-header">
		        	Ebay <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		             <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		             <li class="active">Ebay</li>
		        </ol>
		     </div>
		</div>

        <div class="row">
         	<form id="ebaySettings" method="" action="">	
				<div class="col-md-12">
					<div class="box internal-box brd-pink">
						<div class="box-header">
							<h3 class="box-title">Informazioni generali</h3>
						</div>
						<div class="box-body">
							<div class="p-b-35">
        		    	 		<p>In questa sezione potrai inserire tutte le informazioni necessarie alla corretta sincronizzazione con eBay. Prima di tutto è necessario generare la Session ID, accedere ad eBay ed accettare le condizioni d'uso. Successivamente sarà necessario generare un Token di accesso e per concludere un User ID.</p>
    		    	 		</div>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-xs-12">
											<label>eBay SessionID</label>
										</div>
										<div class="col-sm-7">
											<div class="form-group">
												<input type="text" class="form-control" name="sessionId" value="<?php echo $settings->sessionId; ?>">
											</div>
										</div>
										<div class="col-sm-5">
											<button type="button" id="btnSessionEbay" class="btn btn-pink full-width">Genera SessionID</button>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<label>eBay Token</label>
										</div>
										<div class="col-sm-7">
											<div class="form-group">
												<input type="text" class="form-control" name="accessToken" value="<?php echo $settings->accessToken; ?>">
											</div>
										</div>
										<div class="col-sm-5">
											<button type="button" id="btnTokenEbay" class="btn btn-pink full-width">Genera Access Token</button>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<label>Utente eBay</label>
										</div>
										<div class="col-sm-7">
											<div class="form-group">
												<input type="hidden" name="storeOwner"/>
												<input type="text" class="form-control" name="userId" value="<?php echo $settings->userId; ?>">
											</div>
										</div>
										<div class="col-sm-5">
											<button type="button" id="btnUserId" class="btn btn-pink full-width">Controlla User ID</button>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Nazione eBay</label>
										<select class="form-control" name="siteId" disabled="true">
											<?php for ($i = 0; $i < count($sites); $i++) {
												if ($settings->siteId == $sites[$i]->siteId) {
													$isSelected = 'selected';
												} else {
													$isSelected = '';
												}
												echo '<option value="'.$sites[$i]->siteId.'" '.$isSelected.'>'.$sites[$i]->siteName.'</option>';
											} ?>
										</select>
									</div>
									<div class="form-group">
										<label>Durata preparazione spedizione</label>
										<input type="text" class="form-control" name="dispatchTimeMax" value="<?php echo $settings->dispatchTimeMax; ?>">
									</div>
									<div class="form-group">
										<label>Durata inserzione</label>
										<select class="form-control" name="listingDuration">
											<option value="Days_3" <?php echo($settings->listingDuration=='Days_3'?'selected':''); ?>>3 giorni</option>
											<option value="Days_5" <?php echo($settings->listingDuration=='Days_5'?'selected':''); ?>>5 giorni</option>
											<option value="Days_7" <?php echo($settings->listingDuration=='Days_7'?'selected':''); ?>>7 giorni</option>
											<option value="Days_10" <?php echo($settings->listingDuration=='Days_10'?'selected':''); ?>>10 giorni</option>
											<option value="Days_14" <?php echo($settings->listingDuration=='Days_14'?'selected':''); ?>>14 giorni</option>
											<option value="Days_21" <?php echo($settings->listingDuration=='Days_21'?'selected':''); ?>>21 giorni</option>
											<option value="Days_30" <?php echo($settings->listingDuration=='Days_30'?'selected':''); ?>>30 giorni</option>
											<option value="Days_60" <?php echo($settings->listingDuration=='Days_60'?'selected':''); ?>>60 giorni</option>
											<option value="Days_90" <?php echo($settings->listingDuration=='Days_90'?'selected':''); ?>>90 giorni</option>
											<option value="Days_120" <?php echo($settings->listingDuration=='Days_120'?'selected':''); ?>>120 giorni</option>
											<option value="GTC" <?php echo($settings->listingDuration=='GTC'?'selected':''); ?>>Fino a cancellazione del prodotto</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="box-header">
							<h3 class="box-title">Informazioni ritorno</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Ritorno</label>
										<select class="form-control" name="returnsOptions">
											<option value="MoneyBack" <?php echo($settings->returnsOptions=='MoneyBack'?'selected':''); ?>>Ritorno denaro</option>
											<option value="MoneyBackOrExchange" <?php echo($settings->returnsOptions=='MoneyBackOrExchange'?'selected':''); ?>>Ritorno denaro o cambio prodotto</option>
										</select>
									</div>
									
									<div class="form-group">
										<label>Validità ritorno</label>
										<select class="form-control" name="returnsWithinOptions">
											<option value="Days_14" <?php echo($settings->returnsWithinOptions=='Days_14'?'selected':''); ?>>14 giorni</option>
											<option value="Days_30" <?php echo($settings->returnsWithinOptions=='Days_30'?'selected':''); ?>>30 giorni</option>
											<option value="Days_60" <?php echo($settings->returnsWithinOptions=='Days_60'?'selected':''); ?>>60 giorni</option>
										</select>
									</div>
									<div class="form-group">
										<label>Carico dei costi di spedizione nel ritorno</label>
										<select class="form-control" name="shippingCostPaidByOption">
											<option value="Buyer" <?php echo($settings->shippingCostPaidByOption=='Buyer'?'selected':''); ?>>Cliente</option>
											<option value="Seller" <?php echo($settings->shippingCostPaidByOption=='Seller'?'selected':''); ?>>Venditore</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Descrizione Ritorno</label>
										<textarea class="form-control" rows="9" name="ReturnsDescription"><?php echo $settings->returnsDescription; ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="box-header">
							<h3 class="box-title">Informazioni pagamenti</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<label>Metodi di pagamento</label>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<div class="checkbox">
													<label>
												    	<input type="checkbox" name="paypal" checked="true" disabled="true">Paypal
												    </label>
												</div>
												<div class="checkbox">
													<label>
												    	<input type="checkbox" name="creditCard" value="1" <?php echo $settings->creditCard == 1 ? 'checked' : '' ?> >Carta di credito
												    </label>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="checkbox">
													<label>
												    	<input type="checkbox" name="personalCheck" value="1" <?php echo $settings->personalCheck == 1 ? 'checked' : '' ?> >Assegno
												    </label>
												</div>
												<div class="checkbox">
													<label>
												    	<input type="checkbox" name="postalTransfert" value="1"  <?php echo $settings->postalTransfert == 1 ? 'checked' : '' ?> >Trasferimento postale
												    </label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Indirizzo di posta Paypal</label>
										<input type="text" class="form-control" name="paypalEmail" value="<?php echo $settings->paypalEmail; ?>">
									</div>
									<div class="form-group">
										<label>Descrizione Pagamento</label>
										<textarea class="form-control" rows="4" name="paymentsDescription"><?php echo $settings->paymentsDescription; ?></textarea>
									</div>
								</div>
							</div>
						</div>	
						<div class="box-header">
							<h3 class="box-title">Informazioni garanzia</h3>
						</div>
						<div class="box-body">	
							<div class="row">		
								<div class="col-md-6">
									<div class="form-group">
										<label>Garanzia Offerta</label>
										<select class="form-control" name="warrantyOfferedOption">
											<option value="null" <?php echo($settings->warrantyOfferedOption==null?'selected':''); ?>>No</option>
											<option value="WarrantyOffered" <?php echo($settings->warrantyOfferedOption=='WarrantyOffered'?'selected':''); ?>>Si</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Durata garanzia</label>
										<select class="form-control" name="warrantyDurationOption" disabled="true">
											<option value="Months_1" <?php echo($settings->warrantyDurationOption=='Months_1'?'selected':''); ?>>1 Mese</option>
											<option value="Months_3" <?php echo($settings->warrantyDurationOption=='Months_3'?'selected':''); ?>>3 Mesi</option>
											<option value="Months_6" <?php echo($settings->warrantyDurationOption=='Months_6'?'selected':''); ?>>6 Mesi</option>
											<option value="Years_1" <?php echo($settings->warrantyDurationOption=='Years_1'?'selected':''); ?>>1 Anno</option>
											<option value="Years_2" <?php echo($settings->warrantyDurationOption=='Years_2'?'selected':''); ?>>2 Anni</option>
											<option value="Years_3" <?php echo($settings->warrantyDurationOption=='Years_3'?'selected':''); ?>>3 Anni</option>
											<option value="Years_MoreThan3" <?php echo($settings->warrantyDurationOption=='Years_MoreThan3'?'selected':''); ?>>Più di 3 anni</option>
										</select>
									</div>
									<div class="form-group">
										<label>Tipo di garanzia</label>
										<select class="form-control" name="warrantyTypeOption" disabled="true">
											<option value="DealerWarranty" <?php echo($settings->warrantyTypeOption=='DealerWarranty'?'selected':''); ?>>Garanzia offerta dall'inserzionista</option>
											<option value="ManufacturerWarranty" <?php echo($settings->warrantyTypeOption=='ManufacturerWarranty'?'selected':''); ?>>Garanzia offerta dal produttore</option>
											<option value="ReplacementWarranty" <?php echo($settings->warrantyTypeOption=='ReplacementWarranty'?'selected':''); ?>>Oggetto sostituito, se sotto garanzia</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
									<button type="reset" class="btn btn-default">Cancella</button>
									<button type="submit" class="btn btn-pink">Salva Impostazioni</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<form id="ebayCategories">	
				<div class="col-md-12">
					<div class="box internal-box brd-pink">
						<div class="box-header">
							<h3 class="box-title">Sincronizzazione categorie eBay</h3>
						</div>
						<div class="box-body">
							<?php foreach ($categories as $category) : ?>
								<div class="row">
						            <div class="col-md-3">
						            	<input type="hidden" name="idCategory[]" value="<?php echo $category->idCategory ?>" />
						                <p><?php echo ucfirst($category->categoryName); ?></p>
						            </div>     
					               	<div class="col-md-3">
					               		<div class="form-group">
						                	<select name="cat00[]" class="form-control">
						                		<option value="null">Seleziona la categoria</option>
						                		<?php for ($i = 0; $i < count($ebaycategories); $i++)  : ?>
						                			<option value="<?php echo $ebaycategories[$i]->idEbayCategory ?>">
						                				<?php echo $ebaycategories[$i]->name ?> ( <?php echo $ebaycategories[$i]->idEbayCategory ?> )
						                			</option>
						                		<?php endfor; ?>
						                	</select>
					                	</div>
					                </div>          
					                <div class="col-md-3">
					                	<div class="form-group">
						                	<select name="cat01[]" class="form-control" disabled="true">
						                		<option value="null">Seleziona la sotto-categoria</option>
						                	</select>
					                	</div>
					                </div>        
					                <div class="col-md-3">
					                	<div class="form-group">
						                	<select name="idEbayCategory[]" class="form-control" disabled="true">
						                		<option value="null">Seleziona la sotto-categoria</option>	
						                	</select>
					                	</div>
					                </div>
								</div>
						   	<?php endforeach; ?>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
									<button type="reset" class="btn btn-default">Cancella</button>
									<button type="submit" class="btn btn-pink">Salva Impostazioni</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>	
			
			<form class="test">
				<button type="submit" class="btn btn-pink">Test getStore</button>
			</form>		
		</div>
         
         <script type="text/javascript">
	         function getSessionID() {
	         	var _tempSessionID;
	         	$.ajax({
	     			type: 'post',
	     			dataType: 'json',
	     		    url: '<?php echo base_url(); ?>/index.php/back/getSessionID',
	     		    async: false,
	     		    success: function(result) {
	     		    	_tempSessionID = result;
	     		    }
	     		});
	     		return _tempSessionID;
	         }
	         function getToken() {
	         	var _tempToken;
	         	$.ajax({
	         		type: 'post',
	         	    url: '<?php echo base_url(); ?>/index.php/back/getToken',
	         	    async: false,
	         	    success: function(result) {
	         	    	_tempToken = result;
	         	    }
	         	});
	         	return _tempToken;
	         }
	         function getEbayAuth() {
	          	$.ajax({
	          		type: 'post',
	          		url: '<?php echo base_url(); ?>/index.php/back/r_STN_Ebay',
	          		dataType: 'json',
	          		success: function(result) {
	          		   	window.open('https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&RUName='+result.ruName+'&SessID='+result.sessionId,'_blank');
	          		}
	          	});
	         }
	         function getUser(userId) {
	         	var _tempUser;
	         	$.ajax({
	         		type: 'post',
	         		dataType: 'json',
	         	    url: '<?php echo base_url(); ?>/index.php/back/getUser',
	         	    data: {userId: userId},
	         	    async: false,
	         	    success: function(result) {
	         	    	_tempUser = result;
	         	    }
	         	});
	         	return _tempUser;
	         }
	         function getCategories(parentID, level, destination) {
	         	$.ajax({
	         		type: "post",
	         		url: '<?php echo base_url(); ?>/index.php/back/getCategories/',
	         		data: {parentCat: parentID, levelCategories: level},
	         		dataType: 'json',
	         		success: function(result) {
	         			destination.empty();
	         			destination.append( $("<option></option>").attr("value", 'null').text("Seleziona la sotto-categoria"));
	         			$.each(result, function(id) {
	         				if (result[id].level != 1) {
	         					destination.append( $("<option></option>").attr("value", result[id].idEbayCategory).text(result[id].name + " ( " + result[id].idEbayCategory + " )")); 
	         			    }
	         			});
	         		},
	         		complete: function(){
	         			destination.prop("disabled", false);
	         		}
	         	});
	         }
	         function getStore(userId) {
	         	$.ajax({
	             	type: 'post',
	             	dataType: 'json',
	                 url: '<?php echo base_url(); ?>/index.php/back/getStore',
	                 data: {userId: userId},  
	                 async: false,
	                 
	                 success: function (result) {
	                 	console.log(result);
	                 }
	             });
	         }
	         
	         $(document).on('click','#btnUserId',function() {
	         	var userId = $("input[name='userId']").val();
	         	var $this = $(this);
	         	loading($this,'progress','');
	         	var userInfo = getUser(userId);
	         	if (userInfo.error) {
	         		loading($this,'error',userInfo.error);
	         	} else {
	         		if (userInfo.StoreOwner == true) {
	         			$("input[name='storeOwner']").val(1);
	         		} else {
	         			$("input[name='storeOwner']").val(0);
	         		}
	         		loading($this,'complete','Utente Verificato');
	         	}
	         	
	         });
	         $(document).on('click','#btnSessionEbay',function() {
	         	var sessionID = getSessionID();
	         	console.log(sessionID);
	         	getEbayAuth();
	         	$("input[name='sessionId']").val(sessionID);
	         });
	         $(document).on('click','#btnTokenEbay',function() {
	         	var $this = $(this);
	         	loading($this,'progress','');
	         	var accessToken = getToken();
	         	if (accessToken.error) {
	     			loading($this,'error',accessToken.error);
	     		} else {
	     			loading($this,'complete','Token generato Correttamente');
	     			$("input[name='accessToken']").val(accessToken);
	     		}
	         });
	         $(document).on('submit','#ebaySettings', function (e) {
	         	e.preventDefault();
	         	var $this = $(this);
	         	loading($this,'progress','');
	         	$.ajax({
	             	type: 'post',
	                 url: '<?php echo base_url(); ?>/index.php/back/u_STN_Ebay',
	                 data: $('#ebaySettings').serialize(),  
	                 success: function () {
	                 	loading($this,'complete','Salvataggio Completato');
	                 },
	                 error: function (result) {
	                 	loading($this,'error','Errore 001 - Salvataggio non Completato');
	                 }
	             });
	         });   
	         $(document).on('change',"select[name='warrantyOfferedOption']", function() {
	         	if ($(this).val() == 'WarrantyOffered') {
	         		$("select[name='warrantyTypeOption']").prop("disabled",false);
	         		$("select[name='warrantyDurationOption']").prop('disabled',false);
	         	} else {
	         		$("select[name='warrantyTypeOption']").prop("disabled",true);
	         		$("select[name='warrantyDurationOption']").prop('disabled',true);
	         	}
	         });
	         
	     	 $(document).on('change',"select[name='cat00[]']",function() {
	     	 	 var parent = $(this).val();
	     		 var destination = $(this).closest('.row').find("select[name='cat01[]']");
	     		 getCategories(parent, 2, destination);
	     	 });
	     	 $(document).on('change',"select[name='cat01[]']",function() {
	     	  	 var parent = $(this).val();
	     	 	 var destination = $(this).closest('.row').find("select[name='idEbayCategory[]']");
	     	 	 getCategories(parent, 3, destination);
	     	 });
     	 
     	 	 
 			 $(document).on('submit', '#ebayCategories', function (e) {
 			     e.preventDefault();
 			     var $this = $(this);
 			     loading($this,'progress','');
 			 	 $.ajax({
         	     	type: 'post',
         	     	dataType: 'json',
         	        url: '<?php echo base_url(); ?>/index.php/back/u_STN_Ebay_Category',
         	        data: $('#ebayCategories').serialize(),  
         	        
         	        success: function (result) {
         	        	if (result.error) {
     	        			loading($this,'error',result.error);
     	        		} else {
     	        			loading($this,'complete',result);
     	        		}
         	        }
         	     });
 			 });
         
         
//         $(document).on('submit','.test', function (e) {
//         	e.preventDefault();
//         	var name = 'testuser_webevo';
//         	getStore(name);
//         });
         
         
         </script>
	</div>
</div>
