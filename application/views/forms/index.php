<?php include_once( APPPATH . 'views/inc/ciuis_data_table_header.php' ); ?>
<div class="ciuis-body-content" ng-controller="WebLeads_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <div class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0">
    </div>
    <div class="main-content container-fluid col-xs-12 col-md-9 col-lg-9 md-p-0 lead-table">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
            <md-icon><i class="ion-earth text-muted"></i></md-icon>
          </md-button>
          <h2 flex md-truncate><?php echo lang('webleads') ?><br>
            <small flex md-truncate><?php echo lang('manage_webleads'); ?></small>
          </h2>
          <div class="ciuis-external-search-in-table">
            <input ng-model="weblead_search" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
            <md-button class="md-icon-button" aria-label="Search" ng-cloak>
              <md-icon><i class="ion-search text-muted"></i></md-icon>
            </md-button>
          </div>
          <?php if (check_privilege('leads', 'create')) { ?> 
            <md-button ng-click="createForm()" class="md-icon-button" aria-label="Update" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('newform') ?></md-tooltip>
              <md-icon><i class="ion-android-add-circle text-success"></i></md-icon>
            </md-button>
          <?php } ?>
        </div>
      </md-toolbar>
      <div ng-show="webleadsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
          <span>
            <?php echo lang('please_wait') ?> <br>
            <small><strong><?php echo lang('loading'). ' '. lang('webleads').'...' ?></strong></small>
          </span>
        </p>
      </div>
      <md-content ng-show="!webleadsLoader">
        <md-content ng-show="!webleadsLoader" class="bg-white" ng-cloak>
          <md-table-container ng-show="webleads.length > 0">
            <table md-table md-progress="promise">
              <thead md-head md-order="weblead_list.order">
                <tr md-row>
                  <th md-column><span><?php echo lang('form_name'); ?></span></th>
                  <th md-column md-order-by="createddate"><span><?php echo lang('created'); ?></span></th>
                  <th md-column md-order-by="total_submissions"><span><?php echo lang('total_submissions'); ?></span>
                  </th>
                  <th md-column md-order-by="status"><span><?php echo lang('form_status'); ?></span></th>
                  <th md-column md-order-by="sourcename"><span><?php echo lang('source'); ?></span></th>
                  <th md-column md-order-by="total"><span><?php echo lang('staff'); ?></span></th>
                </tr>
              </thead>
              <tbody md-body>
                <tr class="select_row" md-row ng-repeat="weblead in webleads | orderBy: weblead_list.order | limitTo: weblead_list.limit : (weblead_list.page -1) * weblead_list.limit | filter: weblead_search | filter: FilteredData" class="cursor" ng-click="goToLink('leads/form/'+weblead.id)">
                  <td md-cell>
                    <strong>
                      <a class="link" ng-href="<?php echo base_url('leads/form/') ?>{{weblead.id}}"> <span ng-bind="weblead.name"></span></a>
                    </strong><br>
                  </td>
                  <td md-cell>
                    <strong><span class="badge" ng-bind="weblead.createddate"></span></strong>
                  </td>
                  <td md-cell>
                    <strong><span ng-bind="weblead.total_submissions"></span></strong>
                  </td>
                  <td md-cell>
                    <strong ng-show="form.status == '1'" class="badge green"><?php echo lang('active'); ?></strong>
                    <strong ng-show="form.status != '1'" class="badge red"><?php echo lang('inactive'); ?></strong>
                  </td>
                  <td md-cell>
                    <strong><span class="badge" ng-bind="weblead.sourcename"></span></strong>
                  </td>
                  <td md-cell>
                    <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Assigned: {{weblead.assigned}}" class="assigned-staff-for-this-lead user-avatar">
                    <img src="<?php echo base_url('uploads/images/{{weblead.avatar}}')?>" alt="{{weblead.assigned}}"> 
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </md-table-container>
          <md-table-pagination ng-show="webleads.length > 0" md-limit="weblead_list.limit" md-limit-options="limitOptions" md-page="weblead_list.page" md-total="{{webleads.length}}">
          </md-table-pagination>
          <md-content ng-show="!webleadsLoader" ng-hide="webleads.length > 0" class="md-padding no-item-data" ng-cloak>
            <?php echo lang('no_webleads_found') ?></md-content>
        </md-content>
      </md-content>
    </div>
  </div>

  <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create" style="width: 450px;" ng-cloak>
    <md-toolbar class="md-theme-light" style="background:#262626">
      <div class="md-toolbar-tools">
        <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
        <md-truncate><?php echo lang('create_weblead_form') ?></md-truncate> &nbsp;&nbsp;&nbsp;
        <md-switch ng-checked="true" ng-model="weblead.status" aria-label="Type">
          <strong class="text-muted"><?php echo lang('active') ?></strong>
        </md-switch>
      </div>
    </md-toolbar>
    <md-content>
      <md-content layout-padding>
        <md-input-container class="md-block">
          <label><?php echo lang('form_name'); ?></label>
          <input ng-model="weblead.name">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('assigned'); ?></label>
          <md-select placeholder="<?php echo lang('choosestaff'); ?>" ng-model="weblead.assigned_id" style="min-width: 200px;">
            <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('weblead').' '.lang('status'); ?></label>
          <md-select placeholder="<?php echo lang('weblead').' '.lang('status'); ?>" ng-model="weblead.status_id" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('weblead').' '.lang('status') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewStatus()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="status.id" ng-repeat="status in leadstatuses">{{status.name}}</md-option>
          </md-select>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label><?php echo lang('weblead').' '.lang('source'); ?></label>
          <md-select placeholder="<?php echo lang('weblead').' '.lang('source'); ?>" ng-model="weblead.source_id" style="min-width: 200px;">
            <md-select-header>
              <md-toolbar class="toolbar-white">
                <div class="md-toolbar-tools">
                  <h4 flex md-truncate><?php echo lang('weblead').' '.lang('source') ?></h4>
                  <md-button class="md-icon-button" ng-click="NewSource()" aria-label="Create New">
                    <md-icon><i class="mdi mdi-plus text-muted"></i></md-icon>
                  </md-button>
                </div>
              </md-toolbar>
            </md-select-header>
            <md-option ng-value="source.id" ng-repeat="source in leadssources">{{source.name}}</md-option>
          </md-select>
        </md-input-container><br>
        <!-- <md-input-container class="md-block">
          <label><?php echo lang('submit_text'); ?></label>
          <?php $submit = lang('submit'); ?>
          <input ng-model="weblead.submit_text" ng-init="weblead.submit_text = '<?php echo $submit ?>'">
        </md-input-container> -->
        <md-input-container class="md-block">
          <label><?php echo lang('message_after_success') ?></label>
          <?php $success = lang('leads_success_message'); ?>
          <textarea ng-model="weblead.success_message" ng-init="weblead.success_message = '<?php echo $success ?>'" md-maxlength="300" md-select-on-focus></textarea>
        </md-input-container>
        <md-checkbox ng-checked="true" ng-model="weblead.notification"><?php echo lang('email_notification') ?>
        </md-checkbox>
        <md-checkbox ng-checked="true" ng-model="weblead.duplicate"><?php echo lang('allow_duplicate') ?></md-checkbox>
      </md-content>
      <md-content layout-padding>
        <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
          <md-button ng-click="AddWebLeadForm()" class="md-raised md-primary pull-right" ng-disabled="saving == true">
            <span ng-hide="addingLead == true"><?php echo lang('create');?></span>
            <md-progress-circular class="white" ng-show="addingLead == true" md-mode="indeterminate" md-diameter="20">
            </md-progress-circular>
          </md-button>
        </section>
      </md-content>
    </md-content>
  </md-sidenav>
</div>
<?php include_once( APPPATH . 'views/inc/other_footer.php' ); ?>
<script src="<?php echo base_url('assets/js/ciuis_data_table.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/webleads.js'); ?>"></script>