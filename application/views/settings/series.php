 <md-content class="md-padding bg-white">
 	<div class="col-md-6">
 		<md-card layout="column" class="md-padding" style="margin-left: 1.8%;">
 			<md-card-content>
 				<!-- Invoice -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('invoice').' '.lang('prefix')?></label>
 							<input required ng-model="finance.inv_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('invoice').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.invoice_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Project -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('project').' '.lang('prefix')?></label>
 							<input required ng-model="finance.project_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('project').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.project_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Proposal -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('proposal').' '.lang('prefix')?></label>
 							<input required ng-model="finance.proposal_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('proposal').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.proposal_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Expense -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('expense').' '.lang('prefix')?></label>
 							<input required ng-model="finance.expense_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('expense').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.expense_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Order -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('order').' '.lang('prefix')?></label>
 							<input ng-model="finance.order_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('order').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.order_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Purchases -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('purchases').' '.lang('prefix')?></label>
 							<input ng-model="finance.purchase_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('purchases').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.purchase_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Tasks -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('task').' '.lang('prefix')?></label>
 							<input ng-model="finance.task_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('task').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.task_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Tickets -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('ticket').' '.lang('prefix')?></label>
 							<input ng-model="finance.ticket_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('ticket').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.ticket_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Deposit -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('deposit').' '.lang('prefix')?></label>
 							<input ng-model="finance.deposit_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('deposit').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.deposit_series">
 						</md-input-container>
 					</div>
 				</div>
 			</div>
 		</md-card-content>
 	</md-card>
 	<div class="col-md-6">
 		<md-card layout="column" class="md-padding" style="margin-left: 1.8%;">
 			<md-card-content>
 				<!-- Product -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('product').' '.lang('prefix')?></label>
 							<input ng-model="finance.product_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('product').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.product_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Staff -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('staff').' '.lang('prefix')?></label>
 							<input ng-model="finance.staff_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('staff').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.staff_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Vendors -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('vendor').' '.lang('prefix')?></label>
 							<input ng-model="finance.vendor_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('vendor').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.vendor_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Customers -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('customer').' '.lang('prefix')?></label>
 							<input ng-model="finance.customer_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('customer').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.customer_series">
 						</md-input-container>
 					</div>
 				</div>
 				<!-- Leads -->
 				<div layout="row" layout-wrap>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('lead').' '.lang('prefix')?></label>
 							<input ng-model="finance.lead_prefix">
 						</md-input-container>
 					</div>
 					<div flex-gt-xs="50" flex-xs="100">
 						<md-input-container class="md-block">
 							<label><?php echo lang('lead').' '.lang('series')?></label>
 							<input type="number" ng-model="finance.lead_series">
 						</md-input-container>
 					</div>
 				</div>
 			</md-card-content>
 		</md-card>
 	</div>
 </md-content>