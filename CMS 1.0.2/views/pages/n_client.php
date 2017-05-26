<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">
        <div class="row">
        	<div class="col-xs-12">
           		<h1 class="page-header">
           			<?php $x =0; if( $this->uri->segment(3) == "private" ) : ?>
               		Privato <small>Creazione</small>
               		<?php else : ?>
               		Azienda <small>Creazione</small>
               		<?php endif; ?>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                    <li><a href="<?php echo site_url('back/clients') ?>">Prodotti </a></li>
                    <?php $x =0; if( $this->uri->segment(3) == "private" ) : ?>
                    <li class="active">Creazione Privato</li>
                    <?php else : ?>
                    <li class="active">Creazione Azienda</li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
        
        <?php $x =0; if( $this->uri->segment(3) == "private" ) : ?>
	        <form id="addClient" method="post" action="">
				<div class="row">
					<div class="col-xs-12">
						<div class="box internal-box client-box brd-blue">
							<div class="box-header">
						  		<h3 class="box-title">Informazioni Generali</h3>
							</div>
							<?php if($countries) : ?>
							<div class="box-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Nome *</label>
											<input type="text" class="form-control" name="clientName" required="true">
										</div>
										<div class="form-group">
											<label>Cognome *</label>
											<input type="text"  class="form-control" name="clientSurname" required="true" >
										</div>
										<label>Email</label>
										<div class="form-group input-group">
											<span class="input-group-addon">@</span>
											<input type="email" class="form-control" name="clientEmail" required="true">
										</div>   
										<div class="form-group">
											<label>Password</label>
											<input type="password" class="form-control" name="clientPassword"/>
										</div>
										<label>Numero di telefono</label>
										<div class="form-group">
											<input type="text" class="form-control" name="clientPhone">
										</div>
										<div class="form-group">
											<label>Codice Fiscale</label>
											<input type="text" class="form-control" name="clientFiscalCode" disabled="true">
										</div>
									</div>
									<div class="col-md-6">	    
										<div class="form-group">
											<label>Nazione</label>
											<select class="form-control" name="idCountry">
												<?php foreach ($countries as $country) : ?>
													<option value="<?php echo $country->idCountry; ?>">
														<?php echo ucwords($country->countryName); ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="form-group">
											<label>Provincia</label>
											<input type="text" class="form-control" name="clientState">
										</div>
										<div class="form-group">
											<label>Città</label>
											<input type="text" class="form-control" name="clientCity">
										</div>
										<div class="form-group">
											<label>Indirizzo</label>
											<input type="text" class="form-control" name="clientAddress">
										</div>  
										<div class="form-group">
											<label>Numero Civico</label>
											<input type="text" class="form-control" name="clientHouseNumber">
										</div>
										<div class="form-group">
											<label>Codice Postale</label>
											<input type="text" class="form-control" name="clientPostalCode">
										</div>
									</div>
								</div>
						    </div>
						    <div class="box-footer">
						    	<div class="row">
						    		<div class="col-xs-12">
						    			<div class="pull-right">
						    				<button type="reset" class="btn btn-default">Cancella</button>
						    				<button type="submit" class="btn btn-blue">Inserisci cliente</button>
						    			</div>
						    		</div>
						    	</div>
						    </div>
						    <?php else : ?>
						    <div class="box-body">
						    	<div class="row">
						    		<div class="col-xs-12">
						    			<p class="text-warning"><strong>ATTENZIONE:</strong> Per procedere con la creazione del cliente è necessario aver creato almeno una nazione.</p>
						    			<p class="text-warning">Creane almeno uno e riprova ad eseguire l'operazione.</p>
						    		</div>
						    	</div>
						    </div>
						    <?php endif; ?>
						</div>
					</div>
				</div>
			</form>
        <?php else : ?>
        <form id="addClient" method="post" action="">
        	<div class="row">
        		<div class="col-xs-12">
        			<div class="box internal-box client-box brd-blue">
        				<div class="box-header">
        			  		<h3 class="box-title">Informazioni Generali</h3>
        				</div>
        				<?php if($countries) : ?>
        				<div class="box-body">
        					<div class="row">
        						<div class="col-md-6">
        							<div class="form-group">
        								<label>Ragione Sociale *</label>
        								<input type="text" class="form-control" name="clientName" required="true">
        							</div>
        							<div class="form-group">
        								<label>Partita Iva *</label>
        								<input type="text"  class="form-control" name="clientSurname" required="true" >
        							</div>
        							<label>Capitale Sociale</label>
        							<div class="form-group input-group">
        								<span class="input-group-addon">€</span>
        								<input type="email" class="form-control" name="clientEmail" required="true">
        							</div> 
        							<label>Registro delle imprese</label>
        							<div class="form-group">
        								<input type="text" class="form-control" name="clientPhone">
        							</div> 
        							<label>Email</label>
        							<div class="form-group input-group">
        								<span class="input-group-addon">@</span>
        								<input type="email" class="form-control" name="clientEmail" required="true">
        							</div>  
        							<label>Numero di telefono</label>
        							<div class="form-group">
        								<input type="text" class="form-control" name="clientPhone">
        							</div> 
        						</div>
        						<div class="col-md-6">	    
        							<div class="form-group">
        								<label>Nazione</label>
        								<select class="form-control" name="idCountry">
        									<?php foreach ($countries as $country) : ?>
        										<option value="<?php echo $country->idCountry; ?>">
        											<?php echo ucwords($country->countryName); ?>
        										</option>
        									<?php endforeach; ?>
        								</select>
        							</div>
        							<div class="form-group">
        								<label>Provincia</label>
        								<input type="text" class="form-control" name="clientState">
        							</div>
        							<div class="form-group">
        								<label>Città</label>
        								<input type="text" class="form-control" name="clientCity">
        							</div>
        							<div class="form-group">
        								<label>Indirizzo</label>
        								<input type="text" class="form-control" name="clientAddress">
        							</div>  
        							<div class="form-group">
        								<label>Numero Civico</label>
        								<input type="text" class="form-control" name="clientHouseNumber">
        							</div>
        							<div class="form-group">
        								<label>Codice Postale</label>
        								<input type="text" class="form-control" name="clientPostalCode">
        							</div>
        						</div>
        					</div>
        			    </div>
        			    <div class="box-footer">
        			    	<div class="row">
        			    		<div class="col-xs-12">
        			    			<div class="pull-right">
        			    				<button type="reset" class="btn btn-default">Cancella</button>
        			    				<button type="submit" class="btn btn-blue">Inserisci azienda</button>
        			    			</div>
        			    		</div>
        			    	</div>
        			    </div>
        			    <?php else : ?>
        			    <div class="box-body">
        			    	<div class="row">
        			    		<div class="col-xs-12">
        			    			<p class="text-warning"><strong>ATTENZIONE:</strong> Per procedere con la creazione del cliente è necessario aver creato almeno una nazione.</p>
        			    			<p class="text-warning">Creane almeno uno e riprova ad eseguire l'operazione.</p>
        			    		</div>
        			    	</div>
        			    </div>
        			    <?php endif; ?>
        			</div>
        		</div>
        	</div>
        </form>
        <?php endif; ?>
	</div>
</div>	
		
