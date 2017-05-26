<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper">
	<div class="container-fluid">

		<div class="row">
			<div class="col-xs-12">
		   		<h1 class="page-header">
		       		Esportazione <small>Riepilogo</small>
		        </h1>
		        <ol class="breadcrumb">
		            <li><a href="<?php echo site_url('back') ?>"><i class="fa fa-dashboard"></i> Dashboard </a></li>
		            <li class="active">Esportazione</li>
		        </ol>
		    </div>
		</div> 

		<div class="row">
		 	<form method="post" action="<?php echo site_url('back/exportxml'); ?>">
				<div class="col-md-12">
					<div class="box internal-box brd-yellow">
						<div class="box-header">
							<h3 class="box-title">Esportazione XML</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Tabella da esportare</label>
								<select class="form-control" name="tableName">
							        <option value="product">Articoli</option>
							        <option value="text">Testi</option>
							        <option value="photo">Fotografie</option>
							     </select>
							</div>
				        </div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
									<button type="reset" class="btn btn-default">Cancella</button>
									<button type="submit" class="btn btn-yellow">Genera XML</button>
								</div>
							</div>
						</div>
				    </div>
				</div>	
		 	</form>
		</div>
		
        <div class="row">
         	<form method="post" action="<?php echo site_url('back/exportcsv'); ?>">
				<div class="col-md-12">
					<div class="box internal-box brd-yellow">
						<div class="box-header">
							<h3 class="box-title">Esportazione CSV</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label>Tabella da esportare</label>
								<select class="form-control" name="tableName">
							        <option value="product">Articoli</option>
							        <option value="text">Testi</option>
							        <option value="photo">Fotografie</option>
							     </select>
							</div>
				        </div>
						<div class="box-footer">
							<div class="row">
								<div class="col-xs-12 text-right">
									<button type="reset" class="btn btn-default">Cancella</button>
									<button type="submit" class="btn btn-yellow">Genera CSV</button>
								</div>
							</div>
						</div>
				    </div>
				</div>	
         	</form>
		</div>
		
	</div>
</div>
