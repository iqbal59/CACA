<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Purchase_Controller">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<md-toolbar class="toolbar-white">
			<div class="md-toolbar-tools">
				<md-button class="md-icon-button" aria-label="Purchase" ng-disabled="true">
					<md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
				</md-button>
				<h2 flex md-truncate><?php echo lang('updated').' '.lang('purchase') ?></h2>
				<md-switch ng-model="purchase.recurring_status" aria-label="recurring_status" ng-cloak>
					<strong class="text-muted"><?php echo lang('recurring') ?></strong>
				</md-switch>
				<md-button ng-href="<?php echo base_url('purchases/purchase/{{purchase.id}}')?>" class="md-icon-button" aria-label="View" ng-cloak>
					<md-tooltip md-direction="bottom"><?php echo lang('view') ?></md-tooltip>
					<md-icon><i class="ion-eye text-muted"></i></md-icon>
				</md-button>
				<md-button ng-href="<?php echo base_url('purchases/purchase/{{purchase.id}}')?>" class="md-icon-button" aria-label="Cancel" ng-cloak>
					<md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
					<md-icon><i class="ion-close-circled text-muted"></i></md-icon>
				</md-button>
				<?php if (check_privilege('purchases', 'edit')) { ?>
					<md-button ng-click="saveAll()" class="md-icon-button" aria-label="Save" ng-cloak>
						<md-tooltip md-direction="bottom"><?php echo lang('save') ?></md-tooltip>
						<md-icon><i class="ion-checkmark-circled text-muted"></i></md-icon>
					</md-button>
				<?php }?>
			</div>
		</md-toolbar>
		<div ng-show="purchaseLoader" layout-align="center center" class="text-center" id="circular_loader">
			<md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
			<p style="font-size: 15px;margin-bottom: 5%;">
				<span>
					<?php echo lang('please_wait') ?> <br>
					<small><strong><?php echo lang('loading'). ' '. lang('purchase').'...' ?></strong></small>
				</span>
			</p>
		</div>
		<md-content ng-show="!purchaseLoader" class="bg-white" layout-padding ng-cloak>
			<div layout-gt-xs="row">
				<md-input-container class="md-block" flex-gt-sm>
					<label><?php echo lang('serie')?></label>
					<input ng-model="purchase.serie" name="serie">
				</md-input-container>
				<md-input-container class="md-block" flex-gt-sm>
					<label><?php echo lang('purchase_number')?></label>
					<input ng-model="purchase.no" name="no">
				</md-input-container>
				<md-input-container class="md-block" flex-gt-xs>
					<label><?php echo lang('vendor'); ?></label>
					<md-select required placeholder="<?php echo lang('choisevendor'); ?>" ng-model="purchase.vendor" name="vendor" style="min-width: 200px;">
						<md-option ng-value="vendor.id" ng-repeat="vendor in all_vendors">{{vendor.name}}</md-option>
					</md-select>
					<div ng-messages="userForm.vendor" role="alert" multiple>
						<div ng-message="required" class="my-message"><?php echo lang('you_must_supply_a_customer') ?></div>
					</div>
				</md-input-container>
				<md-input-container>
					<label><?php echo lang('dateofissuance') ?></label>
					<md-datepicker name="created" ng-model="purchase.created_edit" md-open-on-focus></md-datepicker>
				</md-input-container>
			</div>
			<div ng-show="purchase.recurring_status" layout-gt-xs="row">
				<input name="recurring_id" ng-model="purchase.recurring_id" type="hidden">
				<md-input-container class="md-block" flex-gt-xs>
					<label><?php echo lang('recurring_period') ?></label>
					<input ng-model="purchase.recurring_period" name="recurring_period">
				</md-input-container>
				<md-input-container class="md-block" flex-gt-xs>
					<label><?php echo lang('recurring_type') ?></label>
					<md-select ng-model="purchase.recurring_type" name="recurring_type">
						<md-option value="0"><?php echo lang('days') ?></md-option>
						<md-option value="1" selected><?php echo lang('weeks') ?></md-option>
						<md-option value="2"><?php echo lang('months') ?></md-option>
						<md-option value="3"><?php echo lang('years') ?></md-option>
					</md-select>
				</md-input-container>
				<md-input-container>
					<label><?php echo lang('ends_on') ?></label>
					<md-datepicker name="recurring_endDate" ng-model="purchase.recurring_endDate" md-open-on-focus></md-datepicker>
					<div >
						<div ng-message="required" class="my-message"><?php echo lang('leave_blank_for_lifetime') ?></div>
					</div>
				</md-input-container>
			</div>
			<div ng-show="purchase.status_id != '2'" layout-gt-xs="row">
				<md-input-container class="md-block" flex-gt-sm>
					<label><?php echo lang('duenote') ?></label>
					<input ng-model="purchase.duenote" name="duenote">
				</md-input-container>
				<md-input-container>
					<label><?php echo lang('duedate') ?></label>
					<md-datepicker name="duedate" ng-model="purchase.duedate_edit" md-open-on-focus></md-datepicker>
				</md-input-container>
			</div>
		</md-content>
		<md-content  ng-show="!purchaseLoader"  class="bg-white" layout-padding ng-cloak>
			<md-list-item ng-repeat="item in purchase.items">
				<div layout-gt-sm="row">
					<md-autocomplete
					md-autofocus
					md-items="product in GetProduct(item.name)"
					md-search-text="item.name"
					md-item-text="product.name"   
					md-selected-item="selectedProduct"
					md-no-cache="true"
					md-min-length="0"
					md-floating-label="<?php echo lang('productservice'); ?>"
					placeholder="What is your favorite US state?">
					<md-item-template>
						<span md-highlight-text="item.name">{{product.name}}</span> 
						<strong ng-bind-html="product.price | currencyFormat:cur_code:null:true:cur_lct"></strong>
					</md-item-template>
				</md-autocomplete>
				<md-input-container class="md-block">
					<label><?php echo lang('description'); ?></label>
					<input class="min_input_width" type="hidden" ng-model="item.name">
					<bind-expression ng-init="selectedProduct.name = item.name" expression="selectedProduct.name" ng-model="item.name" />
					<textarea class="min_input_width" ng-model="item.description"></textarea>
					<bind-expression ng-init="selectedProduct.description = item.description" expression="selectedProduct.description" ng-model="item.description" />
					<input class="min_input_width" type="hidden" ng-model="item.product_id">
					<bind-expression ng-init="selectedProduct.product_id = item.product_id" expression="selectedProduct.product_id" ng-model="item.product_id" />
					<input class="min_input_width" type="hidden" ng-model="item.code" ng-value="selectedProduct.code">
					<bind-expression ng-init="selectedProduct.code = item.code" expression="selectedProduct.code" ng-model="item.code" />
				</md-input-container>
				<md-input-container class="md-block" flex-gt-sm style="min-width: 40px;">
					<label><?php echo lang('quantity'); ?></label>
					<input class="min_input_width" ng-model="item.quantity" >
				</md-input-container>
				<md-input-container class="md-block" flex-gt-xs style="min-width: 40px;">
					<label><?php echo lang('unit'); ?></label>
					<input class="min_input_width" ng-model="item.unit" >
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('price'); ?></label>
					<input class="min_input_width" ng-model="item.price">
					<bind-expression ng-init="selectedProduct.price = item.price" expression="selectedProduct.price" ng-model="item.price" />
				</md-input-container>
				<md-input-container class="md-block" flex-gt-sm style="min-width: 50px;">
					<label><?php echo $appconfig['tax_label']; ?></label>
					<input class="min_input_width" ng-model="item.tax">
					<bind-expression ng-init="selectedProduct.tax = item.tax" expression="selectedProduct.tax" ng-model="item.tax" />
				</md-input-container>
				<md-input-container class="md-block" flex-gt-xs>
					<label><?php echo lang('discount'); ?></label>
					<input class="min_input_width" ng-model="item.discount">
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('total'); ?></label>
					<input class="min_input_width" readonly="" ng-value="item.quantity * item.price + ((item.tax)/100*item.quantity * item.price) - ((item.discount)/100*item.quantity * item.price)">
				</md-input-container>
			</div>
			<md-icon aria-label="Remove Line" ng-click="remove($index)" class="md-secondary ion-trash-b text-muted"></md-icon>
		</md-list-item>
		<md-content class="bg-white" layout-padding>
			<div class="col-md-6">
				<?php if (check_privilege('purchases', 'edit')) { ?>
					<md-button ng-click="add()" class="md-fab pull-left" ng-disabled="false" aria-label="Add Line">
						<md-icon class="ion-plus-round text-muted"></md-icon>
					</md-button>
				<?php }?>
			</div>
			<div class="col-md-6 md-pr-0" style="font-weight: 900; font-size: 16px; color: #c7c7c7;">
				<div class="col-md-7">
					<div class="text-right text-uppercase text-muted">Sub Total:</div>
					<div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted">Total Discount:</div>
					<div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted">Total Tax:</div>
					<div class="text-right text-uppercase text-black">Grand Total:</div>
				</div>
				<div class="col-md-5">
					<div class="text-right" ng-bind-html="subtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
					<div ng-show="linediscount() > 0" class="text-right" ng-bind-html="linediscount() | currencyFormat:cur_code:null:true:cur_lct"></div>
					<div ng-show="totaltax() > 0"class="text-right" ng-bind-html="totaltax() | currencyFormat:cur_code:null:true:cur_lct"></div>
					<div class="text-right" ng-bind-html="grandtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
				</div>
			</div>
		</md-content>
	</md-content>
</div>
<div class="main-content container-fluid col-md-3 md-pl-0">
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<h2 flex md-truncate class="pull-left" ng-show="purchase.balance != 0"><strong><?php echo lang('balance')?> : <span ng-bind-html="purchase.balance | currencyFormat:cur_code:null:true:cur_lct"></span></strong></h2>
			<h2 flex md-truncate class="pull-left text-success" ng-hide="purchase.balance != 0" ng-cloak><strong><?php echo lang('paidinv') ?></strong></h2>
			<md-button ng-hide="purchase.partial_is != 'true'" class="md-icon-button" aria-label="Partial" ng-cloak>
				<md-tooltip md-direction="bottom"><?php echo lang('partial') ?></md-tooltip>
				<md-icon><i class="ion-pie-graph text-muted"></i></md-icon>
			</md-button>
			<md-button ng-hide="purchase.balance != 0" class="md-icon-button" aria-label="Paid" ng-cloak>
				<md-tooltip md-direction="bottom"><?php echo lang('paid') ?></md-tooltip>
				<md-icon><i class="ion-checkmark-circled text-success"></i></md-icon>
			</md-button>
		</div>
	</md-toolbar>
	<md-content class="bg-white" style="border-bottom:1px solid #e0e0e0;" ng-cloak>
		<md-list flex>
			<md-list-item>
				<md-icon class="ion-ios-bell"></md-icon>
				<p ng-bind="purchase.duedate_text"></p>
			</md-list-item>
			<md-divider></md-divider>
			<md-list-item>
				<md-icon class="ion-android-mail"></md-icon>
				<p ng-bind="purchase.mail_status"></p>
			</md-list-item>
			<md-divider></md-divider>
			<md-list-item>
				<md-icon class="ion-person"></md-icon>
				<p><a href="<?php echo $purchases['staff_id'];?>"><b><?php echo $purchases['staffmembername'];?></b></a></p>
			</md-list-item>
		</md-list>
	</md-content>
	<md-toolbar class="toolbar-white">
		<div class="md-toolbar-tools">
			<h2 flex md-truncate class="text-bold"><?php echo lang('payments'); ?><br><small flex md-truncate><?php echo lang('paymentssidepurchase'); ?></small></h2>
			<md-button ng-show="purchase.balance != 0" ng-click="RecordPayment()" class="md-icon-button" aria-label="Record Payment" ng-cloak>
				<md-tooltip md-direction="left"><?php echo lang('recordpayment') ?></md-tooltip>
				<md-icon><i class="ion-plus-round text-muted"></i></md-icon>
			</md-button>
		</div>
	</md-toolbar>
	<md-content class="bg-white" ng-cloak>
		<md-content ng-show="!purchase.payments.length" class="md-padding no-item-payment bg-white"></md-content>
		<md-list flex>
			<md-list-item class="md-2-line" ng-repeat="payment in purchase.payments">
				<md-icon class="ion-arrow-down-a text-muted"></md-icon>
				<div class="md-list-item-text">
					<h3 ng-bind="payment.name"></h3>
					<p ng-bind-html="payment.amount | currencyFormat:cur_code:null:true:cur_lct"></p>
				</div>
				<md-button class="md-secondary md-primary md-fab md-mini md-icon-button" ng-click="doSecondaryAction($event)" ng-href="<?php echo base_url('expenses/receipt/{{payment.expense_id}}');?>" aria-label="call">
					<md-icon class="ion-ios-search-strong"></md-icon>
				</md-button>
				<md-divider></md-divider>
			</md-list-item>
		</md-list>
	</md-content>
</div>
<div id="remove{{purchase.id}}" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span>
					</div>
					<h3><?php echo lang('attention'); ?></h3>
					<p><?php echo lang('inv_remove_msg'); ?></p>
					<div class="xs-mt-50"> <a type="button" data-dismiss="modal" class="btn btn-space btn-default"><?php echo lang('cancel'); ?></a> <a href="<?php echo site_url('purchases/remove/'.$purchases['id']); ?>" type="button" class="btn btn-space btn-danger"><?php echo lang('delete'); ?></a> </div>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="RecordPayment" ng-cloak style="width: 450px;">
	<md-toolbar class="md-theme-light" style="background:#262626">
		<div class="md-toolbar-tools">
			<md-button ng-click="close()" class="md-icon-button" aria-label="Close">
				<i class="ion-android-arrow-forward"></i>
			</md-button>
			<md-truncate><?php echo lang('recordpayment') ?></md-truncate>
		</div>
	</md-toolbar>
	<md-content layout-padding="">
		<form name="projectForm">
			<md-content layout-padding>
				<md-input-container class="md-block">
					<label><?php echo lang('datepayment') ?></label>
					<input mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="date" class=" dtp-no-msclear dtp-input md-input">
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('amount') ?></label>
					<input required type="number" name="amount" ng-model="amount"/>
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('description') ?></label>
					<textarea required name="not" ng-model="not" placeholder="Type something" class="form-control"></textarea>
				</md-input-container>
				<md-input-container class="md-block">
					<label><?php echo lang('account'); ?></label>
					<md-select placeholder="<?php echo lang('account'); ?>" ng-model="account" name="account" style="min-width: 200px;">
						<md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
					</md-select>
				</md-input-container>
				<div class="form-group pull-right">
					<md-button ng-click="AddPayment()" class="md-raised md-primary ion-ios-paperplane" type="button">
						<span><?php echo lang('save');?></span>
					</md-button>
				</div>
			</md-content>
		</form>
	</md-content>
</md-sidenav>
<script>
	var PURCHASEID = <?php echo $purchases['id']; ?>;
	var PURCHASVENDOR = <?php echo $purchases['vendor_id']; ?>;
</script>
</div>

<?php include_once( APPPATH . 'views/inc/footer.php' );?>
<script src="<?php echo base_url('assets/js/purchases.js'); ?>"></script>