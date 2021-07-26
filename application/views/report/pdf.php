<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/lib/bootstrap/dist/css/bootstrap.css'); ?>" 
			type="text/css"/> 
	</head>
	<body>
		<div class="container">
			<div class="row">
				<?php if($type == 'invoices') { ?>
					<h2 class="text-center"><?php echo lang('invoices') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('invoice')."</th>";
									echo "<th>".lang('issuance_date')."</th>";
									echo "<th>".lang('duedate')."</th>";
									echo "<th>".lang('companyname')."</th>";
									echo "<th>".lang('staffname')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('total')."</th>";
									echo "<th>".lang('default_payment_method')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('invoices', $data[ 'id' ], 'invoice','inv'))."<br>".($data['serie'] ? $data['serie'] : '')."</td>";
										echo "<td class='text-left'>".($data['created'] ? $data['created'] : '')."</td>";
										echo "<td class='text-left'>".($data['duedate'] ? $data['duedate'] : '')."</td>";
										echo "<td class='text-left'>".($data['customercompany'] ? $data['customercompany'] : $data['individual'])."<br>(".(get_number('customers', $data[ 'customer_id' ], 'customer','customer')).")</td>";
										echo "<td class='text-left'>".($data['staffmembername'])."<br>(".(get_number('staff', $data[ 'staff_id' ], 'staff','staff')).")</td>";
										echo "<td class='text-left'>".($data['statusname'])."</td>";
										echo "<td class='text-left'>".($data['total']).' '.currency."</td>";
										echo "<td class='text-left'>".($data['default_payment_method'] ? $data['default_payment_method'] : '')."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'customers') { ?>
					<h2 class="text-center"><?php echo lang('customers') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('customer')."</th>";
									echo "<th>".lang('companyname')."</th>";
									echo "<th>".lang('phone')."</th>";
									echo "<th>".lang('email')."</th>";
									echo "<th>".lang('group')."</th>";
									echo "<th>".lang('amount')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 3 AND customer_id = ' . $data[ 'id' ] . ') ' );
									$total_unpaid_invoice_amount = $this->db->get()->row()->total;
									$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 2 AND customer_id = ' . $data[ 'id' ] . ') ' );
									$total_paid_invoice_amount = $this->db->get()->row()->total;
									$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND customer_id = ' . $data[ 'id' ] . ') ' );
									$total_paid_amount = $this->db->get()->row()->amount;
									echo "<tr style='max-width:100%; overflow:auto;'>";
										echo "<td class='text-left'>".(get_number('customers', $data[ 'id' ], 'customer','customer'))."</td>";
										echo "<td class='text-left'>".($data['company'] ? $data['company'] : $data['namesurname'])."</td>";
										echo "<td class='text-left'>".($data['phone'] ? $data['phone'] : '')."</td>";
										echo "<td class='text-left'>".$data['email']."</td>";
										echo "<td class='text-left'>".$data['name']."</td>";
										echo "<td class='text-left'>".($total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount).' '.currency."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'expenses') { ?>
					<h2 class="text-center"><?php echo lang('expenses') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('expenses')."</th>";
									echo "<th>".lang('category')."</th>";
									echo "<th>".lang('type')."</th>";
									echo "<th>".lang('customer')."</th>";
									echo "<th>".lang('date')."</th>";
									echo "<th>".lang('amount')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('payment_account')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									if ( $data[ 'invoice_id' ] == NULL) {
										$billstatus = lang( 'notbilled' );
									} else {
										$billstatus = lang( 'billed' );
									}
									if( $data['internal'] == '1') {
										$customer = get_number('staff', $data[ 'staffid' ], 'staff','staff');
										$customername = $data['staff'];
										$billstatus = lang( 'internal' );
									} else {
										$customer = get_number('customers', $data[ 'customerid' ], 'customer','customer');																																																																			
										$customername = $data['company'] ? $data['company'] : $data['namesurname'];
									}
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('expenses', $data[ 'id' ], 'expense','expense'))."<br>".($data['title']?$data['title']:'')."</td>";
										echo "<td class='text-left'>".($data['category'] ? $data['category'] : '')."</td>";
										echo "<td class='text-left'>".($data['internal'] == '1' ? lang('internal'): '')."</td>";
										echo "<td class='text-left'>".$customername."<br>".$customer."</td>";
										echo "<td class='text-left'>".$data['date']."</td>";
										echo "<td class='text-left'>".$data['amount'].' '.currency."</td>";
										echo "<td class='text-left'>".$billstatus."</td>";
										echo "<td class='text-left'>".($data['payment_account'] ? $data['payment_account'] : '')."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'proposals') { ?>
					<h2 class="text-center"><?php echo lang('proposals') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('proposals')."</th>";
									echo "<th>".lang('assignedstaff')."</th>";
									echo "<th>".lang('created')."</th>";
									echo "<th>".lang('opentill')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('total')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									switch ( $data[ 'status_id' ] ) {
										case '0':
											$status = lang( 'quote' ).' '.lang( 'request' );
											break;
										case '1':
											$status = lang( 'draft' );
											break;
										case '2':
											$status = lang( 'sent' );
											break;
										case '3':
											$status = lang( 'open' );
											break;
										case '4':
											$status = lang( 'revised' );
											break;
										case '5':
											$status = lang( 'declined' );
											break;
										case '6':
											$status = lang( 'accepted' );
											break;
										default :
											$status = lang( 'open' );
											break;
									};
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('proposals', $data[ 'id' ], 'proposal','proposal'))."<br>".$data['subject']."</td>";
										echo "<td class='text-left'>".$data['staffmembername']."<br>".(get_number('staff', $data[ 'staffid' ], 'staff','staff'))."</td>";
										echo "<td class='text-left'>".($data['date'] ? $data['date'] : '')."</td>";
										echo "<td class='text-left'>".($data['opentill'] ? $data['opentill'] : '')."</td>";
										echo "<td class='text-left'>".$status."</td>";
										echo "<td class='text-left'>".($data['total']).' '.currency."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'deposits') { ?>
					<h2 class="text-center"><?php echo lang('deposits') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('deposit')."</th>";
									echo "<th>".lang('category')."</th>";
									echo "<th>".lang('customer')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('date')."</th>";
									echo "<th>".lang('amount')."</th>";
									echo "<th>".lang('payment_account')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									if ( $data[ 'status' ] == '1' ) {
										$billstatus = lang( 'paid' ) and $color = 'success';
									} else if( $data[ 'status' ] == '0' ) {
										$billstatus = lang( 'unpaid' ) and $color = 'danger';
									} else {
										$billstatus = lang( 'internal' ) and $color = 'success';
									}
									if( $data['status'] == '2') {
										$customer = get_number('staff', $data[ 'staffid' ], 'staff','staff');
										$customername = $data['staffname'];
									} else {
										$customer = get_number('customers', $data[ 'customerid' ], 'customer','customer');
										$customername = $data['company'] ? $data['company'] : $data['namesurname'];
									}
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('deposits', $data[ 'id' ], 'deposit','deposit'))."<br>".$data['title']."</td>";
										echo "<td class='text-left'>".($data['category'] ? $data['category'] : '')."</td>";
										echo "<td class='text-left'>".$customername."<br>".$customer."</td>";
										echo "<td class='text-left'>".$billstatus."</td>";
										echo "<td class='text-left'>".$data['date']."</td>";
										echo "<td class='text-left'>".$data['amount'].' '.currency."</td>";
										echo "<td class='text-left'>".$data['payment_account']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'orders') { ?>
					<h2 class="text-center"><?php echo lang('orders') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('order')."</th>";
									echo "<th>".lang('customer')."</th>";
									echo "<th>".lang('assigned')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('issuance_date')."</th>";
									echo "<th>".lang('opentill')."</th>";
									echo "<th>".lang('total')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									if($data['relation_type'] == 'customer') {
										$customer_number = get_number('customers', $data[ 'customerid' ], 'customer','customer');
										$customer = $data['company'] ? $data['company'] : $data['namesurname'];
									} else {
										$customer_number = get_number('leads', $data[ 'leadid' ], 'lead','lead');
										$customer = $data['leadname'];	
									}
									switch ( $data[ 'status_id' ] ) {
										case '1':
											$status = lang( 'draft' );
											break;
										case '2':
											$status = lang( 'sent' );
											break;
										case '3':
											$status = lang( 'open' );
											break;
										case '4':
											$status = lang( 'revised' );
											break;
										case '5':
											$status = lang( 'declined' );
											break;
										case '6':
											$status = lang( 'accepted' );
											break;
										default: 
											$status = lang( 'open' );
											break;
									};
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('orders', $data[ 'id' ], 'order','order'))."<br>".$data['subject']."</td>";
										echo "<td class='text-left'>".$customer."<br>".$customer_number."</td>";
										echo "<td class='text-left'>".$data['staffmembername']."<br>".(get_number('staff', $data[ 'staffid' ], 'staff','staff'))."</td>";
										echo "<td class='text-left'>".$status."</td>";
										echo "<td class='text-left'>".$data['date']."</td>";
										echo "<td class='text-left'>".$data['opentill']."</td>";
										echo "<td class='text-left'>".$data['total'].' '.currency."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'vendors') { ?>
					<h2 class="text-center"><?php echo lang('vendors') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('vendor')."</th>";
									echo "<th>".lang('name')."</th>";
									echo "<th>".lang('groupname')."</th>";
									echo "<th>".lang('email')."</th>";
									echo "<th>".lang('address')."</th>";
									echo "<th>".lang('balance')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 3 AND vendor_id = ' . $data[ 'id' ] . ') ' );
									$total_unpaid_invoice_amount = $this->db->get()->row()->total;
									$this->db->select_sum( 'total' )->from( 'purchases' )->where( '(status_id = 2 AND vendor_id = ' . $data[ 'id' ] . ') ' );
									$total_paid_invoice_amount = $this->db->get()->row()->total;
									$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND vendor_id = ' . $data[ 'id' ] . ') ' );
									$total_paid_amount = $this->db->get()->row()->amount;
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('vendors', $data[ 'id' ], 'vendor','vendor'))."</td>";
										echo "<td class='text-left'>".$data['company']."</td>";
										echo "<td class='text-left'>".$data['name']."</td>";
										echo "<td class='text-left'>".$data['email']."</td>";
										echo "<td class='text-left'>".($data['address'] ? $data['address'] : '')."<br>".($data['phone'] ? $data['phone'] : '')."</td>";
										echo "<td class='text-left'>".($total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount).' '.currency."</td>";
										

									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'purchases') { ?>
					<h2 class="text-center"><?php echo lang('purchases') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('purchase')."</th>";
									echo "<th>".lang('vendor')."</th>";
									echo "<th>".lang('issuance_date')."</th>";
									echo "<th>".lang('duedate')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('amount')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									$totalx = $data[ 'total' ];
									$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(purchase_id =' . $data[ 'id' ] . ') ' );
									$paytotal = $this->db->get();
									$balance = $totalx - $paytotal->row()->amount;
									if ( $balance > 0 ) {
										$purchasesstatus = '';
									} else $purchasesstatus = lang( 'paidinv' );
									$color = 'success';;
									if ( $paytotal->row()->amount < $data[ 'total' ] && $paytotal->row()->amount > 0 && $data[ 'status_id' ] == 3 ) {
										$purchasesstatus = lang( 'partial' );
									} else {
										if ( $paytotal->row()->amount < $data[ 'total' ] && $paytotal->row()->amount > 0 ) {
											$purchasesstatus = lang( 'partial' );
										}
										if ( $data[ 'status_id' ] == 3 ) {
											$purchasesstatus = lang( 'unpaid' );
										}
									}
									if ( $data[ 'status_id' ] == 1 ) {
										$purchasesstatus = lang( 'draft' );
									}
									if ( $data[ 'status_id' ] == 4 ) {
										$purchasesstatus = lang( 'cancelled' );
									}
									echo "<tr>";
										echo "<td class='text-left'>".(get_number("purchases",$data['id'],'purchase','purchase'))."<br>".($data['serie'] ? $data['serie'] : '')."</td>";
										echo "<td class='text-left'>".($data['vendorcompany'])."<br>(".(get_number("vendors",$data['vendor_id'],'vendor','vendor')).")</td>";
										echo "<td class='text-left'>".($data['created'] ? $data['created'] : '')."</td>";
										echo "<td class='text-left'>".($data['duedate'] ? $data['duedate'] : '')."</td>";
										echo "<td class='text-left'>".($purchasesstatus)."</td>";
										echo "<td class='text-left'>".($data['total']).' '.currency."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'contacts') { ?>
					<h2 class="text-center"><?php echo lang('customers').' '.lang('customercontacts') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('name')."</th>";
									echo "<th>".lang('email')."</th>";
									echo "<th>".lang('contactmobile').' '.lang('phone')."</th>";
									echo "<th>".lang('customer')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									$customer = $data['company'] ? $data['company'] : $data['namesurname'];
									echo "<tr>";
										echo "<td class='text-left'>".($data['name'].' '.$data['surname'])."</td>";
										echo "<td class='text-left'>".$data['email']."</td>";
										echo "<td class='text-left'>".($data['mobile'] ? $data['mobile'] : $data['phone'])."</td>";
										echo "<td class='text-left'>".($customer)."<br>(".(get_number("customers",$data['customerid'],'customer','customer')).")</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'tickets') { ?>
					<h2 class="text-center"><?php echo lang('tickets') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('ticket')."</th>";
									echo "<th>".lang('customer')."</th>";
									echo "<th>".lang('department')."</th>";
									echo "<th>".lang('priority')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('assigned')."</th>";
									echo "<th>".lang('lastreply')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									switch ( $data[ 'priority' ] ) {
										case '1':
											$priority = lang( 'low' );
											break;
										case '2':
											$priority = lang( 'medium' );
											break;
										case '3':
											$priority = lang( 'high' );
											break;
										default:
											$priority = lang( 'medium' );
											break;
									};
									switch ( $data[ 'status_id' ] ) {
										case '1':
											$status = lang( 'open' );
											break;
										case '2':
											$status = lang( 'inprogress' );
											break;
										case '3':
											$status = lang( 'answered' );
											break;
										case '4':
											$status = lang( 'closed' );
											break;
										default: 
											$status = lang( 'open' );
											break;
									};
									$customer = ($data[ 'type' ] == 0  ?  $data[ 'company' ] : $data[ 'namesurname' ]);
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('tickets', $data[ 'id' ], 'ticket','ticket'))."<br><small>".$data['subject']."</small></td>";
										echo "<td class='text-left'>".$customer."<br>".(get_number('customers', $data[ 'customer_id' ], 'customer','customer'))."</td>";
										echo "<td class='text-left'>".($data['department'] ? $data['department'] : '')."</td>";
										echo "<td class='text-left'>".$priority."</td>";
										echo "<td class='text-left'>".$status."</td>";
										echo "<td class='text-left'>".($data['staffmembername'])."</td>";
										echo "<td class='text-left'>".($data['lastreply'] ? $data['lastreply'] : '')."</td>";


									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'leads') { ?>
					<h2 class="text-center"><?php echo lang('leads') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('lead')."</th>";
									echo "<th>".lang('companyname')."</th>";
									echo "<th>".lang('email')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('source')."</th>";
									echo "<th>".lang('assigned')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('leads', $data[ 'id' ], 'lead','lead'))."<br>".$data['leadname']."</td>";
										echo "<td class='text-left'>".($data['company'] ? $data['company'] : '')."</td>";
										echo "<td class='text-left'>".($data['email'] ? $data['email'] : '')."</td>";
										echo "<td class='text-left'>".($data['statusname'] ? $data['statusname']:'')."</td>";
										echo "<td class='text-left'>".($data['sourcename'] ? $data['sourcename']:'')."</td>";
										echo "<td class='text-left'>".$data['leadassigned']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'tasks') { ?>
					<h2 class="text-center"><?php echo lang('tasks') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('task')."</th>";
									echo "<th>".lang('project')."</th>";
									echo "<th>".lang('startdate')."</th>";
									echo "<th>".lang('duedate')."</th>";
									echo "<th>".lang('priority')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('assigned')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									switch ( $data[ 'status_id' ] ) { 
										case '1':
											$status = lang( 'open' );
											break;
										case '2':
											$status = lang( 'inprogress' );
											break;
										case '3':
											$status = lang( 'waiting' );
											break;
										case '4':
											$status = lang( 'complete' );
											break;
										case '5':
											$status = lang( 'cancelled' );
											break;
										default:
											$status = lang( 'open' );
											break;
									};
									switch ( $data[ 'priority' ] ) {
										case '1':
											$priority = lang( 'low' );
											break;
										case '2':
											$priority = lang( 'medium' );
											break;
										case '3':
											$priority = lang( 'high' );
											break;
										default:
											$priority = lang( 'medium' );
											break;
									};
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('tasks', $data[ 'id' ], 'task','task'))."<br>".$data['taskname']."</td>";
										echo "<td class='text-left'>".$data['projectname']."<br>".(get_number('projects',$data['projectid'],'project','project'))."</td>";
										echo "<td class='text-left'>".($data['startdate'] ? $data['startdate'] : '')."</td>";
										echo "<td class='text-left'>".($data['duedate'] ? $data['duedate'] : '')."</td>";
										echo "<td class='text-left'>".$priority."</td>";
										echo "<td class='text-left'>".$status."</td>";
										echo "<td class='text-left'>".($data['staffname'])."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'products') { ?>
					<h2 class="text-center"><?php echo lang('products') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								$appconfig = get_appconfig();
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('product')."</th>";
									echo "<th>".lang('name')."</th>";
									echo "<th>".lang('purchaseprice')."</th>";
									echo "<th>".lang('salesprice')."</th>";
									echo "<th>".$appconfig['tax_label']."</th>";
									echo "<th class='col-md-1'>".lang('instock')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('products', $data[ 'id' ], 'product','product'))."<br>".$data['name']."</td>";
										echo "<td class='text-left'>".$data['productname']."<br>".($data['description'] ? $data['description'] : '')."</td>";
										echo "<td class='text-left'>".$data['purchase_price'].' '.currency."</td>";
										echo "<td class='text-left'>".$data['sale_price'].' '.currency."</td>";
										echo "<td class='text-left'>".$data['vat']."</td>";
										echo "<td class='text-left'>".$data['stock']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'staff') { ?>
					<h2 class="text-center"><?php echo lang('staff') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('staff')."</th>";
									echo "<th>".lang('name')."</th>";
									echo "<th>".lang('department')."</th>";
									echo "<th>".lang('email')."</th>";
									echo "<th>".lang('phone')."</th>";
									echo "<th class='col-md-1'>".lang('type')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									if( $data['admin'] == '1' ) {
										$type = lang('admin'); 
									} else if ( $data['staffmember'] == '1' && $data['other'] == null) {
										$type = lang('staff');
									} else {
										$type = lang('other');
									}
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('staff', $data[ 'id' ], 'staff','staff'))."</td>";
										echo "<td class='text-left'>".$data['staffname']."</td>";
										echo "<td class='text-left'>".$data['department']."</td>";
										echo "<td class='text-left'>".$data['email']."</td>";
										echo "<td class='text-left'>".($data['phone'] ? $data['phone'] : '')."</td>";
										echo "<td class='text-left'>".$type."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
				<?php if($type == 'projects') { ?>
					<h2 class="text-center"><?php echo lang('projects') ?></h2>
					<table  class="table panel">
						<thead>
							<?php
								echo "<tr>";
									echo "<th class='col-md-1'>".lang('project')."</th>";
									echo "<th>".lang('customer')."</th>";
									echo "<th>".lang('startdate')."</th>";
									echo "<th>".lang('end').' '.lang('date')."</th>";
									echo "<th>".lang('project_value')."</th>";
									echo "<th>".lang('status')."</th>";
									echo "<th>".lang('project').' '.lang('members')."</th>";
								echo "</tr>";
							?>
						</thead> 
						<tbody>
							<?php 
								foreach ($results as $data) {
									switch ( $data[ 'status' ] ) {
										case '1':
											$status = lang( 'notstarted' );
											break;
										case '2':
											$status = lang( 'started' );
											break;
										case '3':
											$status = lang( 'percentage' );
											break;
										case '4':
											$status = lang( 'cancelled' );
											break;
										case '5':
											$status = lang( 'completed' );
											break;
										default :
											$status = lang( 'started' );
											break;
									}
									if($data[ 'template' ] == '1')	{
										$customer = lang('template');
									} else {
										$customer = ($data['customercompany'])?$data['customercompany']:$data['namesurname'];
									}
									$members = $this->Projects_Model->get_members_index( $data['id'] );
									$staff =array();
									foreach ($members as $member) {
									 	$staff[] = $member['staffname'];
									}
									echo "<tr>";
										echo "<td class='text-left'>".(get_number('projects', $data[ 'id' ], 'project','project'))."<br><small>".$data['name']."</small></td>";
										echo "<td class='text-left'>".$customer."<br><small>".(get_number('customers', $data[ 'customerid' ], 'customer','customer'))."</small></td>";
										echo "<td class='text-left'>".$data['start_date']."</td>";
										echo "<td class='text-left'>".$data['deadline']."</td>";
										echo "<td class='text-left'>".$data['projectvalue'].' '.currency."</td>";
										echo "<td class='text-left'>".$status."</td>";
										echo "<td class='text-left'>".implode(',', $staff)."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				<?php }?>
			</div>
		</div>
	</body>
</html>