<?php $rebrand = load_config(); ?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code');?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
<meta name="author" content="">
<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
<title><?php echo lang('loginsystem')?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/ciuis.css'); ?>" type="text/css"/>
<script>var BASE_URL = "<?php echo base_url(); ?>",ACTIVESTAFF = "<?php echo $this->session->userdata('usr_id'); ?>",SHOW_ONLY_ADMIN = "false",CURRENCY = "false",LOCATE_SELECTED = "<?php echo lang('lang_code');?>",UPIMGURL = "<?php echo base_url('uploads/images/'); ?>",IMAGESURL = "<?php echo base_url('assets/img/'); ?>",SETFILEURL = "<?php echo base_url('uploads/ciuis_settings/') ?>",NTFTITLE = "<?php echo lang('notification')?>",EVENTADDEDMSG = "<?php echo lang('eventadded')?>",TODOADDEDMSG = "<?php echo lang('todoadded')?>",TODODONEMSG = "<?php echo lang('tododone')?>",REMINDERREAD = "<?php echo lang('remindermarkasread')?>",INVMARKCACELLED = "<?php echo lang('invoicecancelled')?>",TICKSTATUSCHANGE = "<?php echo lang('ticketstatuschanced')?>",LEADMARKEDAS = "<?php echo lang('leadmarkedas')?>",LEADUNMARKEDAS = "<?php echo lang('leadunmarkedas')?>",TODAYDATE = "<?php echo date('Y.m.d ')?>",LOGGEDINSTAFFID = "<?php echo $this->session->userdata('usr_id'); ?>",LOGGEDINSTAFFNAME = "<?php echo $this->session->userdata('staffname'); ?>",LOGGEDINSTAFFAVATAR = "<?php echo $this->session->userdata('staffavatar'); ?>",VOICENOTIFICATIONLANG = "<?php echo lang('lang_code_dash');?>",initialLocaleCode = "<?php echo lang('initial_locale_code');?>";</script>
</head>
<body class="ciuis-body-splash-screen">
<div class="ciuis-body-wrapper ciuis-body-login"> 
  <div class="ciuis-body-content">
    <div class="col-md-4 login-left hide-xs hide-sm" style="background-image: url(<?php echo base_url('assets/img/images/'.$rebrand['admin_login_image'].''); ?>) !important;">
      <div class="lg-content">
        <h2><?php echo $rebrand['title'] ?></h2>
        <p class="text-muted"><?php echo $rebrand['admin_login_text'] ?></p> 
      </div>
    </div>
    <div class="main-content container-fluid col-md-8 login_page_right_block">
      <div class="splash-container">
        <md-card flex-xs flex-gt-xs="100" layout="column">
          <div class="panel panel-default"> 
            <div class="panel-heading"><img src="<?php echo base_url('uploads/ciuis_settings/'.$rebrand['nav_logo'].''); ?>" alt="logo" class="logo-img nav-logo"> <?php echo $rebrand['title'] ?><span class="splash-description">Please enter Your Envato Purchase Code</span>
            </div>
            <div class="panel-body">
              <?php echo form_open('login/verify_licence',array('name' => 'userForm')) ?>
              <div class="form-group">
                <input id="license" required="" type="text" placeholder="Envato Purchase Code" name="license" autocomplete="off" class="form-control">
                <p><a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" class="link">Click here</a> to read more how to obtain your purchase key</p>
              </div>
              <strong><?php echo '<label class="text-danger bold">' . $this->session->flashdata( "error" ) . '</label>';?></strong>
              <div class="form-group login-submit">
                <button data-dismiss="modal" type="submit" class="login-button btn btn-ciuis btn-xl"><?php echo lang('verify')?></button>
              </div>
            </div>
          </div>
        </md-card>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('assets/lib/jquery/jquery.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/js/Ciuis.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/bootstrap/dist/js/bootstrap.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo base_url('assets/lib/jquery.gritter/js/jquery.gritter.js'); ?>" type="text/javascript"></script> 
<?php if ( $this->session->flashdata('ntf1')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf1'); ?>',
      class_name: 'color success'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf2')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf2'); ?>',
      class_name: 'color primary'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf3')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf3'); ?>',
      class_name: 'color warning'
    } );
  </script>
<?php }?>
<?php if ( $this->session->flashdata('ntf4')) {?>
<script type="text/javascript">
    $.gritter.add( {
      title: '<b><?php echo lang('notification')?></b>',
      text: '<?php echo $this->session->flashdata('ntf4'); ?>',
      class_name: 'color danger'
    } );
  </script>
<?php }?>
</body>
</html>
