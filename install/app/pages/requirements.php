<?php 
$ReqCheck = $Requirement->check([ 
    '../application/config/database.php',
    '../application/config/config.php',
    './app/templates/codeigniter_2x_&_3x.php',
]);
 $version_error  = $ReqCheck['version_error'];
 $writable_error  = $ReqCheck['writable_error'];
 $extension_error  = $ReqCheck['extension_error'];
$errors_requirement = false;
?> 
<div class="content">
    <div class="row">
	    <div class="col-sm-12">
		    <!-- BEGIN DIRECTORY & FILE PERMISSION -->
		    <?php if ($ReqCheck['version_error'] || $ReqCheck['writable_error'] || $ReqCheck['extension_error']) { ?>
		    <div class="alert alert-danger" role="alert">
				<?php
				if ($writable_error) {
					echo $ReqCheck['writable_error_msg'] .'<br>';
				}
				if ($extension_error) {
					echo $ReqCheck['extension_error_msg'] .'<br>';
				}
				if ($version_error) {
					echo $ReqCheck['version_error_msg'] .'<br>';
				}
				?>
			</div>
			<?php } ?>
			<table>
				<thead>
					<tr><td>Directory/File Permission</td><td width="100">Writable</td></tr>
				</thead>
				<tbody>
					<?php foreach ($ReqCheck['permissions'] as $key => $value) { ?>
					<tr><td class="text-bold"><?php echo $key; ?></td><td style="text-align: center;"><?php echo $value ?></td></tr>
					<?php } 
					if(!is_writable('../assets/files')) { ?>
					<tr><td><?php echo '/assets/files'; ?></td><td style="text-align: center;"><i class="fa fa-times red"></i></td></tr>
					<?php 
					$writable_error = true;
					} else { ?>
						<tr><td class="text-bold"><?php echo '/assets/files'; ?></td><td style="text-align: center;"><i class="fa fa-check green"></i></td></tr>
					<?php }
					if(!is_writable('../uploads')) { ?>
					<tr><td><?php echo '/uploads'; ?></td><td style="text-align: center;"><i class="fa fa-times red"></i></td></tr>
					<?php $writable_error = true;
					} else { ?>
						<tr><td class="text-bold"><?php echo '/uploads'; ?></td><td style="text-align: center;"><i class="fa fa-check green"></i></td></tr>
					<?php } 
					if(!is_writable('../uploads/files')) { ?>
					<tr><td><?php echo '/uploads/files'; ?></td><td style="text-align: center;"><i class="fa fa-times red"></i></td></tr>
					<?php $writable_error = true;
					} else { ?>
						<tr><td class="text-bold"><?php echo '/uploads/files'; ?></td><td style="text-align: center;"><i class="fa fa-check green"></i></td></tr>
					<?php } if(!is_writable('../tmp')) { ?>
					<tr><td><?php echo '/tmp'; ?></td><td style="text-align: center;"><i class="fa fa-times red"></i></td></tr>
					<?php $writable_error = true;
					} else { ?>
						<tr><td class="text-bold"><?php echo '/tmp'; ?></td><td style="text-align: center;"><i class="fa fa-check green"></i></td></tr>
					<?php } ?>
				</tbody> 
			</table>
			<!-- ENDS DIRECTORY & FILE PERMISSION -->

			<!-- BEGIN CHECK EXTENSION -->
			<table>
				<thead>
					<tr><td>Required Extensions</td><td width="100">Status</td></tr>
				</thead>
				<tbody>
					<?php foreach ($ReqCheck['extensions'] as $key => $value) { ?>
					<tr><td class="text-bold"><?php echo $key; ?></td><td style="text-align: center;"><?php echo $value ?></td></tr>
					<?php }
					if(!ini_get('allow_url_fopen')) { ?>
					<tr><td><?php echo 'allow_url_fopen'; ?></td><td style="text-align: center;"><i class="fa fa-times red"></i></td></tr>
					<?php $extension_error = true; 
					} else { ?>
						<tr><td class="text-bold"><?php echo 'allow_url_fopen'; ?></td><td style="text-align: center;"><i class="fa fa-check green"></i></td></tr>
					<?php } ?> 
				</tbody> 
			</table>
			<!-- ENDS EXTENSION -->

			<!-- BEGIN REQUIRE VERSION  -->
			<table>
				<thead>
					<tr><td>Version</td><td>Status</td></tr>
				</thead>
				<tbody>
					<?php foreach ($ReqCheck['versions'] as $key => $value) { ?>
					<tr><td class="text-bold"><?php echo $key; ?></td><td><?php echo $value ?></td></tr>
					<?php } ?> 
				</tbody> 
			</table>
			<!-- ENDS REQUIRE VERSION -->
			 
			<div class="divider"></div>
			<?php if ($ReqCheck['version_error'] || $ReqCheck['writable_error'] || $ReqCheck['extension_error']) { ?>
				<a href="#" class="btn btn-default pull-right disabled" disabled="disabled">Next <i class="fa fa-forward"></i></a>
			<?php } else { ?>
				<a href="?step=installation" class="btn btn-default pull-right text-success text-bold">Next <i class="fa fa-forward"></i></a>
			<?php }  ?>
		</div>
	</div>
</div>
