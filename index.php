<?php

	/**
	* Plugin Name: PhotoID Document Verification
	* Description: You can position this API anywhere on your website or inside a form and customise the look and feel to suit the style of your website.n Australia the Document Verification API will allow you to identify an individual though the submission of Driving Licence/Passport.
	* Author: Identity Verification Services
	* Version: 1.0
	* Author URI: https://profiles.wordpress.org/identity-verification-services
	*/

	
	// Plugin Activation

	register_activation_hook( __FILE__,'piv_activation');
	function piv_activation(){
		global $wpdb;
		$piv_table=$wpdb->prefix."piv_configurations";
		if($wpdb->get_var("SHOW TABLES LIKE '$piv_table'") != $piv_table) {
			$sql="CREATE TABLE $piv_table(
					configuration_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
					client_id varchar(150),
					client_secret varchar(150),
					redirect_url varchar(100),
					error_url varchar(100)
				);";
		 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		}
	}


	// Plugin Deactivation

	register_deactivation_hook( __FILE__,'piv_deactivation');
	function piv_deactivation(){
		global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."piv_configurations");
	}


	// Admin Menu 

	add_action("admin_menu","piv_admin_menu");

	function piv_admin_menu(){
		add_menu_page("Photo ID Verification","Photo ID Verification","manage_options","photo_id_verification","photo_id_configuration_form");
	}


	// Configuration Form

	function photo_id_configuration_form(){
		global $wpdb;
		if($_POST){
			
			$configuration=$wpdb->get_results("select * from ".$wpdb->prefix ."piv_configurations");
			if(count($configuration)>0)
				$wpdb->query("update ".$wpdb->prefix."piv_configurations set client_id='".$_POST['client_id']."',client_secret='".$_POST['client_secret']."',redirect_url='".$_POST['redirect_url']."',error_url='".$_POST['error_url']."' where configuration_id=".$configuration[0]->configuration_id);
			else	
			$wpdb->insert($wpdb->prefix ."piv_configurations",$_POST);
		}
		$configuration=$wpdb->get_results("select * from ".$wpdb->prefix."piv_configurations");
		include("configuration_form.php");
	}


	// Form for PHOTO ID Verification

	function piv_verification_form(){
		global $wpdb;
		$configuration=$wpdb->get_results("select * from ".$wpdb->prefix ."piv_configurations");
		include("verification_form.php");

	}

	add_shortcode("IVS_PHOTOID_VERIFICATION","piv_verification_form");

	// Loading Styles

	add_action("wp_enqueue_scripts","piv_styles");
	add_action("admin_enqueue_scripts","piv_styles");


	function piv_styles(){

		wp_register_style("piv_styles",plugins_url("piv_styles/piv_styles.css", __FILE__));
		wp_enqueue_style("piv_styles");
	}


	// Loading Scripts

	add_action("wp_enqueue_scripts","piv_scripts");

	function piv_scripts(){
		wp_enqueue_script("jquery");
		?>
<script>
	var site_url='<?php echo site_url()?>'
</script/>
	<?php
		wp_register_script("piv_scripts",plugins_url("piv_scripts/validation.js", __FILE__));
		wp_enqueue_script("piv_scripts");
	}



	// Ajax Calls for API Calls
	add_action("wp_ajax_photo_id_verify", "piv_photo_id_document_verification");
	add_action("wp_ajax_nopriv_photo_id_verify", "piv_photo_id_document_verification");

	function piv_photo_id_document_verification(){

		global $wpdb;
		$url='https://api.identityverification.com/get_verified/get_auth_token/';
		$configuration=$wpdb->get_results("select * from ".$wpdb->prefix ."piv_configurations");
		$config_auth['client_id']=$configuration[0]->client_id;
		$config_auth['client_secret']=$configuration[0]->client_secret;
		
		$auth_token_result=piv_sendPostData_api($url,json_encode($config_auth));


		// PHOTO ID Document Verification 
		$config_details['auth_token']=$auth_token_result->auth_token;
		
		if($_POST['region']!='')
			$config_details['region']=$_POST['region'];


		if($_POST['photoid_url']!=''){
			$config_details['photoid_url']=$_POST['photoid_url'];

		}else{
			 $upload_dir = wp_upload_dir();
			// echo "<pre>";print_r($upload_dir);
			 $image=str_replace(" ","_",$_FILES['photo']['name']);
			copy($_FILES['photo']['tmp_name'],$upload_dir['basedir']."/".$image);
			$config_details['photoid_url']=$upload_dir['baseurl']."/".$image;
		}
		$config_details['identity_type']=$_POST['identity_type'];

		 $photoid_verification_url='https://api.identityverification.com/get_verified/photo_id';
		 
		$response=piv_sendPostData_api($photoid_verification_url,json_encode($config_details));
		
		 $result=json_encode($response);
		// echo "<pre>";print_r($response);
		//exit;
		$redirect_url=$api_credentials[0]->redirect_url;
		$error_url=$api_credentials[0]->error_url;
		include("thankyou.php");
		exit;
	}


	function piv_sendPostData_api($url, $post){
		  $ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		  $resulty = curl_exec($ch);
		  curl_close($ch);  // Seems like good practice
		  return json_decode($resulty);
	}



?>