<?php

if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))

	exit("Do not access this file directly.");
	global $wpdb;
    $arrayDetails=$_POST;
    $encode_transaction= json_encode($arrayDetails);
    $user_id= $arrayDetails['option_selection1'];
	$query="update company set transaction='".$encode_transaction."' where user_id='".$user_id."' ";
	$row_update = mysql_query($query);
   sleep(10);
   if (is_user_logged_in()) {
  
			global $current_user;
  
			get_currentuserinfo();
		    global $functions_ph;
			
			if($arrayDetails['item_number']=='1')
			{
			 $memberShipType="Base Membership";	
			}elseif($arrayDetails['item_number']=='2')
			{
				 $memberShipType="Premium Membership";	
			}
						
			$email_from = get_option('admin_email');
			
			$email_to = $current_user->user_email;
			// $email_to = 'jc.hu920@gmail.com';
			
			$email_title = "Thank you! Your account has been updated";
			$email_content = "<p>Thank you! You've been updated to:</p>
                              <p>".$memberShipType." to IT Locator </p>

                              <p>Please log back in now.</p>
                              <a href='http://www.itlocator.com/wp-login.php?loginas=user'>Click Here</a>";			
			
			if($arrayDetails){
			$functions_ph->send_mail($email_to, $email_from, $email_title, $email_content);	
			}
			
unset($arrayDetails);
			

}
/*

%%doctype_html_head%%

<!-- Note. The DOCTYPE and HEAD Replacement Code can be removed if you would rather build your own. -->

<!-- Note. It is OK to use PHP code inside this template file (when/if needed). -->

	<body class="s2member-return-body s2member-default-return-body">



		<!-- Header Section (contains information and possible custom code from the originating site/domain). -->

		<div id="s2member-default-return-header-section" class="s2member-return-section s2member-return-header-section s2member-default-return-header-section">

			<div id="s2member-default-return-header-div" class="s2member-return-div s2member-return-header-div s2member-default-return-header-div">

				%%header%% <!-- (this is auto-filled by s2Member, based on configuration). -->

			</div>

			<div style="clear:both;"></div>

		</div>



		<!-- Response Section (this is auto-filled by s2Member, based on what action has taken place). -->

		<!-- Although NOT recommended, you can remove the response Replacement Code and build your own message if you prefer. -->

		<!-- It is NOT recommended, because the dynamic response message may vary, depending on what action has taken place. -->

		<div id="s2member-default-return-response-section" class="s2member-return-section s2member-return-response-section s2member-default-return-response-section">

			<div id="s2member-default-return-response-div" class="s2member-return-div s2member-return-response-div s2member-default-return-response-div">

				%%response%% <!-- (this is auto-filled by s2Member, based on what action has taken place). -->

				<div id="s2member-default-return-continue" class="s2member-return-continue s2member-default-return-continue">

					%%continue%% <!-- (auto-filled by s2Member, based on what action has taken place). -->

				</div>

			</div>

			<div style="clear:both;"></div>

		</div>



		<!-- Support Section (contains information about how a Customer can contact support). -->

		<div id="s2member-default-return-support-section" class="s2member-return-section s2member-return-support-section s2member-default-return-support-section">

			<div id="s2member-default-return-support-div" class="s2member-return-div s2member-return-support-div s2member-default-return-support-div">

				%%support%% <!-- (this is auto-filled by s2Member, based on configuration). -->

			</div>

			<div style="clear:both;"></div>

		</div>



		%%tracking%% <!-- (this is auto-filled, supports tracking codes integrated w/ s2Member). -->



	</body>
</html>
*/
//print_r($_POST);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>IT Locator</title>

</head>

<body style="background:url(<?php echo get_template_directory_uri(); ?>/images/bg-earth.png) no-repeat center top transparent;">

	<table style="width:100%;">

		<tr>"

			<td></td>

			<td style="width:50%;">

				<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" style="float:left;"/>

				<p style="color:#F1570B;float:right;font-weight:700;">

					<font face="TAHOMA">The Best Solutions Come From Local Technology Resellers</font>

				</p>

			</td>

			<td></td>

		</tr>

		<tr>

			<td></td>

			<td style="width:50%;background: rgba(255,255,255,0.6);">

				<p style="font-family:'TAHOMA';font-weight:700;">Thank Your for Joining IT Locator!</p>



				<p style="font-family:'TAHOMA';font-weight:700;">You will receive and email with a confirmation link for your registration.  It may take up to 15 minutes to receive this email.  Once you receive it please click the link in the email to confirm your registration and to begin creating your profile.</p>



				<p style="font-family:'TAHOMA';font-weight:700;">Thanks</p>



				<p style="font-family:'TAHOMA';font-weight:700;">The IT Loctaor team</p>

				<p style="font-family:'TAHOMA';font-weight:700;">support@itlocator.com</p>

			</td>

			<td></td>

		</tr>

		<tr>

			<td></td>

			<td style="text-align:center;font-family:'TAHOMA';font-weight:700;"><a href="<?php echo get_site_url(); ?>">Go to IT Locator Home Page </a></td>

			<td></td>

		</tr>

	</table>

</body>

</html>