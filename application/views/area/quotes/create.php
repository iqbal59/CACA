<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content">
  <div  class="main-content container-fluid col-xs-12 col-md-12 col-lg-9" ng-controller="Quotation_Create_Controller"> 
    <!-- <div ng-show="proposalsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading').'...' ?></strong></small>
         </span>
       </p>
     </div> -->
     <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Proposal" ng-disabled="true" ng-cloak>
          <md-icon><i class="ion-ios-paper-outline text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('request').' '.lang('quote') ?></h2>
        <md-button ng-href="<?php echo base_url('area/quotes')?>" class="md-icon-button" aria-label="Save" ng-cloak>
          <md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
          <md-icon><i class="ion-close-circled text-danger"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <md-content class="bg-white" layout-padding ng-cloak>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('quote').' '.lang('subject')?></label>
          <input ng-model="subject" name="subject">
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('staff') ?></label>
          <input type="text" ng-value="settings.cutomer_staff" ng-model="settings.cutomer_staff" ng-disabled="true">
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('created') ?></label>
          <md-datepicker ng-disabled="true" name="created" ng-model="created"></md-datepicker>
        </md-input-container>
      </div>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('quote').' '.lang('details') ?></label>
          <textarea ng-model="content" rows="3"></textarea>
        </md-input-container>
      </div>
      <md-button ng-click="RequestQuote()" class="template-button">
        <span ng-hide="saving == true"><?php echo lang('request');?></span>
        <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
      </md-button>
    </md-content>
  </div>
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3">
    <?php include_once(APPPATH . 'views/area/inc/sidebar.php'); ?>
  </div>
</div>