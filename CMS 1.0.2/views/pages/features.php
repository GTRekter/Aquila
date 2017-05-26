<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
			<div class="col-xs-12">
		   	 <h1 class="page-header">
		            Caratteristiche <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Caratteristiche</li>
		        </ol>
		    </div>
		</div> 
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="ion ion-bookmark sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Caratteristiche</span>
			            	<span class="data"><?php echo count($features); ?></span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="ion ion-ios-pricetags-outline sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">Valori</span>
			            	<span class="data"><?php echo count($values); ?></span>
			             </div>
			        </div>
			    </div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="statistics-box">
			    	<div class="row">
			        	<div class="col-xs-5">
			        		<div class="icon-box bg-blue cl-white">
			            		<i class="ion ion-flash sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">CAMPO LIBERO</span>
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
			            		<i class="ion ion-flash sz-50"></i>
			            	</div>
			            </div>
			            <div class="col-xs-7 text-box">
			            	<span class="title">CAMPO LIBERO</span>
			            	<span class="data">N.D</span>
			             </div>
			        </div>
			    </div>
			</div>
		</div>	  	
         
        <form id="features" method="POST" action="">
	        <div class="row">
				<div class="col-md-12">
					<div class="box internal-box brd-blue">
						<div class="box-header">
							<h3 class="box-title">Lista Caratteristiche</h3>
							<span class="header-action pull-right">
								<i id="delete" class="ion ion-trash-a pointer"></i>
							</span>
							<span class="header-action pull-right">
								<i id="duplicate" class="ion ion-ios-copy"></i>
							</span>
							<span class="header-action pull-right">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="ion ion-plus-circled"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a data-toggle="modal" data-target="#addFeature"> Caratteristica</a></li>
									<li><a data-toggle="modal" data-target="#addValue"> Valore</a></li>
								</ul>
							</span>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover table-striped table-condensed table-feature">
							    	<thead>
							        	<tr>
							        		<th></th>
							            	<th>Caratteristica</th>
							                <th>Valori Associati</th>
							            </tr>
							        </thead>
							        <?php if($features) : ?>
							        	<?php foreach ($features as $feature) : ?>
									    	<tr>
									    		<td>
									    			<input type="checkbox" name="idFeature[]" value="<?php echo $feature->idFeature; ?>"/>
									    		</td>
									    		<td class="lineModifyFeature" data-id="<?php echo $feature->idFeature; ?>" data-toggle="modal" data-target="#modifyFeature" >
									    			<?php echo ucfirst($feature->featureName); ?>
									    		</td>
									        	<td class="lineModifyValue" data-id="<?php echo $feature->idFeature ?>">
									        		<?php 
										        		$featureValue = array();
										        		if($values) {
											        		foreach ($values as $value) {
											        			if ( $value->idFeature == $feature->idFeature ) {	
											        				array_push($featureValue, $value->valueName);
											        			}
											        		} 
											        	}
										        		sort($featureValue);
										        		foreach($featureValue as $value) {
										        			echo $value. ' | ';
										        		}
									        		
									        		?>
									        	</td>
									    	</tr>
							    		<?php endforeach; ?>
							    	</table>
							    <?php else : ?>
							        </tbody>
							    </table>
							    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna caratteristica presente</p></div>
							    <?php endif; ?>
							</div>	
						</div>
					</div>
				</div>	
				
			</div>
		</form>
        <form id="values" method="POST" action="">
            <div class="row">
        		<div class="col-md-12">
        			<div class="box internal-box brd-blue">
        				<div class="box-header">
        					<h3 class="box-title">Lista Valori</h3>
        					<span class="header-action pull-right">
        						<i id="delete-values" class="ion ion-trash-a pointer"></i>
        					</span>
        					<span class="header-action pull-right">
        						<i id="duplicate" class="ion ion-ios-copy"></i>
        					</span>
        					<span class="header-action pull-right">
        						<i id="add" class="ion ion-plus-circled"></i>
        					</span>
        				</div>
        				<div class="box-body">
        					<div class="table-responsive">
        						<table class="table table-hover table-striped table-condensed table-values">
        					    	<thead>
        					        	<tr>
        					        		<th></th>
        					            	<th>Valori</th>
        					            </tr>
        					        </thead>
        					       	<tbody></tbody>
        					    </table>
        					</div>	
        				</div>
        			</div>
        		</div>	
        		
        	</div>
        </form>
        
		<!-- Modal -->
		<div id="addFeature" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Crea nuova caratteristica</h4>
		      		</div>
		      		<form id="add-feature-form">
			      		<div class="modal-body">
			      			<p>Inserisci il nome della caratteristica in lingua italiana e clicca sul pulsante di salvataggio per crearlo. Al momento della creazione il nome verrà automaticamente tradotto nelle lingue indicate nelle <a href="<?php echo site_url('back/settings') ?>">impostazioni</a>.</p>
							<div class="form-group">
						    	<label>Nome Caratteristica</label>
						    	<input type="hidden" name="isFeature" value="1" />
						        <input class="form-control" name="featureName" required="true">
						        <input type="hidden" name="type" value="<?php echo $page; ?>">
						    </div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn">Inserisci</button>
			        		<button type="button" class="btn" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>
		<div id="addValue" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Crea nuovo valore</h4>
		      		</div>
		      		<form id="add-value-form">
			      		<div class="modal-body">	
			      			<p>Inserisci il nome del valore in lingua italiana, il relativo attributo di riferimento e clicca sul pulsante di salvataggio per crearlo. Al momento della creazione il nome verrà automaticamente tradotto nelle lingue indicate nelle <a href="<?php echo site_url('back/settings') ?>">impostazioni</a>.</p>
			      			<div class="form-group">
				      			<label>Caratteristica Associata</label>
				      			<select class="form-control" <?php if(!$features){echo 'disabled';} ?> name="idFeature">
				      				<?php 
				      					for ($i = 0; $i < count($features); $i++) {
				      						echo '<option value="'.$features[$i]->idFeature.'">'.ucfirst($features[$i]->featureName).'</option>';
				      					} 
				      				?>
				      			</select>
			      			</div>
							<div class="form-group">
								<label>Nome Valore</label>
						        <input class="form-control" name="valueName" required="true">
						        <input type="hidden" name="type" value="<?php echo $page; ?>">
						    </div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn" <?php if(!$features){echo 'disabled';} ?>>Inserisci</button>
			        		<button type="button" class="btn" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>
		<div id="modifyFeature" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Modifica caratteristica</h4>
		      		</div>
		      		<form id="modify-feature-form">
			      		<div class="modal-body">
			      			<p>Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing™ al momento della creazione della caratteristica. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
			      			<input type="hidden" name="idFeature" value="" />
			      			<ul id="tablist-feature" class="nav nav-tabs nav-blue" role="tablist"></ul>
			      			<div id="tabcontent-feature" class="tab-content"></div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn ">Salva</button>
			        		<button type="button" class="btn  btn-close" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>
		<div id="modifyValue" class="modal fade modal-blue" role="dialog">
			<div class="modal-dialog">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close btn-close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Modifica Valore</h4>
		      		</div>
		      		<form id="modify-value-form">
			      		<div class="modal-body">
			      			<p>Seleziona la lingua desiderata e modifica la traduzione automatica effettuata tramite API Bing™ al momento della creazione del valore. Per completare la modifica, cliccare sul pulsante di salvataggio alla fine di ogni pannello.</p>
			      			<input type="hidden" name="idValue" value="" />
			      			<ul id="tablist" class="nav nav-tabs nav-blue" role="tablist"></ul>
			      			<div id="tabcontent" class="tab-content"></div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="submit" class="btn ">Salva</button>
			        		<button type="button" class="btn  btn-close" data-dismiss="modal">Chiudi</button>
			      		</div>
		      		</form>
		    	</div>
			</div>
		</div>		
	</div>
</div>
