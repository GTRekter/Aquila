<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

</div>
    <script src="<?php echo $this->config->item('resources_js'); ?>/bootstrap.min.js"></script>
    <!-- LOADING - GENERAL AJAX ACTIONS -->
    <script type="text/javascript">
    	/* CUSTOM INPUT FILE */
    	$(document).on('change', '.btn-file :file', function() {
    		var label;
    		if($(this)[0].files.length > 1){
    			label = 'Selezione di immagini multiple';
    		} else {
    			label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
    		}
    		$(this).closest('.input-group').find('input[type="text"]').val(label);
    	});
    	function ajaxGet(url,postData) {
	    	var tempData = new Array();
	    	$.ajax({
	    	 	type: "POST",
	    	    dataType: 'json',
	    	    url: url,
	    	    data: postData,
	    	    async: false,
	    	       				
	    	     success: function(result) {
	    	     	tempData = result;
	    	     },
	    	     error: function(result) {
	    	     	alert(result);
	    	     },
	    	});
	    	return tempData;
    	}
		function ajaxPost(url,$this,postData,isModal,actionType,hasFormData,withReload) {
			var action = "";
			switch (actionType) {
				default:
					action = "Salvataggio"
					break;
				case 1:
					action = "Eliminazione";
					break;
				case 2:
					action = "Duplicazione";
					break;
			}
			if(isModal == true) {
				$this.closest('.modal').modal('hide');
			}
			loading($this,'progress','');
			
			if (hasFormData == true) {
				$.ajax({
					type: 'post',
				    url: url,
				    data: postData,
					dataType: 'json',
					cache: false,
					contentType: false,
					processData: false,
					
				    success: function(result) {
				    	if(result.message != null){
					    	loading($this,'complete',result.message);
					    	if(withReload == true){
						    	setTimeout(function() {
						    		location.reload();
						    	}, 1100);
					    	}
					    } else {
					    	loading($this,'error',result.error);
					    }
				    },
				    error: function(error) {
				    	console.log(error);
				    	loading($this,'error','Errore 001SVR - Operazione di ' + action + ' incompleta');
				    }
				});
			} else {
				$.ajax({
					type: 'post',
				    url: url,
				    data: postData,
					dataType: 'json',
					async: false,
					
				    success: function(result) {
				    	if(result.message != null){
				    		loading($this,'complete',result.message);
				    		if(withReload == true){
				    			setTimeout(function() {
				    				location.reload();
				    			}, 1100);
				    		}
				    	} else {
				    		loading($this,'error',result.error);
				    	}
				    },
				    error: function(error) {
				    	console.log(error);
				    	loading($this,'error','Errore 001 - Operazione di ' + action + ' incompleta');
				    }
				});
			}
			
		}
	    function loading(context, state, message) {
			switch (state) {
				case 'complete': 
					$('#loading-modal .loading-content').empty();
					$('#loading-modal .loading-content').append('<i class="fa fa-check" style="font-size: 50px;"></i><p style="font-size:24px">' + message + '</p>');
					setTimeout(function() {
						$('#loading-modal').modal('hide');
						$('#page-wrapper').remove('#loading-modal');
					}, 1000);
					break;
				case 'progress':
					$('#loading-modal').remove();
					$('#page-wrapper').append('<div id="loading-modal" class="modal fade" role="dialog"><div style="width: 100%; height: 100%; margin: 0px;" class="modal-dialog"><div class="loading-content" style="color: rgb(255, 255, 255); text-align: center; position: relative; top: 45%;"><i class="fa fa-refresh fa-spin" style="font-size: 50px;"></i> <br><p style="font-size: 24px">Caricamento</p></div></div></div>');
					$('#loading-modal').modal('show');
					break;
				case 'error':
					$('#loading-modal .loading-content').empty();
					$('#loading-modal .loading-content').append('<i class="fa fa-exclamation-triangle" style="font-size: 50px;"></i><p style="font-size:24px">' + message + '</p>');
					setTimeout(function() {
						$('#loading-modal').modal('hide');
						$('#page-wrapper').remove('#loading-modal');
					}, 2000);
			}
		}
    </script>
    <!-- ONLINE-USERS -->
    <script type="text/javascript">
    	$(window).on('load',function(){
    		getNewUsers();
    	});
    	setInterval(function (){
    		getNewUsers();
    	}, 10000);
     	function getNewUsers() {
     		$.ajax({
     			type: 'post',
     		    url: '<?php echo base_url('index.php/back/ra_LOG_Access'); ?>',
     		    dataType: 'json',
     		    success: function(results) {
     		    	addUsersToOnline(results); 
     		    }
     		});
     	} 
     	function addUsersToOnline(users) {
     	 	if (users != null) {
     	 		$('.online-users').empty();
     	 		var online_users = 0;
     	 		for (var i = 0; i < users.length; i++) {
    	 			var user_status = '';
    	 			var color = '';
     	 			var _tempDate = new Date(users[i].lastUpdate);
     	 			var now = new Date();
     	 			
     	 			var oneDay = 60*1000; // seconds*milliseconds
     	 			var diffDate = Math.round(Math.abs((now.getTime() - _tempDate.getTime())/(oneDay)));
     	 			
     	 			if (diffDate >= 0 && diffDate < 10) {
     	 				user_status = 'Online';
     	 				color = '55C1E7';
     	 				online_users ++;
     	 			}
     	 			if (diffDate > 10 && diffDate < 30) {
     					user_status = 'Inattivo da ' + diffDate + ' minuti';
     					color = 'f9f9f9';
     				}
     				if (diffDate > 30) {
    					user_status = 'Offline';
    					color = 'f9f9f9';
    				}
     	 			$('.online-users').append('<li><a href="#"><div class="pull-left"><img src="http://placehold.it/50/' + color + '/fff&text=' + users[i].accessName.charAt(0) + '" class="img-circle" alt="' + users[i].accessName + '"></div><h4>' + users[i].accessName + '</h4><p><small><i class="fa fa-clock-o"></i> '+ user_status +'</small></p></a></li>');
     	 		}
     	 		$('.count-online-users').empty();
     	 		$('.count-online-users').append(online_users);
     	 		if (online_users == 0) {
     	 			$('.users-dropdown a .count-online-users').addClass('label-danger');
     	 		}
     	 		if (online_users > 0 && online_users < (users.length/2)) {
     				$('.users-dropdown a .count-online-users').addClass('label-warning');
     			}
     			if (online_users > (users.length/2) && online_users < users.length) {
    				$('.users-dropdown a .count-online-users').addClass('label-success');
    			}
     	 	}
     	}
    </script>
    <!-- EXPAND/COLLAPSE SIDE-NAV -->
    <script type="text/javascript">
        $(document).on('ready',function () {
            $(document).click(function (event) {
                $('.navbar-collapse li ul').collapse('hide');
            });
        });
    </script>
    
    <?php if($page == 'index') : ?>  
    	<!-- GRAFICI --> 
    	<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/chartjs/chart.min.js" type="text/javascript"></script>
    	<script type="text/javascript">
    		<?php $year = date("Y"); ?>
    		var clients = <?php echo json_encode($clients_chart); ?>;
    		var orders = <?php echo json_encode($orders_chart); ?>;
    		var labels = <?php echo json_encode($label_chart); ?>;
    		var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
    		var salesChart = new Chart(salesChartCanvas);
    		var salesChartData = {
    			labels: labels,
    			datasets: [
    				{
    					label: "Ordini",
    					fillColor: "rgb(210, 214, 222)",
    					strokeColor: "rgb(210, 214, 222)",
    					pointColor: "rgb(210, 214, 222)",
    					pointStrokeColor: "#C1C7D1",
    					pointHighlightFill: "#fff",
    					pointHighlightStroke: "rgb(220,220,220)",
    					data: orders
    				}, {
    					label: "Clienti",
    					fillColor: "rgba(92,184,92,0.7)",
    					strokeColor: "rgba(92,184,92,0.8)",
    					pointColor: "#5CB85C",
    					pointStrokeColor: "rgba(60,141,188,1)",
    					pointHighlightFill: "#fff",
    					pointHighlightStroke: "rgba(60,141,188,1)",
    					data: clients
    				}
    			]
    		};
    		var salesChartOptions = {
    			showScale: true,
    			scaleShowGridLines: false,
    			scaleGridLineColor: "rgba(0,0,0,.05)",
    			scaleGridLineWidth: 1,
    			scaleShowHorizontalLines: true,
    			scaleShowVerticalLines: true,
    			bezierCurve: true,
    			bezierCurveTension: 0.3,
    			pointDot: false,
    			pointDotRadius: 4,
    			pointDotStrokeWidth: 1,
    			pointHitDetectionRadius: 20,
    			datasetStroke: true,
    			datasetStrokeWidth: 2,
    			datasetFill: true,
    			legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    			maintainAspectRatio: true,
    			responsive: true
    		};
    		salesChart.Line(salesChartData, salesChartOptions);
        </script>
    	<!-- TO DO LIST -->
    	<script type="text/javascript">
    		$(window).on('load',function(){
    			getNewCommitment();
    		});
    	 	$(document).on('click','.deleteCommitment', function (e) {
    			e.preventDefault();
    			var parent = $(this).parent().parent();
    			var _tempCommitment = parent.attr('id');
    			var arr = _tempCommitment.split('-');
    			var idCommitment = arr[1];
    			$.ajax({
    				type: 'post',
    			    url: '<?php echo base_url('index.php/back/d_STN_Commitment'); ?>',
    			    data: {idCommitment: idCommitment},
    			});
    			$('#commitment-'+idCommitment).remove();
    			
    		});
    		$(document).on('submit','#formCommitment', function (e) {
    			e.preventDefault();
    			var commitmentText = $("input[name='commitmentText']").val();
    			$("input[name='commitmentText']").val('');
    			sendCommitment(commitmentText);
    		});
    		$(document).on('click','.check-to-do',function(){
    		    var parent = $(this).parent();
    		    var _tempCommitment = parent.attr('id');
    		    var arr = _tempCommitment.split('-');
    		    var idCommitment = arr[1];
    		    var status = 0;
    		 	if (parent.hasClass('done')) {
    		 		parent.removeClass('done');
    		 		status = 0;
    		 	} else {
    		 		parent.addClass('done');
    		 		status = 1;
    		 	}
    		 	$.ajax({
    	 			type: 'post',
    	 		    url: '<?php echo base_url('index.php/back/u_STN_Commitment'); ?>',
    	 		    data: {idCommitment: idCommitment,commitmentStatus: status},
    	 		});
    		 });
    		function sendCommitment(message) {
    	 		$.ajax({
    	 			type: 'post',
    	 		    url: '<?php echo base_url('index.php/back/c_STN_Commitment'); ?>',
    	 		    data: {commitmentText: message},
    	 		    
    	 		    success: function() {
    	 		    	getNewCommitment();
    	 		    }
    	 		});
    	 	 } 
    	 	function getNewCommitment() {
    	 		$.ajax({
    	 			type: 'post',
    	 		    url: '<?php echo base_url('index.php/back/ra_STN_Commitments'); ?>',
    	 		    dataType: 'json',
    	 		    
    	 		    success: function(results) {
    	 		    	addCommitmentToReceived(results); 
    	 		    }
    	 		});
    	 	 } 
    	 	function addCommitmentToReceived(commitments) {
    	 	 	if (commitments != null) {
    	 	 		$('.todo-list').empty();
    	 	 		for (var i = 0; i < commitments.length; i++) {
    	 	 			var done = '';
    	 	 			var checked = '';
    	 	 			if (commitments[i].commitmentStatus == 1) {
    	 	 				done = 'done';
    	 	 				checked = 'checked';
    	 	 			}
    	 	 			var urgency = 'primary';
    	 	 			var _tempDate = new Date(commitments[i].createdOn);
    	 	 			var now = new Date();
    	 	 			var diffDate = ((((now - _tempDate)/1000)/60)/60)/24;
    	 	 			if (diffDate>2 && diffDate<7) {
    	 	 				urgency = 'warning';
    	 	 			}
    	 	 			if (diffDate>7) {
    	 	 				urgency = 'danger';		
    	 	 			}
    	 	 			$('.todo-list').append('<li id="commitment-'+commitments[i].idCommitment+'" class="'+done+'"><input type="checkbox" class="check-to-do" value="'+commitments[i].commitmentStatus+'" '+checked+'><span class="text">'+commitments[i].commitmentText+'</span><small class="label label-'+urgency+'"><i class="fa fa-clock-o"></i> '+commitments[i].createdOn+'</small><div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o deleteCommitment"></i></div></li>');
    	 	 		}
    	 	 	}
    	 	 }
    	</script>
    	<!-- CHAT -->
    	<script type="text/javascript">
    	    var time = 0;
    	  	$(document).on('submit','#formMessage', function (e) {
    	    	e.preventDefault();
    	        var messageText = $("input[name='messageText']").val();
    	        $("input[name='messageText']").val('');
    	        sendChat(messageText);
    	  	});
    	  	setInterval(function (){
    	  		getNewChats(10);
    	  	}, 1000);
    	  	function sendChat(message) {
    	  		$.ajax({
    	  			type: 'post',
    	  		    url: '<?php echo base_url('index.php?/back/c_STN_Message'); ?>',
    	  		    data: {messageText: message}
    	  		});
    	  	} 
    	  	function getNewChats() {
    	  		$.ajax({
    	  			type: 'post',
    	  		    url: '<?php echo base_url('index.php?/back/ra_STN_Messages'); ?>',
    	  		    data: {messageTime: time},
    	  		    dataType: 'json',
    	  		    success: function(results) {
    	  		    	addDataToReceived(results); 
    	  		    }
    	  		});
    	  	} 
    		function addDataToReceived(messages) {
    			if (messages != null) {
    				for (var i = 0; i < messages.length; i++) {
    					if (messages[i].accessEmail != '<?php echo $this->session->userdata('accessEmail') ?>') {
    						var color = 'D2D6DE';
    						var align = '';
    					} else {
    						var color = '4CAE4C';
    						var align = 'right';
    					}
    					$('#chat-box').append('<div class="direct-chat-msg '+align+'"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">' + messages[i].accessName + '</span><span class="direct-chat-timestamp pull-left">' + messages[i].createdOn + '</span></div><img class="direct-chat-img" src="http://placehold.it/50/'+color+'/fff&text=' + messages[i].accessName.charAt(0) + '" alt="' + messages[i].accessName + '"><div class="direct-chat-text">'+ messages[i].messageText + '</div></div>');
    				}
    				time = messages[messages.length-1].messageTime; 
    				var element = document.getElementById("chat-box");
    				element.scrollTop = element.scrollHeight;
    			}
    		}
    	</script>
    	<!-- COLLAPSE/EXPAND BOX -->
    	<script type="text/javascript">
    	    $(document).on('click','.btn-box-tool',function(){
    	    	var action = $(this).data('action');
    	    	switch (action) {
    	    		case 'collapse':
    	    			$(this).data('action','expand');
    	    			$(this).find('i').removeClass('fa-minus');
    	    			$(this).find('i').addClass('fa-plus');
    	    			$(this).parent().parent().next().slideToggle(); 
    	    			break;
    	    		case 'expand':
    	    			$(this).data('action','collapse');
    	    			$(this).find('i').removeClass('fa-plus');
    	    			$(this).find('i').addClass('fa-minus');
    	    			$(this).parent().parent().next().slideToggle(); 
    	    			break;
    	    		case 'remove':
    	    			$(this).closest('.box').slideUp();;
    	    	}
    	    });
    	</script>
    <? endif; ?>
    <?php if($page == 'index' || $page == 'products') : ?>
    	<!-- AZIONE DAL SECONDO ELEMENTO DELLA TABELLA -->
    	<script type="text/javascript">
    		$(document).on('click','table tr td:nth-child(n+2)',function(){
    		    window.location = $(this).attr('href');
    		    return false;
    		});
    	</script>
    <?php endif; ?>
    <?php if($page == 'products') : ?> 
    	<!-- PRODUCTS SCRIPT -->
    	<script type="text/javascript">
    		$(document).on('click','#delete',function(){
    			var url = "<?php echo base_url('index.php/Back/d_PRD_Products'); ?>";
    			var data = $("#modifyProduct").serialize();
    			ajaxPost(url,$(this),data,false,1,false,true);
    		});
    		$(document).on('click','#delete-search',function(){
    			var url = "<?php echo base_url('index.php/Back/d_PRD_Products'); ?>";
    			var data = $("#modifyProduct-search").serialize();
    			ajaxPost(url,$(this),data,false,1,false,true);
    		});
    		$(document).on('click','#duplicate',function(){
    			var url = "<?php echo base_url('index.php/Back/dp_PRD_Products'); ?>";
    			var data = $("#modifyProduct").serialize();
    			ajaxPost(url,$(this),data,false,2,false,true);
    		});
    		$(document).on('click','#duplicate-search',function(){
    			var url = "<?php echo base_url('index.php/Back/dp_PRD_Products'); ?>";
    			var data = $("#modifyProduct-search").serialize();
    			ajaxPost(url,$(this),data,false,2,false,true);
    		});
    		$(document).on('click',function (e) {
    		    if ( $("#search").is(e.target) ) {
    				$('#modifyProduct-search').empty();
    		    	if( $('#modifyProduct').css('opacity') == 1) {
    		    		$("#product-search").show();
    		    	    $("#modifyProduct").animate({
    		        		opacity: 0,
    		        	  	height: "toggle"
    		        	}, 500, function() {});
    		        	$("#product-search").animate({
    		    			opacity: 1,
    		    		}, 500, function() {});
    		    	}
    		    } else if ($("#product-search").has(e.target).length <= 0 ) {
    				if( $('#modifyProduct').css('opacity') == 0 ) {
    				    $("#modifyProduct").animate({
    						opacity: 1,
    					  	height: "toggle"
    					}, 500, function() {});
    					$("#product-search").animate({
    						opacity: 0,
    					}, 500, function() {
    						$("#product-search").hide();
    					});
    				}
    		    }
    		});
    		$(document).on('submit','#product-search-form',function (e) {
    			e.preventDefault();
    			
    			$('#modifyProduct-search').empty();
    			
    			var $this = $(this);
    			loading($this,'progress','');
    			
    			$.ajax({
    				type: 'post',
    			    url: '<?php echo base_url('index.php/back/s_PRD_Products'); ?>',
    			    data: $(this).serialize(),
    			    dataType: 'json',
    			        
    			    success: function (result) {
    			    	$('#modifyProduct-search').append('<div class="col-xs-12"><div class="table-responsive"><table class="table table-hover table-striped table-condensed table-product"><thead><tr><th></th><th>Immagine</th><th>Titolo</th><th>Produttore</th><th>Codici</th><th>Categoria</th><th>Prezzo</th><th>Sconto</th></tr></thead> <tbody></tbody></table></div>');
    			    	if (result) {
    				    	$.each(result, function(id) {
    				    		var tempCode = '';
    				    		if(result[id].productSKU != null) 
    				    		{ 
    				    			tempCode = 'SKU'; 
    				    		}
    				    		if(result[id].productEAN != null) 
    				    		{ 
    				    			tempCode = 'EAN-13 '; 
    				    		} 
    				    		if(result[id].productEAN == null && result[id].productSKU == null) 
    				    		{ 
    				    			tempCode = 'Nessun codice presente'; 
    				    		} 
    				    		$('#modifyProduct-search table tbody').append('<tr><td><input type="checkbox" name="idProduct[]" value="' + result[id].idProduct + '" /></td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '"><img src="<?php echo $this->config->item('resources_img') ?>/products/extra_small/' + result[id].photoName + '" alt="' + result[id].productName + '"></td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '">' + result[id].productName + '</td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '">' + result[id].manufacturerName + '</td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '">' + tempCode + '</td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '">' + result[id].categoryName + '</td><td>' + result[id].productPrice + ' €</td><td href="<?php echo site_url(); ?>back/product/' + result[id].idProduct + '">' + result[id].productDiscount + ' %</td></tr>');
    				    	});
    			    	} else {
    			    		$('#modifyProduct-search').append('<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun Prodotto trovato</p></div>');
    			    	}
    			    	loading($this,'complete','Ricerca Completata');
    			    },
    			    error: function (error) {
    			    	loading($this,'error','Errore 005 - Ricerca non Completato');
    			    }
    			});
    		});
    	</script>
    <?php endif; ?>
    <?php if($page == 'product') : ?>
    	<!-- PRODUCT SCRIPT -->
	    <script  type="text/javascript">
			/* TABELLA CARATTERISTICHE */
			var idCategory = <?php echo $product->idCategory; ?>;
			var values = <?php echo json_encode($values); ?>; 
			var _tempCombinations = <?php echo json_encode($_tempCombinations); ?>;
			var combinations = <?php echo json_encode($combinations); ?>;
			var feature_combinations = <?php echo json_encode($feature_combinations); ?>;
			
			$(window).on('load',function() {
				loadCategories('cat-modify');
				autoFillPrice($("input[name='productPrice']"),'grossPrice',$('select[name="idTax"] option:selected').data("percentage"));
				popolateFeatures(feature_combinations);
				popolateAttributes(combinations);
				popolateAttributesLabels(_tempCombinations, values);
			});
			$(document).on('submit','#modify_product_form',function (e) {
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/back/u_PRD_Product'); ?>";
				ajaxPost(url,$(this),data,false,0,false,false);
			});
			$(document).on('submit','#modify_feature_form',function (e) {
				e.preventDefault();
				
				var usedValues = new Array();
				$("select[name='idValue[]']").each(function() {
				    usedValues.push($(this).val());
				});  
				
				var data = {
					idProduct: $("input[name='idProduct']").val(), 
					idValue: usedValues
				};
				
				var url = "<?php echo base_url('index.php/back/u_PRD_Combination'); ?>";
				ajaxPost(url,$(this),data,false,0,false,false);
				
				$('#btn_add_feature').prop('disabled',true);
				$(this).find('button[type="submit"]').prop('disabled',true);
			});	
			$(document).on('submit','#modify_combination_form',function (e) {
				e.preventDefault();
				
				var $this = $(this);
				loading($this,'progress','');
				
				var idP = $("input[name='idProduct']").val();	
				
				for (var i = 0; i < _tempCombinations.length; i++) {
					var data = {
						'idProduct': idP,
						'combinationQuantity': _tempCombinations[i][_tempCombinations[i].length-1].valueName,
						'idValue': new Array()
					}
					for (var k = 0; k < _tempCombinations[i].length-1; k++) {
						data.idValue.push(_tempCombinations[i][k].idValue);
					}
					$.ajax({
						type: 'post',
					    url: '<?php echo base_url('index.php/back/u_PRD_Combination'); ?>',
					    data: data, 
					    dataType: 'json',
					    async: false,
					    success: function(result) {	
					    	loading($this,'complete',result.message);
					    },
					    error: function(error) {
					    	loading($this,'error',result.message);
					    }
					});
				}
				loading($this,'complete','Salvataggio Completato');
			});	
			$(document).on('submit','#modify_images_form',function (e) {
				e.preventDefault();
				
				var formData = new FormData(this);
				var id = $(this).find("input[name='idProduct']").val();
				var nFile = $(this).find("input[type=file]").get(0).files.length;
				formData.append("idProduct", id);
				formData.append("filesNumber", nFile);
				
				var url = "<?php echo base_url('index.php/back/u_PRD_Photo'); ?>";
				ajaxPost(url,$(this),formData,false,0,true,false);
				
				try {
					loadImages(id);
				}catch (error) {}
			});
			
			// FUNIONI NECESSARIE AL POPOLAMENTO ED ORIDNAMENTO DELLA VIEW CON I DATI DELLE COMBINAZIONI
			function popolateAttributesLabels(_tempCombinations, values){
				var input = $('#modify_combination_form input[name="feature[]"]');
				for (var i = 0; i < _tempCombinations.length; i++) {
					for (var k = 0; k < input.length; k++) {
						if(input[k].value == _tempCombinations[i].idFeature) {
							input[k].checked = true;
							input[k].disabled = true;
							// leggo i valori per ogni feature
							$("#list-values").append( '<div class="col-xs-12"><div id="feature_' + _tempCombinations[i].idFeature + '" class="row"><div class="col-xs-12"><label>' + _tempCombinations[i].featureName + ':&nbsp;&nbsp;</label></div></div></div');
							for(var l = 0; l < values.length; l++){
								if(values[l].idFeature == _tempCombinations[i].idFeature) {
									// Determino se checked o no
									var checked = '';
									for(var m = 0; m < _tempCombinations[i].values.length; m++){
										if(_tempCombinations[i].values[m].idValue == values[l].idValue){
											var checked = 'checked disabled';
										}
									}
									$('#feature_' + _tempCombinations[i].idFeature).append( '<div class="col-xs-12 col-md-3"><label class="checkbox-inline"><input type="checkbox" data-valueName="' + values[l].valueName + '" data-idFeature="' + _tempCombinations[i].idFeature + '" name="value[]" value="' + values[l].idValue + '" ' + checked + ' />' + values[l].valueName + '</label></div>' ); 
								}
							}
						}
					}
				}
			}
			function popolateAttributes(combinations){
				if(combinations.lenght > 0){
					for (var i = 0; i < combinations[0].length; i++) {
						$('#table-combinations thead tr').append('<th data-idFeature="' + combinations[0][i].idFeature + '">' + combinations[0][i].featureName + '</th>');
					}
					$('#table-combinations thead tr').append('<th> Cancella </th>');
					for (var i = 0; i < combinations.length; i++) {
						$('#table-combinations tbody').append('<tr id="comb-' + i + '"></tr>');	
						for (var k = 0; k < combinations[i].length-1; k++) {
							var m = 0;
							var isAdded = false;
							do{
								var CidFeature = combinations[i][k].idFeature;
								
								var idFeature = $('#table-combinations thead th').get(m).attributes[0].value;
								
								if(CidFeature == idFeature) {
									$('#table-combinations tbody tr:last').append('<td>' + combinations[i][m].valueName + '</td>');
									isAdded = true;
								} else {
									m++;
								}
							} while(isAdded == false);
						}
						$('#table-combinations tbody tr:last').append('<td><input type="text" class="quantity" name="quantity[]" data-id="' + i + '" value="' + combinations[i][k].valueName + '" /></td>');
						$('#table-combinations tbody tr:last').append('<td><a class="delete-combination pointer" data-id="' + i + '"><i class="ion-trash-a"></i></a></td>');
					}
				}
			}
			function popolateFeatures(feature_combinations){
				if(feature_combinations.length >= 0) {
					for (var i = 0; i < feature_combinations.length; i++) {
						$('#table-features tbody').append('<tr><td><select class="form-control" name="idFeature[]"></select></td><td><select class="form-control" name="idValue[]"></select></td><td><a class="delete-feature pointer" data-id="' + i + '"><i class="ion-trash-a"></i></a></td></tr>');
						for (var k = 0; k < features.length; k++) {
							$("select[name='idFeature[]']").last().append('<option value="' + features[k].idFeature + '">' + features[k].featureName + '</option>');
							if (features[k].idFeature == feature_combinations[i][0].idFeature) {
								$("select[name='idFeature[]']").last().find("option[value='" + features[k].idFeature + "']").prop('selected', true);
							}
						}
						for (var k = 0; k < values.length; k++) {
							if(values[k].idFeature == feature_combinations[i][0].idFeature){
								$("select[name='idValue[]']").last().append('<option value="' + values[k].idValue + '">' + values[k].valueName + '</option>');
							}
							if (values[k].idValue == feature_combinations[i][0].idValue) {
								$("select[name='idValue[]']").last().find("option[value='" + values[k].idValue + "']").prop('selected', true);
							}
						}
					}
				}
			}
			
			// CARICAMENTO VALORI
			function loadValue(idF,idN, arrayObj) {
				var valuePresent = '';
				$.ajax({
					type: 'post',
				    url: '<?php echo base_url(); ?>index.php?/back/r_PRD_Values_byFeature',
				    data: {idFeature: idF},
				    dataType: 'json',
					
				    success: function(result) {	
				    	$("#list-values").append( '<div class="col-xs-12"><div id="feature_' + idF + '" class="row p-b-20"><div class="col-xs-12"><label>' + idN + ':&nbsp;&nbsp;</label></div></div></div>');
				    	$.each(result, function(id) {	
				    		valuePresent = '';
				    		for (var i = 0; i < arrayObj.length; i++) {
				    			for (var k = 0; k < arrayObj[i].length-1; k++) {
				    				if (result[id].idValue == arrayObj[i][k].idValue) {
				    					valuePresent = 'checked="true" disabled="true"';
				    				}
				    			}
				    		}
				    	
				    		$('#feature_' + idF).append( '<div class="col-xs-12 col-md-3"><label class="checkbox-inline"><input type="checkbox" data-valueName="' + result[id].valueName + '" data-idFeature="' + idF + '" name="value[]" value="' + result[id].idValue + '"' + valuePresent + '"/>' + result[id].valueName + '</label></div>' ); 
				    	});
				    },
				    error: function(error) {
				    	alert('Error 401: '+ error);
				    	console.log(error);
				    }
				});
			}
		</script>
    <?php endif; ?>
    <?php if($page == 'n_product') : ?>
    	<!-- N_PRODUCT SCRIPT -->
    	<script type="text/javascript">
    		var idValues = new Array();
    		var idFeatures = new Array();
    		var idCategory = '';
    		var combinations = new Array();
    		var _tempCombinations = new Array();
    		
    		$(window).on('load',function() {
    			idValues.length = 0;
    			idFeatures.length = 0;
    			idCategory = '';
    			$("#gallery").empty();
    			$("button").prop('disabled', true); 
    			$("#add_product_form button").prop('disabled', false);
    			loadCategories('cat-create');
    		});
    		$(document).on('submit','#add_product_form',function (e) {
    			e.preventDefault();
    			
    			var $this = $(this);
    			loading($this,'progress','');
    			
    			$.ajax({
    		    	type: 'post',
    		        url: '<?php echo base_url('index.php/back/c_PRD_Product'); ?>',
    		        data: $(this).serialize(),			        
					async: false,   
    		        success: function (result) {
    		        	if(result.message != null){
    		        		$("input[name='idProduct']").val(result.idProduct);	
    		        		$('#btn_add_feature').prop('disabled', false);
    		        		$('#btn_generate_combinations').prop('disabled', false);
    		        		$("#add_product_form button[type='submit']").prop('disabled', true);
    		        		loading($this,'complete',result.message);
    		        	} else {
    		        		loading($this,'error',result.error);
    		        	}
    		        },
    		        error: function (result) {
    		        	loading($this,'error',result.message);
    		        }
    		    });
    		});
    		$(document).on('submit','#add_feature_form',function (e) {
    			e.preventDefault();
    			
    			var usedValues = new Array();
    			$("select[name='idValue[]']").each(function() {
    			    usedValues.push($(this).val());
    			});  
    			
    			console.log(usedValues);  	 						
    			
    			var data = {
    				idProduct: $("input[name='idProduct']").val(), 
    				idValue: usedValues
    			};
    			var url = "<?php echo base_url('index.php/back/c_PRD_Combination'); ?>";
    			ajaxPost(url,$(this),data,false,0,false,false);
    			
    			$('#btn_add_feature').prop('disabled',true);
    			$(this).find('button[type="submit"]').prop('disabled',true);
    		});
    		$(document).on('submit','#add_combination_form',function (e) {
    			e.preventDefault();
    			
    			var $this = $(this);
    			loading($this,'progress','');
    			
    			var idP = $("input[name='idProduct']").val();	
    			
    			for (var i = 0; i < _tempCombinations.length; i++) {
	    			var data = {
	    				'idProduct': idP,
	    				'combinationQuantity': _tempCombinations[i][_tempCombinations[i].length-1].valueName,
	    				'idValue': new Array()
	    			}
	    			for (var k = 0; k < _tempCombinations[i].length-1; k++) {
	    				data.idValue.push(_tempCombinations[i][k].idValue);
	    			}
	    			$.ajax({
	    				type: 'post',
	    			    url: '<?php echo base_url('index.php/back/c_PRD_Combination'); ?>',
	    			    data: data, 
	    			    dataType: 'json',
	    			    async: false,
	    			    success: function(result) {	
	    			    	loading($this,'complete',result.message);
	    			    },
	    			    error: function(error) {
	    			    	loading($this,'error',result.message);
	    			    }
	    			});
	    		}
    			loading($this,'complete','Salvataggio Completato');
    		});	
    		$(document).on('submit','#add_images_form',function (e) {
    			e.preventDefault();
    			
    			var formData = new FormData(this);
    			var id = $(this).find("input[name='idProduct']").val();
    			var nFile = $(this).find("input[type=file]").get(0).files.length;
    			formData.append("idProduct", id);
    			formData.append("filesNumber", nFile);
    			
    			var url = "<?php echo base_url('index.php/back/u_PRD_Photo'); ?>";
    			ajaxPost(url,$(this),formData,false,0,true,false);
    			
    			try {
    				loadImages(id);
    			}catch (error) {}
    		});	
    		
    		// CARICAMENTO VALORI
    		function loadValues(idFeature) {
    			$("select[name='value']").val('');
    			$("select[name='value']").find('option').remove();
    			
    			var postData = {
    				idFeature: idFeature
    			}
    			var url = '<?php echo base_url('index.php?/back/r_PRD_Values_byFeature'); ?>';
    			var values = ajaxGet(url,postData);
    			
    			if (values != null) {
    				$("select[name='value']").prop('disabled', false);
    				$("button[id='btnCombination']").prop('disabled', false);
    				$.each(result, function(id) {	
    					$("select[name='value']").append( $("<option></option>").attr("value",values[id].idValue).text(values[id].valueName) ); 
    				});
    			} else {
    				$("select[name='value']").prop('disabled', true);
    				$("button[id='btnCombination']").prop('disabled', true);
    			}
    		}
    	</script>
    <?php endif; ?>
    <?php if($page == 'product' || $page == 'n_product') : ?>
    	<!-- PRODUCTS - N_PRODUCT SCRIPT -->
    	<script type="text/javascript">
    	    var previousFeature;
    		var features = <?php echo json_encode($features); ?>; 
    		var languages = <?php echo json_encode($languages); ?>;
    		var categories = <?php echo json_encode($categories); ?>;
    		
    		// CONTROLLO MAGGIORE DI ZERO E INT
    		function checkNumber(source) {
    			var pattern = new RegExp('^[0-9]+([,.][0-9]{1,2})?$'); 
    			if(!pattern.test(source.val())) {
    				source.parent().removeClass('has-success');
    				source.parent().addClass('has-error');
    				source.closest('form').find("button[type='submit']").prop('disabled',true);
    		    	return false;
    		  	} else {
    		  		source.parent().removeClass('has-error');
    		  		source.parent().addClass('has-success');
    		  		source.closest('form').find("button[type='submit']").prop('disabled',false);
    		    	return true;
    		  	}
    		}
    		/* AUTO-COMPLETAMENTO PREZZI */
    		function autoFillPrice(source,destination,percentage) {
    			if ( checkNumber(source) == true ){	
    				var _tempPrice = 0.00;
    				var _tempSource = parseInt(source.val());
    				if (destination == 'productPrice') {
    					_tempPrice = (_tempSource - ((_tempSource/100)*percentage));
    				} else {
    					_tempPrice = (_tempSource + ((_tempSource/100)*percentage));
    				}
    				$('input[name="'+ destination + '"]').val(_tempPrice);
    			} 
    		}	
    		// UPLOAD IMAGES
    		function loadImages(idProduct) {
    			$("#gallery").empty();
    			
    			var postData = {
    				idProduct: idProduct
    			};
    			var url = "<?php echo base_url('index.php/Back/r_PRD_Photos_byProduct'); ?>";
    			var productImages = ajaxGet(url,postData);
    			console.log(productImages);
    			
    			for (var i = 0; i < productImages.length; i++) {
    				var isCover = '';
    				if (productImages[i].isCover != 0) {
    					isCover = 'checked';
    				} else {
    					isCover = '';
    				}
    				$("#gallery").append('<div id="' + productImages[i].idPhoto + '" class="col-xs-6 col-lg-4"><div class="img-thumbnail" style="width: 100%;"> <img style="margin-top: 10px; height: 150px; margin: 0 auto;" src="<?php echo $this->config->item('resources_img') ?>/products/medium/' + productImages[i].photoName + '" class="img-responsive"></div><input type="radio" name="idPhotoCover" value="' + productImages[i].idPhoto + '" ' + isCover + '/> Principale &nbsp;<input type="checkbox" name="idPhoto[]" value="' + productImages[i].idPhoto + '"/> Cancella<br/></div>');
    			}
    		}
    		// UPLOAD CATEGORIES
    		function loadCategories(idCatTree) {
    			$('#' + idCatTree).empty();
    			for (var i = 0; i < categories.length; i++) {
    				var isChecked = '';
    				if (idCategory == categories[i].idCategory) {
    					isChecked = 'checked';
    				} else {
    					isChecked = '';
    				}
    				if (categories[i].idParentCategory == null) {
    					$('#' + idCatTree).append('<li><input type="radio" ' + isChecked + ' value="' + categories[i].idCategory + '" name="idCategory"> <i class="fa fa-folder pointer" data-toggle="collapse" data-target="#' + idCatTree + '-' + categories[i].idCategory + '"></i> ' + categories[i].categoryName + '</span><ul id="' + idCatTree + '-' + categories[i].idCategory + '" class="collapse"></ul>');
    				}else{
    					$('#' + idCatTree + '-' + categories[i].idParentCategory).append('<li><input type="radio" ' + isChecked + ' value="' + categories[i].idCategory + '" name="idCategory"> <i class="fa fa-folder pointer" data-toggle="collapse" data-target="#' + idCatTree + '-' + categories[i].idCategory + '"></i> ' + categories[i].categoryName + '</span><ul id="' + idCatTree + '-' + categories[i].idCategory + '" class="collapse"></ul>');
    				} 
    			}	
    			$('.pointer').on('click',function(){
    				$(this).toggleClass( "fa-folder-open" );
    			});
    			for (var i = 0; i < categories.length; i++) {
    				if ( $('#' + idCatTree + '-' + categories[i].idCategory).find('input:checked').length > 0 ) {
    					$('#' + idCatTree + '-' + categories[i].idCategory).addClass('in');
    					$('#' + idCatTree + '-' + categories[i].idCategory + ' ul').addClass('in');
    				} else {
    					$('#' + idCatTree + '-' + categories[i].idCategory).removeClass('in');
    					$('#' + idCatTree + '-' + categories[i].idCategory + ' ul').removeClass('in');
    				}
    			}
    		}
    		// MANAGE COMBINATIONS
    		function getCombinations(arr, n){
    		    var i,j,k,elem,l = arr.length,childperm,ret=[];
    		    if(n == 1){
    		        for(var i = 0; i < arr.length; i++){
    		            for(var j = 0; j < arr[i].length; j++){
    		                ret.push([arr[i][j]]);
    		            }
    		        }
    		        return ret;
    		    }
    		    else{
    		        for(i = 0; i < l; i++){
    		            elem = arr.shift();
    		            for(j = 0; j < elem.length; j++){
    		                childperm = getCombinations(arr.slice(), n-1);
    		                for(k = 0; k < childperm.length; k++){
    		                    ret.push([elem[j]].concat(childperm[k]));
    		                }
    		            }
    		        }
    		        return ret;
    		    }
    		    i=j=k=elem=l=childperm=ret=[]=null;
    		}
    		function getValues(idFeature) {
    			var postData = {
    				idFeature: idFeature
    			}
    			var url = '<?php echo base_url('index.php/back/r_PRD_Values_byFeature'); ?>';
    			return ajaxGet(url,postData);
    		}
    		// CREAZIONE COMBINAZIONI ARRAY DI ARRAY
    		function generateTableCombinations(arrayObj) {
    			for (var i = 0; i < arrayObj[0].length; i++) {
    				$('#table-combinations thead tr').append('<th>' + arrayObj[0][i].featureName + '</th>');
    			}
    			$('#table-combinations thead tr').append('<th> Cancella </th>');
    			
    			for (var i = 0; i < arrayObj.length; i++) {
    				$('#table-combinations tbody').append('<tr id="comb-' + i + '"></tr>');
    				
    				for (var k = 0; k < arrayObj[i].length; k++) {
    					if (k == arrayObj[i].length-1 ) {
    						$('#table-combinations tbody tr:last').append('<td><input type="text" class="quantity" name="quantity[]" data-id="' + i + '" value="' + arrayObj[i][k].valueName + '" /></td>');
    						$('#table-combinations tbody tr:last').append('<td><a class="delete-combination pointer" data-id="' + i + '"><i class="ion-trash-a"></i></a></td>');
    					} else {
    						$('#table-combinations tbody tr:last').append('<td>' + arrayObj[i][k].valueName + '</td>');
    					}
    				}
    			}	
    		}
    		/* PULSANTE SWITCH */
    		$(document).on('click','select[name="idFeature[]"]',function () {
    		    previousFeature = {
    		    	idFeature: $(this).find('option:selected').val(),
    		    	featureName: $(this).find('option:selected').text()
    		    };
    		});
    		$(document).on('click','input[name="feature[]"]',function() {
    			var idF = $(this).val();
    			var featureName = $(this).attr("data-featureName");
    			
    			if(this.checked) {
    				$.ajax({
    					type: 'post',
    				    url: '<?php echo base_url('index.php?/back/r_PRD_Values_byFeature'); ?>',
    				    data: {idFeature: idF},
    				    dataType: 'json',
    					
    				    success: function(result) {	
    				    	$("#list-values").append( '<div id="feature_' + idF + '" class="form-group"><label>' + featureName + ':&nbsp;&nbsp;</label></div>');
    				    	$.each(result, function(id) {	
    				    		$('#feature_' + idF).append( '<label class="checkbox-inline"><input type="checkbox" data-valueName="' + result[id].valueName + '" data-idFeature="' + idF + '" name="value[]" value="' + result[id].idValue + '" />' + result[id].valueName + '</label>' ); 
    				    	});
    				    	
    				    	var _temp = {idFeature: idF,featureName: featureName,values: new Array()};
    				    	combinations.push(_temp);
    				    	console.log(combinations);
    				    	console.log(_tempCombinations);
    				    },
    				    error: function(error) {
    				    	alert('Error 401: '+ error);
    				    	console.log(error);
    				    }
    				});
    			} else {
    				$('#feature_' + idF).remove();
    				
    				combinations = $.grep(combinations, function(array) {
    			 		return array.idFeature != idF;
    				});
    				console.log(combinations);
    			}
    		});	
    		$(document).on('click','input[name="value[]"]',function() {
    			var idV = parseInt($(this).val());
    			var idF = parseInt($(this).attr("data-idFeature"));
    			var valueName = $(this).attr("data-valueName");
    			
    			if(this.checked) {
    				for (var i = 0; i < combinations.length; i++) {
    					if ( combinations[i].idFeature == idF ) {
    						var _temp = {	
    										featureName: combinations[i].featureName,
    										idValue: idV,
    										valueName: valueName
    									};
    						combinations[i].values.push( _temp );
    					}
    				}
    			} else {
    				for (var i = 0; i < combinations.length; i++) {
    					combinations[i].values = jQuery.grep(combinations[i].values, function(array) {
    					  return array.idValue != idV;
    					});
    				}
    			}
    		});
    		$(document).on('click','.switch-button .switch-light span',function() {
    			var $this = $(this);
    			var value = $(this).data("value");
    			if (value == 0) {
    				$this.parent().find('.light').animate({
    				    right: '50%',
    				}, 250, function() {
    					$this.parent().find('span.active').removeClass('active');
    					$this.addClass('active');
    					$this.parent().find('.light').removeClass('right');
    				});
    				$this.parent().parent().find('input[type="checkbox"]').prop('checked', false);
    			} else {
    				$this.parent().find('.light').animate({
    				    right: '0%',
    				}, 250, function() {
    					$this.parent().find('span.active').removeClass('active');
    						$this.addClass('active');
    						$this.parent().find('.light').addClass('right');
    				});
    				$this.parent().parent().find('input[type="checkbox"]').prop('checked', true);
    			}
    		});
    		$(document).on('click','.delete-feature',function() {
    			$(this).closest('tr').remove();
    		});
    		$(document).on('click','.delete-combination',function() {
    			var idC = $(this).attr("data-id");
    			$('#comb-' + idC).remove();
    			_tempCombinations.splice(idC,1);
    		});
    		$(document).on('click','#btn_add_feature',function(){
    			var usedFeatures = new Array();
    			$("select[name='idFeature[]']").each(function() {
    			    usedFeatures.push($(this).val());
    			});
    			if (usedFeatures.length < features.length) {
    				$('#table-features tbody').append('<tr><td><select class="form-control" name="idFeature[]"></select></td><td><select class="form-control" name="idValue[]"></select></td><td><a class="delete-feature pointer" data-id="' + i + '"><i class="ion-trash-a"></i></a></td></tr>');
    				for (var i = 0; i < features.length; i++) {
    					if ($.inArray(features[i].idFeature,usedFeatures) == -1) {
    						$("select[name='idFeature[]']").last().append('<option value="' + features[i].idFeature + '">' + features[i].featureName + '</option>');
    					}
    				} 
    				var currentFeature = $("select[name='idFeature[]']").last().find("option:selected").val();
    				$("select[name='idFeature[]']").not(':last').find("option[value='" + currentFeature + "']").remove();
    				
    				var values = getValues(currentFeature);
    				for (var i = 0; i < values.length; i++) {
    					$("select[name='idValue[]']").last().append('<option value="' + values[i].idValue + '">' + values[i].valueName + '</option>');
    				}
    				
    				$("#add_feature_form button[type='submit']").prop('disabled', false);
    			} else {
    				alert('ATTENZIONE: Hai già inserito tutte le caratteristiche disponibili');
    			}
    		});
    		$(document).on('click','#btn_generate_combinations',function (e) {
    			e.preventDefault(); 
    			
    			var _temp = new Array();
    			for (var i = 0; i < combinations.length; i++) {
    				combinations[i].values.sort(function(a, b) {
    				    return a.valueName.localeCompare(b.valueName);
    				});
    				_temp.push(combinations[i].values);
    			}
    			_tempCombinations = getCombinations(_temp, _temp.length);
    			for (var i = 0; i < _tempCombinations.length; i++) {
    				_tempOject = {
    					featureName: "Quantità",
    					valueName: 0,
    				}
    				_tempCombinations[i].push(_tempOject);
    			}
    			generateTableCombinations(_tempCombinations);
    			
    			$(this).prop('disabled', true);
    			$("#add_combination_form button[type='submit']").prop('disabled', false);
    		});	
    		$(document).on('keyup','input[name="grossPrice"]',function(){
    			autoFillPrice($(this),'productPrice',$('select[name="idTax"] option:selected').data("percentage"));
    		});
    		$(document).on('keyup','input[name="productPrice"]',function(){
    			autoFillPrice($(this),'grossPrice',$('select[name="idTax"] option:selected').data("percentage"));
    		});
    		$(document).on('keyup','input[name="productLenght"]',function(){
    			checkNumber($(this));
    		});
    		$(document).on('keyup','input[name="productWidth"]',function(){
    			checkNumber($(this));
    		});
    		$(document).on('keyup','input[name="productHeight"]',function(){
    			checkNumber($(this));
    		});
    		$(document).on('keyup','input[name="productWeight"]',function(){
    			checkNumber($(this));
    		});
    		$(document).on('change','select[name="idTax"]',function(){
    			autoFillPrice($(this),'grossPrice',$('select[name="idTax"] option:selected').data("percentage"));
    		});
			$(document).on('change','select[name="idFeature[]"]',function() {
				var idFeature = $(this).val();
				var values = getValues(idFeature);
				for (var i = 0; i < values.length; i++) {
					$(this).closest('tr').find('select[name="idValue[]"]').empty();
					$(this).closest('tr').find('select[name="idValue[]"]').append('<option value="' + values[i].idValue + '">' + values[i].valueName + '</option>');
				}

				$("select[name='idFeature[]']").not(this).append('<option value="' + previousFeature.idFeature + '">' + previousFeature.featureName + '</option>');
				
				$("select[name='idFeature[]']").not(this).find("option[value='" + idFeature + "']").remove();
			});	
    		$(document).on('change','input[name="quantity[]"]',function(){
    			idC = $(this).attr("data-id");
    			_tempCombinations[idC][_tempCombinations[idC].length-1].valueName = $(this).val();
    		});
    		$(document).on('change','input[name="files[]"]',function(){
    			$('#add_images_form').find('button[type="submit"]').prop('disabled',false);
    		});
    	</script>
    <?php endif; ?>
    
	<?php if ($page == 'categories') : ?>
		<!-- CATEGORIES SCRIPTS -->
		<script type="text/javascript">
			
			$(document).on('click','#add',function() {
				idCategory = null;
				loadCategories('cat-create',null,null);
			});
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_PRD_Categories'); ?>";
				var data = $("#category").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','.btnModifyCategory',function() {
				var idCategory = $(this).data('id');
				var idParentCategory = null;	
							
				for ( var k = 0; k < categories.length; k++ ) {
					if ( categories[k].idCategory == idCategory ) {
						idParentCategory = categories[k].idParentCategory;
						break;
					}
				}
				
				$("#modifyCategory input[name='idCategory']").val(idCategory);
				$('#tablist_category').empty();
				$('#tabcontent_category').empty();
		
				$.ajax({
					type: "POST",
				    dataType: 'json',
				    url: "<?php echo base_url('index.php?/Back/r_LANG_Categories/') ?>",
				    data: {idCategory: idCategory},
				    async: false,
				      				
				    success: function(result) {
				    	var firstIntereaction = '';
				    	for (var i = 0; i < languages.length; i++) {
				    		if (i == 0) {
				    			firstIntereaction = 'active';
				    		} else {
				    			firstIntereaction = '';
				    		}
				    		
				    		for (var j = 0; j < result.length; j++) {
				    			if (languages[i] == result[j].language) {
				    				idLangCategory = result[j].idLangCategory;
				    				categoryName = result[j].categoryName;
				    				categoryDescription = result[j].categoryDescription;
				    			}
				    		}
				    		$('#tablist_category').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
				    		
				    		$('#tabcontent_category').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"></div>');
				    		
				    		if (languages[i] == 'it') {
				    			$('#it').append('<label>Categoria Padre</label><div class="panel panel-control"><div class="panel-body"><div class="form-group"><ul id="cat-modify" class="nav nav-list"></ul></div></div></div>');
				    		}
				    		
				    		$('#'+ languages[i]).append('<div class="form-group"><label>Nome categoria</label><input type="hidden" class="form-control" name="idLangCategory[]" value="' + idLangCategory + '"><input class="form-control" name="categoryName[]" value="' + categoryName + '"></div><label>Descrizione *</label><div class="form-group"><textarea class="form-control" name="categoryDescription[]" required="true" rows="4">' + categoryDescription + '</textarea></div>');
				    	}
				    },
				    error: function(result) {
				    	alert(result);
				    },
				});
		
				loadCategories('cat-modify',idCategory,idParentCategory);
			});
			$(document).on('submit','#add-category-form',function(e){
				e.preventDefault();				
				var url = "<?php echo base_url('index.php/Back/c_PRD_Category'); ?>";
				var data = $(this).serialize();
				ajaxPost(url,$(this),data,true,0,false,true);
			});
			$(document).on('submit','#modify-category-form',function(e){
				e.preventDefault();
				var url = "<?php echo base_url('index.php/Back/u_PRD_Category'); ?>";
				var data = $(this).serialize();
				ajaxPost(url,$(this),data,true,0,false,true);
			});
		
			var idCategory = '';
			var categories = <?php echo json_encode($categories); ?>;
			var languages = <?php echo json_encode($languages); ?>;
			
			// UPLOAD CATEGORIES
			function loadCategories(idCatTree,idCategory,idParentCategory) {
				var isChecked = '';
				var isVisible = '';
				
				$('#' + idCatTree).empty();
				
				if(idParentCategory == null){
					isChecked = 'checked';
				}
				$('#' + idCatTree).append('<li><input type="radio" value="NULL" name="idParentCategory" ' + isChecked + '>&nbsp;<i class="fa fa-folder-o pointer"></i> Home </li>');
				
				for (var i = 0; i < categories.length; i++) {
					isChecked = '';
					
					if (idParentCategory == categories[i].idCategory) {
						isChecked = 'checked';
					} else {
						isChecked = '';
					}
					if (idCategory == categories[i].idCategory && idParentCategory == null) {
						isVisible = 'hidden';
					}
					if (categories[i].idParentCategory == null) {
						$('#' + idCatTree).append('<li class="' + isVisible + '"><input type="radio" ' + isChecked + ' value="' + categories[i].idCategory + '" name="idParentCategory"> <i class="fa fa-folder pointer" data-toggle="collapse" data-target="#' + idCatTree + '-' + categories[i].idCategory + '"></i> ' + categories[i].categoryName + '</span><ul id="' + idCatTree + '-' + categories[i].idCategory + '" class="collapse"></ul>');
					}else{
						$('#' + idCatTree + '-' + categories[i].idParentCategory).append('<li  class="' + isVisible + '"><input type="radio" ' + isChecked + ' value="' + categories[i].idCategory + '" name="idParentCategory"> <i class="fa fa-folder pointer" data-toggle="collapse" data-target="#' + idCatTree + '-' + categories[i].idCategory + '"></i> ' + categories[i].categoryName + '</span><ul id="' + idCatTree + '-' + categories[i].idCategory + '" class="collapse"></ul>');
					} 
				}	
				$('.pointer').on('click',function(){
					$(this).toggleClass( "fa-folder-open" );
				});
				
				for (var i = 0; i < categories.length; i++) {
					if ( $('#' + idCatTree + '-' + categories[i].idCategory).find('input:checked').length > 0 ) {
						$('#' + idCatTree + '-' + categories[i].idCategory).addClass('in');
						$('#' + idCatTree + '-' + categories[i].idCategory + ' ul').addClass('in');
					} else {
						$('#' + idCatTree + '-' + categories[i].idCategory).removeClass('in');
						$('#' + idCatTree + '-' + categories[i].idCategory + ' ul').removeClass('in');
					}
				}
			}
		</script>
	<?php endif; ?>
	<?php if ($page == 'clients') : ?>
		<!-- CLIENTS SCRIPTS -->
		<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/morris/raphael.min.js"></script>
		<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/morris/morris.min.js"></script>
		<script type="text/javascript">
			var graph_order = Morris.Area({
			    element: 'client-area-chart-orders',
			    resize: true,
			    data: [],
			    xkey: 'x',
			    ykeys: ['y', 'z'],
			    parseTime: false,
			    labels: ['Importo Carrelli', 'Numero Ordini'],
			    lineColors: ['#D9534F', '#5CB85C'],
			    hideHover: 'auto'
			}); 
			var graph_products = Morris.Bar({
			    element: 'client-area-chart-products',
			    resize: true,
			    data: [],
			    xkey: 'x',
			    ykeys: ['y'],
			    parseTime: false,
			    labels: ['Numero Prodotti'],
			    lineColors: ['#D9534F', '#5CB85C'],
			    hideHover: 'auto'
			}); 
			
			var ordersId = new Array();
			$(document).on('click',function (e) {
				if ( $(".lineClientDetail").is(e.target) ) {
					var id = $(e.target).data('id');
					
					$('#client-general-informations').empty();
					var client = getClient(id);
					
					$('#client-general-informations').append('<div class="col-md-6"><label>Nominativo:</label> ' + client.clientName + ' ' + client.clientSurname + '<br><label>Indirizzo:</label> ' + client.clientAddress + ' ' + client.clientHouseNumber + '<br><label>Città:</label> ' + client.clientCity + '<br><label>Codice Postale:</label> ' + client.clientPostalCode + '<br></div><div class="col-md-6"><label>Provincia:</label> ' + client.clientState + '<br><label>Nazione:</label> ' + client.countryName + '<br><label>Telefono:</label> +' + client.callPrefix + ' ' + client.clientPhone + '<br><label>Email:</label> ' + client.clientEmail + '<br></div>');
					
					if( $('#clients').css('opacity') == 1) {
						$("#client-detail").show();
						loadCharts(id);
					    $("#clients").animate({
				    		opacity: 0,
				    	  	height: "toggle"
				    	}, 500, function() {});
				    	$("#client-detail").animate({
							opacity: 1,
						}, 500, function() {
						});
					}
				} else if ($("#client-detail").has(e.target).length <= 0 ) {
					if( $('#clients').css('opacity') == 0 ) {
					    $("#clients").animate({
							opacity: 1,
						  	height: "toggle"
						}, 500, function() {});
						$("#client-detail").animate({
							opacity: 0,
						}, 500, function() {
							$("#client-detail").hide();
						});
					}
				}
			});
			function loadCharts(id) {
				if(refreshDataOrders(id).length > 0) {
					graph_order.setData(refreshDataOrders(id));
					graph_products.setData(refreshDataProducts(id));
				} else {
					$('#box-client-charts .row').append('<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna statistica disponibile</p></div>');
					$('#client-area-chart-orders').hide();
					$('#client-area-chart-products').hide();
					
				}
			}
			function getOrder(idClient) {
				var url = "<?php echo base_url('index.php/Back/ra_ORD_Orders_byClient/') ?>"
				var postData = {
					idClient: idClient
				}
				return ajaxGet(url,postData);
			}
			function getOrdersProducts(idOrders) {
				var url = "<?php echo base_url('index.php/Back/ra_ORD_Orders_Products_Array/') ?>"
				var postData = {
					idOrders: idOrders
				}
				return ajaxGet(url,postData);
			}
			function refreshDataOrders(id) {
				var orders = getOrder(id);
				var dataOrders = [];
				var currentDate = new Date();
				var currentYear = currentDate.getFullYear();
				for (var i = 1; i <= 12; i++) {
					var orderAmount = 0;
					var montlyAmount = 0;
					var montlyOrders = 0;
					if (orders) {
						for (var k = 0; k < orders.length; k++) {
							var date = new Date(orders[k].createdOn);
							var year = date.getFullYear();
							var month = date.getMonth() + 1;
							if ( currentYear == year && i == month ) {
								montlyAmount += orders[k].orderAmount;
								montlyOrders ++ ;
							}
							ordersId.push(orders[k].idOrder);
						}
						var tempData = {
							'x': i + '-' + currentYear,
							'y': montlyAmount,
							'z': montlyOrders
						};
						dataOrders.push(tempData);
					}
				}
				return dataOrders;
			}
			function refreshDataProducts(id) {
				var orders = getOrder(id);
				var ordersId = new Array();
				var manufacturers = new Array();
				
				if (orders) {
					for (var i = 0; i < orders.length; i++) {
						ordersId.push(orders[i].idOrder);
					}
					var products = getOrdersProducts(ordersId);
					for (var i = 0; i < products.length; i++) {
						if ($.inArray( products[i].manufacturerName, manufacturers ) == -1) {
							manufacturers.push(products[i].manufacturerName);
						}
					}
					
					var dataProducts = [];
					for (var i = 0; i < manufacturers.length; i++) {
						var manufacturerProducts = 0;
						for (var k = 0; k < products.length; k++) {
							if (manufacturers[i] == products[k].manufacturerName) {
								manufacturerProducts ++;
							}
						}
						var tempData = {
							'x': manufacturers[i],
							'y': manufacturerProducts
						};
						dataProducts.push(tempData);
					}
				}

				return dataProducts;
			}
			function getClient(idClient) {
				var url = "<?php echo base_url('index.php/Back/r_ORD_Client/') ?>"
				var postData = {
					idClient: idClient
				}
				return ajaxGet(url,postData);
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'features' || $page == 'attributes') : ?>
		<!-- FEATURES - ATTRIBUTES SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>;
			
			$(document).on('click','#duplicate',function(){
				var url = "<?php echo base_url('index.php/Back/dp_PRD_Features'); ?>";
				var data = $(this).serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_PRD_Features'); ?>";
				var data = $("#features").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#delete-values',function(){
				var url = "<?php echo base_url('index.php/Back/d_PRD_Value'); ?>";
				var data = $("#values").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});	
			$(document).on('submit','#add-feature-form',function(e){
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/Back/c_PRD_Features'); ?>";
				ajaxPost(url,$(this),data,true,0,false,true);
			});
			$(document).on('submit','#add-value-form',function(e){
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/Back/c_PRD_values'); ?>";
				ajaxPost(url,$(this),data,true,0,false,true);
			});
			$(document).on('submit','#modify-feature-form',function(e){
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/Back/u_PRD_Features'); ?>";
				ajaxPost(url,$(this),data,true,0,false,true);
			});
			$(document).on('submit','#modify-value-form',function(e){
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/Back/u_PRD_Values'); ?>";
				ajaxPost(url,$(this),data,true,0,false,true);
			});
			$(document).on('click',function (e) {
			    if ( $(".lineModifyValue").is(e.target) ) {
			    	var id = $(e.target).data('id');
					var values = getValues(id);
					$('#values table tbody').empty();
					for (var i = 0; i < values.length; i++) {
						$('#values table tbody').append('<tr><td><input type="checkbox" name="idValue[]" value="' + values[i].idValue + '" /></td><td class="modifyValue" data-id="' + values[i].idValue + '" data-toggle="modal" data-target="#modifyValue">' + values[i].valueName + '</td></tr>');
					}
			    	
			    	if( $('#features').css('opacity') == 1) {
			    		$("#values").show();
			    	    $("#features").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#values").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else {
			    	if ( $("#values").is(e.target) || $("#values").has(e.target).length > 0 ) {
			    		if ( $(".modifyValue").is(e.target) ) {
			    			var id = $(e.target).data('id');
			    			$(".modal-body input[name='idValue']").val(id);
							$('#tablist').empty();
							$('#tabcontent').empty();
							var translates = getLangValues(id);
							
							for (var i = 0; i < languages.length; i++) {
								if (i == 0) {
					    			firstIntereaction = 'active';
					    		} else {
					    			firstIntereaction = '';
					    		}
								$('#tablist').append('<li role="presentation" class="' + firstIntereaction + '"><input type="hidden" name="languages[]" value="' + languages[i] + '" /><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
								$('#tabcontent').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"></div>');
							}
							for (var i = 0; i < translates.length; i++) {
								$('#'+ translates[i].language).append('<div class="form-group"><label>Traduzione valore</label><input type="hidden" name="idLangValue[]" value="' + translates[i].idLangValue + '"><input class="form-control" name="valueName[]" required="true" value="' + translates[i].valueName + '"></div>');
							}
			    		}
			    	} else {
			    		if ( $(".lineModifyFeature").is(e.target) ) {
			    			var id = $(e.target).data('id');
			    			$('#tablist-feature').empty();
			    			$('#tabcontent-feature').empty();
			    			var translates = getLangFeatures(id);
			    			
			    			for (var i = 0; i < languages.length; i++) {
			    				if (i == 0) {
			    					firstIntereaction = 'active';
			    				} else {
			    					firstIntereaction = '';
			    				}
			    				$('#tablist-feature').append('<li role="presentation" class="' + firstIntereaction + '"><input type="hidden" name="languages[]" value="' + languages[i] + '" /><a href="#f-' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
			    				$('#tabcontent-feature').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="f-' + languages[i] + '"></div>');
			    			}
			    			for (var i = 0; i < translates.length; i++) {
			    				$('#f-'+ translates[i].language).append('<div class="form-group"><label>Traduzione Attributo</label><input type="hidden" name="idLangFeature[]" value="' + translates[i].idLangFeature + '"><input class="form-control" name="featureName[]" required="true" value="' + translates[i].featureName + '"></div>');
			    			}
			    		}
				    	if( $('#features').css('opacity') == 0) {
					    	$("#features").animate({
					    		opacity: 1,
					    	  	height: "toggle"
					    	}, 500, function() {});
					    	$("#values").animate({
					    		opacity: 0,
					    	}, 500, function() {
					    		$("#values").hide();
					    	});
				    	}
			    	}
			    }
			});	
			function getValues(idFeature) {
				var url = "<?php echo base_url('index.php/Back/r_PRD_Values_byFeature/') ?>"
				var postData = {
					idFeature: idFeature
				}
				return ajaxGet(url,postData);
			}	
			function getLangValues(idValue) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Value/') ?>"
				var postData = {
					idValue: idValue
				}
				return ajaxGet(url,postData);
			}
			function getLangFeatures(idFeature) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Features/') ?>"
				var postData = {
					idFeature: idFeature
				}
				return ajaxGet(url,postData);
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'n_client') : ?>
		<!-- N_CLIENT SCRIPT -->
		<script type="text/javascript">
			$(document).on('submit','#addClient',function (e) {
				e.preventDefault();
				var url = "<?php echo base_url('index.php/Back/c_ORD_Client'); ?>";
				var data = $('#addClient').serialize();
				ajaxPost(url,$(this),data,false,0,false,true);
			});
			// RENDERE REQUIRED SE IN ITALIA
			$(document).on('change',"select[name='idCountry']",function(){
				if ($("select[name='idCountry']").val() == 10) {
					$("input[name='clientFiscalCode']").prop({required: true, disabled: false });
					$("input[name='clientFiscalCode']").prev().append('<span class="asterisk"> *</span>');
				} else {
					$("input[name='clientFiscalCode']").prop({required: false, disabled: true });
					$("input[name='clientFiscalCode']").val('');
					$(this).parent().removeClass('has-error');
					$(this).parent().removeClass('has-success');
					$(".asterisk").remove();
				}
			});
			$(document).on('keyup',"input[name='clientFiscalCode']",function() {
				var pattern = new RegExp('^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$');
				if(!pattern.test( $(this).val() )) {
					$(this).parent().removeClass('has-success');
					$(this).parent().addClass('has-error');
				} else {
					$(this).parent().removeClass('has-error');
					$(this).parent().addClass('has-success');
				}	
			});
		</script>
	<?php endif; ?>
	<?php if($page == 'orders') : ?>
		<!-- ORDERS SCRIPT -->
		<script type="text/javascript">
			$(document).on('click',function (e) {
			    if ( $(".lineOrderDetail").is(e.target) ) {
			    	var id = $(e.target).data('id');
			    	
					$('#order-general-informations').empty();
					$('#order-shipping-informations').empty();
					$('#order-products-informations').empty();
					$('#order-products table tbody').empty();
					var order = getOrder(id);
					
					console.log(order);
					var products = getProducts(order.idOrder);
					var tempCombinations = new Array();
					for (var i = 0; i < products.length; i++) {
						tempCombinations.push(products[i].idCombination);
					}
					var combinations = getCombinations(tempCombinations);
					var verificaPaypal = "Non verificato";
					var orderState = "In attesa di spedizione";
					if( order.invoicePaypal == 1 ) {
						verificaPaypal = "Verificato";
					}
					switch (order.orderStatus) {
						case 0:
							orderState = "In attesa di spedizione";
							break;
						case 1:
							orderState = "Completato";
							break;
					}
					$('#order-general-informations').append('<div class="row"><div class="col-md-6"><label>ID Ordine:</label> ' + order.idOrder + '<br><label>Identificativo Paypal:</label> ' + order.idPaypal + '<br><label>Verifica Paypal:</label> ' + verificaPaypal + '<br><label>Data di creazione:</label> ' + order.createdOn + '<br></div><div class="col-md-6"><label>Numero di prodotti:</label> ' + products.length + '<br><label>Importo totale (Escluse spese di spedizione):</label> ' + order.orderAmount + ' € <br><label>Spese di spedizione:</label>  ' + order.shippingAmount + ' € <br><label>Stato dell ordine:</label> ' + orderState + '<br></div></div>');
					
					$('#id-order').append(order.idOrder);
					console.log(order);
					$('#order-shipping-informations').append('<div class="row"><div class="col-md-6">	<h4>INDIRIZZO DI FATTURAZIONE</h4><label>Nominativo:</label> ' + castingNull(order.billingName) + '<br><label>Indirizzo:</label> ' + castingNull(order.billingAddress) + '<br><label>Città:</label> ' + castingNull(order.billingCity) + '<br><label>Codice Postale:</label> ' + castingNull(order.billingZip) + '<br><label>Nazione:</label> ' + castingNull(order.billingCountryName) + '<br><label>Email di Contatto:</label> ' + castingNull(order.billingEmail) + '<br></div><div class="col-md-6"><h4>INDIRIZZO DI SPEDIZIONE</h4><label>Nominativo:</label> ' + order.shippingName + '<br><label>Indirizzo:</label> ' + order.shippingAddress + '<br><label>Città:</label> ' + order.shippingCity + '<br><label>Codice Postale:</label> ' + order.shippingZip + '<br><label>Nazione:</label> ' + order.shippingCountryName + '<br></div></div>');
					
					for (var i = 0; i < products.length; i++) {
						var combinationText = '';
						for (var k = 0; k < combinations.length; k++) {
							if (products[i].idCombination == combinations[k].idCombination) {
								for (var j = 0; j < combinations[k].groups.length; j++) {
									if (j == 0) {
										combinationText += combinations[k].groups[j].featureName + ': ' + combinations[k].groups[j].valueName;
									} else {
										combinationText += ' - ' + combinations[k].groups[j].featureName + ': ' + combinations[k].groups[j].valueName;
									}
								}
							}
						}
						$('#order-products table tbody').append('<tr><td><img src="<?php echo $this->config->item('resources_img'); ?>/products/extra_small/' + products[i].photoName + '"></td><td>' + products[i].productName + '<br>' + combinationText + '</td><td>' + (products[i].productAmount / products[i].productQuantity) + ' €</td><td>' + products[i].productQuantity + '</td><td>' + products[i].productAmount+ '€ </td></tr>');
					}
					
			    	if( $('#orders').css('opacity') == 1) {
			    		$("#order-detail").show();
			    	    $("#orders").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#order-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else if ($("#order-detail").has(e.target).length <= 0 ) {
					if( $('#orders').css('opacity') == 0 ) {
					    $("#orders").animate({
							opacity: 1,
						  	height: "toggle"
						}, 500, function() {});
						$("#order-detail").animate({
							opacity: 0,
						}, 500, function() {
							$("#order-detail").hide();
						});
					}
			    }
			});
			function getOrder(idOrder) {
				var url = "<?php echo base_url('index.php/Back/r_ORD_Order/') ?>"
				var postData = {
					idOrder: idOrder
				}
				return ajaxGet(url,postData);
			}
			function getProducts(idOrder) {
				var url = "<?php echo base_url('index.php/Back/ra_ORD_Orders_Products/') ?>"
				var postData = {
					idOrder: idOrder
				}
				return ajaxGet(url,postData);
			}
			function getCombinations(idCombinations) {
				var url = "<?php echo base_url('index.php/Back/r_PRD_Groups_byCombinations/') ?>"
				var postData = {
					idCombinations: idCombinations
				}
				return ajaxGet(url,postData);
			}
			function castingNull(value) {
				if (value == null || value == '') {
					return 'Non disponibile';
				} else {
					return value;
				}
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'banners') : ?>
		<!-- BANNERS SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>;
		
			$(document).on('click',function (e) {
			    if ( $(".lineBannerDetail").is(e.target) ) {
			    	var id = $(e.target).data('id');
					$('#banner-detail ul').empty();
					$('#banner-detail .tab-content').empty();
					$("#banner-detail-form input[name='idBanner']").val(id);
					var transaltions = getTransaltions(id);
					var banner = getBanner(id);
					popolateGallery(banner);
					var firstIntereaction = '';
					for (var i = 0; i < languages.length; i++) {
						if (i == 0) {
							firstIntereaction = 'active';
						} else {
							firstIntereaction = '';
						}
						for (var j = 0; j < transaltions.length; j++) {
							if (languages[i] == transaltions[j].language) {
								idLangBanner = transaltions[j].idLangBanner;
								bannerName = transaltions[j].bannerName;
								bannerDescription = transaltions[j].bannerDescription;
							}
						}
						$('#banner-detail ul').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
						
						$('#banner-detail .tab-content').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"><div class="form-group"><label>Titolo articolo *</label><input type="hidden" class="form-control" name="idLangBanner[]" value="' + idLangBanner + '"><input class="form-control" name="bannerName[]" value="' + bannerName + '"></div><label>Descrizione dell articolo *</label><div class="form-group"><textarea class="form-control" name="bannerDescription[]" rows="8" required="true">' + bannerDescription + '</textarea></div></div>');
					}
					$('#banner-detail input[name="bannerURL"]').val(banner.bannerURL);
					
			    	if( $('#modifyBanner').css('opacity') == 1) {
			    		$("#banner-detail").show();
			    	    $("#modifyBanner").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#banner-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else if ($("#banner-detail").has(e.target).length <= 0 ) {
					if( $('#modifyBanner').css('opacity') == 0 ) {
					    $("#modifyBanner").animate({
							opacity: 1,
						  	height: "toggle"
						}, 500, function() {});
						$("#banner-detail").animate({
							opacity: 0,
						}, 500, function() {
							$("#banner-detail").hide();
						});
					}
			    }
			});
			$(document).on('submit','#banner-detail-form',function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				
				var $this = $(this);
				loading($this,'progress','');
				
				var id = $("#banner-detail-form input[name='idBanner']").val();
				formData.append("idBanner", id);
				
				$.ajax({
					type: 'POST',
				    url: '<?php echo base_url('index.php/back/u_STN_Banner'); ?>',
				    data: formData,
				    cache: false,
				    contentType: false,
				    processData: false,
				
				    success:function(){
					    $("#banner-detail .gallery").empty();
					    var banner = getBanner(id);
					    popolateGallery(banner);
					    
					    loading($this,'complete','Salvataggio Completato');
					    setTimeout(function() {
					    	location.reload();
					    }, 1100);
					},
					error: function(result){
						loading($this,'error','Errore 001 - Salvataggio non Completata');
					}
				});
			});
			function getTransaltions(idBanner) {
				var tempBanner = new Array();
				$.ajax({
				 	type: "POST",
				    dataType: 'json',
				    url: "<?php echo base_url('index.php/Back/r_LANG_Banners/') ?>",
				    data: {idBanner: idBanner},
				    async: false,
				       				
				     success: function(result) {
				     	tempBanner = result;
				     },
				     error: function(result) {
				     	alert(result);
				     },
				});
				return tempBanner;
			}
			function getBanner(idBanner) {
				var tempBanner = new Array();
				$.ajax({
					type: 'post',
					dataType: 'json',
				    url: '<?php echo base_url('index.php?/back/r_STN_Banner'); ?>',
				    data: {idBanner: idBanner},
				    async: false,
					
				    success: function(result) {
				    	tempBanner = result;
				    },
				    error: function(error) {
				    	alert('Errore 631:' + error);
				    }
				});
				return tempBanner;
			}
			function popolateGallery(banner) {
				$("#banner-detail .gallery").empty();
				$("#banner-detail .gallery").append('<div class="item img-thumbnail"> <img src="<?php echo $this->config->item('resources_img'); ?>/img/banners/' + banner.photoName + '" class="img-responsive"><br/><br/></div>');
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'n_slide') : ?>
		<script type="text/javascript">
			$(document).on('submit','#addSlide',function (e) {
				e.preventDefault();
				var formData = new FormData(this);
				var url = "<?php echo base_url('index.php/Back/c_STN_Slide'); ?>";
				ajaxPost(url,$(this),formData,false,0,true,false);
				setTimeout(function() {
					location.href = "<?php echo site_url('back/slides'); ?>";
				}, 1100);
			});
		</script>
	<?php endif; ?>
	<?php if($page == 'slides') : ?>
		<!-- SLIDES SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>;
			
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Slide'); ?>";
				var data = $("#modifySlide").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#duplicate',function(){
				var url = "<?php echo base_url('index.php/Back/dp_STN_Slides'); ?>";
				var data = $("#modifySlide").serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			$(document).on('click',function (e) {
			    if ( $(".lineSlideDetail").is(e.target) ) {
			    	var id = $(e.target).data('id');
					$('#slide-detail ul').empty();
					$('#slide-detail .tab-content').empty();
					$("#slide-detail-form input[name='idSlide']").val(id);
					var transaltions = getTransaltions(id);
					var slide = getSlide(id);
					$("#slide-detail-form input[name='photoName']").val(slide.photoName);
					popolateGallery(slide);
					var firstIntereaction = '';
					for (var i = 0; i < languages.length; i++) {
						if (i == 0) {
							firstIntereaction = 'active';
						} else {
							firstIntereaction = '';
						}
						for (var j = 0; j < transaltions.length; j++) {
							if (languages[i] == transaltions[j].language) {
								idLangSlide = transaltions[j].idLangSlide;
								slideName = transaltions[j].slideName;
								slideDescription = transaltions[j].slideDescription;
							}
						}
						$('#slide-detail ul').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
						$('#slide-detail .tab-content').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"><div class="form-group"><label>Titolo slide *</label><input type="hidden" class="form-control" name="idLangSlide[]" value="' + idLangSlide + '"><input class="form-control" name="slideName[]" value="' + slideName + '"></div><label>Descrizione della slide *</label><div class="form-group"><textarea class="form-control" name="slideDescription[]" rows="8" required="true">' + slideDescription + '</textarea></div></div>');
					}
			    	if( $('#modifySlide').css('opacity') == 1) {
			    		$("#slide-detail").show();
			    	    $("#modifySlide").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#slide-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else if ($("#slide-detail").has(e.target).length <= 0 ) {
					if( $('#modifySlide').css('opacity') == 0 ) {
					    $("#modifySlide").animate({
							opacity: 1,
						  	height: "toggle"
						}, 500, function() {});
						$("#slide-detail").animate({
							opacity: 0,
						}, 500, function() {
							$("#slide-detail").hide();
						});
					}
			    }
			});
			$(document).on('submit','#slide-detail-form',function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				var id = $("#slide-detail-form input[name='idSlide']").val();
				formData.append("idArticle", id);
				var $this = $(this);
				loading($this,'progress');
				$.ajax({
					type: 'POST',
				    url: '<?php echo base_url('index.php/back/u_STN_Slide'); ?>',
				    data: formData,
				    cache: false,
				    contentType: false,
				    processData: false,
				    dataTyoe: 'json',
				    success:function(result){
					    $("#slide-detail .gallery").empty();
					    var slide = getSlide(id);
					    popolateGallery(slide);
					    loading($this,'complete','Salvataggio Completato');
					},
					error: function(result){
						loading($this,'error','Errore 003 - Salvataggio non Completato');
					}
				});
			});
			function getSlide(idSlide) {
				var url = "<?php echo base_url('index.php/Back/r_STN_Slide/') ?>"
				var postData = {
					idSlide: idSlide
				}
				return ajaxGet(url,postData);
			}
			function getTransaltions(idSlide) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Slides/') ?>"
				var postData = {
					idSlide: idSlide
				}
				return ajaxGet(url,postData);
			}
			function popolateGallery(slide) {
				$("#slide-detail .gallery").empty();
				$("#slide-detail .gallery").append('<div class="item img-thumbnail"> <img src="<?php echo $this->config->item('resources_img'); ?>/slides/' + slide.photoName + '" class="img-responsive"><br/><br/></div>');
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'article_categories') : ?>
		<!-- ARTICLES_CATEGORIES SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>;
			
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Articles_Categories'); ?>";
				var data = $("#modifyCategory").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('submit','#add_images_form',function(){
				var url = "<?php echo base_url('index.php/Back/c_STN_Articles_Category'); ?>";
				var data = $(this).serialize();
				ajaxPost(url,$(this),data,false,0,false,true);
			});
			$(document).on('submit','#modify_articles_category_form',function(){
				var url = "<?php echo base_url('index.php/Back/u_STN_Articles_Category'); ?>";
				var data = $(this).serialize();
				ajaxPost(url,$(this),data,false,0,false,true);
			});
			
			$(document).on('click',function (e) {
				var action = $(e.target).data('action');
				switch (action) {
					case 'update':
						var idCategory = $(e.target).data('id');
						popolateCategory(idCategory);
						if( $('#modifyCategory').css('opacity') == 1) {
							$("#category-detail").show();
						    $("#modifyCategory").animate({
								opacity: 0,
							  	height: "toggle"
							}, 500, function() {});
							$("#category-detail").animate({
								opacity: 1,
							}, 500, function() {});
						}
						break;
					case 'add':
						if( $('#modifyCategory').css('opacity') == 1) {
							$("#add-articles-category").show();
						    $("#modifyCategory").animate({
								opacity: 0,
							  	height: "toggle"
							}, 500, function() {});
							$("#add-articles-category").animate({
								opacity: 1,
							}, 500, function() {});
						}
						break;
					default:
						if ($("#category-detail").has(e.target).length <= 0 && $("#add-articles-category").has(e.target).length <= 0 ) {
							if( $('#modifyCategory').css('opacity') == 0 ) {
								$("#modifyCategory").animate({
									opacity: 1,
								  	height: "toggle"
								}, 500, function() {});
								$("#add-articles-category").animate({
									opacity: 0,
								}, 500, function() {
									$("#add-articles-category").hide();
								});
								$("#category-detail").animate({
									opacity: 0,
								}, 500, function() {
									$("#category-detail").hide();
								});
							}
						}
						break;
				}   
			});
			function getTransaltions(idArticlesCategory) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Articles_Categories/') ?>"
				var postData = {
					idArticlesCategory: idArticlesCategory
				}
				return ajaxGet(url,postData);
			}
			function popolateCategory(idCategory) {
				$('#category-detail ul').empty();
				$('#category-detail .tab-content').empty();
				
				var transaltions = getTransaltions(idCategory);
				
				var firstIntereaction = '';
				for (var i = 0; i < languages.length; i++) {
					if (i == 0) {
						firstIntereaction = 'active';
					} else {
						firstIntereaction = '';
					}
	
					for (var j = 0; j < transaltions.length; j++) {
						if (languages[i] == transaltions[j].language) {
							idLangArticlesCategory = transaltions[j].idLangArticlesCategory;
							articlesCategoryName = transaltions[j].articlesCategoryName;
							articlesCategoryDescription = transaltions[j].articlesCategoryDescription;
						}
					}
					
					$('#category-detail ul').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
					
					$('#category-detail .tab-content').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"><div class="form-group"><label>Titolo categoria *</label><input type="hidden" class="form-control" name="idLangArticlesCategory[]" value="' + idLangArticlesCategory + '"><input class="form-control" name="articlesCategoryName[]" value="' + articlesCategoryName + '"></div><label>Descrizione categoria *</label><div class="form-group"><textarea class="form-control" name="articlesCategoryDescription[]" rows="8" required="true">' + articlesCategoryDescription + '</textarea></div></div>');
				}
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'articles') : ?>
		<!-- ARTICLES SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>
			
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Articles'); ?>";
				var data = $("#modifyArticle").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#duplicate',function(){
				var url = "<?php echo base_url('index.php/Back/dp_STN_Articles'); ?>";
				var data = $("#modifyArticle").serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			$(document).on('click',function (e) {
			    if ( $(".lineArticleDetail").is(e.target) ) {
			    	// STO OPERANDO SULLA TABELLA DEGLI ARTICOLI
			    	var id = $(e.target).data('id');
					$('#article-detail ul').empty();
					$('#article-detail .tab-content').empty();
					$("#article-detail-form input[name='idArticle']").val(id);
					var transaltions = getTransaltions(id);
					var images = getImages(id);
					popolateGallery(images);
					var firstIntereaction = '';
					for (var i = 0; i < languages.length; i++) {
						if (i == 0) {
							firstIntereaction = 'active';
						} else {
							firstIntereaction = '';
						}
						for (var j = 0; j < transaltions.length; j++) {
							if (languages[i] == transaltions[j].language) {
								idLangArticle = transaltions[j].idLangArticle;
								articleName = transaltions[j].articleName;
								articleDescription = transaltions[j].articleDescription;
							}
						}
						$('#article-detail ul').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
						$('#article-detail .tab-content').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"><div class="form-group"><label>Titolo articolo *</label><input type="hidden" class="form-control" name="idLangArticle[]" value="' + idLangArticle + '"><input class="form-control" name="articleName[]" value="' + articleName + '"></div><label>Descrizione dell articolo *</label><div class="form-group"><textarea class="form-control" name="articleDescription[]" rows="8" required="true">' + articleDescription + '</textarea></div></div>');
					}
					var article = getArticle(id);
					$('#article-detail input[name="idArticlesCategory"]').each(function(){
						if ($(this).val() == article.idArticlesCategory) {
							$(this).prop('checked',true);
						}
					});
			    	if( $('#modifyArticle').css('opacity') == 1) {
			    		$("#article-detail").show();
			    	    $("#modifyArticle").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#article-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	} else {
			    		$("#article-detail").show();
			    		$("#article-search").animate({
			    			opacity: 0,
			    		  	height: "toggle"
			    		}, 500, function() {});
			    		$("#article-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else {
			    	if ( $("#article-search").has(e.target).length <= 0 && $("#article-detail").has(e.target).length <= 0 && !$("#search").is(e.target)) {
				    	// HO CLICCATO FUORI DA TUTTO
				    	if( $('#modifyArticle').css('opacity') == 0) {
					    	$("#modifyArticle").animate({
					    		opacity: 1,
					    	  	height: "toggle"
					    	}, 500, function() {});
					    	$("#article-search").animate({
					    		opacity: 0,
					    	}, 500, function() {
					    		$("#article-search").hide();
					    	});
					    	$("#article-detail").animate({
					    		opacity: 0,
					    	}, 500, function() {
					    		$("#article-detail").hide();
					    	});
				    	}
			    	} else {
			    	
			    		if ( $("#search").is(e.target) ) {
					    	// STO OPERANDO SULLA RCERCA
					    	$('#modifyArticle-search').empty();
					    	if( $('#modifyArticle').css('opacity') == 1) {
					    		$("#article-search").show();
					    	    $("#modifyArticle").animate({
					        		opacity: 0,
					        	  	height: "toggle"
					        	}, 500, function() {});
					        	$("#article-search").animate({
					    			opacity: 1,
					    		}, 500, function() {});
					    	}
					    }
		    			
			    	}
			    }
			});
			$(document).on('click','#delete-search',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Articles'); ?>";
				var data = $("#modifyArticle-search").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#duplicate-search',function(){
				var url = "<?php echo base_url('index.php/Back/dp_STN_Articles'); ?>";
				var data = $("#modifyArticle-search").serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			$('#article-search-form').on('submit', function (e) {
				e.preventDefault();
				
				$('#modifyArticle-search').empty();
				
				var $this = $(this);
				loading($this,'progress','');
				
				$.ajax({
					type: 'post',
				    url: '<?php echo base_url('index.php/back/s_STN_Articles'); ?>',
				    data: $(this).serialize(),
				    dataType: 'json',
				        
				    success: function (result) {
				    	$('#modifyArticle-search').append('<div class="col-xs-12"><div class="table-responsive"><table class="table table-hover table-striped table-condensed table-articles"><thead><tr><th></th><th>Immagine</th><th>Titolo</th><th>Descrizione</th><th>Categoria</th><th>Creazione</th></tr></thead> <tbody></tbody></table></div>');
				    	if (result) {
				    		console.log(result);
					    	$.each(result, function(id) {
					    		$('#modifyArticle-search table tbody').append('<tr><td><input type="checkbox" name="idArticle[]" value="' + result[id].idArticle + '" /></td><td class="lineArticleDetail" data-id="' + result[id].idArticle + '"><img src="<?php echo $this->config->item('resources_img') ?>/news/extra_small/' + result[id].photoName + '" alt="' + result[id].articleName + '"></td><td class="lineArticleDetail" data-id="' + result[id].idArticle + '">' + result[id].articleName.substring(0, 100) + '</td><td class="lineArticleDetail" data-id="' + result[id].idArticle + '">' + result[id].articleDescription.substring(0, 150) + ' ... </td><td class="lineArticleDetail" data-id="' + result[id].idArticle + '">' + result[id].articlesCategoryName + '</td><td class="lineArticleDetail" data-id="' + result[id].idArticle + '">' + result[id].createdOn + '</td></tr>');
					    	});
				    	} else {
				    		$('#modifyArticle-search').append('<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessun Articolo trovato</p></div>');
				    	}
				    	loading($this,'complete','Ricerca Completata');
				    },
				    error: function (error) {
				    	loading($this,'error','Errore 005 - Ricerca non Completato');
				    }
				});
			});
			$(document).on('submit','#article-detail-form',function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				
				var $this = $(this);
				loading($this,'progress','');
				
				var id = $("#article-detail-form input[name='idArticle']").val();
				formData.append("idArticle", id);
				
				var nFile = $('#article-detail-form input[type=file]').get(0).files.length;
				formData.append("filesNumber", nFile);
				
				$.ajax({
					type: 'POST',
				    url: '<?php echo base_url('index.php/back/u_STN_Article'); ?>',
				    data: formData,
				    cache: false,
				    contentType: false,
				    processData: false,
				
				    success:function(){
					    $("#article-detail .gallery").empty();
					    var images = getImages(id);
					    popolateGallery(images);
					    
					    loading($this,'complete','Salvataggio Completato');
					    setTimeout(function() {
					    	location.reload();
					    }, 1100);
					},
					error: function(result){
						loading($this,'error','Errore 002 - Salvataggio non Completato');
					}
				});
			});
			function getArticle(idArticle) {
				var url = "<?php echo base_url('index.php/Back/r_STN_Article/') ?>"
				var postData = {
					idArticle: idArticle
				}
				return ajaxGet(url,postData);
			}
			function getTransaltions(idArticle) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Articles/') ?>"
				var postData = {
					idArticle: idArticle
				}
				return ajaxGet(url,postData);
			}
			function getImages(idArticle) {
				var url = "<?php echo base_url('index.php/Back/r_STN_Photos_byArticle/') ?>"
				var postData = {
					idArticle: idArticle
				}
				return ajaxGet(url,postData);
			}
			function popolateGallery(images) {
				$("#article-detail .gallery").empty();
				
				$.each(images, function(id) {
					var isChecked = ''
					if ( images[id].isCover != 0 ) {
						isChecked = 'checked';
					} else {
						isChecked = '';
					}
				     $("#article-detail .gallery").append('</label><div id="' + images[id].idPhoto + '" class="col-xs-12 col-md-3"> <img src="<?php echo $this->config->item('resources_img'); ?>/news/small/' + images[id].photoName + '" class="img-responsive img-thumbnail"><input type="radio" name="idPhotoCover" value="' + images[id].idPhoto + '" ' + isChecked + '> Principale &nbsp;<input type="checkbox" name="idPhoto[]" value="' + images[id].idPhoto + '"/> Cancella<br/><br/></div>');
				 });
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'n_article') : ?>
		<!-- N_ARTICLES SCRIPT -->
		<script type="text/javascript">
			$(document).on('submit','#addArticle',function (e) {
				e.preventDefault();
				var formData = new FormData(this);
				
				var nFile = $('#addArticle input[type=file]').get(0).files.length;
				formData.append("filesNumber", nFile);
				
				var url = "<?php echo base_url('index.php/Back/c_STN_Article'); ?>";
				ajaxPost(url,$(this),formData,false,0,true,true);
				location.href = "<?php echo site_url('back/articles'); ?>";
			});
		</script>
	<?php endif; ?>
	<?php if ($page == 'pages' || $page == 'n_page') : ?>
		<!-- PAGES - N_PAGE SCRIPT -->
		<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/bootstrap-wysiwyg/bootstrap3-wysihtml5.all.min.js"></script>
		<script type="text/javascript">
		    $('.wysihtml5').wysihtml5({
		        "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
		        "emphasis": true, //Italics, bold, etc. Default true
		        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
		        "html": true, //Button which allows you to edit the generated HTML. Default false
		        "link": true, //Button to insert a link. Default true
		        "image": false, //Button to insert an image. Default true,
		        "color": true //Button to change color of font  
		    });
		</script> 
	<?php endif; ?> 
	<?php if($page == 'pages') : ?>
		<!-- PAGES SCRIPT -->
		<script type="text/javascript">
			var languages = <?php echo json_encode($languages); ?>;
			
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Pages'); ?>";
				var data = $("#modifyPage").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#duplicate',function(){
				var url = "<?php echo base_url('index.php/Back/dp_STN_Pages'); ?>";
				var data = $("#modifyPage").serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			$(document).on('click',function (e) {
			    if ( $(".linePageDetail").is(e.target) ) {
			    	var id = $(e.target).data('id');
					$('#page-detail ul').empty();
					$('#page-detail .tab-content').empty();
					var transaltions = getTransaltions(id);
					var firstIntereaction = '';
					for (var i = 0; i < languages.length; i++) {
						if (i == 0) {
							firstIntereaction = 'active';
						} else {
							firstIntereaction = '';
						}
						for (var j = 0; j < transaltions.length; j++) {
							if (languages[i] == transaltions[j].language) {
								idLangPage = transaltions[j].idLangPage;
								pageName = transaltions[j].pageName;
								pageDescription = transaltions[j].pageDescription;
							}
						}
						$('#page-detail ul').append('<li role="presentation" class="' + firstIntereaction + '"><a href="#' + languages[i] + '" role="tab" data-toggle="tab">' + languages[i].toUpperCase() + '</a></li>');
						$('#page-detail .tab-content').append('<div role="tabpanel" class="tab-pane ' + firstIntereaction + '" id="' + languages[i] + '"><div class="form-group"><label>Titolo pagina</label><input type="hidden" class="form-control" name="idLangPage[]" value="' + idLangPage + '"><input class="form-control" name="pageName[]" value="' + pageName + '"></div><label>Corpo della pagina *</label><div class="form-group"><textarea class="wysihtml5 form-control" name="pageDescription[]" rows="16" required="true">' + pageDescription + '</textarea></div></div>');
					}
					$('.wysihtml5').wysihtml5({
					    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
					    "emphasis": true, //Italics, bold, etc. Default true
					    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
					    "html": true, //Button which allows you to edit the generated HTML. Default false
					    "link": true, //Button to insert a link. Default true
					    "image": false, //Button to insert an image. Default true,
					    "color": true //Button to change color of font  
					});
					$('#page-detail ul').append()
					$('#order-general-informations').append('');
			    	if( $('#modifyPage').css('opacity') == 1) {
			    		$("#page-detail").show();
			    	    $("#modifyPage").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#page-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	} else {
			    		$("#page-detail").show();
			    		$("#page-search").animate({
			    			opacity: 0,
			    		  	height: "toggle"
			    		}, 500, function() {});
			    		$("#page-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else {
				    if ( $("#page-search").has(e.target).length <= 0 && $("#page-detail").has(e.target).length <= 0 && !$("#search").is(e.target)) {
			        	// HO CLICCATO FUORI DA TUTTO
			        	if( $('#modifyPage').css('opacity') == 0) {
			    	    	$("#modifyPage").animate({
			    	    		opacity: 1,
			    	    	  	height: "toggle"
			    	    	}, 500, function() {});
			    	    	$("#page-search").animate({
			    	    		opacity: 0,
			    	    	}, 500, function() {
			    	    		$("#page-search").hide();
			    	    	});
			    	    	$("#page-detail").animate({
			    	    		opacity: 0,
			    	    	}, 500, function() {
			    	    		$("#page-detail").hide();
			    	    	});
			        	}
			    	} else {
			    		if ( $("#search").is(e.target) ) {
			    	    	// STO OPERANDO SULLA RCERCA
			    	    	$('#modifyPage-search').empty();
			    	    	if( $('#modifyPage').css('opacity') == 1) {
			    	    		$("#page-search").show();
			    	    	    $("#modifyPage").animate({
			    	        		opacity: 0,
			    	        	  	height: "toggle"
			    	        	}, 500, function() {});
			    	        	$("#page-search").animate({
			    	    			opacity: 1,
			    	    		}, 500, function() {});
			    	    	}
			    	    }
			    	}
				}
			});
			$('#page-search-form').on('submit', function (e) {
				e.preventDefault();
				$('#modifyPage-search').empty();
				
				var $this = $(this);
				loading($this,'progress','');
				
				$.ajax({
					type: 'post',
				    url: '<?php echo base_url('index.php/back/s_STN_Pages'); ?>',
				    data: $(this).serialize(),
				    dataType: 'json',
				        
				    success: function (result) {
				    	$('#modifyPage-search').append('<div class="col-xs-12"><div class="table-responsive"><table class="table table-hover table-striped table-condensed table-pages"><thead><tr><th></th><th>Titolo</th><th>Testo</th><th>Data Creazione</th></tr></thead> <tbody></tbody></table></div>');
				    	if (result) {
				    		console.log(result);
					    	$.each(result, function(id) {
					    		$('#modifyPage-search table tbody').append('<tr><td><input type="checkbox" name="idPage[]" value="' + result[id].idPage + '" /></td><td class="linePageDetail" data-id="' + result[id].idPage + '">' + result[id].pageName.substring(0, 100) + '</td><td class="linePageDetail" data-id="' + result[id].idPage + '">' + result[id].pageDescription.substring(0, 150) + ' ... </td><td class="linePageDetail" data-id="' + result[id].idPage + '">' + result[id].createdOn + '</td></tr>');
					    	});
				    	} else {
				    		$('#modifyPage-search').append('<div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna Pagina trovata</p></div>');
				    	}
				    	loading($this,'complete','Ricerca Completata');
				    },
				    error: function (error) {
				    	loading($this,'error','Errore 005 - Ricerca non Completata');
				    }
				});
			});
			$(document).on('click','#delete-search',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Pages'); ?>";
				var data = $("#modifyPage-search").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			$(document).on('click','#duplicate-search',function(){
				var url = "<?php echo base_url('index.php/Back/d_STN_Pages'); ?>";
				var data = $("#modifyPage-search").serialize();
				ajaxPost(url,$(this),data,false,2,false,true);
			});
			function getTransaltions(idPage) {
				var url = "<?php echo base_url('index.php/Back/r_LANG_Page/') ?>"
				var postData = {
					idPage: idPage
				}
				return ajaxGet(url,postData);
			}
		</script>
	<?php endif; ?>
	<?php if($page == 'n_page') : ?>
		<!-- N_PAGE SCRIPT -->
		<script type="text/javascript">
			$(document).on('submit','#addPage',function (e) {
				e.preventDefault();
				var url = "<?php echo base_url('index.php/Back/c_STN_Page'); ?>";
				var data = $("#addPage").serialize();
				ajaxPost(url,$(this),data,false,0,false,true);
			});
		</script>
	<?php endif; ?>
	<?php if($page == 'settings') : ?>
		<!-- SETTINGS SCRIPT -->
		<script type="text/javascript">
			$(document).on('submit','#modifySettings',function (e) {
				e.preventDefault();
				var url = "<?php echo base_url('index.php/Back/u_STN_Settings'); ?>";
				var data = $("#modifySettings").serialize();
				ajaxPost(url,$(this),data,false,0,false,true);
			});
		</script>
	<?php endif; ?>
	<?php if($page == 'manufacturers') : ?>
		<!-- MANUFACTURER SCRIPT -->
		<script type="text/javascript">
			$(document).on('click','#delete',function(){
				var url = "<?php echo base_url('index.php/Back/d_PRD_Manufacturers'); ?>";
				var data = $("#manufacturer").serialize();
				ajaxPost(url,$(this),data,false,1,false,true);
			});
			
			$(document).on('click','.btnModifyManufacturer',function() {
				var id = $(this).data('id');
				
				$("#modifyManufacturer input[name='idManufacturer']").val(id);
				$('#modifyManufacturer input').val('');
		
				$.ajax({
					type: "POST",
				    dataType: 'json',
				    url: "<?php echo base_url('index.php/Back/r_PRD_Manufacturer/') ?>",
				    data: {idManufacturer: id},
				    async: false,
				      				
				    success: function(result) {
				    	$("#modifyManufacturer input[name='idManufacturer']").val(id);
				    	$("#modifyManufacturer input[name='manufacturerName']").val(result[0].manufacturerName);
				    	$("#modifyManufacturer textarea[name='manufacturerDescription']").val(result[0].manufacturerDescription);
				    	$("#modifyManufacturer input[name='coverName']").val(result[0].photoName);
				    },
				    error: function(result) {
				    	alert(result);
				    },
				});
			});
			$(document).on('submit','#modify-manufacturer-form',function(e){
				e.preventDefault();
				var formData = new FormData(this);
				var url = "<?php echo base_url('index.php/Back/u_PRD_Manufacturer'); ?>";
				ajaxPost(url,$(this),formData,true,0,true,true);
			});
			$(document).on('submit','#add-manufacturer-form',function(e){
				e.preventDefault();
				var formData = new FormData(this);
				var url = "<?php echo base_url('index.php/Back/c_PRD_Manufacturer'); ?>";
				ajaxPost(url,$(this),formData,true,0,true,true);
			});
		</script>
	<?php endif; ?>
	<?php if($page == 'sales') : ?>
		<!-- SALES SCRIPT -->
		<script type="text/javascript">
			var idProducts = new Array();
			var sales = new Array();
			var nTabSale = 0;
			
			$(document).on('change','input[name="saleStart"]',function() {
				var tabSale = $(this).closest('.tab-pane').attr('id');
				var saleStart = $(this).val();
				var saleEnd = $(this).parent().find('input[name="saleEnd"]').val();
				changeDate(saleStart, saleEnd, tabSale);
			});
			$(document).on('change','input[name="saleEnd"]',function() {
				var tabSale = $(this).closest('.tab-pane').attr('id');
				var saleStart = $(this).parent().find('input[name="saleStart"]').val();
				var saleEnd = $(this).val();
				changeDate(saleStart, saleEnd, tabSale);
			});
			$(document).on('keyup','input[name="salePercentage"]',function(){
				var $this = $(this);
				var anotherSaleType = $('input[name="saleAmount"]');
				checkSelectedSaleType($this, anotherSaleType);
				checkNumberLessHundred($this);
				changeSale( $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('keyup','input[name="saleAmount"]',function(){
				var $this = $(this);
				var anotherSaleType = $('input[name="salePercentage"]');
				checkSelectedSaleType($this, anotherSaleType);
				checkNumber($this);
				changeSale( $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('click','input[name="idProduct"]',function() {
				var _tempTabSale = $(this).closest('.tab-pane').attr('id');
				var _tempIdProduct = $(this).val();
				for (var i = 0; i < sales.length; i++) {
					if (sales[i].tabSale == _tempTabSale) {
						if ($(this).is(':checked')) {
							// AGGIUNGO IL PRODOTTO DALL'ARRAY
							sales[i].idProducts.push(_tempIdProduct);
							break;
						} else {
							// RIMUOVO IL PRODOTTO DALL'ARRAY
							sales[i].idProducts = $.grep(sales[i].idProducts, function(value) {
							    return value != _tempIdProduct;
							});
							break;
						}
					}
				}
			});
			$(document).on('click','#newSaleTab',function() {
				nTabSale ++;
				// Deseleziono tutte le tab
				var $this = $(this);
				$this.closest('.nav-tabs').find('li').removeClass('active');
				$this.closest('.nav-tabs').parent().find('.tab-pane').removeClass('active');
				$this.closest('.nav-tabs li:last-child').before('<li class="active"><a class="pointer" data-target="#tabSale_' + nTabSale + '" data-toggle="tab">Assoociazione n°' + nTabSale + '</a></li>');	
				$this.closest('.nav-tabs').parent().find('.tab-content').append('<div class="tab-pane active" id="tabSale_' + nTabSale + '"> <p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente e nel caso si voglia, programmare quest&#39;ultimo. Nel caso in cui non venga inserita alcuna datazione, lo sconto sarà fisso.</p><div class="row p-b-20"> <div class="col-xs-12"> <div class="input-group input-daterange" data-provide="datepicker"> <span class="input-group-addon"> DA </span> <input type="text" class="form-control" name="saleStart"> <span class="input-group-addon"> A </span> <input type="text" class="form-control" name="saleEnd"> </div></div></div><div class="row"> <div class="col-xs-12"> <div class="table-responsive"> <table class="table table-hover table-striped table-bordered table-product"> <thead> <tr> <th></th> <th>Immagine</th> <th>Titolo</th> <th>Produttore</th> <th>Codice</th> <th>Categoria</th> <th>Prezzo</th> </tr></thead> <tbody> </tbody> </table> </div></div><div class="col-xs-12 pagination"> <ul></ul> </div></div></div>');
				
				var currentTable = $('#tabSale_' + nTabSale);
				popolateTableProducts(currentTable, null);
				
				initializeArraySalesGroups('tabSale_' + nTabSale, $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('click',function (e) {
			    if ( $(".lineSaleDetail").is(e.target) ) {  
			    	var id = $(e.target).data('id');
			    	var sale = getSale(id);
					
					console.log(sale);
					
			    	popolateSales(sale);
			    	var container = $('#sale-detail');
					popolateTableSales(container,sale);
			    	
			    	if( $('#modifySale').css('opacity') == 1) {
			    		$("#sale-detail").show();
			    	    $("#modifySale").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#sale-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else {
			    	if ($("#sale-detail").has(e.target).length <= 0) {
						if( $('#modifySale').css('opacity') == 0 ) {
						    $("#modifySale").animate({
								opacity: 1,
							  	height: "toggle"
							}, 500, function() {});
							$("#sale-detail").animate({
								opacity: 0,
							}, 500, function() {
								$("#sale-detail").hide();
							});
						} 	
					} else {
						if ($('.pagination ul a').is(e.target)) {
							e.preventDefault();	
							$this = $(e.target);	
							var paginationNumber = $this.data('ci-pagination-page');
							var container = $this.closest('div.tab-pane');
							for (var k = 0; k < sales.length; k++) {	
								if(sales[k].tabSale == $this.closest('div.tab-pane').attr('id')) {
									popolateTableProducts(container, paginationNumber);
									break;
								}
							}
						}
					}    
				}
			});
			$(document).on('submit','#modifySaleDetail',function(e){
				e.preventDefault();
				var data = {sales: sales};
				var url = "<?php echo base_url('index.php/back/u_PRD_Sale'); ?>";
				ajaxPost(url,$(this),data,false,0,false,true);				
			});
			
			function getProducts(page) {
				var postData = {
					page: page
				};
				var url = "<?php echo base_url('index.php/Back/ra_PRD_Products'); ?>";
				return ajaxGet(url,postData);
			}
			function getSale(idSale) {
				var postData = {
					idSale: idSale
				};
				var url = "<?php echo base_url('index.php/Back/r_PRD_Sale'); ?>";
				return ajaxGet(url,postData);
			}
			// MODIFICO LA DATA IN UNA TAB
			function changeDate(saleStart, saleEnd, tabSale) {
				for (var i = 0; i < sales.length; i++) {
					if (sales[i].tabSale == tabSale) {
						sales[i].newSaleStart = saleStart;
						sales[i].newSaleEnd = saleEnd;
						break;
					}
				}
			}
			// MODIFICO LO SCONTO IN TUTTE LE TAB
			function changeSale(saleAmount, salePercentage) {
				var _tempAmount = saleAmount != '' ? saleAmount : null;
				var _tempPercentage = salePercentage != '' ? salePercentage : null;
				for (var i = 0; i < sales.length; i++) {
					sales[i].newSaleAmount = _tempAmount;
					sales[i].newSalePercentage = _tempPercentage;
				}
			}
			// INIZIALIZZO UN NUOVO ELEMENTO DELL'ARRAY (NUOVA TAB)
			function initializeArraySalesGroups(tabSale, saleAmount, salePercentage) {
				var _tempAmount = saleAmount != '' ? saleAmount : null;
				var _tempPercentage = salePercentage != '' ? salePercentage : null;
				object = {	
					tabSale: tabSale,
					saleStart: null,
					saleEnd: null,
					saleAmount: _tempAmount,
					salePercentage: _tempPercentage,
					newSaleStart: null,
					newSaleEnd: null,
					newSaleAmount: _tempAmount,
					newSalePercentage: _tempPercentage,
					idProducts: new Array()
				}
				sales.push(object);
			}
			// CONTROLLI NUMERO E MINORE DI 100
			function checkNumberLessHundred(source) {
				var pattern = new RegExp('^([0-9]|[1-9][0-9]|100)$'); 
				if(!pattern.test(source.val())) {
					source.parent().removeClass('has-success');
					source.parent().addClass('has-error');
					source.closest('form').find("button[type='submit']").prop('disabled',true);
			    	return false;
			  	} else {
			  		source.parent().removeClass('has-error');
			  		source.parent().addClass('has-success');
			  		source.closest('form').find("button[type='submit']").prop('disabled',false);
			    	return true;
			  	}
			}
			// CONTROLLO MAGGIORE DI ZERO E INT
			function checkNumber(source) {
				var pattern = new RegExp('^[0-9]+([,.][0-9]{1,2})?$'); 
				if(!pattern.test(source.val())) {
					source.parent().removeClass('has-success');
					source.parent().addClass('has-error');
					source.closest('form').find("button[type='submit']").prop('disabled',true);
			    	return false;
			  	} else {
			  		source.parent().removeClass('has-error');
			  		source.parent().addClass('has-success');
			  		source.closest('form').find("button[type='submit']").prop('disabled',false);
			    	return true;
			  	}
			}
			// CONTROLLO QUALE TIPO E' STATO INSERITO E DISABILITO L'ALTRO
			function checkSelectedSaleType(current, other) {
				if (current.val() != '') {
					other.prop('disabled',true);
				} else {
					other.prop('disabled',false);
				}
			}
			// POPOLO L'ARRAY TABSALES 
			function popolateSales(sale) {
				for (var i = 0; i < sale.length; i++) {
					object = {	
						tabSale: 'tabSale_' + nTabSale,
						saleStart: sale[i].saleStart,
						saleEnd: sale[i].saleEnd,
						saleAmount: sale[i].saleAmount,
						salePercentage: sale[i].salePercentage,
						newSaleStart: sale[i].saleStart,
						newSaleEnd: sale[i].saleEnd,
						newSaleAmount: sale[i].saleAmount,
						newSalePercentage: sale[i].salePercentage,
						idProducts: sale[i].idProducts
					}
					nTabSale ++;
					sales.push(object);
				}
			}
			// POPOLO LE TAB
			function popolateTableSales(container,sale) {
				_tempNTabSale = 0;
				container.find('.tab-content').empty();
				container.find('.nav-tabs li:last-child').prev().remove();
				
		    	for (var i = 0; i < sale.length; i++) {
		    		var active = '';
		    		if (i == 0) {
		    			var active = 'active';
		    			
		    			if (sale[i].salePercentage == null) {
		    				$('input[name="salePercentage"]').prop('disabled',true);
		    				$('input[name="salePercentage"]').val('');
		    				$('input[name="saleAmount"]').val(sale[i].saleAmount);
		    			} else {
		    				$('input[name="saleAmount"]').prop('disabled',true);
		    				$('input[name="saleAmount"]').val('');
		    				$('input[name="salePercentage"]').val(sale[i].salePercentage);
		    			}
		    			
		    		} else {
		    			active = '';
		    		}
		    		container.find('.nav-tabs li:last-child').before('<li class="'+ active +'"><a class="pointer" data-target="#tabSale_' + _tempNTabSale + '" data-toggle="tab">Assoociazione n°' + _tempNTabSale + '</a></li>');
		    		
		    		container.find('.tab-content').append('<div class="tab-pane ' + active + '" id="tabSale_' + _tempNTabSale + '"> <p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente e nel caso si voglia, programmare quest&#39;ultimo. Nel caso in cui non venga inserita alcuna datazione, lo sconto sarà fisso.</p><div class="row p-b-20"> <div class="col-xs-12"> <div class="input-group input-daterange" data-provide="datepicker"> <span class="input-group-addon"> DA </span> <input type="text" class="form-control" name="saleStart" value="' + sale[i].saleStart + '"> <span class="input-group-addon"> A </span> <input type="text" class="form-control" name="saleEnd" value="' + sale[i].saleEnd + '"> </div></div></div><div class="row"> <div class="col-xs-12"> <div class="table-responsive"> <table class="table table-hover table-striped table-bordered table-product"> <thead> <tr> <th></th> <th>Immagine</th> <th>Titolo</th> <th>Produttore</th> <th>Codice</th> <th>Categoria</th> <th>Prezzo</th> </tr></thead> <tbody> </tbody> </table> </div></div><div class="col-xs-12 pagination"> <ul></ul> </div></div></div>');
		    		
		    		var _tempContainer = $('#tabSale_' + _tempNTabSale);
		    		popolateTableProducts(_tempContainer, null);
		    		_tempNTabSale ++;
		    	}	
			}
			// POPOLO LA TABELLA DENTRO UN CONTAINER CON I PRODOTTI
			function popolateTableProducts(container, paginationNumber) {
				container.find('table tbody').empty();
				var object = getProducts(paginationNumber);	
				for (var i = 0; i < object.products.length; i++) {
					var productCode;
					var checked = '';
					
					if (object.products[i].productEAN != null) {
						productCode = 'EAN-13';
					}
					if (object.products[i].productSKU != null) {
						productCode = 'SKU';
					}
					if (object.products[i].productEAN == null && object.products[i].productSKU == null) {
						productCode = 'Nessun codice presente';
					}
					// Controllo se è stato già selezionato precedentemente
					for (var k = 0; k < sales.length; k++) {
						if(sales[k].tabSale == container.attr('id')) {
							if( $.inArray(object.products[i].idProduct, sales[k].idProducts) != -1) {
								checked = 'checked';
							}
							break;
						}
					}
					
					container.find('table tbody').append('<tr><td><input type="checkbox" name="idProduct" value="' + object.products[i].idProduct + '" ' + checked + '/></td><td><img src="<?php echo $this->config->item('resources_img') ?>/products/extra_small/' + object.products[i].photoName + '" alt="' + object.products[i].productName + '"></td><td>' + object.products[i].productName + '</td><td>' + object.products[i].manufacturerName + '</td><td>' + productCode + '</td><td>' + object.products[i].categoryName + '</td><td>' + object.products[i].productPrice + ' € </td></tr>');
				}
				container.find('.pagination ul').empty();
				container.find('.pagination ul').append(object.pagination);
			}
		</script>
		<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/bootstrap-datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
		<script type="text/javascript">
			$('.datepicker').datepicker({});
		</script>
	<?php endif; ?>
	<?php if ($page == 'n_sale') : ?>
		<script type="text/javascript">
			var nTabSale = 1;
			
			$(window).on('load',function(){
				var container = $('#tabSale_1');
				initializeArraySalesGroups('tabSale_1', $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
				popolateTableProducts(container, null);
			});
			$(document).on('change','input[name="saleStart"]',function() {
				var tabSale = $(this).closest('.tab-pane').attr('id');
				var saleStart = $(this).val();
				var saleEnd = $(this).parent().find('input[name="saleEnd"]').val();
				changeDate(saleStart, saleEnd, tabSale);
			});
			$(document).on('change','input[name="saleEnd"]',function() {
				var tabSale = $(this).closest('.tab-pane').attr('id');
				var saleStart = $(this).parent().find('input[name="saleStart"]').val();
				var saleEnd = $(this).val();
				changeDate(saleStart, saleEnd, tabSale);
			});
			$(document).on('keyup','input[name="salePercentage"]',function(){
				var $this = $(this);
				var anotherSaleType = $('input[name="saleAmount"]');
				checkSelectedSaleType($this, anotherSaleType);
				checkNumberLessHundred($this);
				changeSale( $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('keyup','input[name="saleAmount"]',function(){
				var $this = $(this);
				var anotherSaleType = $('input[name="salePercentage"]');
				checkSelectedSaleType($this, anotherSaleType);
				checkNumber($this);
				changeSale( $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('click','#newSaleTab',function() {
				nTabSale ++;
				// Deseleziono tutte le tab
				var $this = $(this);
				$this.closest('.nav-tabs').find('li').removeClass('active');
				$this.closest('.nav-tabs').parent().find('.tab-pane').removeClass('active');
				$this.closest('.nav-tabs li:last-child').before('<li class="active"><a class="pointer" data-target="#tabSale_' + nTabSale + '" data-toggle="tab">Assoociazione n°' + nTabSale + '</a></li>');	
				$this.closest('.nav-tabs').parent().find('.tab-content').append('<div class="tab-pane active" id="tabSale_' + nTabSale + '"> <p>In questa sezione è possibile associare uno o più prodotti allo sconto creato nella schermata precedente e nel caso si voglia, programmare quest&#39;ultimo. Nel caso in cui non venga inserita alcuna datazione, lo sconto sarà fisso.</p><div class="row p-b-20"> <div class="col-xs-12"> <div class="input-group input-daterange" data-provide="datepicker"> <span class="input-group-addon"> DA </span> <input type="text" class="form-control" name="saleStart"> <span class="input-group-addon"> A </span> <input type="text" class="form-control" name="saleEnd"> </div></div></div><div class="row"> <div class="col-xs-12"> <div class="table-responsive"> <table class="table table-hover table-striped table-bordered table-product"> <thead> <tr> <th></th> <th>Immagine</th> <th>Titolo</th> <th>Produttore</th> <th>Codice</th> <th>Categoria</th> <th>Prezzo</th> </tr></thead> <tbody> </tbody> </table> </div></div><div class="col-xs-12 pagination"> <ul></ul> </div></div></div>');
				
				var currentTable = $('#tabSale_' + nTabSale);
				popolateTableProducts(currentTable, null);
				
				initializeArraySalesGroups('tabSale_' + nTabSale, $('input[name="saleAmount"]').val(), $('input[name="salePercentage"]').val() );
			});
			$(document).on('click','.pagination ul a',function(e){
				var container = $(this).closest('.tab-pane');
				e.preventDefault();
				var paginationNumber = $(this).data('ci-pagination-page');
				popolateTableProducts(container, paginationNumber)
			});
			$(document).on('click','input[name="idProduct"]',function() {
				var _tempTabSale = $(this).closest('.tab-pane').attr('id');
				var _tempIdProduct = $(this).val();
				for (var i = 0; i < sales.length; i++) {
					if (sales[i].tabSale == _tempTabSale) {
						if ($(this).is(':checked')) {
							// AGGIUNGO IL PRODOTTO DALL?ARRAY
							sales[i].idProducts.push(_tempIdProduct);
							break;
						} else {
							// RIMUOVO IL PRODOTTO DALL'ARRAY
							sales[i].idProducts = $.grep(sales[i].idProducts, function(value) {
							    return value != _tempIdProduct;
							});
							break;
						}
					}
				}
			});
			$(document).on('submit','#newSale',function(e){
				e.preventDefault();
				var salePercentage = $('input[name="salePercentage"]').val();
				var saleAmount = $('input[name="saleAmount"]').val();
				var $this = $(this);
				loading($this,'progress','');
				
				console.log(sales);
				$.ajax({
					type: 'post',
				    url: '<?php echo base_url('index.php/back/c_PRD_Sale'); ?>',
				    dataType: 'json',
				    data: {salePercentage: salePercentage, saleAmount: saleAmount, sales, sales},
				    success: function(results) {
				    	$this.find("button[type='submit']").prop('disabled',true);
						loading($this,'complete',results.message);
				    },
				    error: function(error) {
				    	loading($this,'error',results.message);
				    }
				});
			});
			
			var sales = new Array();
			function getProducts(page) {
				var _tempProducts;
				$.ajax({
					type: 'post',
				    url: '<?php echo base_url('index.php/back/ra_PRD_Products'); ?>',
				    dataType: 'json',
				    data: {page: page},
				    async: false,
				    success: function(results) {
				    	_tempProducts = results; 
				    }
				});
				return _tempProducts;
			}
			// MODIFICO LA DATA IN UNA TAB
			function changeDate(saleStart, saleEnd, tabSale) {
				for (var i = 0; i < sales.length; i++) {
					if (sales[i].tabSale == tabSale) {
						sales[i].newSaleStart = saleStart;
						sales[i].newSaleEnd = saleEnd;
						break;
					}
				}
			}
			// MODIFICO LO SCONTO IN TUTTE LE TAB
			function changeSale(saleAmount, salePercentage) {
				var _tempAmount = saleAmount != '' ? saleAmount : null;
				var _tempPercentage = salePercentage != '' ? salePercentage : null;
				for (var i = 0; i < sales.length; i++) {
					sales[i].newSaleAmount = _tempAmount;
					sales[i].newSalePercentage = _tempPercentage;
				}
			}
			// INIZIALIZZO UN NUOVO ELEMENTO DELL'ARRAY (NUOVA TAB)
			function initializeArraySalesGroups(tabSale, saleAmount, salePercentage) {
				var _tempAmount = saleAmount != '' ? saleAmount : null;
				var _tempPercentage = salePercentage != '' ? salePercentage : null;
				object = {	
							 tabSale: tabSale,
							 saleStart: null,
							 saleEnd: null,
							 saleAmount: _tempAmount,
							 salePercentage: _tempPercentage,
							 newSaleStart: null,
							 newSaleEnd: null,
							 newSaleAmount: _tempAmount,
							 newSalePercentage: _tempPercentage,
							 idProducts: new Array()
						 }
				sales.push(object);
			}
			// CONTROLLI NUMERO E MINORE DI 100
			function checkNumberLessHundred(source) {
				var pattern = new RegExp('^([0-9]|[1-9][0-9]|100)$'); 
				if(!pattern.test(source.val())) {
					source.parent().removeClass('has-success');
					source.parent().addClass('has-error');
					source.closest('form').find("button[type='submit']").prop('disabled',true);
			    	return false;
			  	} else {
			  		source.parent().removeClass('has-error');
			  		source.parent().addClass('has-success');
			  		source.closest('form').find("button[type='submit']").prop('disabled',false);
			    	return true;
			  	}
			}
			// CONTROLLO MAGGIORE DI ZERO E INT
			function checkNumber(source) {
				var pattern = new RegExp('^[0-9]+([,.][0-9]{1,2})?$'); 
				if(!pattern.test(source.val())) {
					source.parent().removeClass('has-success');
					source.parent().addClass('has-error');
					source.closest('form').find("button[type='submit']").prop('disabled',true);
			    	return false;
			  	} else {
			  		source.parent().removeClass('has-error');
			  		source.parent().addClass('has-success');
			  		source.closest('form').find("button[type='submit']").prop('disabled',false);
			    	return true;
			  	}
			}
			// CONTROLLO QUALE TIPO E' STATO INSERITO E DISABILITO L'ALTRO
			function checkSelectedSaleType(current, other) {
				if (current.val() != '') {
					other.prop('disabled',true);
				} else {
					other.prop('disabled',false);
				}
			}
			// POPOLO LA TABELLA DENTRO UN CONTAINER CON I PRODOTTI
			function popolateTableProducts(container, paginationNumber) {
				container.find('table tbody').empty();
				var object = getProducts(paginationNumber);
				for (var i = 0; i < object.products.length; i++) {
					var productCode;
					var checked = '';
					
					if (object.products[i].productEAN != null) {
						productCode = 'EAN-13';
					}
					if (object.products[i].productSKU != null) {
						productCode = 'SKU';
					}
					if (object.products[i].productEAN == null && object.products[i].productSKU == null) {
						productCode = 'Nessun codice presente';
					}
					// Controllo se è stato già selezionato precedentemente
					for (var k = 0; k < sales.length; k++) {
						if(sales[k].tabSale == container.attr('id')) {
							if( $.inArray(object.products[i].idProduct, sales[k].idProducts) != -1) {
								checked = 'checked';
							}
							break;
						}
					}
					
					container.find('table tbody').append('<tr><td><input type="checkbox" name="idProduct" value="' + object.products[i].idProduct + '" ' + checked + '/></td><td><img src="<?php echo $this->config->item('resources_img') ?>/products/extra_small/' + object.products[i].photoName + '" alt="' + object.products[i].productName + '"></td><td>' + object.products[i].productName + '</td><td>' + object.products[i].manufacturerName + '</td><td>' + productCode + '</td><td>' + object.products[i].categoryName + '</td><td>' + object.products[i].productPrice + ' € </td></tr>');
				}
				container.find('.pagination ul').empty();
				container.find('.pagination ul').append(object.pagination);
			}
		</script>
		<script src="<?php echo $this->config->item('resources_js'); ?>/plugin/bootstrap-datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
	<?php endif; ?>
	<?php if ($page == 'currencies') : ?>
		<!-- CURRENCIES SCRIPT -->
		<script type="text/javascript">
			$(document).on('click','#refresh',function(e){
				e.preventDefault();
				var data = null;
				var url = "<?php echo base_url('index.php/back/ua_STN_Currencies'); ?>";
				ajaxPost(url,$(this),data,false,0,false,true);
			});
			$(document).on('click',function (e) {
			    if ( $(".lineCurrencyDetail").is(e.target) ) {
			    	var id = $(e.target).data('id');    	
					$("#currency-detail-form input[name='idCurrency']").val(id);
					var currency = getCurrency(id);			
					popolateCurrency(currency);		
			    	if( $('#modifyCurrency').css('opacity') == 1) {
			    		$("#currency-detail").show();
			    	    $("#modifyCurrency").animate({
			        		opacity: 0,
			        	  	height: "toggle"
			        	}, 500, function() {});
			        	$("#currency-detail").animate({
			    			opacity: 1,
			    		}, 500, function() {});
			    	}
			    } else if ($("#currency-detail").has(e.target).length <= 0 ) {
					if( $('#modifyCurrency').css('opacity') == 0 ) {
					    $("#modifyCurrency").animate({
							opacity: 1,
						  	height: "toggle"
						}, 500, function() {});
						$("#currency-detail").animate({
							opacity: 0,
						}, 500, function() {
							$("#currency-detail").hide();
						});
					}
			    }
			});
			$(document).on('submit', '#currency-detail-form', function (e) {
				e.preventDefault();
				var data = $(this).serialize();
				var url = "<?php echo base_url('index.php/back/u_STN_Currency'); ?>";
				ajaxPost(url,$(this),data,false,0,false,true);
			});
			function getCurrency(idCurrency) {
				var postData = {
					idCurrency: idCurrency
				};
				var url = "<?php echo base_url('index.php/Back/r_STN_Currency'); ?>";
				return ajaxGet(url,postData);
			}
			function popolateCurrency(currency) {
				console.log(currency);
				currencyState = new Array(0,0);
				if (currency.currencyStatus == 0) {
					currencyState[0] = 'checked="true"';
					currencyState[1] = '';
				} else {
					currencyState[0] = '';
					currencyState[1] = 'checked="true"';
				}
				$("#currency-detail-form .currency-general-informations").empty();
				$("#currency-detail-form .currency-general-informations").append('<div class="col-md-6"><input type="hidden" name="idCurrency" value="' + currency.idCurrency + '" /><label>Nome:</label> ' + currency.currencyName + '<br><label>Codice ISO:</label> ' + currency.currencyCode + '</div><div class="col-md-6"><label>Tasso di cambio:</label> 1 € = ' + currency.currencyValue + ' ' + currency.currencySymbol + '<br><div class="form-group"><label>Stato:</label> <label class="radio-inline"><input name="currencyStatus" value="0" ' + currencyState[0] + 'type="radio">Non attivo</label><label class="radio-inline"><input name="currencyStatus" value="1" ' + currencyState[1] + 'type="radio">Attivo</label></div></div>');
			}
		</script>
	<?php endif; ?>
	<?php if ($page == 'tax') : ?>
		<script type="text/javascript">
			/* CONTROLLO PERCENTUALE */	
			$(document).on('keyup',"input[name='taxValue']",function(){
				if ( checkPercentage( $(this).val() ) == true ) {	
					$(this).parent().removeClass('has-error');
					$(this).parent().addClass('has-success');
					$(this).closest('form').find('button[type="submit"]').prop('disabled',false);
				} else {
					$(this).parent().removeClass('has-success');
					$(this).parent().addClass('has-error');
					$(this).closest('form').find('button[type="submit"]').prop('disabled',true);
				}
			});		
			$(document).on('click','#delete',function(){
				e.preventDefault();
				var data = $("#tax").serialize();
				var url = "<?php echo base_url('index.php/back/d_STN_Tax'); ?>";
				ajaxPost(url,$(this),data,false,1,false,true);
			});		
			$(document).on('click','.btnModifyTax',function() {
				var id = $(this).data('id');
				$("#modifyTax .modal-body").empty();
				$("#modifyTax input[name='idTax']").val(id);
		
				$.ajax({
					type: "POST",
				    dataType: 'json',
				    url: "<?php echo base_url('index.php?/Back/r_STN_Tax/') ?>",
				    data: {idTax: id},
				    async: false,
				      				
				    success: function(result) {
				    	$("#modifyTax .modal-body").append('<div class="form-group"><label>Nome tassazione</label><input type="hidden" class="form-control" name="idTax" value="' + result.idTax + '"><input class="form-control" name="taxName" value="' + result.taxName + '"></div><label>Descrizione *</label><div class="form-group"><textarea class="form-control" name="taxDescription" required="true" rows="4">' + result.taxDescription + '</textarea></div><label>Percentuale *</label><div class="form-group input-group"><input class="form-control" name="taxValue" required="true" type="text" value="' + result.taxValue + '"><span class="input-group-addon">%</span></div>');
				    },
				    error: function(result) {
				    	alert(result);
				    },
				});
			});		
			$(document).on('submit','#modify-tax-form',function(e){
				e.preventDefault();
				var formData = new FormData(this);
				var url = "<?php echo base_url('index.php/back/u_STN_tax'); ?>";
				ajaxPost(url,$(this),formData,true,0,true,true);
			});
			$(document).on('submit','#add-tax-form',function(e){
				e.preventDefault();
				var formData = new FormData(this);
				var url = "<?php echo base_url('index.php/back/c_STN_Tax'); ?>";
				ajaxPost(url,$(this),formData,true,0,true,true);
			});
			function checkPercentage(tempPercentage) {
				var pattern = new RegExp('^([0-9]{1,2})?$');
				if(!pattern.test(tempPercentage))
			  	{
				   	return false;
				} else {
					if(tempPercentage <= 100) {
						return true;
					} else {
						return false;
					}
				}
			}
		</script>
	<?php endif; ?>
</body>
</html>