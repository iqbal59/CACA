<md-content class="md-padding bg-white">
	<div class="col-md-6">
		<md-input-container class="md-block">
			<label><?php echo lang('currency'); ?></label>
			<md-select required placeholder="<?php echo lang('currency'); ?>"
				ng-model="settings_detail.currencyid" style="min-width: 200px;">
				<md-option ng-value="currency.id" ng-repeat="currency in currencies">
				{{currency.name}}</md-option>
			</md-select>
			<br>
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('tax').' '.lang('label')?></label>
			<textarea required name="address" class="form-control"
			ng-model="finance.tax_label"></textarea>
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('termtitle')?></label>
			<input required ng-model="settings_detail.termtitle">
		</md-input-container>
		<md-input-container class="md-block">
			<label><?php echo lang('termdescription')?></label>
			<textarea required name="address" class="form-control"
			ng-model="settings_detail.termdescription"></textarea>
		</md-input-container>
	</div>
	<div class="col-md-6">
		<div layout="row" layout-wrap>
			<div flex-gt-xs="50" flex-xs="100">
				<md-input-container class="md-block"> 
					<label><?php echo lang('thousand').' '.lang('separator')?></label>
					<md-select required placeholder="<?php echo lang('thousand').' '.lang('separator')?>" ng-model="settings_detail.thousand_separator" style="min-width: 200px;">
						<md-option value="auto"><span><?php echo lang('auto') ?></span></md-option>
						<md-option value=","><strong>,</strong></md-option>
						<md-option value="."><strong>.</strong></md-option>
						<md-option value=""><?php echo lang('none')?></md-option>
						<md-option value=" "><?php echo lang('space')?></md-option>
					</md-select><br>
				</md-input-container>
			</div>
			<div flex-gt-xs="50" flex-xs="100">
				<md-input-container class="md-block">
					<label><?php echo lang('decimal').' '.lang('separator')?></label>
					<md-select required placeholder="<?php echo lang('decimal').' '.lang('separator')?>" ng-model="settings_detail.decimal_separator" style="min-width: 200px;">
						<md-option value="auto"><span><?php echo lang('auto') ?></span></md-option>
						<md-option value=","><strong>,</strong></md-option>
						<md-option value="."><strong>.</strong></md-option>
					</md-select><br>
				</md-input-container>
			</div>
			<div flex-gt-xs="50" flex-xs="100">
				<md-input-container class="md-block">
					<label><?php echo lang('currency').' '.lang('position')?></label>
					<md-select required placeholder="<?php echo lang('currency').' '.lang('position')?>" ng-model="settings_detail.currency_position" style="min-width: 200px;">
						<md-option value="auto"><span><?php echo lang('auto') ?></span></md-option>
						<md-option value="after"><span><?php echo lang('after') ?></span></md-option>
						<md-option value="before"><span><?php echo lang('before') ?></span></md-option>
					</md-select><br>
				</md-input-container>
			</div>
			<div flex-gt-xs="50" flex-xs="100">
				<md-input-container class="md-block" ng-hide="settings_detail.currency_position == 'auto'">
					<label><?php echo lang('currency').' '.lang('display')?></label>
					<md-select required placeholder="<?php echo lang('currency').' '.lang('display')?>" ng-model="settings_detail.currency_display" style="min-width: 200px;" ng-disabled="settings_detail.currency_position == 'auto'">
						<md-option value="code"><span><?php echo lang('code') ?> => USD</span></md-option>
						<md-option value="symbol"><span><?php echo lang('symbol') ?> => $ </span></md-option>
					</md-select><br>
				</md-input-container>
			</div>
		</div>
		<br>
		<p style="font-weight: 500;background: #adadad;padding-left: 4px;">
			<?php echo lang('currency_note') ?>
		</p>
	</div>
</md-content>