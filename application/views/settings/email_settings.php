 <md-content class="md-padding bg-white">
 	<div class="col-md-6">
 		<md-input-container class="md-block" flex-gt-xs> 
 			<label><?php echo lang('email').' '.lang('type')?></label>
 			<md-select required placeholder="<?php echo lang('thousand').' '.lang('separator')?>" ng-model="settings_detail.email_type" style="min-width: 200px;">
 				<md-option value="1"><span><?php echo 'SMTP' ?></span></md-option>
 				<md-option value="0"><span><?php echo lang('php_mail') ?></span></md-option>
 			</md-select><br>
 		</md-input-container>
 		<md-input-container class="md-block" ng-hide="settings_detail.email_type == 0">
 			<label><?php echo lang('smtpusername')?></label>
 			<input required ng-model="settings_detail.smtpusername">
 		</md-input-container>
 		<md-input-container class="md-block" ng-hide="settings_detail.email_type == 0">
 			<label><?php echo lang('smtphost')?></label>
 			<input required ng-model="settings_detail.smtphost">
 		</md-input-container>
 		<md-input-container class="md-block">
 			<label><?php echo lang('sender_name')?></label>
 			<input required ng-model="settings_detail.sender_name">
 		</md-input-container>
 	</div>
 	<div class="col-md-6">
 		<md-input-container class="md-block" flex-gt-xs ng-hide="settings_detail.email_type == 0">
 			<label><span class="text-bold"><?php echo lang('email_encryption'); ?></span></label>
 			<md-select ng-init="email_encryptions = [{value: '0',name: 'NONE'},{value: '1',name: 'SSL'}, {value: '2',name: 'TLS'}];" placeholder="<?php echo lang('email_encryption'); ?>" ng-model="settings_detail.email_encryption">
 				<md-option ng-value="email_encryption.value" ng-repeat="email_encryption in email_encryptions">
 					<span class="text-uppercase">{{email_encryption.name}}</span>
 				</md-option>
 			</md-select>
 			<br>
 		</md-input-container>
 		<md-input-container class="md-block password-input" ng-hide="settings_detail.email_type == 0">
 			<label><?php echo lang('password') ?></label>
 			<input type="text" required ng-model="settings_detail.smtppassoword">
 			<md-icon ng-click="seePasswordModal()" class="ion-eye" style="display:inline-block;">
 				<md-tooltip md-direction="top"><?php echo lang('view_password'); ?></md-tooltip>
 			</md-icon>
 		</md-input-container>
 		<div layout="row" layout-wrap ng-hide="settings_detail.email_type == 0">
 			<div flex-gt-xs="50" flex-xs="100">
 				<md-input-container class="md-block">
 					<label><?php echo lang('smtpport')?></label>
 					<input required ng-model="settings_detail.smtpport">
 				</md-input-container>
 			</div>
 			<div flex-gt-xs="50" flex-xs="100">
 				<md-input-container class="md-block">
 					<label><?php echo lang('emailcharset')?></label>
 					<input required ng-model="settings_detail.emailcharset">
 				</md-input-container>
 			</div>
 		</div>
 		<md-input-container class="md-block">
 			<label><?php echo lang('sender_email')?></label>
 			<input required ng-model="settings_detail.sendermail">
 		</md-input-container>
 	</div>
 </md-content>
 <md-card flex-xs flex-gt-xs="30" layout="column" class="md-padding" style="margin-left: 1.8%;">
 	<md-input-container class="md-block">
 		<label><?php echo lang('email')?></label>
 		<input type="email" required ng-model="settings_detail.testEmail" minlength="10"
 		maxlength="100" ng-pattern="/^.+@.+\..+$/">
 	</md-input-container>
 	<section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
 		<?php if (check_privilege('settings', 'edit')) { ?> 
 			<md-button ng-click="sendTestEmail()" class="md-raised md-primary pull-right"
 			ng-disabled="sendingTestEmail == true">
 			<span ng-hide="sendingTestEmail == true"><?php echo lang('sendtestmail');?></span>
 			<md-progress-circular class="white" ng-show="sendingTestEmail == true"
 			md-mode="indeterminate" md-diameter="20"></md-progress-circular>
 		</md-button>
 	<?php }?>
 </section>
</md-card>