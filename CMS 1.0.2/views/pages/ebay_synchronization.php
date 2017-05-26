<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
		 	<div class="col-xs-12">
		    	<h1 class="page-header">
		        	Ebay Sincronizzazione <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		             <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		             <li class="active">Ebay Sincronizzazione</li>
		        </ol>
		     </div>
		</div>
		
		<div class="row">
<!--			<div class="col-md-6">
				<form id="ebayOrders" method="" action="">
					<div class="box internal-box brd-pink">
						<div class="box-header">
							<h3 class="box-title">Sincronizzazione ordini</h3>
						</div>
						<div class="box-body"></div>
				        <div class="box-footer">
				        	<div class="row">
				        		<div class="col-xs-12 text-right">
				        			<button type="submit" class="btn btn-pink">Sincronizza</button>
				        		</div>
				        	</div>
				        </div>
				    </div>
			    </form>
			</div>-->
			<div class="col-md-12">
				<form id="ebayProducts">
					<div class="box internal-box brd-pink">
						<div class="box-header">
							<h3 class="box-title">Sincronizzazione Prodotti</h3>
						</div>
						<div class="box-body">
							<p>In questa sezione sarà possibile sincronizzare i prodotti inseriti all'interno del portale eCommerce sul marketplace eBay. In caso di mancata sincronizzazione di un prodotto, verrà mostrato un'avviso nella tabella sottostante.</p>
						</div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
									<button type="submit" class="btn btn-pink">Sincronizza</button>
								</div>
							</div>
						</div>
				    </div>
			    </form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">  
     $(document).on('click',"#ebayProducts",function(e) {
     	e.preventDefault();
     	var $this = $(this);
     	loading($this,'progress','');
     	$.ajax({
     		type: 'post',
     		dataType: 'json',
     	    url: '<?php echo site_url(); ?>/back/postProducts',
     		
     	    success: function(result) {
     	    	if (result.error) {
 	    			loading($this,'error',result.error);
 	    		} else {
 	    			loading($this,'complete',result);
 	    		}
     	    },
     	    error: function(error) {
     	    	loading($this,'error','Error ');
     	    	console.log(error);
     	    }
     	});
     });    
       
//     $("#btnSincOrders").click(function() {
//     	$.ajax({
//     		type: 'pot',
//     	    url: '<?php echo base_url(); ?>/index.php?/back/getOrders',
//     		
//     	    success: function() {
//     	    	alert('fatto xD');
//     	    }
//     	});
//     	
//     });
//     $("#btnToken").click(function() {
//     	
//     	$.ajax({
//     		type: 'post',
//     	    url: '<?php echo base_url(); ?>/index.php?/back/getEbayToken',
//     		
//     	    success: function(result) {
//     	    	$("input[name='ebayToken']").val(result.ebayToken);
//     	    },
//     	    
//     	    error: function(error) {
//     	    	alert('ERROR');
//     	    	console.log(error);
//     	    }
//     	});
//     	
//     });
//     function getEbaySettings() {
//      	$.ajax({
//      		type: 'post',
//      		url: '<?php echo base_url(); ?>/index.php?/back/r_STN_Ebay',
//      		dataType: 'json',
//      			
//      		success: function(result) {
//      		   	console.log(result);
//      		   	window.open('https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&RUName='+result.ebayRuName+'&SessID='+result.ebaySessionID,'_blank');
//      		},
//      		    
//      		error: function(error) {
//      			alert('ERROR');
//      		    console.log(error);
//      		}
//      	});
//      
//     }
</script>