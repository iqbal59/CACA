<?php include_once( APPPATH . 'views/inc/ciuis_data_table_header.php' ); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Settings_Controller">
    <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
        <md-toolbar class="toolbar-white">
            <div class="md-toolbar-tools">
                <md-button class="md-icon-button" aria-label="Settings" ng-disabled="true">
                    <md-icon><i class="ion-ios-gear text-muted"></i></md-icon>
                </md-button>
                <h2 flex md-truncate><?php echo lang('crmsettings') ?></h2>
                <?php if (check_privilege('settings', 'edit')) { ?> 
                    <md-button ng-click="VersionCheck()" class="md-icon-button" aria-label="Update" ng-cloak>
                        <md-tooltip md-direction="bottom"><?php echo lang('version_check') ?></md-tooltip>
                        <md-icon><i class="ion-ios-cloud-download text-muted"></i></md-icon>
                    </md-button>
                    <md-button ng-click="UpdateSettings()" class="md-icon-button" aria-label="Save" ng-cloak>
                        <md-progress-circular ng-show="savingSettings == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
                        <md-tooltip ng-hide="savingSettings == true" md-direction="bottom"><?php echo lang('update') ?></md-tooltip>
                        <md-icon ng-hide="savingSettings == true"><i class="ion-checkmark-circled text-muted"></i></md-icon>
                    </md-button>
                <?php } ?>
            </div>
        </md-toolbar>
        <md-content class="bg-white">
            <div ng-show="settings.loader" layout-align="center center" class="text-center" id="circular_loader">
                <md-progress-circular md-mode="indeterminate" md-diameter="40"></md-progress-circular>
                <p style="font-size: 15px;margin-bottom: 5%;">
                    <span>
                        <?php echo lang('please_wait') ?> <br>
                        <small><strong><?php echo lang('loading'). ' '. lang('settings').'...' ?></strong></small>
                    </span>
                </p>
            </div>
            <md-tabs ng-show="!settings.loader" md-dynamic-height md-border-bottom>
                <md-tab label="<?php echo lang('companysettings'); ?>">
                    <?php include_once( APPPATH . 'views/settings/company_settings.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('financialsettings'); ?>">
                    <?php include_once( APPPATH . 'views/settings/financial_settings.php' ); ?>
                </md-tab>
                <md-tab ng-click="get_roles()" label="<?php echo lang('roles'); ?>">
                    <?php include_once( APPPATH . 'views/settings/roles.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('series');?>">
                   <?php include_once( APPPATH . 'views/settings/series.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('localization'); ?>">
                   <?php include_once( APPPATH . 'views/settings/localization.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('emailsettings'); ?>">
                   <?php include_once( APPPATH . 'views/settings/email_settings.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('customization'); ?>">
                    <?php include_once( APPPATH . 'views/settings/customization.php' ); ?>
                </md-tab>
                <md-tab ng-click="get_custom_fields()" label="<?php echo lang('custom_fields');?>">
                   <?php include_once( APPPATH . 'views/settings/custom_fields.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('security'); ?>">
                    <?php include_once( APPPATH . 'views/settings/security.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('paymentgateway'); ?>" ng-click="get_payment_methods()">
                    <?php include_once( APPPATH . 'views/settings/payment_gateway.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('cron_job'); ?>">
                    <?php include_once( APPPATH . 'views/settings/cron_job.php' ); ?>
                </md-tab>
                <md-tab ng-click = "get_database_backup();" label="<?php echo lang('backup'); ?>">
                    <?php include_once( APPPATH . 'views/settings/backup.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('modules'); ?>">
                <?php include_once( APPPATH . 'views/settings/modules.php' ); ?>
                </md-tab>
                <md-tab label="<?php echo lang('system'); ?>">
                    <?php include_once( APPPATH . 'views/settings/system.php' ); ?>
                </md-tab>
            </md-tabs>
        </md-content>
        <?php include_once( APPPATH . 'views/settings/sidenaves.php' ); ?>
    </div>
    <?php include_once( APPPATH . 'views/settings/dialogs.php' ); ?>
</div>

<?php include_once( APPPATH . 'views/inc/other_footer.php' ); ?>
<script src="<?php echo base_url('assets/js/ciuis_data_table.js'); ?>"></script>
<script type="text/javascript">
    var lang = {};
    lang.doIt = '<?php echo lang('doIt')?>';
    lang.cancel = '<?php echo lang('cancel')?>';
    lang.attention = '<?php echo lang('attention')?>';
    lang.delete_role_meesage = "<?php echo lang('delete_meesage').''.lang('role').'.'?>";
    lang.invoice = "<?php echo lang('invoice')?>";
    lang.proposal = "<?php echo lang('proposal')?>";
    lang.customer = "<?php echo lang('customer')?>";
    lang.task = "<?php echo lang('task')?>";
    lang.project = "<?php echo lang('project')?>";
    lang.ticket = "<?php echo lang('ticket')?>";
    lang.expense = "<?php echo lang('expense')?>";
    lang.product = "<?php echo lang('product')?>";
    lang.lead = "<?php echo lang('lead')?>";
    lang.input = "<?php echo lang('input')?>";
    lang.datepicker = "<?php echo lang('datepicker')?>";
    lang.textarea = "<?php echo lang('textarea')?>";
    lang.select = "<?php echo lang('select')?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/settings.js') ?>"></script>