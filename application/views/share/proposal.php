<?php $rebrand = load_config(); ?>
<?php $appconfig = get_appconfig(); ?>
<!DOCTYPE html>
<html ng-app="Ciuis" lang="<?php echo lang('lang_code'); ?>">
<head>
	<script src="<?php echo base_url('assets/lib/angular/angular.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/lib/angular/angular-resource.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/lib/angular/angular-route.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/lib/angular/angular-loader.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/lib/angular/angular-sanitize.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/lib/angular/angular-cookies.min.js'); ?>"></script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
	<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
	<title><?php echo $proposals['subject'] ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
    <style>
		body {
			padding: 0;
			background-color: rgb(255, 255, 255);
			height: 100%;
			font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-weight: 300;
			-webkit-font-smoothing: antialiased;
		}
		.p-s-v{
			padding: 10px;
			width: 100px;
			margin-top: 20px;
		}
		tr, td, th, small, span, strong {
			font-family: DejaVu Sans; sans-serif;
		}
	</style>
</head>
<?php $settings = $this->Settings_Model->get_settings_ciuis(); ?>
<body ng-controller="Ciuis_Controller">
	<?php include_once(APPPATH . 'views/inc/widgets/proposal_view_sidebar.php'); ?>
	
	<div class="main-content container-fluid col-md-9 borderten">
		<div class="col-md-12 md-pr-0">
			<div class="proposal panel borderten" style="padding-top: 20px;padding-right: 0px;">
				<main>
					<div id="details" class="clearfix">
						<div id="client">
							<h1><b><?php echo $proposals['subject'] ?></b></h1>
							<div class="date text-bold"><?php echo lang('dateofissuance')?>:
								<?php echo date(get_dateFormat(), strtotime($proposals['date']))?>
							</div>
							<div class="date text-bold"><?php echo lang('opentill')?>:
								<?php echo date(get_dateFormat(), strtotime($proposals['opentill']))?>
							</div>
						</div>
						<div class="pull-right"><?php if($proposals['status_id'] == 1){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('draft').'</span>';}  ?><?php if($proposals['status_id'] == 2){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('sent').'</span>';}  ?><?php if($proposals['status_id'] == 3){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('open').'</span>';}  ?><?php if($proposals['status_id'] == 4){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('revised').'</span>';}  ?><?php if($proposals['status_id'] == 5){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('declined').'</span>';}  ?><?php if($proposals['status_id'] == 6){echo '<span class="label label-default p-s-lab p-s-v pull-left">'.lang('accepted').'</span>';}  ?></div>
					</div>
					<div class="col-md-12"><?php echo $proposals['content'] ?></div>
					<hr>
					<table border="0" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th class="desc"><?php echo lang('description') ?></th>
								<th class="qty text-right"><?php echo lang('quantity') ?></th>
								<th class="unit text-right"><?php echo lang('price') ?></th>
								<th class="discount text-right"><?php echo lang('discount') ?></th>
								<th class="tax text-right"><?php echo $appconfig['tax_label'] ?></th>
								<th class="total text-right"><?php echo lang('total') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($items as $item) {?>
							<tr>
								<td class="desc"><h3><?php echo $item['name']?><br></h3><?php echo nl2br($item['description']);?></td>
								<td class="qty"><?php echo '' . amount_format($item[ 'quantity' ]). '' ?></td>
								<td class="unit"><span class="money-area"><?php echo '' . amount_format($item[ 'price' ]) . ''; ?></span></td>
								<td class="discount"><?php echo '' . amount_format($item[ 'discount' ]) . '%';?></td>
								<td class="tax"><?php echo '' . amount_format($item[ 'tax' ]) . '%';?></td>
								<td class="total"><span class="money-area"><?php echo '' . amount_format($item[ 'total' ]) . '' ;?></span></td>
							</tr>
							<?php }?>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td colspan="2"></td>
								<td colspan="2"><?php echo lang('subtotal')?></td>
								<td><span class="money-area"><?php echo '' . amount_format($proposals[ 'sub_total' ], true). '' ?></span></td>
							</tr>
							<tr>
								<td></td>
								<td colspan="2"></td>
								<td class="text-uppercase" colspan="2"><?php echo lang('discount') ?></td>
								<td><span class="money-area"><?php echo '' . amount_format($proposals[ 'total_discount' ], true). '' ?></span></td>
							</tr>
							<tr>
								<td></td>
								<td colspan="2"></td>
								<td colspan="2"><?php echo $appconfig['tax_label'] ?></td>
								<td><span class="money-area"><?php echo '' . amount_format($proposals[ 'total_tax' ], true). '' ?></span></td>
							</tr>
							<tr>
								<td></td>
								<td colspan="2"></td>
								<td colspan="2"><?php echo lang('grandtotal') ?></td>
								<td><span class="money-area"><?php echo '' . amount_format($proposals[ 'total' ], true). '' ?></span></td>
							</tr>
						</tfoot>
					</table>
				</main>
			</div>
		</div>
	</div>
	<?php include_once(APPPATH . 'views/inc/footer.php'); ?>
<script>
$( ".accept-proposal" ).click( function () {
	var base_url = "<?php echo base_url();?>"
	var proposal = $( this ).data( 'proposal' );
	var statusna = "<?php echo lang('accepted') ?>";
	$.ajax( {
		type: "POST",
		url: base_url + "share/markasproposal",
		data: {
			status_id: 6,
			proposal_id: proposal,
		},
		dataType: "text",
		cache: false,
		success: function ( data ) {
			$.gritter.add( {
				title: '<b><?php echo lang('notification')?></b>',
				text: '<b><?php echo lang('markedas')?> '+statusna+'</b>',
				class_name: 'color success'
			} );
			$( ".p-s-lab" ).text(statusna);
			window.location.reload();
		}
	} );
	return false;
});
$( ".decline-proposal" ).click( function () {
var base_url = "<?php echo base_url();?>"
var proposal = $( this ).data( 'proposal' );
var statusna = "<?php echo lang('declined') ?>";
$.ajax( {
	type: "POST",
	url: base_url + "share/markasproposal",
	data: {
		status_id: 5,
		proposal_id: proposal,
	},
	dataType: "text",
	cache: false,
	success: function ( data ) {
		$.gritter.add( {
			title: '<b><?php echo lang('notification')?></b>',
			text: '<b><?php echo lang('markedas')?> '+statusna+'</b>',
			class_name: 'color danger'
		} );
		$( ".p-s-lab" ).text(statusna);
	}
} );
return false;
});
</script>