<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
include_once APPPATH.'/third_party/script/script_update_config.php';
include_once APPPATH.'/third_party/script/script_update_functions.php';
class Settings extends CIUIS_Controller {

	function index() {
		$data[ 'title' ] = lang( 'settings' );
		if ( $this->session->userdata( 'admin' ) ) {
			$this->load->view( 'settings/index', $data );
		} else {
			redirect( 'panel' );
		}
	}

	function update( $settingname ) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'all' ) ) {
			if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
				if ( isset( $settingname ) ) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$is_demo = $this->Settings_Model->is_demo();
						if (!$is_demo) {
							$config[ 'upload_path' ] = './uploads/ciuis_settings/';
							$config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
							switch ( $_POST[ 'pushState' ] ) {
								case 'true':
								$PushState = 0;
								break;
								case 'false':
								$PushState = 0;
								break;
							}
							switch ( $_POST[ 'voicenotification' ] ) {
								case 'true':
								$VoiceNotification = 1;
								break;
								case 'false':
								$VoiceNotification = 0;
								break;
							}
							$is_mysql = $this->input->post('is_mysql');
							if ($is_mysql == '1' || $is_mysql == 'true') {
								$is_mysql = '1';
							} else {
								$is_mysql = '0';
							}
							$params = array(
								'crm_name' => $this->input->post( 'crm_name' ),
								'company' => $this->input->post( 'company' ),
								'email' => $this->input->post( 'email' ),
								'address' => $this->input->post( 'address' ),
								'city' => $this->input->post( 'city' ),
								'town' => $this->input->post( 'town' ),
								'state_id' => $this->input->post( 'state_id' ),
								'country_id' => $this->input->post( 'country_id' ),
								'zipcode' => $this->input->post( 'zipcode' ),
								'phone' => $this->input->post( 'phone' ),
								'fax' => $this->input->post( 'fax' ),
								'vatnumber' => $this->input->post( 'vatnumber' ),
								'taxoffice' => $this->input->post( 'taxoffice' ),
								'currencyid' => $this->input->post( 'currencyid' ),
								'termtitle' => $this->input->post( 'termtitle' ),
								'termdescription' => $this->input->post( 'termdescription' ),
								'dateformat' => $this->input->post( 'dateformat' ),
								'languageid' => $this->input->post( 'languageid' ),
								'default_timezone' => $this->input->post( 'default_timezone' ),
								'smtphost' => $this->input->post( 'smtphost' ),
								'smtpport' => $this->input->post( 'smtpport' ),
								'emailcharset' => $this->input->post( 'emailcharset' ),
								'smtpusername' => $this->input->post( 'smtpusername' ),
								'sendermail' => $this->input->post( 'sendermail' ),
								'sender_name' => $this->input->post( 'sender_name' ),
								'email_encryption' => $this->input->post( 'email_encryption' ),
								'accepted_files_formats' => $this->input->post( 'accepted_files_formats' ),
								'allowed_ip_adresses' => $this->input->post( 'allowed_ip_adresses' ),
								'pushState' => $PushState,
								'voicenotification' => $VoiceNotification,
								'thousand_separator' => $this->input->post( 'thousand_separator' ),
								'decimal_separator' => $this->input->post( 'decimal_separator' ),
								'currency_position' => $this->input->post( 'currency_position' ),
								'currency_display' => $this->input->post( 'currency_display' ),
								'email_type' => $this->input->post( 'email_type' ),
								'is_mysql' => $is_mysql,
							);
							if ($this->input->post( 'smtppassoword' ) != '********') {
								$params['smtppassoword'] = $this->input->post( 'smtppassoword' );
							}
							$this->Settings_Model->update_settings( $settingname, $params );
							$this->Settings_Model->update_appconfig();
							$datas['success'] = true;
							$datas['message'] = lang('settingsupdated');
							echo json_encode($datas);
						} else {
							$datas['success'] = false;
							$datas['message'] = lang('demo_error');
							echo json_encode($datas);
						}
					}
				}
			} else {
				$datas['success'] = false;
				$datas['message'] = lang('you_dont_have_permission');
				echo json_encode($datas);
			}
		} else {
			$datas['success'] = false;
			$datas['message'] = lang('you_dont_have_permission');
			echo json_encode($datas);
		}
	} 

	function db_backup() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'create' ) ) {
			$version = $this->Settings_Model->get_version_detail();
	        $this->load->helper('file');
	        $this->load->dbutil();
	        $date = date('Y-m-d_H-i-s');
	        $prefs = array('format' => 'zip','ignore'=> array('db_backup', 'versions', 'sessions'), 'filename' => 'DB-backup_' . $date);

	        $backup = $this->dbutil->backup($prefs);
	        if (!write_file('./uploads/backup/DB-backup_' . $date . '.zip', $backup)) {
	            $data['success'] = false;
	            $data['message'] = lang('errormessage');
	        } else {
	        	$persentVersion = $version['versions_name'];
	        	write_file('./uploads/backup/DB-backup_' . $date . '.txt', $persentVersion);
	        	$zip = new ZipArchive();
		        $this->zip->read_file('./uploads/backup/DB-backup_' . $date . '.txt');
		        $this->zip->archive('./uploads/backup/DB-backup_' . $date . '.zip');
		        unlink('./uploads/backup/DB-backup_' . $date . '.txt');
	            $data['success'] = true;
	            $data['message'] = lang('backup'). ' '. lang('createmessage');
	        }
	        $activity = array(
	            'staff_id' => $this->session->userdata( 'usr_id' ),
	            'version' => $version['versions_name'],
	            'created' => date( 'Y-m-d H:i:s' ),
	            'filename' => $prefs['filename']
	        );
	        $this->Settings_Model->db_backup($activity);
	        $staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date_by_timezone(date('Y-m-d H:i:s')),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('created').' '.lang('db').' '.lang('backup') . '' ),
				'staff_id' => $loggedinuserid
			));
	        echo json_encode($data);
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		} 
    }

    function download_backup($file) {	
    	$is_demo = $this->Settings_Model->is_demo();
    	if(!$is_demo){
    		if (is_file('./uploads/backup/' . $file)) {
    			$this->load->helper('file');
    			$this->load->helper('download');
    			$data = file_get_contents('./uploads/backup/' . $file);
    			force_download($file, $data);
    		} else {
    			$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
    			redirect('settings/index');
    		}
    	} else {
    		$this->session->set_flashdata( 'ntf4', "Database cannot be downloaded in Demo Version");
    		redirect('settings/index');
    	}
    }

    function get_backup() {
    	$settings = $this->Settings_Model->get_backup();
    	foreach ($settings as $backup) {
    		$data_setting[] = array(
    			'id' => $backup['id'],
    			'filename' => $backup['filename'],
    			'version' => $backup['version'],
    			'created' => date(get_dateTimeFormat(), strtotime($backup['created']))
    		);
    	}
		echo json_encode($data_setting);
    }

    function remove_backup( $id ) {
    	if ( $this->Privileges_Model->check_privilege( 'settings', 'delete' ) ) {
			if ( isset( $id ) ) {
				$backup = $this->Settings_Model->get_db_backup($id);
				$file = $backup['filename'].'.zip';
				if ($backup['id'] == $id) {
					$response = $this->db->delete( 'db_backup', array( 'id' => $id ) );
					if ( $response ) {
						if (file_exists('./uploads/backup/' . $file)) {
							unlink('./uploads/backup/' . $file);
						}
						$staffname = $this->session->staffname;
						$loggedinuserid = $this->session->usr_id;
						$this->db->insert( 'logs', array(
							'date' => date_by_timezone(date('Y-m-d H:i:s')),
							'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('deleted').' '.lang('db').' '.lang('backup') . '' ),
							'staff_id' => $loggedinuserid
						));
						$data['success'] = true;
						$data['message'] = lang('backup'). ' ' . lang('deletemessage');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = lang('errormessage');
						echo json_encode($data);
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('backup_remove_error');
					echo json_encode($data);
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
    	} else {
    		$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
    	}
	}

	function restore_database() {
    	if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
	        if (isset( $_POST )) {
	        	$is_demo = $this->Settings_Model->is_demo();
				if (!$is_demo) {
		        	ini_set('max_execution_time', 0); 
		        	ini_set('memory_limit','2048M');
		        	$version = $this->Settings_Model->get_version_detail();
		            $this->load->helper('file'); 
		            $this->load->helper('unzip');
		            $this->load->database();

		            $config['upload_path'] = './uploads/temp/';
		            $config['allowed_types'] = '*';
		            $config['max_size'] = '9000';
		            $config['overwrite'] = TRUE;

		            $this->load->library('upload', $config);
		            $this->upload->initialize($config);

		            if (!$this->upload->do_upload('upload_file')) {
		                $error = $this->upload->display_errors('', ' ');
		                $type = 'error';
		                $message = $error;
		                $this->session->set_flashdata( 'ntf4', $message);
		                redirect('settings/index');
		            } else {
		                $data = array('upload_data' => $this->upload->data());
		                $backup = "./uploads/temp/" . $data['upload_data']['file_name'];
		            }
		            if (!unzip($backup, "./uploads/temp/", true, true)) {
		                $type = 'error';
		                $message = lang('backup_restore_error');
		            } else {
		            	$backup = str_replace('.zip', '', $backup);
		            	$userVersion = file_get_contents($backup . ".txt");
		            	if ($userVersion == $version['versions_name']) {
		            		$this->load->dbforge();
			                $file_content = file_get_contents($backup . ".sql");
			                $this->db->query('USE ' . $this->db->database . ';');
			                foreach (explode(";\n", $file_content) as $sql) {
			                    $sql = trim($sql);
			                    if ($sql) {
			                        $this->db->query($sql);
			                    }
			                }
			                $staffname = $this->session->staffname;
							$loggedinuserid = $this->session->usr_id;
			                $this->db->insert( 'logs', array(
								'date' => date_by_timezone(date('Y-m-d H:i:s')),
								'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('restored').' '.lang('db').' '.lang('backup') . '' ),
								'staff_id' => $loggedinuserid
							));
			                $this->session->set_flashdata( 'ntf1', lang('restoresuccess'));
			                unlink($backup . ".sql");
			                unlink($backup . ".txt");
		            		unlink($backup . ".zip");
			                redirect('settings/index');
		            	} else {
		            		unlink($backup . ".sql");
			                unlink($backup . ".txt");
		            		unlink($backup . ".zip");
		            		$this->session->set_flashdata( 'ntf4', lang('version_missmatch_error'));
		            		redirect('settings/index');
		            	}
		            }
		        } else {
		        	$this->session->set_flashdata( 'ntf4', lang('demo_error'));
		        	redirect('settings/index');
		        }
	        }
    	} else {
    		$this->session->set_flashdata( 'ntf3', lang('you_dont_have_permission'));
		    redirect('settings/index');
    	}
	    
    }

    function restore_backup($id) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
	        if ($id) {
	        	$is_demo = $this->Settings_Model->is_demo();
				if (!$is_demo) {
		        	ini_set('max_execution_time', 0); 
		        	ini_set('memory_limit','2048M');
		        	$backupDetails = $this->Settings_Model->get_db_backup($id);
		        	$version = $this->Settings_Model->get_version_detail();
		        	if ($version['versions_name'] == $backupDetails['version']) {
		        		$tables = $this->db->list_tables();
		        		foreach($tables as $tab) {
		        			if($tab == "db_backup" || $tab == "versions" || $tab == "sessions") {
		        				continue;
		        			}
		        			$this->load->dbforge();
		        			$this->dbforge->drop_table($tab,TRUE);
		        		}
		        		$backup =  "./uploads/backup/" . $backupDetails['filename'].'.zip';
			        	if( is_file($backup) ) {
			        		$this->load->helper('file'); 
				            $this->load->helper('unzip');
				            $this->load->database();
				            if (!unzip($backup, "./uploads/backup/", true, true)) {
				                $data['success'] = false;
								$data['message'] = lang('backup_restore_error');
								echo json_encode($data);
				            } else {
				                $this->load->dbforge();
				                $backup = str_replace('.zip', '', $backup);
				                $file_content = file_get_contents($backup . ".sql");
				                $this->db->query('USE ' . $this->db->database . ';');
				                foreach (explode(";\n", $file_content) as $sql) {
				                    $sql = trim($sql);
				                    if ($sql) {
				                        $this->db->query($sql);
				                    }
				                }
				                $staffname = $this->session->staffname;
								$loggedinuserid = $this->session->usr_id;
				                $this->db->insert( 'logs', array(
									'date' => date_by_timezone(date('Y-m-d H:i:s')),
									'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('restored').' '.lang('db').' '.lang('backup') . '' ),
									'staff_id' => $loggedinuserid
								));
				                unlink($backup . ".sql");
				                unlink($backup . ".txt");
				                $this->session->set_flashdata( 'ntf1', lang('restoresuccess'));
				                $data['success'] = true;
								$data['message'] = lang('restoresuccess');
								echo json_encode($data);
				            }
			        	} else {
			        		$data['success'] = false;
							$data['message'] = lang('backup_restore_error');
							echo json_encode($data);
			        	}
		        	} else {
		        		$data['success'] = false;
		        		$data['message'] = lang('version_missmatch_error');
		        		echo json_encode($data);
		        	}
		        } else {
		        	$data['success'] = false;
		        	$data['message'] = lang('demo_error');
		        	echo json_encode($data);
		        }
	        } else {
	        	$data['success'] = false;
	        	$data['message'] = lang('errormessage');
	        	echo json_encode($data);
	        }
		} else {
			$data['success'] = false;
        	$data['message'] = lang('you_dont_have_permission');
        	echo json_encode($data);
		}
    	
    }

    function replace_files() {
    	if (isset( $_POST )) {
    		$this->load->helper('file'); 
            $this->load->helper('unzip');
            $config['upload_path'] = './';
            $config['allowed_types'] = 'zip';
            $config['max_size'] = '9000';
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_file')) {
                $error = $this->upload->display_errors('', ' ');
                $type = 'error';
                $message = $error;
                $this->session->set_flashdata( 'ntf4', $message);
                redirect('settings/index');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $backup = './' . $data['upload_data']['file_name'];
                if (!unzip($backup, './', true, true)) {
	                $type = 'error';
	                $message = lang('backup_restore_error');
	            } else {
	            	unlink($backup);
	            	$this->session->set_flashdata( 'ntf1', lang('upload_success'));
	            	$this->output->delete_cache();
	            	redirect('settings');
	            }
            }
    	}
    }

	function version_details() {
		$settings = $this->Settings_Model->get_version_detail();
		echo json_encode($settings);
	}

	function version_detail() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			$version_notifications_array = ausGetAllVersions();
			$settings = $this->Settings_Model->get_version_detail();
			if ($version_notifications_array['notification_case'] == "notification_operation_ok") {
				$version = $version_notifications_array['notification_data']['product_versions'];
				$list_array = array();
				$list_array_log = array();
				$flag = false;
				foreach (array_reverse($version) as $key => $value) {
					if ($flag) {
						if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
							$value['version_number'];
							$list_array[] = array('version_number' => $value['version_number']);
						}
						break;
					} else {

						if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
							$value['version_number'];
							$list_array[] = array('version_number' => $value['version_number']);
							$flag = true;
						}

						if ($settings['versions_name'] == $value['version_number']) {
							$flag = true;
						}
					}
				}
				$msg = "";
				$updated = "available";
				if (empty($list_array)) {
					$list_array[0] = array('version_number' => $settings['versions_name']);
					$msg = 'Already updated';
					$updated ="";
				}
			} else {
				$msg = 'alreadyupdated';
				$updated = "";
				$list_array[0] = array('version_number' => $settings['versions_name']);
				$list_array_log[0] = '';
			}
			$download_version = ausGetVersion($list_array[0]['version_number']);
			$version_changelog = $download_version['notification_data'];
			if (!$version_changelog) {
				$version_changelog = NULL;
			} else {
				$version_changelog = $download_version['notification_data']['version_changelog'];
			}
			if ($updated == '') {
				$this->db->where('id', '1');
				$this->db->update('versions',array('is_update_available' => 0, 'last_checked' => date('Y-m-d')));
			}
			$version_details  =	array(
				'settings' => $settings,
				'version' => $list_array[0],
				'msg' => $msg,
				'updated' => $updated,
				'version_changelog' => $version_changelog
			);
			echo json_encode($version_details);
		} else {
			$this->session->set_flashdata( 'ntf3', lang('you_dont_have_permission'));
		    redirect('settings/index');
		}
    	
	}

	function download_update() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			// download the update
			$data[ 'title' ] = lang( 'settings' );
			$data['success'] = false;
			$data['message'] = '';
			// Only admin can download the update
			if ($this->session->userdata('admin')) {
				ini_set('max_execution_time', 0);
				ini_set('memory_limit','2048M');
				$download_notifications_array=ausDownloadFile('version_upgrade_file', $_POST['version_number']);
				if ($download_notifications_array['notification_case']=="notification_operation_ok") {
					$download_notifications_array1=ausDownloadFile('version_upgrade_query', $_POST['version_number']);
					if ($download_notifications_array1['notification_case']=="notification_operation_ok") {	
						$data['success'] = true;
						$data['message'] = lang('update_downloaded');
					} else {
						$data['success'] = false;
						$data['message'] = lang('programfiles');
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('programfiles');
	    		}
	    	} else {
	    		$data['success'] = false;
	    		$data['message'] = lang('programfiles');
	    	}
		} else {
			$data['success'] = false;
	    	$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
    }

    function install_update() {
    	if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
	    	if (isset($_POST) && $this->input->post('version_number')) {
				$version = $this->Settings_Model->get_version_detail();
				$this->load->helper('file');
				$this->load->helper('unzip');

				$db_file_name = './Database_package.zip'; // database package file name
				$app_files = './Update_package.zip'; // app files package
				$data['error'] = '';
				$data['message'] = '';
				$data['warning'] = lang('warning');
				if (!unzip($db_file_name, "./", true, true)) { // error if db unzip fails
					$data['success'] = false;
					$data['message'] = lang('install_update_error');
				} else {
					$db_file = './SQLupdate/update.sql';
					$file_content = file_get_contents($db_file); // get db content from file
					$sqls = explode(';', $file_content);
					array_pop($sqls);
					foreach ($sqls as $sql) {
						$sql = trim($sql);
						if ($sql) {
							$this->db->db_debug = FALSE;
							$this->db->query($sql.';');
							$error = $this->db->error();
							if ($error) {
								if(ini_get('allow_url_fopen')) {
									$error_file = APPPATH.'views/app-errors/errors-log.php';
									$fp = fopen($error_file, 'a');
									fwrite($fp, "<br>\nError[Type='OTA Update'][".date("Y-m-d H:i:s")."]: ".$error['message']);
									fclose($fp);
									$data['error'] = $error;
								}
								continue;
							}
						}
					}
					$data['success'] = true;
					$data['message'] = lang('updated_installed');
					if (!unzip($app_files, "./", true, true)) {
						$data['success'] = false;
						$data['message'] = lang('install_update_error');
					} 
					if (is_file($app_files)) {
						unlink($app_files);
					}
					if (is_file($db_file_name)) {
						copy($db_file, "SQLupdate/update.".date("Y-m-d_H-i-s").".sql");
						unlink($db_file);
						unlink($db_file_name);
					}
					$version = $this->Settings_Model->get_version_detail();
					$this->db->where( 'id', '1' );
					$this->db->update('versions',array('versions_name'=>$_POST['version_number'],'last_version'=>$version['versions_name'],'last_updated'=>date('Y-m-d'), 'is_update_available' => 0, 'last_checked' => date('Y-m-d')));
					$this->session->set_flashdata( 'ntf1', lang('softwareversionupdate'));
					$staffname = $this->session->staffname;
					$loggedinuserid = $this->session->usr_id;
					$this->db->insert( 'logs', array( 
						'date' => date_by_timezone(date('Y-m-d H:i:s')),
						'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updatednewversion') . '' ),
						'staff_id' => $loggedinuserid
					));
					$this->output->delete_cache();
				}
				echo json_encode($data);
			}
    	} else {
    		$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
    	}
    }

    function run_sql_file() {
        if (isset( $_POST )) {
        	$is_demo = $this->Settings_Model->is_demo();
			//if (!$is_demo) {
				if (!is_dir('tmp')) {
					mkdir('./tmp', 0777, true);
				}
				$config[ 'upload_path' ] = './tmp';
				$config['allowed_types'] = '*';
				$config['max_size'] = '9000';
				$config['file'] = $_FILES["file"]['name'];
				$this->load->library( 'upload', $config );
				if (!$this->upload->do_upload('file')) {
					$data['success'] = false;
					$data['message'] = $this->upload->display_errors();
				} else {
					$image_data = $this->upload->data();
					if (is_file('./tmp/'.$image_data[ 'file_name' ])) {
						$db_file = './tmp/'.$image_data[ 'file_name' ];
						$file_content = file_get_contents($db_file); // get db content from file
						$sqls = explode(';', $file_content);
						array_pop($sqls);
						foreach ($sqls as $sql) {
							$sql = trim($sql);
							if ($sql) {
								$this->db->db_debug = FALSE;
								$this->db->query($sql.';');
								$error = $this->db->error();
								if ($error) {
									if(ini_get('allow_url_fopen')) {
										$error_file = APPPATH.'views/app-errors/errors-log.php';
										$fp = fopen($error_file, 'a');
										fwrite($fp, "<br>\nError[Type='Manual SQL File Upload'][".date("Y-m-d H:i:s")."]: ".$error['message']);
										fclose($fp);
										$data['error'] = $error;
									}
									continue;
								}
							}
						}
						unlink($db_file);
						$staffname = $this->session->staffname;
						$loggedinuserid = $this->session->usr_id;
						$this->db->insert( 'logs', array(
							'date' => date_by_timezone(date('Y-m-d H:i:s')),
							'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('executed').' '.lang('manual_sql_query') .lang('file'). '' ),
							'staff_id' => $loggedinuserid 
						));
						$data['success'] = true;
						$data['message'] = lang('query_success');
					} else {
						$data['success'] = false;
						$data['message'] = 'File didnot uploaded, please try again';
					}
				}
				echo json_encode($data);
			// } else {
			// 	$data['success'] = false;
	  //       	$data['message'] = lang('demo_error');
	  //       	echo json_encode($data);
			// }
		}
    }

	function sendTestEmail() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			$emailInput = $this->input->post('email');
			if (!empty($emailInput) || $emailInput != '') {
				$settings = $this->Settings_Model->get_settings_ciuis();
				$email = $this->security->xss_clean( $emailInput );
				$this->load->library('mail');
				$body = '<p>This is test SMTP email.</p> <p>If you are receiving this email that means your smtp setting are correct</p>';
				$data = $this->mail->send_email($email, $email, 'SMTP setup testing', $body);
				$param = array(
					'from_name' => $settings['sender_name'],
					'email' => $email,
					'subject' => 'SMTP setup testing',
					'message' => $body,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 0
				);
				if ($data['success'] == true) {
					$return['success'] = true;
					$return['message'] = lang( 'mail_successfully_sent' );
					if ($email) {
						$this->db->insert( 'email_queue', $param );
					}
					echo json_encode($return);
				} else {
					$return['success'] = false;
					$return['message'] = lang( 'wrong_email_settings_msg' );
					echo json_encode($return);
				}
			} else {
				$return['success'] = false;
				$return['message'] = lang('invalidmessage'). ' ' .lang('email');
				echo json_encode($return);
			}
		} else {
			$return['success'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
	}

	function save_config() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			$is_demo = $this->Settings_Model->is_demo();
			if (!$is_demo) {
				$branding = load_config();
				if (isset($_FILES['applogo']) && $_FILES['applogo']['name'] != '') {
					$config[ 'upload_path' ] = './uploads/ciuis_settings/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
					$new_name = preg_replace("/[^a-z0-9\_\-\'\"\.]/i", '', basename($_FILES["applogo"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config );
					$this->upload->do_upload( 'applogo' );
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./uploads/ciuis_settings/'.$branding['app_logo'])) {
							unlink('./uploads/ciuis_settings/'.$branding['app_logo']);
						}
					}
					$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'app_logo' => $image_data[ 'file_name' ] ) );
					$this->db->where('name', 'app_logo')->update('branding', array('value' => $image_data['file_name']));
					$staffname = $this->session->staffname;
					$loggedinuserid = $this->session->usr_id;
					$this->db->insert( 'logs', array(
						'date' => date_by_timezone(date('Y-m-d H:i:s')),
						'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updated').' '.lang('applogo') . '' ),
						'staff_id' => $loggedinuserid 
					));
				}
				if (isset($_FILES['navlogo']) && $_FILES['navlogo']['name'] != '') {
					$config[ 'upload_path' ] = './uploads/ciuis_settings/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["navlogo"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config );
					$this->upload->do_upload( 'navlogo' );
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./uploads/ciuis_settings/'.$branding['nav_logo'])) {
							unlink('./uploads/ciuis_settings/'.$branding['nav_logo']);
						}
					}
					$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'logo' => $image_data[ 'file_name' ] ) );
					$this->db->where('name', 'nav_logo')->update('branding', array('value' => $image_data['file_name']));
					$staffname = $this->session->staffname;
					$loggedinuserid = $this->session->usr_id;
					$this->db->insert( 'logs', array(
						'date' => date_by_timezone(date('Y-m-d H:i:s')),
						'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('updated').' '.lang('navlogo') . '' ),
						'staff_id' => $loggedinuserid 
					));
				}
				if (isset($_FILES['favicon']) && $_FILES['favicon']['name'] != '') {
					$config[ 'upload_path' ] = './assets/img/images/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["favicon"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config );
					$this->upload->do_upload( 'favicon' );
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./uploads/ciuis_settings/'.$branding['favicon_icon'])) {
							unlink('./uploads/ciuis_settings/'.$branding['favicon_icon']);
						}
					}
					$this->db->where(array('name' => 'favicon_icon'));
					$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
				}
				if (isset($_FILES['admin_login_image']) && $_FILES['admin_login_image']['name'] != '') {
					$config[ 'upload_path' ] = './assets/img/images/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["admin_login_image"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config );
					$this->upload->do_upload( 'admin_login_image');
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./uploads/ciuis_settings/'.$branding['admin_login_image'])) {
							unlink('./uploads/ciuis_settings/'.$branding['admin_login_image']);
						}
					}
					$this->db->where(array('name' => 'admin_login_image'));
					$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
				}
				if (isset($_FILES['client_login_image']) && $_FILES['client_login_image']['name'] != '') {
					$config[ 'upload_path' ] = './assets/img/images/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["client_login_image"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config);
					$this->upload->do_upload( 'client_login_image');
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./uploads/ciuis_settings/'.$branding['client_login_image'])) {
							unlink('./uploads/ciuis_settings/'.$branding['client_login_image']);
						}
					}
					$this->db->where(array('name' => 'client_login_image'));
					$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
				}
				if (isset($_FILES['preloader']) && $_FILES['preloader']['name'] != '') {
					$config[ 'upload_path' ] = './assets/img/';
					$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
					$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["preloader"]['name']));
					$config['file_name'] = $new_name;
					$this->load->library( 'upload', $config);
					$this->upload->do_upload( 'preloader');
					$data_upload_files = $this->upload->data();
					$image_data = $this->upload->data();
					if ($image_data['file_name']) {
						if (is_file('./assets/img/'.$branding['preloader'])) {
							if ($branding['preloader'] != 'preloader.gif') {
								unlink('./assets/img/'.$branding['preloader']);
							}
						}
					}
					$this->db->where(array('name' => 'preloader'));
					$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
				}
				$support = '0';
				if ($this->input->post('enable_support_button_on_client') == '1') {
					$support = '1';
				}
				$this->db->where('name', 'meta_keywords')->update('branding', array('value' => $this->input->post('meta_keywords')));
				$this->db->where('name', 'meta_description')->update('branding', array('value' => $this->input->post('meta_description')));
				$this->db->where('name', 'title')->update('branding', array('value' => $this->input->post('title')));
				$this->db->where('name', 'admin_login_text')->update('branding', array('value' => $this->input->post('admin_login_text')));
				$this->db->where('name', 'client_login_text')->update('branding', array('value' => $this->input->post('client_login_text')));
				$this->db->where('name', 'enable_support_button_on_client')->update('branding', array('value' => $support));
				$this->db->where('name', 'support_button_title')->update('branding', array('value' => $this->input->post('support_button_title')));
				$this->db->where('name', 'support_button_link')->update('branding', array('value' => $this->input->post('support_button_link')));
				$this->db->where('name', 'disable_preloader')->update('branding', array('value' => $this->input->post('disable_preloader')));
				$data['success'] = true;
				$data['message'] = lang('settings').' '.lang('updatemessage');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['message'] = lang('demo_error');
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function create_custom_field() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$hasError = false;
				$data['message'] = '';
				if($this->input->post('name') == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('name');
				} else if($this->input->post('type') == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('type');
				} else if($this->input->post('relation') == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage').' '.lang('relation');
				}
				if($hasError){
					$data['success'] = false;
					echo json_encode($data);
				}
				if(!$hasError){
					$params = array(
						'name' => $this->input->post( 'name' ),
						'type' => $this->input->post( 'type' ),
						'order' => $this->input->post( 'order' ),
						'data' => $this->input->post( 'data' ),
						'relation' => $this->input->post( 'relation' ),
						'icon' => $this->input->post( 'icon' ),
						'permission' => $this->input->post( 'permission' ),
						'updated_on' => date('Y-m-d H:i:s'),
					);
					$response = $this->Fields_Model->create_new_field( $params );
					if ( $response ) {
						$data['success'] = true;
						$data['message'] = lang('custom_field_created');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = lang('custom_field_not_created');
						echo json_encode($data);
					}
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update_custom_field( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			if ( isset( $id ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$hasError = false;
					$data['message'] = '';
					if($this->input->post('name') == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('name');
					} else if($this->input->post('type') == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('type');
					} else if($this->input->post('relation') == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage').' '.lang('relation');
					}
					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					}
					if (!$hasError) {
						$params = array(
							'name' => $this->input->post( 'name' ),
							'type' => $this->input->post( 'type' ),
							'order' => $this->input->post( 'order' ),
							'data' => $this->input->post( 'data' ),
							'relation' => $this->input->post( 'relation' ),
							'icon' => $this->input->post( 'icon' ),
							'permission' => $this->input->post( 'permission' ),
							'updated_on' => date('Y-m-d H:i:s'),
						);
						$response = $this->Fields_Model->update_custom_field( $id, $params );
						if ( $response ) {
							$data['success'] = true;
							$data['message'] = lang('custom_field_updated');
							echo json_encode($data);
						} else {
							$data['success'] = true;
							$data['message'] = lang('custom_field_not_updated');
							echo json_encode($data);
						}
					}
				} else {
					echo 'Custom field is not updated';
				}
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
			echo json_encode($data);
		}
	}

	function update_custom_field_status( $id, $value ) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			if ( isset( $id ) ) {
				$this->db->where( 'id', $id );
				$response = $this->db->update( 'custom_fields', array( 'active' => $value ) );
				$date = date('Y-m-d H:i:s');
				$this->db->where( 'id', $id );
				$response = $this->db->update( 'custom_fields', array( 'updated_on' => $date ) );
				$data['success'] = true;
			} else {
				$data['success'] = false;
				$data['message'] = 'Custom field status is not updated';
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function remove_custom_field( $id ) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'delete' ) ){
			if ( isset( $id ) ) {
				$response = $this->db->delete( 'custom_fields', array( 'id' => $id ) );
				if ( $response ) {
					$data['success'] = true;
					$data['message'] = lang('custom_field_removed');
				} else {
					$data['success'] = false;
					$data['message'] = lang('custom_field_not_removed');
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('custom_field_not_removed');
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('you_dont_have_permission');
		}
		echo json_encode($data);
	}

	function execute_mysql_query() {
		if (isset($_POST) && count($_POST) > 0 ) {
			$mysql_query = $this->input->post('mysql_query');
			$hasError = false;
			$data['message'] = '';
			if ($mysql_query == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' '. lang('query');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$this->db->db_debug = FALSE;
				$resp = $this->db->query($mysql_query);
				$staffname = $this->session->staffname;
				$loggedinuserid = $this->session->usr_id;
				$this->db->insert( 'logs', array(
					'date' => date_by_timezone(date('Y-m-d H:i:s')),
					'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('executed').' '.lang('manual_sql_query') . '' ),
					'staff_id' => $loggedinuserid 
				));
				$data['success'] = true;
				$data['message'] = lang('query_success');
				$return_data = '';
				$data['result'] = '';
				$data['info_message'] = '';
				if (is_array($resp) || ($resp instanceof Traversable) || $resp != 'true' || $resp != 'false') {
					$data['success'] = 'info';
					$data['result'] = $resp->result_array();
					$data['info_message'] = 'You can find your query result in Console';
					$return_data = $resp->result_array();
				} else {
					$data['success'] = true;
					$return_data = '';
				}
				$data['query_response'] = $resp;
				echo json_encode($data);
			}
		}
	}

	function get_smtp_password() {
		if (isset($_POST) && count($_POST) > 0 ) {
			$is_demo = $this->Settings_Model->is_demo();
			if (!$is_demo) {
				$password = $this->input->post('password');
				$hasError = false;
				$data['message'] = '';
				if ($password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' '. lang('password');
				} 
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$this->db->from( 'staff' );
					$this->db->where( 'email', $this->session->userdata('email') );
					$this->db->where( 'password', md5( $password ) );
					$login = $this->db->get()->result();
					if ( is_array( $login ) && count( $login ) == 1 ) {
						$settings = $this->Settings_Model->get_settings_ciuis_origin();
						$data['password'] = $settings['smtppassoword'];
						$data['success'] = true;
						echo json_encode($data);
					} else {
						$data['message'] = lang('incorrect_password');
						$data['success'] = false;
						echo json_encode($data);
					}
				}
			} else {
				$data['message'] = 'You can not see smtp password of demo version';
				$data['success'] = false;
				echo json_encode($data);
			}
		}
	}

	function check_for_update() {
		$version = $this->Settings_Model->get_version_detail();
		$value = 2;
		if ((strtotime($version['last_checked'].' +'.$value.'week' ) < strtotime(date('Y-m-d'))) && $version['is_update_available'] == 0) {
			$check = $this->version_detail_of_app();
			if ($check === true) {
				$this->db->where('id', '1');
				$this->db->update('versions',array('is_update_available'=>1,'last_checked'=>date('Y-m-d')));
			} else {
				$this->db->where('id', '1');
				$this->db->update('versions',array('is_update_available'=>0,'last_checked'=>date('Y-m-d')));
			}
		}
	}

	function version_detail_of_app() {
		$version_notifications_array = ausGetAllVersions();
		$settings = $this->Settings_Model->get_version_detail();
		if ($version_notifications_array['notification_case'] == "notification_operation_ok") {
			$version = $version_notifications_array['notification_data']['product_versions'];
			$list_array = array();
			$list_array_log = array();
			$flag = false;
			foreach (array_reverse($version) as $key => $value) {
				if ($flag) {
					if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
						$value['version_number'];
						$list_array[] = array('version_number' => $value['version_number']);
					}
					break;
				} else {
					if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
						$value['version_number'];
						$list_array[] = array('version_number' => $value['version_number']);
						$flag = true;
					}
					if ($settings['versions_name'] == $value['version_number']) {
						$flag = true;
					}
				}
			}
			$msg = "";
			$updated = "available";
			if (empty($list_array)) {
				$list_array[0] = array('version_number' => $settings['versions_name']);
				$msg = 'Already updated';
				$updated ="";
			}
		} else {
			$msg = 'alreadyupdated';
			$updated = "";
			$list_array[0] = array('version_number' => $settings['versions_name']);
			$list_array_log[0] = '';
		}
		if ($updated === "available") {
			return true;
		} else {
			return false;
		}
	}

	function uninstall() {
		$is_demo = $this->Settings_Model->is_demo();
		if (!$is_demo) {
			if (isset($_POST) && count($_POST) > 0 ) {
				if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
					$hasError = false;
					if ($this->input->post('confirm') != '1') {
						$hasError = true;
						$data['message'] = lang('uninstall_note');
					} 
					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					}
					if (!$hasError) {
						include_once(APPPATH . 'third_party/script/app_configuration.php');
						include_once(APPPATH . 'third_party/script/app_functions.php');
						$check_data = remote_check();
						if ($check_data['notification_case'] = 'remote_check_done') {
							$handle=@fopen(APL_DIRECTORY."/".APL_LICENSE_FILE_LOCATION, "w+");
							@fclose($handle);
							$data['success'] = true;
							$data['message'] = lang('removed_lics');
							unsetSession();
						} else {
							$handle=@fopen(APL_DIRECTORY."/".APL_LICENSE_FILE_LOCATION, "w+");
							@fclose($handle);
							$data['success'] = false;
							$data['message'] = $check_data['notification_text'];
						}
						echo json_encode($data);
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('you_dont_have_permission');
				}
			} else {
				echo 'Error';
			}
		} else {
			$data['success'] = false;
			$data['message'] = 'You can not uninstall license in demo version';
			echo json_encode($data);
		}
	}

	function get_modules() {
		$module = $this->db->get_where('modules', array())->row_array();
		// $modules_data = array();
		// foreach ($modules as $module) {
		// 	$modules_data[] = array(
		// 		'name' => $module['name'],
		// 		'module_status' => ($module['status'] == '1')?true:false,
		// 		'module_updated' => $module['updatedat']
		// 	);
		// }
		$modules_data = array(
			'name' => $module['name'],
			'hr_status' => ($module['status'] == '1')?true:false,
			'module_updated' => $module['updatedat'],
			'module_license' => $module['license']
		);
		echo json_encode($modules_data);
	}

	/**********Create New Role************/
	function create_role() {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'create' ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$role = $this->input->post('role');
				$hasError = false;
				if ($role == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('role');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$params = array(
						'role_name' => $role,
						'role_type' => $this->input->post('type'),
						'role_createdat' => date('Y-m-d_H-i-s'),
						'role_updatedat' => date('Y-m-d_H-i-s'),
						'created_by' => $this->session->usr_id
					);
					$this->Settings_Model->create_role($params);
					$data['message'] = lang('role').' '.lang('createmessage');
					$data['success'] = true;
					echo json_encode($data);
				}
			}
		} else {
			$data['message'] = lang('you_dont_have_permission');
			$data['success'] = false;
			echo json_encode($data);
		}
	}

	/**********Update Role************/
	function update_role($id) {
		if(($id == '1' || $id == '2' || $id == '3') && ($this->Settings_Model->is_demo())) {
			$data['message'] = 'Role can not be changed in Demo mode.';
			$data['success'] = false;
			echo json_encode($data);
		} else {
			if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
				$roles = $this->Settings_Model->get_role($id);
				if($roles) {
					if ( isset( $_POST ) && count( $_POST ) > 0 ) {
						$role = $this->input->post('role');
						$hasError = false;
						if ($role == '') {
							$hasError = true;
							$data['message'] = lang('invalidmessage'). ' ' .lang('role');
						}
						if ($hasError) {
							$data['success'] = false;
							echo json_encode($data);
						}
						if (!$hasError) {
							$params = array(
								'role_name' => $role,
								'role_type' => $this->input->post('type'),
								'role_updatedat' => date('Y-m-d_H-i-s'),
							);
							$this->Settings_Model->update_role($params, $id);
							$data['message'] = lang('role').' '.lang('updatemessage');
							$data['success'] = true;
							echo json_encode($data);
						}
					}
				} else {
					$data['message'] = lang('role').' '.lang('does_not_exist');
					$data['success'] = false;
					echo json_encode($data);
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		}
	}

	/**********Delete Role************/
	function delete_role( $id ) {
		if(($id == '1' || $id == '2' || $id == '3') && ($this->Settings_Model->is_demo())) {
			$data['message'] = 'Role can not be deleted in Demo mode.';
			$data['success'] = false;
			echo json_encode($data);
		} else {
			if ( $this->Privileges_Model->check_privilege( 'settings', 'delete' ) ) {
				$role = $this->Settings_Model->get_role($id);
				if($role) {
					if ($this->Settings_Model->check_role($id) == 0) {
						$this->Settings_Model->delete_role( $id );
						$data['success'] = true;
						$data['message'] = lang('role').' '.lang('deletemessage');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = $data['message'] = lang('role').' '.lang('is_linked').' '.lang('with').' '.lang('staff').', '.lang('so').' '.lang('cannot_delete').' '.lang('role');
						echo json_encode($data);
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('role').' '.lang('does_not_exist');
					echo json_encode($data);
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('you_dont_have_permission');
				echo json_encode($data);
			}
		}
	}

	/**********Get Permissions************/
	function get_permission($type='', $permission_all='') {
		$permissions = $this->Privileges_Model->get_all_permissions();
		$permissions_data = array();
		foreach ($permissions as $permission) {
			if( $type == 'staff' && $permission['key'] == 'settings' ) {
				continue;
			} 
			if ( $type == 'other' && $permission['key'] != 'invoices' && $permission['key'] != 'expenses') {
				continue;
			}
			$permissions_datas = array(
				'id' => $permission['id'],
				'key' => $permission['key'],
				'permission_key' => lang($permission['permission']),
				'permission_view_own' => false,
				'permission_view_all' => false,
				'permission_create' => false,
				'permission_edit' => false,
				'permission_delete' => false,
			);
			if($permission_all == '1') {
				if( $type == 'staff' && $permission['key'] == 'settings' ) {
					$permissions_datas = array(
						'id' => $permission['id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => false,
						'permission_view_all' => false,
						'permission_create' => false,
						'permission_edit' => false,
						'permission_delete' => false,
					);	
				} else if( $type == 'staff' && $permission['key'] == 'staff' ) {
					$permissions_datas = array(
						'id' => $permission['id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => false,
						'permission_view_all' => true,
						'permission_create' => false,
						'permission_edit' => false,
						'permission_delete' => false,
					);	
				} else if ( $type == 'other' && ($permission['key'] == 'invoices' || $permission['key'] == 'expenses')) {
					$permissions_datas = array(
						'id' => $permission['id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => false,
						'permission_view_all' => true,
						'permission_create' => false,
						'permission_edit' => false,
						'permission_delete' => false,
					);	
				} else {
					$permissions_datas = array(
						'id' => $permission['id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => true,
						'permission_view_all' => true,
						'permission_create' => true,
						'permission_edit' => true,
						'permission_delete' => true,
					);	
				}
			} 
			$permissions_data[] = $permissions_datas;
			
		}
		echo json_encode( $permissions_data );
	}

	/**********Get all Role************/
	function get_roles() {
		$roles = $this->Settings_Model->get_all_roles();
		$data_role = array();
		foreach($roles as $role) {
			if($role['role_type'] == 'admin') {
				$type = lang('admin');
			} else if ( $role['role_type'] == 'staff') {
				$type = lang('staff');
			} else {
				$type = lang('other');
			}
			$data_role[] = array(
				'role_id' => $role['role_id'],
				'role_name' => $role['role_name'],
				'role_type' => $type,
				'user_type' => $role['role_type'],
				'updated_at' => date(get_dateTimeFormat(), strtotime($role['role_updatedat'])),
			);
		}
		echo json_encode($data_role);
	}
 
	/**********Get Role By Roleid************/
	function get_role($id) {
		$role = $this->Settings_Model->get_role($id);
		$permissions_data = array();
		$role_data = array();
		if($role){
			$permissions = $this->Privileges_Model->get_all_permissions();
			foreach ($permissions as $permission) {
				if( $role['role_type'] == 'staff' && $permission['key'] == 'settings' ) {
					continue;
				} 
				if ( $role['role_type'] == 'other' && $permission['key'] != 'invoices' && $permission['key'] != 'expenses') {
					continue;
				}
				$roles = $this->Settings_Model->get_role_permission($permission['id'], $id);
				if ($roles) {
					$permissions_data[] = array(
						'id' => $permission['id'],
						'role_permission_id' => $roles['role_permission_id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => $roles['permission_view_own'] == '1' ? true : false,
						'permission_view_all' => $roles['permission_view_all'] == '1' ? true : false,
						'permission_create' => $roles['permission_create'] == '1' ? true : false,
						'permission_edit' => $roles['permission_edit'] == '1' ? true : false,
						'permission_delete' => $roles['permission_delete'] == '1' ? true : false,
					);
					$role_data = array(
						'permissions_data' => $permissions_data,
						'role_name' => $role['role_name'],
						'role_type' => $role['role_type'],
					);
				} else {
					$permissions_data[] = array(
						'id' => $permission['id'],
						'role_permission_id' => $roles['role_permission_id'],
						'key' => $permission['key'],
						'permission_key' => lang($permission['permission']),
						'permission_view_own' => false,
						'permission_view_all' => false,
						'permission_create' => false,
						'permission_edit' => false,
						'permission_delete' => false,
					);
					$role_data = array(
						'permissions_data' => $permissions_data,
						'role_name' => $role['role_name'],
						'role_type' => $role['role_type'],
					);
				}
			}
		}
		echo json_encode($role_data);
	}		

	function get_payment_methods() {
		$methods = $this->db->get_where('payment_methods', array())->result_array();
		$methods_data = array();
		foreach ($methods as $method) {
			$methods_data[] = array(
				'id' => $method['id'],
				'input_label1' => lang($method['input_label1'])?lang($method['input_label1']):$method['input_label1'],
				'input_label2' => lang($method['input_label2'])?lang($method['input_label2']):$method['input_label2'],
				'input_label3' => $method['input_label3']?lang($method['input_label3']):null,
				'input_value1' => $method['input_value1'],
				'input_value2' => $method['input_value2'],
				'input_value3' => $method['input_value3']?$method['input_value3']:null,
				'active' => $method['active']=='1'?true:false,
				'sandbox_account' => $method['sandbox_account']=='1'?true:false,
				'payment_record_account' => $method['payment_record_account'],
				'relation' => $method['relation'],
				'gateway_relation' => lang($method['relation'])?lang($method['relation']):$method['relation'],
				'name' => $method['name'],
				'image' => $method['image'],
				'gateway_note' => $method['gateway_note'],
				'updated_at' => date(get_dateTimeFormat(), strtotime($method['updated_at'])),
			);
		}
		echo json_encode($methods_data);
	}

	function update_payment_gateway($payment) {
		if ( $this->Privileges_Model->check_privilege( 'settings', 'edit' ) ) {
			if (isset($payment) && isAdmin()) {
				if (isset($_POST) && count($_POST) > 0 ) {
					$payment_mode = $this->Settings_Model->payment_mode($payment);
					$type = $payment;
					$input_value1 = $this->input->post('input_value1');
					$input_value2 = $this->input->post('input_value2');
					$input_value3 = $this->input->post('input_value3');
					$active = $this->input->post('active');
					$sandbox_account = $this->input->post('sandbox_account');
					$payment_record_account = $this->input->post('payment_record_account');
					$hasError = false;
					if ($active == '1') {
						if ($input_value1 == '' && !empty($payment_mode['input_label1'])) {
							$hasError = true;
							$return['message'] = lang('required_message').' '.lang($payment_mode['input_label1']);
						} else if ($input_value2 == '' && !empty($payment_mode['input_label2'])) {
							$hasError = true;
							$return['message'] = lang('required_message').' '.lang($payment_mode['input_label2']);
						} else if ($input_value3 == '' && !empty($payment_mode['input_label3'])) {
							$hasError = true;
							$return['message'] = lang('required_message').' '.lang($payment_mode['input_label3']);
						} else if ($payment_record_account == '') {
							$hasError = true;
							$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang($type);
						}
					}
					if ($hasError) {
						$return['success'] = false;
						echo json_encode($return);
					}
					if (!$hasError) {
						$params = array(
							'input_value1' => $this->input->post('input_value1'),
							'input_value2' => $this->input->post('input_value2'),
							'input_value3' => $this->input->post('input_value3'),
							'active' => $this->input->post('active'),
							'sandbox_account' => $this->input->post('sandbox_account'),
							'payment_record_account' => $this->input->post('payment_record_account'),
							'updated_at' => date( "Y.m.d H:i:s" ),
						);
						$this->db->where('relation', $payment)->update('payment_methods', $params);
						$return['success'] = true;
						$return['message'] = lang('payment_gateway').' '.lang('updatemessage');
						echo json_encode($return);
					}
				} else {
					$return['message'] = lang('errormessage');
					$return['success'] = false;
					echo json_encode($return);
				}
			} else {
				$return['message'] = lang('errormessage');
				$return['success'] = false;
				echo json_encode($return);
			}
		} else {
			$return['success'] = false;
			$return['message'] = lang('you_dont_have_permission');
			echo json_encode($return);
		}
	}
}
