<?php include_once( APPPATH . 'views/inc/ciuis_data_table_header.php' ); ?>
<?php $appconfig = get_appconfig(); ?>
<div id="pageContent">
	<div class="ciuis-body-content" ng-controller="Invoices_Controller">
		<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
			<div class="panel-default">
				<div class="ciuis-invoice-summary"></div>
			</div>
			<?php if ($this->session->userdata('other')) { ?>
				<div class="panel-default">
					<div class="ciuis-invoice-summary"></div>
				</div>
			<?php } ?>
			<md-toolbar class="toolbar-white">
				<div class="md-toolbar-tools">
					<h2 flex md-truncate class="text-bold"><?php echo lang('invoices'); ?> <small>(<span ng-bind="invoices.length"></span>)</small><br><small flex md-truncate><?php echo lang('organizeyourinvoices'); ?></small></h2>
					<div class="ciuis-external-search-in-table">
						<input ng-model="invoice_search" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('search_by').' '.lang('customer')?>"> 
						<md-button class="md-icon-button" aria-label="Search" ng-cloak>
							<md-icon><i class="ion-search text-muted"></i></md-icon>
						</md-button>
					</div>
					<md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter" ng-cloak>
						<md-icon><i class="ion-android-funnel text-muted"></i></md-icon>
					</md-button>
					<?php if (check_privilege('invoices', 'create')) { ?>
						<md-button ng-href="<?php echo base_url('invoices/create') ?>" class="md-icon-button" aria-label="New" ng-cloak>
							<md-tooltip md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
							<md-icon><i class="ion-android-add-circle text-success"></i></md-icon>
						</md-button>
					<?php } ?>
				</div>
			</md-toolbar>
			<div ng-show="invoiceLoader" layout-align="center center" class="text-center" id="circular_loader">
				<md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
				<p style="font-size: 15px;margin-bottom: 5%;">
					<span>
						<?php echo lang('please_wait') ?> <br>
						<small><strong><?php echo lang('loading'). ' '. lang('invoices').'...' ?></strong></small>
					</span>
				</p>
			</div>
			<md-content ng-show="!invoiceLoader" class="bg-white" ng-cloak> 
				<md-table-container ng-show="invoices.length > 0">
					<table md-table  md-progress="promise">
						<thead md-head md-order="invoice_list.order">
							<tr md-row>
								<th md-column><span><?php echo lang('invoice'); ?></span></th>
								<th md-column md-order-by="created"><span><?php echo lang('billeddate'); ?></span></th>
								<th md-column md-order-by="duedate"><span><?php echo lang('invoiceduedate'); ?></span></th>
								<th md-column md-order-by="status"><span><?php echo lang('status'); ?></span></th>
								<th md-column md-order-by="total"><span><?php echo lang('amount'); ?></span></th>
							</tr>
						</thead>
						<tbody md-body>
							<tr class="select_row" md-row ng-repeat="invoice in invoices | orderBy: invoice_list.order | limitTo: invoice_list.limit : (invoice_list.page -1) * invoice_list.limit | filter: invoice_search | filter: FilteredData" class="cursor" ng-click="goToLink('invoices/invoice/'+invoice.id)">
								<td md-cell>
									<strong>
										<a class="link" ng-href="<?php echo base_url('invoices/invoice/') ?>{{invoice.id}}"> <span ng-bind="invoice.longid"></span></a>
									</strong><br>
									<small ng-bind="invoice.customer"></small>
								</td>
								<td md-cell>
									<strong><span class="badge" ng-bind="invoice.created"></span></strong>
								</td>
								<td md-cell>
									<strong><span class="badge" ng-bind="invoice.duedate"></span></strong>
								</td>
								<td md-cell>
									<strong class="text-uppercase text-{{invoice.color}}" ng-bind="invoice.status"></strong>
								</td>
								<td md-cell>
									<strong ng-bind-html="invoice.total | currencyFormat:cur_code:null:true:cur_lct"></strong>
								</td>
							</tr>
						</tbody>
					</table>
				</md-table-container>
				<md-table-pagination ng-show="invoices.length > 0" md-limit="invoice_list.limit" md-limit-options="limitOptions" md-page="invoice_list.page" md-total="{{invoices.length}}" ></md-table-pagination>
				<md-content ng-show="!invoices.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>	
			</md-content>
		</div>
		<md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ContentFilter" ng-cloak style="width: 450px;">
			<md-toolbar class="md-theme-light" style="background:#262626">
				<div class="md-toolbar-tools">
					<md-button ng-click="close()" class="md-icon-button" aria-label="Close">
						<i class="ion-android-arrow-forward"></i>
					</md-button>
					<md-truncate><?php echo lang('filter') ?></md-truncate>
				</div>
			</md-toolbar>
			<md-content layout-padding="">
				<div ng-repeat="(prop, ignoredValue) in invoices[0]" ng-init="filter[prop]={}" ng-if="prop != 'id' && prop != 'prefix' && prop != 'longid' && prop != 'created' && prop != 'duedate' && prop != 'customer' && prop != 'total' && prop != 'status' && prop != 'color' && prop != 'customer_id' && prop != 'staff_id' && prop != 'recurring_status'">
					<div class="filter col-md-12">
						<h4 class="text-muted text-uppercase"><strong>{{prop}}</strong></h4>
						<hr>
						<div class="labelContainer" ng-repeat="opt in getOptionsFor(prop)" ng-if="prop!='<?php echo lang('filterbycustomer') ?>'">
							<md-checkbox id="{{[opt]}}" ng-model="filter[prop][opt]" aria-label="{{opt}}"><span class="text-uppercase">{{opt}}</span></md-checkbox>
						</div>
						<div ng-if="prop=='<?php echo lang('filterbycustomer') ?>'">
							<md-select aria-label="Filter" ng-model="filter_select" ng-init="filter_select='all'" ng-change="updateDropdown(prop)">
								<md-option value="all"><?php echo lang('all') ?></md-option>
								<md-option ng-repeat="opt in getOptionsFor(prop) | orderBy:'':true" value="{{opt}}">{{opt}}</md-option>
							</md-select>
						</div>
					</div>
				</div>
			</md-content>
		</md-sidenav>
	</div>
	<ciuis-sidebar></ciuis-sidebar>
	<?php include_once( APPPATH . 'views/inc/other_footer.php' ); ?>
	<script src="<?php echo base_url('assets/js/ciuis_data_table.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/invoices.js'); ?>"></script>
	<script type="text/javascript">
	(function umd(root, name, factory)
	{
		'use strict';
		if ('function' === typeof define && define.amd) {
			define(name, ['jquery'], factory);
		} else {
			root[name] = factory();
		}
	}
	(this, 'CiuisInvoiceStats', function UMDFactory()
	{
		'use strict';
		var ReportOverview = ReportOverviewConstructor;
		reportCircleGraph();
		return ReportOverview;
		function ReportOverviewConstructor(options) {
			var factory = {
				init: init
			},
			_elements = {
				$element: options.element
			};
			init();
			return factory;
			function init() {
				_elements.$element.append($(getTemplateString()));

				$('.invoice-percent').percentCircle({
					width: 130,
					trackColor: '#ececec',
					barColor: '#22c39e',
					barWeight: 3,
					endPercent: 0.<?php echo $ofx ?>,
					fps: 60
				});
				$('.invoice-percent-2').percentCircle({
					width: 130,
					trackColor: '#ececec',
					barColor: '#ee7a6b',
					barWeight: 3,
					endPercent: 0.<?php echo $ofy ?>,
					fps: 60
				});

				$('.invoice-percent-3').percentCircle({
					width: 130,
					trackColor: '#ececec',
					barColor: '#808281',
					barWeight: 3,
					endPercent: 0.<?php echo $vgy ?>,
					fps: 60
				});
			}
			function getTemplateString()
			{
				return [
				'<div>',
				'<div class="row">',
				'<div class="col-md-12">',
				'<div style="border-top-left-radius: 10px;" class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header text-uppercase text-bold"><?php echo lang('totalinvoice'); ?></div>',
				'<div class="box-content">',
				'<div class="sentTotal">{{totalinvoicesayisi}}</div>'.replace(/{{totalinvoicesayisi}}/, options.data.totalinvoicesayisi),
				'</div>',
				'<div class="box-foot">',
				'<div class="sendTime box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo amount_format($fam, true) ?></strong></span></div>'.replace(/{{date}}/, options.data.date),
				'</div>',
				'</div>',
				'<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header text-uppercase text-bold"><?php echo lang('paid'); ?></div>',
				'<div class="box-content invoice-percent">',
				'<div class="percentage">%<?php echo $ofx ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo amount_format($ofv, true) ?></strong></span></div>',
				'<span class="arrow arrow-down"></span>',
				'<div class="box-foot-right"><br><span class="box-foot-stats""><strong><?php echo $otf ?></strong> (%<?php echo $ofx ?>)</span></div>',
				'</div>',
				'</div>',
				'<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header text-uppercase text-bold"><?php echo lang('unpaidinvoice'); ?></div>',
				'<div class="box-content invoice-percent-2">',
				'<div class="percentage">%<?php echo $ofy ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo amount_format($oft, true) ?></strong></span></div>'.replace(/{{OdenmeyenInvoicesAmount}}/, options.data.OdenmeyenInvoicesAmount),
				'<span class="arrow arrow-down"></span>',
				'<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $tef ?></strong> (%<?php echo $ofy ?>)</span></div>',
				'</div>',
				'</div>',
				'<div style="border-top-right-radius: 10px;" class="ciuis-invoice-summaries-b1">',
				'<div class="box-header text-uppercase text-bold"><?php echo lang('overdue'); ?></div>',
				'<div class="box-content invoice-percent-3">',
				'<div class="percentage">%<?php echo $vgy ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo amount_format($vgf, true) ?></strong></span></div>'.replace(/{{VadesiDolanInvoices}}/, options.data.VadesiDolanInvoices),
				'<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $vdf ?></strong> (%<?php echo $vgy ?>)</span></div>',
				'</div>',
				'</div>'
				].join('');
			}
		}
		function reportCircleGraph() {
			$.fn.percentCircle = function pie(options) {
				var settings = $.extend({
					width: 130,
					trackColor: '#fff',
					barColor: '#fff',
					barWeight: 3,
					startPercent: 0,
					endPercent: 1,
					fps: 60
				}, options);
				this.css({
					width: settings.width,
					height: settings.width
				});
				var _this = this,
				canvasWidth = settings.width,
				canvasHeight = canvasWidth,
				id = $('canvas').length,
				canvasElement = $('<canvas id="' + id + '" width="' + canvasWidth + '" height="' + canvasHeight + '"></canvas>'),
				canvas = canvasElement.get(0).getContext('2d'),
				centerX = canvasWidth / 2,
				centerY = canvasHeight / 2,
				radius = settings.width / 2 - settings.barWeight / 2,
				counterClockwise = false,
				fps = 500 / settings.fps,
				update = 0.01;
				this.angle = settings.startPercent;
				this.drawInnerArc = function (startAngle, percentFilled, color) {
					var drawingArc = true;
					canvas.beginPath();
					canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
					canvas.strokeStyle = color;
					canvas.lineWidth = settings.barWeight - 2;
					canvas.stroke();
					drawingArc = false;
				};
				this.drawOuterArc = function (startAngle, percentFilled, color) {
					var drawingArc = true;
					canvas.beginPath();
					canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
					canvas.strokeStyle = color;
					canvas.lineWidth = settings.barWeight;
					canvas.lineCap = 'round';
					canvas.stroke();
					drawingArc = false;
				};
				this.fillChart = function (stop) {
					var loop = setInterval(function () {
						canvas.clearRect(0, 0, canvasWidth, canvasHeight);
						_this.drawInnerArc(0, 360, settings.trackColor);
						_this.drawOuterArc(settings.startPercent, _this.angle, settings.barColor);
						_this.angle += update;
						if (_this.angle > stop) {
							clearInterval(loop);
						}
					}, fps);
				};
				this.fillChart(settings.endPercent);
				this.append(canvasElement);
				return this;
			};
		}
		function getMockData() {
			return {
				totalinvoicesayisi: <?php echo $tfa ?>,
			};
		}
	}));
(function activateCiuisInvoiceStats($) {
	'use strict';
	var $el = $('.ciuis-invoice-summary');
	return new CiuisInvoiceStats({
		element: $el,
		data: {
			totalinvoicesayisi: <?php echo $tfa ?>,
		}
	});
}(jQuery));
</script>
</div>
