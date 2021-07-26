<?php
require '../Config.php'; 

//sumit form data
if($_SERVER['REQUEST_METHOD'] == "POST") {

	$validate = $Validation->validate([
	    'hostname'   => $_POST['hostname'],
	    'username'   => $_POST['username'],
	    'password'   => $_POST['password'],
	    'database'   => $_POST['database'],   
	    'htaccess'   => $_POST['htaccess'],  
	    'csrf_token' => $_SESSION['csrf_token'],
	]); 

	if (($validate) === true 
		&& file_exists(SQL_FILE_PATH) && file_exists(DB_FILE_PATH)) {
		// it is used to create codeigniter database.php
		$data = [
			'templatePath' => CI_DATABASE_TEMPLATE,
			'outputPath'   => CI_DATABASE_OUTPUT,
			'hostname'  => $_POST['hostname'],
			'username'  => $_POST['username'],
			'password'  => $_POST['password'],
			'database'  => $_POST['database'] 
		];
		$Write->createDatabaseFile($data);

		//$_SESSION['sql_data'] = $data;

		//initializing $_SESSION['dummyData'] to NULL
		$_SESSION['dummyData'] = NULL;

		//Checking if the check-box is ticked. If ticked, set $_SESSION['dummyData'] to 1
		if (isset($_POST['isDummyData']) && ($_POST['isDummyData'])==1){
			$_SESSION['dummyData'] = 1;
		}

		// it is use to create codeigniter config.php and base_url
		if (isset($_POST['isHtaccess']) && ($_POST['isHtaccess'])==1)
		$Write->createHtaccess($_POST['htaccess']);

		// it is use to create codeigniter config.php and base_url
		$Write->createCodeigniterConfigFile(CI_CONFIG_OUTPUT);

		//create database & tables
		$data = [
			'hostname'  => $_POST['hostname'],
			'username'  => $_POST['username'],
			'password'  => $_POST['password'],
			'database'  => $_POST['database']   
		];
		
		$DB->createDatabase($data);
		$DB->createTables($data); 

        $data['status']  = true;
        $data['success'] = "Success!";
 
	} else { 
		$errors  = "";
		$errors .= "<ul>";
		if(!empty($validate))
		foreach ($validate as $error) {
		    $errors .= "<li>$error</li>";
		}
		$errors .= "</ul>";
		$data['status'] = false;
		$data['exception']  = $errors;	
	}
    echo json_encode($data);
}
