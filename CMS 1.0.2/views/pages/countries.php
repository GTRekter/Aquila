<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

         <div class="row">
         	<div class="col-xs-12">
            	<h1 class="page-header">
                	Nazioni <small>Riepilogo</small>
                </h1>
                <ol class="breadcrumb">
                     <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
                     <li class="active">Nazioni</li>
                </ol>
             </div>
        </div>    	
         
        <div class="row">
			<div class="col-md-12">
				<div class="box internal-box brd-yellow">
					<div class="box-header">
						<h3 class="box-title">Lista Categorie</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover">
						    	<thead>
						        	<tr>
						            	<th>Codice Nazione</th>
						                <th>Nome Nazione</th>
						                <th>Prefisso Telefonico</th>
						                <th>Formato Zip</th>
						            </tr>
						        </thead>
						        <tbody>
						        <?php if($countries) : ?>
						        	<?php foreach ($countries as $country) : ?>
								    	<tr>
								    		<td><?php echo $country->countryCode; ?></td>
								    		<td><?php echo ucfirst($country->countryName); ?></td>
								    		<td>+<?php echo $country->callPrefix; ?></td>
								    		<td><?php echo $country->zipCodeFormat; ?></td>
								    	</tr>
						    		<?php endforeach; ?>
						    		<tbody>
						    	</table>
						    <?php else : ?>
						        </tbody>
						    </table>
						    <div class="col-xs-12 p-t-10 p-b-10"><p class="text-center text-muted">Nessuna nazione presente</p></div>
						    <?php endif; ?>
						</div>	
					</div>
				</div>
			</div>	
		</div>
		
	</div>
</div>
