<md-content class="md-padding bg-white">
	<div class="col-md-6">
		<md-input-container class="md-block">
			<label><?php echo lang('company')?></label>
			<input required name="company" ng-model="settings_detail.company">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('email')?></label>
			<input required name="company" ng-model="settings_detail.email">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('country')?></label>
			<md-select placeholder="<?php echo lang('country'); ?>"
				ng-model="settings_detail.country_id"
				ng-change="getStates(settings_detail.country_id)" style="min-width: 200px;">
				<md-option ng-value="country.id" ng-repeat="country in countries">
				{{country.shortname}}</md-option>
			</md-select>
			<br>
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('state')?></label>
			<md-select placeholder="<?php echo lang('state'); ?>"
				ng-model="settings_detail.state_id" name="state_id" style="min-width: 200px;">
				<md-option ng-value="state.id" ng-repeat="state in states">{{state.state_name}}
				</md-option>
			</md-select>
			<br>
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('city')?></label>
			<input required ng-model="settings_detail.city">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('town')?></label>
			<input required ng-model="settings_detail.town">
		</md-input-container>
	</div>
	<div class="col-md-6">
		<md-input-container class="md-block">
			<label><?php echo lang('crmname')?></label>
			<input required ng-model="settings_detail.crm_name">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('zipcode')?></label>
			<input required ng-model="settings_detail.zipcode">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('phone')?></label>
			<input required ng-model="settings_detail.phone">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('fax')?></label>
			<input required ng-model="settings_detail.fax">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo $appconfig['tax_label'].' '.lang('vatnumber')?></label>
			<input required ng-model="settings_detail.vatnumber">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo $appconfig['tax_label'].' '.lang('taxoffice')?></label>
			<input required ng-model="settings_detail.taxoffice">
		</md-input-container>
	</div>
	<div class="col-md-12">
		<md-input-container class="md-block">
			<label><?php echo lang('address')?></label>
			<textarea name="address" class="form-control"
			ng-model="settings_detail.address"></textarea>
		</md-input-container>
	</div>
</md-content>