<?php include_once( APPPATH . 'views/inc/ciuis_data_table_header.php' ); ?>
<?php $appconfig = get_appconfig(); ?>
<style>
  .topRow {
    margin-bottom: 30px;
  }

  .on-drag-enter {}

  .on-drag-hover:before {
    display: block;
    color: white;
    font-size: x-large;
    font-weight: 800;
  }
</style>
<div class="ciuis-body-content" ng-controller="Leads_Controller" >
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
    <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12 md-p-0 lead-table" ng-show="!leadsLoader" ng-if="KanbanBoard">
      <md-toolbar class="toolbar-white" style="margin-left: 4px;" ng-cloak>
        <div class="md-toolbar-tools">
          <h2 flex md-truncate class="text-bold">
            <?php echo lang('leads'); ?> 
            <small>(<span ng-bind="leads.length"></span>)</small><br>
            <small flex md-truncate><?php echo lang('leaddesc'); ?></small>
          </h2>
          <div class="ciuis-external-search-in-table" ng-cloak>
            <input ng-model="lead_search" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
            <md-button class="md-icon-button" aria-label="Search">
              <md-icon aria-label="Add Source"><i class="ion-search text-muted"></i></md-icon>
            </md-button>
          </div>
          <md-button ng-click="LeadSettings()" class="md-icon-button" aria-label="Settings" ng-cloak>
            <md-tooltip md-direction="bottom"><?php echo lang('settings') ?></md-tooltip>
            <md-icon aria-label="Add"><i class="ion-ios-gear text-muted"></i></md-icon>
          </md-button>
          <md-button ng-if="!KanbanBoard" ng-click="ShowKanban()" class="md-icon-button" aria-label="Show Kanban" ng-cloak>
            <md-tooltip md-direction="bottom"><?php echo lang('showkanban'); ?></md-tooltip>
            <md-icon aria-label="Add Source"><i class="mdi mdi-view-week text-muted"></i></md-icon>
          </md-button>
          <md-button ng-if="KanbanBoard" ng-click="HideKanban()" class="md-icon-button" aria-label="Show List" ng-cloak>
            <md-tooltip md-direction="bottom"><?php echo lang('showlist'); ?></md-tooltip>
            <md-icon aria-label="Add Source"><i class="mdi mdi-view-list text-muted"></i></md-icon>
          </md-button>
          <md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter" ng-cloak>
            <md-tooltip md-direction="bottom"><?php echo lang('filter') ?></md-tooltip>
            <md-icon aria-label="Add Source"><i class="ion-android-funnel text-muted"></i></md-icon>
          </md-button>
          <?php if (check_privilege('leads', 'create')) { ?> 
            <md-button ng-click="Create()" class="md-icon-button" aria-label="New" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
              <md-icon aria-label="Add Source"><i class="ion-android-add-circle text-success"></i></md-icon>
            </md-button>
          <?php } ?>
          <md-menu md-position-mode="target-right target" ng-cloak>
            <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
              <md-icon aria-label="Add Source"><i class="ion-android-more-vertical text-muted"></i></md-icon>
            </md-button>
            <md-menu-content width="4">
              <?php if (check_privilege('leads', 'create')) { ?> 
                <md-menu-item>
                  <md-button ng-click="Import()" aria-label="Add">
                    <div layout="row" flex>
                      <p flex><?php echo lang('importleads') ?></p>
                      <md-icon aria-label="Add Source" md-menu-align-target class="ion-upload" style="margin: auto 3px auto 0;"></md-icon>
                    </div>
                  </md-button>
                </md-menu-item>
              <?php } ?>
              <?php echo form_open_multipart('leads/exportdata',array("class"=>"form-horizontal")); ?>
              <md-menu-item>
                <md-button type="submit" aria-label="Add">
                  <div layout="row" flex>
                    <p flex ng-bind="lang.exportleads"></p>
                    <md-icon aria-label="Add Source" md-menu-align-target class="ion-android-download text-muted" style="margin: auto 3px auto 0;"></md-icon>
                  </div>
                </md-button>
              </md-menu-item>
              <?php echo form_close(); ?>
              <?php if (check_privilege('leads', 'delete')) { ?> 
                <md-menu-item>
                  <md-button ng-click="RemoveConverted()" aria-label="Add">
                    <div layout="row" flex>
                      <p flex><?php echo lang('deleteconvertedleads') ?></p>
                      <md-icon aria-label="Add Source" md-menu-align-target class="ion-android-remove-circle" style="margin: auto 3px auto 0;"></md-icon>
                    </div>
                  </md-button>
                </md-menu-item>
              <?php } ?>
              <md-menu-item>
                <md-button aria-label="Add">
                  <a ng-href="<?php echo base_url('leads/forms')?>">
                    <div layout="row" flex>
                      <p flex ng-bind="lang.webleads"></p>
                      <md-icon aria-label="Add Source" md-menu-align-target class="ion-earth text-muted" style="margin: auto 3px auto 0;"></md-icon>
                    </div>
                  </a>
                </md-button>
              </md-menu-item>
            </md-menu-content>
          </md-menu>
        </div>
      </md-toolbar>
      <div ng-show="leadsLoader" layout-align="center center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
          <span>
            <?php echo lang('please_wait') ?> <br>
            <small><strong><?php echo lang('loading'). ' '. lang('leads').'...' ?></strong></small>
          </span>
        </p>
      </div>
      <md-content class="ciuis_lead_kanban_board" style="padding: 0px;    overflow-y: hidden;" ng-cloak>
        <md-list class="ciuis_lead_status_card" flex ng-repeat="lead_status in leadstatuses" ui-on-Drop="onDrop($event,$data,lead_status.id)">
          <md-toolbar class="toolbar-white">
            <div class="md-toolbar-tools">
              <h4 flex md-truncate>{{lead_status.name}}</h4>
              <md-menu md-position-mode="target-right target">
                <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
                  <md-icon aria-label="Add Source"><i class="ion-android-more-vertical text-muted"></i></md-icon>
                </md-button>
                <md-menu-content width="4">
                  <?php if (check_privilege('leads', 'edit')) { ?> 
                    <md-menu-item>
                      <md-button ng-click="EditStatus(lead_status.id,lead_status.name, $event)" aria-label="Add">
                        <div layout="row" flex>
                          <p flex><?php echo lang('edit_status'); ?></p>
                          <md-icon aria-label="Add Source" md-menu-align-target class="ion-edit" style="margin: auto 3px auto 0;"></md-icon>
                        </div>
                      </md-button>
                    </md-menu-item>
                  <?php } ?>
                </md-menu-content>
              </md-menu>
            </div>
          </md-toolbar>
          <div class="items_list">
            <md-list-item class="md-3-line" ui-draggable="true" drag="lead" on-drop-success="dropSuccessHandler($event,$index,lead_status.id)" ng-repeat="lead in leads | filter:search | filter: { status: lead_status.id}"> 
              <div class="md-list-item-text" layout="column">
                <div layout="row" layout-wrap>
                  <div flex-gt-xs="80" flex-xs="80">
                    <h3 flex>
                      <a class="link" ng-href="<?php echo base_url('leads/lead/') ?>{{lead.id}}">
                        {{ lead.name | limitTo: 30 }}{{lead.name.length > 28 ? '...' : ''}}
                      </a>
                    </h3>
                  </div>
                </div>
                <p class="small">
                  <span class="blur"><?php echo lang('email') ?>:</span> 
                  <span>{{ lead.email | limitTo: 30 }}{{lead.email.length > 28 ? '...' : ''}}</span>
                </p>
                <p class="small">
                  <span class="blur"><?php echo lang('source') ?>:</span> 
                  <span>{{ lead.sourcename | limitTo: 30 }}{{lead.sourcename.length > 28 ? '...' : ''}}</span>
                </p>
                <p class="small" ng-show="lead.phone">
                  <span class="blur"><?php echo lang('phone') ?>:</span> 
                  <span>{{ lead.phone | limitTo: 30 }}{{lead.phone.length > 28 ? '...' : ''}}</span>
                </p>
                <p>
                  <span class="blur"> <?php echo lang('assigned') ?>: </span> 
                  <span>
                    <md-tooltip md-direction="top">{{ lead.assigned }}</md-tooltip>
                    <img ng-src="<?php echo base_url('uploads/images/{{lead.avatar}}')?>" class="md-avatar" alt="{{lead.assigned}}" style="    ">
                  </span> &nbsp;&nbsp;&nbsp;
                  <span>
                    <md-tooltip md-direction="top"><?php echo lang('date_contacted') ?></md-tooltip>
                    <span ng-bind="lead.date_contacted"></span>
                  </span>
                </p>
                <div>
                  <div ng-repeat="tag in lead.tagss" class="badge">
                    {{tag}}
                  </div>
                </div>
              </div>
            </md-list-item>
          </div>
        </md-list>
        <br><br><br>
      </md-content>

    </div>
    <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12" ng-cloak>
      <div class="main-content container-fluid col-xs-12 col-md-3 col-lg-3 md-pl-0" ng-if="!KanbanBoard">
        <div class="panel-default panel-table borderten lead-manager-head">
          <div class="col-md-4 col-xs-4 border-right" style="margin-bottom: 10px;border-bottom: 2px dashed #cecece;padding-bottom: 20px;">
            <div class="tasks-status-stat">
              <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number"><?php echo $tcl ?></span><span class="task-stat-all"> / <?php echo $tlh ?> <?php echo lang('lead') ?></span></h3>
              <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: 40%;"></span> </span>
            </div>
            <span style="color:#989898"><?php echo lang('converted') ?></span>
          </div>
          <div class="col-md-4 col-xs-4 border-right" style="margin-bottom: 10px;border-bottom: 2px dashed #cecece;padding-bottom: 20px;">
            <div class="tasks-status-stat">
              <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number"><?php echo $tll ?></span><span class="task-stat-all"> / <?php echo $tlh ?> <?php echo lang('lead') ?></span></h3>
              <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: 40%;"></span> </span>
            </div>
            <span style="color:#989898"><?php echo lang('junk') ?></span>
          </div>
          <div class="col-md-4 col-xs-4 border-right" style="margin-bottom: 10px;border-bottom: 2px dashed #cecece;padding-bottom: 20px;">
            <div class="tasks-status-stat">
              <h3 class="text-bold ciuis-task-stat-title"><span class="task-stat-number"><?php echo $tjl ?></span><span class="task-stat-all"> / <?php echo $tlh ?> <?php echo lang('lead') ?></span></h3>
              <span class="ciuis-task-percent-bg"> <span class="ciuis-task-percent-fg" style="width: 40%;"></span> </span>
            </div>
            <span style="color:#989898"><?php echo lang('lost') ?></span>
          </div>
          <div class="widget-chart-container" style="border-bottom: 2px dashed #e8e8e8; margin-bottom: 20px; padding-bottom: 20px;">
            <div class="widget-counter-group widget-counter-group-right">
              <div style="width: auto" class="pull-left"> <i style="font-size: 38px;color: #bfc2c6;margin-right: 10px" class="ion-stats-bars pull-left"></i>
                <div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
                  <h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('leadsbyleadsource') ?></b></h4>
                  <small><?php echo lang('leadstatsbysource') ?></small>
                </div>
              </div>
            </div>
            <div class="my-2">
              <div class="chart-wrapper">
                <canvas id="leads_by_leadsource"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="main-content container-fluid col-xs-12 col-md-9 col-lg-9 md-p-0 lead-table" ng-show="!leadsLoader" ng-if="!KanbanBoard">
        <md-toolbar class="toolbar-white" style="margin-left: 4px;" ng-cloak>
          <div class="md-toolbar-tools">
            <h2 flex md-truncate class="text-bold">
              <?php echo lang('leads'); ?> 
              <small>(<span ng-bind="leads.length"></span>)</small><br>
              <small flex md-truncate><?php echo lang('leaddesc'); ?></small>
            </h2>
            <div class="ciuis-external-search-in-table" ng-cloak>
              <input ng-model="lead_search" class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
              <md-button class="md-icon-button" aria-label="Search">
                <md-icon aria-label="Add Source"><i class="ion-search text-muted"></i></md-icon>
              </md-button>
            </div>
            <md-button ng-click="LeadSettings()" class="md-icon-button" aria-label="Settings" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('settings') ?></md-tooltip>
              <md-icon aria-label="Add"><i class="ion-ios-gear text-muted"></i></md-icon>
            </md-button>
            <md-button ng-if="!KanbanBoard" ng-click="ShowKanban()" class="md-icon-button" aria-label="Show Kanban" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('showkanban'); ?></md-tooltip>
              <md-icon aria-label="Add Source"><i class="mdi mdi-view-week text-muted"></i></md-icon>
            </md-button>
            <md-button ng-if="KanbanBoard" ng-click="HideKanban()" class="md-icon-button" aria-label="Show List" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('showlist'); ?></md-tooltip>
              <md-icon aria-label="Add Source"><i class="mdi mdi-view-list text-muted"></i></md-icon>
            </md-button>
            <md-button ng-click="toggleFilter()" class="md-icon-button" aria-label="Filter" ng-cloak>
              <md-tooltip md-direction="bottom"><?php echo lang('filter') ?></md-tooltip>
              <md-icon aria-label="Add Source"><i class="ion-android-funnel text-muted"></i></md-icon>
            </md-button>
            <?php if (check_privilege('leads', 'create')) { ?> 
              <md-button ng-click="Create()" class="md-icon-button" aria-label="New" ng-cloak>
                <md-tooltip md-direction="bottom"><?php echo lang('create') ?></md-tooltip>
                <md-icon aria-label="Add Source"><i class="ion-android-add-circle text-success"></i></md-icon>
              </md-button>
            <?php } ?>
            <md-menu md-position-mode="target-right target" ng-cloak>
              <md-button aria-label="Open demo menu" class="md-icon-button" ng-click="$mdMenu.open($event)">
                <md-icon aria-label="Add Source"><i class="ion-android-more-vertical text-muted"></i></md-icon>
              </md-button>
              <md-menu-content width="4">
                <?php if (check_privilege('leads', 'create')) { ?> 
                  <md-menu-item>
                    <md-button ng-click="Import()" aria-label="Add">
                      <div layout="row" flex>
                        <p flex><?php echo lang('importleads') ?></p>
                        <md-icon aria-label="Add Source" md-menu-align-target class="ion-upload" style="margin: auto 3px auto 0;"></md-icon>
                      </div>
                    </md-button>
                  </md-menu-item>
                <?php } ?>
                <?php echo form_open_multipart('leads/exportdata',array("class"=>"form-horizontal")); ?>
                <md-menu-item>
                  <md-button type="submit" aria-label="Add">
                    <div layout="row" flex>
                      <p flex ng-bind="lang.exportleads"></p>
                      <md-icon aria-label="Add Source" md-menu-align-target class="ion-android-download text-muted" style="margin: auto 3px auto 0;"></md-icon>
                    </div>
                  </md-button>
                </md-menu-item>
                <?php echo form_close(); ?>
                <?php if (check_privilege('leads', 'delete')) { ?> 
                  <md-menu-item>
                    <md-button ng-click="RemoveConverted()" aria-label="Add">
                      <div layout="row" flex>
                        <p flex><?php echo lang('deleteconvertedleads') ?></p>
                        <md-icon aria-label="Add Source" md-menu-align-target class="ion-android-remove-circle" style="margin: auto 3px auto 0;"></md-icon>
                      </div>
                    </md-button>
                  </md-menu-item>
                <?php } ?>
                <md-menu-item>
                  <md-button aria-label="Add">
                    <a ng-href="<?php echo base_url('leads/forms')?>">
                      <div layout="row" flex>
                        <p flex ng-bind="lang.webleads"></p>
                        <md-icon aria-label="Add Source" md-menu-align-target class="ion-earth text-muted" style="margin: auto 3px auto 0;"></md-icon>
                      </div>
                    </a>
                  </md-button>
                </md-menu-item>
              </md-menu-content>
            </md-menu>
          </div>
        </md-toolbar>
        <md-content class="md-pt-0" ng-cloak>
          <md-content ng-show="!leadsLoader" class="bg-white" ng-cloak>
            <md-table-container ng-show="leads.length > 0">
              <table md-table md-progress="promise">
                <thead md-head md-order="lead_list.order">
                  <tr md-row>
                    <th md-column><span>#</span></th>
                    <th md-column md-order-by="name"><span><?php echo lang('name'); ?></span></th>
                    <th md-column md-order-by="phone"><span><?php echo lang('phone'); ?></span></th>
                    <th md-column md-order-by="statusname"><span><?php echo lang('status'); ?></span></th>
                    <th md-column md-order-by="sourcename"><span><?php echo lang('source'); ?></span></th>
                    <th md-column md-order-by="staff"><span><?php echo lang('staff'); ?></span></th>
                  </tr>
                </thead>
                <tbody md-body>
                  <tr class="select_row" md-row ng-repeat="lead in leads | orderBy: lead_list.order | limitTo: lead_list.limit : (lead_list.page -1) * lead_list.limit | filter: lead_search | filter: FilteredData" class="cursor" ng-click="goToLink('leads/lead/'+lead.id)">
                    <td md-cell>
                      <strong>
                        <a class="link" ng-href="<?php echo base_url('leads/lead/') ?>{{lead.id}}"> <span ng-bind="lead.lead_number"></span></a>
                      </strong><br>
                    </td>
                    <td md-cell>
                      <strong><span ng-bind="lead.name"></span></strong><br>
                      <small><span class="blur" ng-bind="lead.company"></span></small>
                    </td>
                    <td md-cell>
                      <strong><span ng-bind="lead.phone"></span></strong><br>
                      <small><span class="blur" ng-bind="lead.email"></span></small>
                    </td>
                    <td md-cell>
                      <strong><span class="badge" style="border-color: #fff;background-color: {{lead.color}};" ng-bind="lead.statusname"></span></strong>
                    </td>
                    <td md-cell>
                      <strong><span class="badge" ng-bind="lead.sourcename"></span></strong>
                    </td>
                    <td md-cell>
                      <div data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Assigned: {{lead.assigned}}" class="assigned-staff-for-this-lead user-avatar"><img src="<?php echo base_url('uploads/images/{{lead.avatar}}')?>" alt="{{lead.assigned}}"> </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </md-table-container>
            <md-table-pagination ng-show="leads.length > 0" md-limit="lead_list.limit" md-limit-options="limitOptions" md-page="lead_list.page" md-total="{{leads.length}}"></md-table-pagination>
            <md-content ng-show="!leads.length" class="md-padding no-item-data"><?php echo lang('notdata') ?></md-content>
          </md-content>
        </md-content>
      </div>
    </div>
    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Create" ng-cloak style="width: 450px;">
      <md-toolbar class="toolbar-white">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
          <md-truncate><?php echo lang('create') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content>
        <md-content layout-padding>
          <md-input-container class="md-block">
            <label><?php echo lang('title'); ?></label>
            <input ng-model="lead.title">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('name'); ?></label>
            <md-icon md-svg-src="<?php echo base_url('assets/img/icons/individual.svg') ?>" aria-label="Add Source">
            </md-icon>
            <input required name="name" ng-model="lead.name">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('company'); ?></label>
            <md-icon md-svg-src="<?php echo base_url('assets/img/icons/company.svg') ?>" aria-label="Add Source">
            </md-icon>
            <input ng-model="lead.company">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('assigned'); ?></label>
            <md-select required placeholder="<?php echo lang('choosestaff'); ?>" ng-model="lead.assigned_id" style="min-width: 200px;">
              <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('status'); ?></label>
            <md-select required placeholder="<?php echo lang('status'); ?>" ng-model="lead.status_id" style="min-width: 200px;">
              <md-option ng-value="status.id" ng-repeat="status in leadstatuses">{{status.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('source'); ?></label>
            <md-select required placeholder="<?php echo lang('source'); ?>" ng-model="lead.source_id" style="min-width: 200px;">
              <md-option ng-value="source.id" ng-repeat="source in leadssources">{{source.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('phone'); ?></label>
            <input ng-model="lead.phone">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('email'); ?></label>
            <input type="email" ng-model="lead.email" required minlength="10" maxlength="100" ng-pattern="/^.+@.+\..+$/">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('web'); ?></label>
            <input ng-model="lead.website">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('country'); ?></label>
            <md-select required placeholder="<?php echo lang('country'); ?>" ng-model="lead.country_id" ng-change="getStates(lead.country_id)" name="country_id" style="min-width: 200px;">
              <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('state'); ?></label>
            <md-select placeholder="<?php echo lang('states'); ?>" ng-model="lead.state_id" name="state_id" style="min-width: 200px;">
              <md-option ng-value="state.id" ng-repeat="state in states">{{state.state_name}}</md-option>
            </md-select>
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('city'); ?></label>
            <input ng-model="lead.city">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('zip'); ?></label>
            <input ng-model="lead.zip">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('address') ?></label>
            <textarea ng-model="lead.address" md-maxlength="500" rows="3" md-select-on-focus></textarea>
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('description') ?></label>
            <textarea required ng-model="lead.description" md-maxlength="500" rows="3" md-select-on-focus></textarea>
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('date_contacted') ?></label>
            <input required mdc-datetime-picker="" date="true" time="true" type="text" id="datetime" placeholder="<?php echo lang('chooseadate') ?>" show-todays-date="" minutes="true" show-icon="true" ng-model="lead.date_contacted" class=" dtp-no-msclear dtp-input md-input">
          </md-input-container>
          <md-chips ng-model="tags" md-separator-keys="keys" placeholder="Lead Tags" secondary-placeholder="Seperate with comma."></md-chips> {{tags}}
          <md-input-container class="md-block pull-left">
            <md-checkbox ng-model="lead.public" aria-label="add"><?php echo lang('public') ?></md-checkbox>
          </md-input-container>
          <md-input-container class="md-block pull-left">
            <md-checkbox ng-model="lead.type" aria-label="add"><?php echo lang('individual') ?></md-checkbox>
          </md-input-container>
        </md-content>
        <custom-fields-vertical></custom-fields-vertical>
        <md-content layout-padding>
          <section layout="row" layout-sm="column" layout-align="center center" layout-wrap>
            <md-button ng-click="AddLead()" class="md-raised md-primary btn-report block-button" ng-disabled="saving == true" aria-label="Add">
              <span ng-hide="saving == true"><?php echo lang('create');?></span>
              <md-progress-circular class="white" ng-show="saving == true" md-mode="indeterminate" md-diameter="20">
              </md-progress-circular>
            </md-button>
            <br/><br/><br/><br/>
          </section>
        </md-content>
      </md-content>
    </md-sidenav>
    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="ContentFilter" ng-cloak style="width: 450px;">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"> <i class="ion-android-arrow-forward"></i> </md-button>
          <md-truncate><?php echo lang('filter') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content layout-padding="">
        <div ng-repeat="(prop, ignoredValue) in leads[0]" ng-init="filter[prop]={}" ng-if="prop != 'name' && prop != 'id' && prop != 'company' && prop != 'phone' && prop != 'color' && prop != 'status' && prop != 'source' && prop != 'assigned' && prop != 'avatar' && prop != 'staff' && prop != 'createddate' && prop != 'statusname' && prop != 'sourcename' && prop != 'lead_number' && prop != 'email' && prop != 'tags' && prop != 'date_contacted' && prop != 'tagss' ">
          <div class="filter col-md-12">
            <h4 class="text-muted text-uppercase"><strong>{{prop}}</strong></h4>
            <hr>
            <div class="labelContainer" ng-repeat="opt in getOptionsFor(prop)">
              <md-checkbox id="{{[opt]}}" ng-model="filter[prop][opt]" aria-label="{{opt}}"><span class="text-uppercase">{{opt}}</span></md-checkbox>
            </div>
          </div>
        </div>
      </md-content>
    </md-sidenav>
    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="LeadsSettings" ng-cloak style="width: 450px;">
      <md-toolbar class="toolbar-white" style="background:#262626">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
          <md-truncate><?php echo lang('settings') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content>
        <md-toolbar class="toolbar-white" style="background:#262626">
          <div class="md-toolbar-tools">
            <h4 class="text-bold text-muted" flex><?php echo lang('leadsstatuses') ?></h4>
            <?php if (check_privilege('leads', 'edit')) { ?> 
              <md-button aria-label="Converted Lead Status" class="md-icon-button" ng-click="ConvertedStatus()">
                <md-tooltip md-direction="top"><?php echo lang('converted_lead_status') ?></md-tooltip>
                <md-icon aria-label="Add Source"><i class="mdi mdi-refresh-sync text-success"></i></md-icon>
              </md-button>
            <?php } if (check_privilege('leads', 'create')) { ?> 
              <md-button aria-label="Add Status" class="md-icon-button" ng-click="NewStatus()">
                <md-tooltip md-direction="top"><?php echo lang('addstatus') ?></md-tooltip>
                <md-icon aria-label="Add Source"><i class="ion-plus-round text-success"></i></md-icon>
              </md-button>
            <?php } ?>
          </div>
        </md-toolbar>
        <md-list-item ng-repeat="status in leadstatuses" class="noright" ng-click="EditStatus(status.id,status.name, $event)" aria-label="Edit Status"> <strong ng-bind="status.name"></strong>
          <?php if (check_privilege('leads', 'delete')) { ?> 
            <md-icon ng-click='DeleteLeadStatus($index)' aria-label="Remove Status" class="md-secondary md-hue-3 ion-trash-b">
              <md-tooltip md-direction="top"><?php echo lang('delete') ?></md-tooltip>
            </md-icon>
          <?php } ?>
        </md-list-item>
        <md-toolbar class="toolbar-white" style="background:#262626">
          <div class="md-toolbar-tools">
            <h4 class="text-bold text-muted" flex><?php echo lang('leadssources') ?></h4>
            <?php if (check_privilege('leads', 'create')) { ?> 
              <md-button aria-label="Add Source" class="md-icon-button" ng-click="NewSource()">
                <md-tooltip md-direction="top"><?php echo lang('addsource') ?></md-tooltip>
                <md-icon aria-label="Add Source"><i class="ion-plus-round text-success"></i></md-icon>
              </md-button>
            <?php } ?>
          </div>
        </md-toolbar>
        <md-list-item ng-repeat="source in leadssources" class="noright" ng-click="EditSource(source.id,source.name, $event)" aria-label="Edit Source"> <strong ng-bind="source.name"></strong>
          <?php if (check_privilege('leads', 'delete')) { ?> 
            <md-icon ng-click='DeleteLeadSource($index)' aria-label="Remove Source" class="md-secondary md-hue-3 ion-trash-b">
              <md-tooltip md-direction="top"><?php echo lang('delete') ?></md-tooltip>
            </md-icon>
          <?php } ?>
        </md-list-item>
      </md-content>
    </md-sidenav>
    <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="Import" ng-cloak style="width: 450px;">
      <md-toolbar class="md-theme-light" style="background:#262626">
        <div class="md-toolbar-tools">
          <md-button ng-click="close()" class="md-icon-button" aria-label="Close"><i class="ion-android-arrow-forward"></i></md-button>
          <md-truncate><?php echo lang('importleads') ?></md-truncate>
        </div>
      </md-toolbar>
      <md-content> <?php echo form_open_multipart('leads/import'); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="name"> <?php echo lang('choosecsvfile'); ?> </label>
          <div class="file-upload">
            <div class="file-select">
              <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span>
                <?php echo lang('attachment')?> </div>
                <div class="file-select-name" id="noFile"> <?php echo lang('notchoise')?> </div>
                <input type="file" name="userfile" id="chooseFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
              </div>
            </div>
          </div>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('assigned'); ?></label>
            <md-select placeholder="<?php echo lang('choosestaff'); ?>" name="importassigned" ng-model="importassigned" style="min-width: 200px;" required>
              <md-option ng-value="staff.id" ng-repeat="staff in staff">{{staff.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('status'); ?></label>
            <md-select placeholder="<?php echo lang('status'); ?>" name="importstatus" ng-model="importstatus" style="min-width: 200px;" ng-required>
              <md-option ng-value="status.id" ng-repeat="status in leadstatuses">{{status.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <md-input-container class="md-block">
            <label><?php echo lang('source'); ?></label>
            <md-select placeholder="<?php echo lang('source'); ?>" name="importsource" ng-model="importsource" style="min-width: 200px;" required>
              <md-option ng-value="source.id" ng-repeat="source in leadssources">{{source.name}}</md-option>
            </md-select>
          </md-input-container>
          <br>
          <div class="well well-sm"><?php echo lang('importcustomerinfo'); ?></div>
        </div>
        <div class="modal-footer"> <a href="<?php echo base_url('uploads/samples/leadimport.csv')?>" class="btn btn-success pull-left"><?php echo lang('downloadsample'); ?></a>
          <button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
        </div>
        <?php echo form_close(); ?>
      </md-content>
    </md-sidenav>
    <script type="text/ng-template" id="converted-status-template.html">
      <md-dialog aria-label="options dialog">
        <md-dialog-content layout-padding>
          <h2 class="md-title"><?php echo lang('converted_lead_status'); ?></h2>
          <md-select required ng-model="ConvertedLeadStatus" style="min-width: 200px;" aria-label="AddMember">
            <md-option ng-value="status.id" ng-repeat="status in leadstatuses">{{status.name}}</md-option>
          </md-select>
        </md-dialog-content>
        <md-dialog-actions>
          <span flex></span>
          <md-button ng-click="close()" aria-label="Add"><?php echo lang('cancel') ?>!</md-button>
          <md-button ng-click="MakeConvertedLedStatus()" aria-label="Add"><?php echo lang('update') ?>!</md-button>
        </md-dialog-actions>
      </md-dialog>
    </script>
  </div>
</div>
<script>
  var MSG_TITLE = '<?php echo lang('attention') ?>',
  MSG_REMOVE = '<?php echo lang('converted_lead_remove_msg') ?>',
  MSG_CANCEL = '<?php echo lang('cancel') ?>',
  MSG_OK = '<?php echo lang('yes') ?>'
</script>
<?php include_once( APPPATH . 'views/inc/other_footer.php' ); ?>
<script src="<?php echo base_url('assets/lib/chartjs/dist/Chart.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/ciuis_data_table.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/leads.js'); ?>"></script> 