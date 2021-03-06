<?php
/*
Template Name: My Profile Page
*/

if (!is_user_logged_in()){
    wp_redirect(get_site_url());
}

global $current_user, $current_company, $functions_ph, $time_zone;

$user = new WP_User($current_user->ID);

if( $user->roles[0] == 'administrator' ){
    wp_redirect(get_site_url() . '/wp-admin');
}

get_currentuserinfo();

$upload_dir = wp_upload_dir();
$company_model = new companyModelItlocation();
$address_model = new addressModelItlocation();

$fsize_limit = 1024;
if( get_option( 'itlocation_generals_comp_file_size_limit' ) ){
    $fsize_limit = get_option('itlocation_generals_comp_file_size_limit');
}

$error = array();
if( $_POST['submit'] ){
    $destination_path = $upload_dir["basedir"] . "/user_photos/";

    if( !file_exists( $destination_path ) ){
        mkdir( $destination_path, 0777 );
	}
		
    if( isset( $_POST['user_photo'] ) && $_POST['user_photo'] == '' ){
        $photo_nm = get_user_meta( $current_user->ID, 'user_photo_nm', true );
        if( $photo_nm ){
            unlink($destination_path . $photo_nm);
		}
        update_user_meta( $current_user->ID, 'user_photo_nm', '' );
    }else if( $_FILES['user_photo']['error'] == 0 ){
        $fname = $_FILES['user_photo']['name'];
		
        if( $fname ){
            $real_fname = mktime() . "_" . $fname;
            $filetype = wp_check_filetype( basename( $fname ), null );
            $destination_file = $destination_path . $real_fname;

            if( $filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png' ){
                $fsize = round($_FILES['user_photo']['size'] / 1024.0);
                if( $fsize < $fsize_limit ){
                    if( move_uploaded_file( $_FILES['user_photo']['tmp_name'], $destination_file ) ){
                        $photo_nm = get_user_meta($current_user->ID, 'user_photo_nm', true);
                        if( $photo_nm ){
                            unlink($destination_path . $photo_nm);
						}
                        update_user_meta($current_user->ID, 'user_photo_nm', $real_fname);
                    } else {
                        $errors[] = 'Error upload';
                    }
                } else {
                    $errors[] = 'Size of image must be small than ' . $fsize_limit . ' KByte.';
                }
            } else {
                $errors[] = 'Extension of image must be jpg and png.';
            }
        }
    }

    $destination_path = $upload_dir["basedir"] . "/comp_logo/";
    if( !file_exists( $destination_path ) ){
        mkdir($destination_path, 0777);
	}
	
    if( isset( $_POST['comp_logo'] ) && $_POST['comp_logo'] == '' ){
        if( $current_company->logo_file_nm ){
            unlink($destination_path . $current_company->logo_file_nm);
		}
        $info['logo_file_nm'] = '';
        $company_model->update_by_id($current_company->id, $info);
    } elseif ($_FILES['comp_logo']['error'] == 0) {
        $fname = $_FILES['comp_logo']['name'];
        if( $fname ){
            $real_fname = mktime() . "_" . $fname;
            $filetype = wp_check_filetype(basename($fname), null);
            $destination_file = $destination_path . $real_fname;

            if( $filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png' ){
                $fsize = round($_FILES['comp_logo']['size'] / 1024.0);
                if( $fsize < $fsize_limit ){
                    if( move_uploaded_file($_FILES['comp_logo']['tmp_name'], $destination_file) ){
                        if( $current_company->logo_file_nm ){
                            unlink($destination_path . $current_company->logo_file_nm);
						}
                        $info['logo_file_nm'] = $real_fname;
                        $company_model->update_by_id($current_company->id, $info);
                    } else {
                        $errors[] = 'error upload';
                    }
                } else {
                    $errors[] = 'Size of image must be small than ' . $fsize_limit . ' KByte.';
                }
            } else {
                $errors[] = 'Extension of image must be jpg and png.';
            }
        }
    }

    if( $_REQUEST['new_pass'] ){
        if( $_REQUEST['new_pass'] == $_REQUEST['conform_pass'] ){
            $arg = array(
                'ID' => $current_user->ID,
                'user_pass' => $_REQUEST['new_pass']
            );
            wp_update_user($arg);
        }
    }

    if( isset( $_POST['services'] ) ){
        $tmp_a = $_POST['services'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('services');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }

    if (isset($_POST['certifications'])) {
        $tmp_a = $_POST['certifications'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('certifications');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }
    if (isset($_POST['industries'])) {
        $tmp_a = $_POST['industries'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('industries');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }
    if (isset($_POST['partners'])) {
        $tmp_a = $_POST['partners'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('partners');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }

    $info = array();

    if ($_POST['companyname'])
        $info['companyname'] = $_POST['companyname'];
    else
        $errors[] = 'Please insert company name';

    $info['firstname'] = $_POST['first_name'];
    $info['lastname'] = $_POST['last_name'];
    $info['contactemail'] = $_POST['user_email'];
    $info['phoneprim'] = $_POST['phoneprim'];
    //$info['phonescond'] = $_POST['phonescond'];
   // $info['companyurl'] = $_POST['companyurl'];
    $info['address1'] = $_POST['address1'];
    $info['address2'] = $_POST['address2'];
    $info['city'] = $_POST['comp_city'];
    $info['zip_code'] = $_POST['comp_zip_code'];
    $info['state'] = $_POST['comp_state'];
    $info['country'] = $_POST['comp_country'];
     if(isset($_POST['contentupdates']))
	{
		$info['content_updates']=1;
	}
	 else{
	 	$info['content_updates']= 0;
	 }
    global $states;
	
    $info['address'] = $states[$_POST['comp_country']][$_POST['comp_state']];

    $info['time_zone'] = $_POST['comp_time_zone'];
    $info['latitude'] = $_POST['latitude'];
    $info['longitude'] = $_POST['longitude'];
	
	
	//Validation companyurl URL
   if($_POST['companyurl']!=''){
	if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['companyurl'])) {
	$info['companyurl'] =''; 
	$errors[] = 'Not A Valid Company Url.'; 
	}else
	{
	$info['companyurl'] = $_POST['companyurl'];
	}
	}
	//Validation twitter URL
   if($_POST['twitter']!=''){
	if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['twitter'])) {
	$info['twitter'] =''; 
	$errors[] = 'Not A Valid Twitter Url.'; 
	}else
	{
	$info['twitter'] = $_POST['twitter'];
	}
	}
	//Validation linkedin URL
	if($_POST['linkedin']!=''){
	if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['linkedin'])) {
	$info['linkedin'] =''; 
	$errors[] = 'Not A Valid Linkedin Url.'; 
	}else
	{
	$info['linkedin'] = $_POST['linkedin'];
	}
	}
	//Validation googleplus URL
	if($_POST['googleplus']!=''){
	if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['googleplus'])) {
	$info['googleplus'] =''; 
	$errors[] = 'Not A Valid Googleplus Url.'; 
	}else
	{
	$info['googleplus'] = $_POST['googleplus'];
	}
	}
	//Validation facebook URL
	if($_POST['facebook']!=''){
	if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['facebook'])) {
	$info['facebook'] =''; 
	$errors[] = 'Not A Valid Facebook Url.'; 
	}else
	{
	$info['facebook'] = $_POST['facebook'];
	}
	}
    //$info['googleplus'] = $_POST['googleplus'];
    //$info['facebook'] = $_POST['facebook'];
    $info['auto_renew'] = $_POST['auto_renew'];
    $info['description'] = $_POST['comp_description'];
    $info['description'] = substr($info['description'], 0, 1000);
    $company_model->update_by_id($current_company->id, $info);
	
	$address_model->del_by_comp_id($current_company->id);
	
	
	if( $info['latitude'] != '' && $info['longitude'] != '' ){
		$address_info = array();
		
		$address_info['comp_id'] = $current_company->id;
		$address_info['lat'] = $_POST['latitude'];
		$address_info['lng'] = $_POST['longitude'];
		$address_info['primary_address'] = 1;
		
		$address_model->new_insert( $address_info );
	}
	// exit;
    if (isset($_POST['locations'])) {
        $locations = $_POST['locations'];
        $locations_lat = $_POST['locations_lat'];
        $locations_lng = $_POST['locations_lng'];

        $idx = 0;
        $address_info = array();
        foreach ($locations as $value) {
            if ($value) {
                $address_info['comp_id'] = $current_company->id;
                $address_info['address'] = $value;
                $address_info['lat'] = $locations_lat[$idx];
                $address_info['lng'] = $locations_lng[$idx];
			$address_info['primary_address'] = 0;
				
                $address_model->new_insert($address_info);
            }
            ++$idx;
        }
    }
	
    update_user_meta($current_user->ID, 'first_name', $_POST['first_name']);
    update_user_meta($current_user->ID, 'last_name', $_POST['last_name']);
    update_user_meta($current_user->ID, 'user_title', $_POST['user_title']);
    if (preg_match('/^[0-9]+$/', $_POST['phoneprim'])) {
		 update_user_meta($current_user->ID, 'phoneprim', $_POST['phoneprim']);
	}
	else{
		update_user_meta($current_user->ID, 'phoneprim', "");
		$errors[] = 'Enter your valid Prime Phone Number.'; 
	}
    update_user_meta($current_user->ID, 'user_email_show_fg', $_POST['user_email_show_fg']);
    
    
    
	if( $_POST['user_phone_show_fg'] ){
        update_user_meta( $current_user->ID, 'user_phone_show_fg', 0 );
    } else {
        update_user_meta( $current_user->ID, 'user_phone_show_fg', 1 );
	}
	
    if ($_POST['user_email']) {
        if (!is_email($_POST['user_email'])) {
            $errors[] = 'Not A Valid Email address.';
        } else {
            if ($current_user->user_email != $_POST['user_email']) {
                if (email_exists($_POST['user_email'])) {
                    $error = 'Not A Valid Email address.';
                } else {
                    wp_update_user(array('ID' => $current_user->ID, 'user_email' => $_POST['user_email']));
                }
            }
        }
    } else {
        $errors[] = 'Not A Valid Email address.';
    }

    if( !count( $errors ) ){
        global $post;
        wp_redirect( get_permalink( $post->ID ) );
    }
}

$current_company_a = array();
if( count( $current_company ) ){
    foreach( $current_company as $key => $value ){
        $current_company_a[$key] = $value;
	}
}
?>
<?php get_header(); ?>

</div>
</div>
</div>
</div>

<link rel='stylesheet' href='<?php echo get_bloginfo('template_url'); ?>/plugins/jquery-checkbox.1.3.0b1/jquery.checkbox.css?ver=3.6' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/jquery-checkbox.1.3.0b1/jquery.checkbox.min.js?ver=3.6'></script>

<div class="page-sub-page inner-page my-profile">
    <div class="container">
        <div class="row">
            <div id="jquery-live-validation-edit-profile">
             <!-- /* basic container starts */ -->
             
                <?php
                if (count($errors)) { ?>
                    <div class="alert alert-danger">
                        <?php
                        foreach ($errors as $error) {
                            echo $error . '<br/>'; }  ?>
                    </div>
                    <?php  }  ?>
                <form method="post" action="" name="profile-form" enctype="multipart/form-data">
                    <div class="col-lg-9 col-md-9 col-sm-9 base page-border">
                    
                    <!-- /* Map holder starts */ -->
                    <div class="map-holder tMx">
                    	<!-- <img src="http://placehold.it/850x300&text=Map goes here" class="img-responsive"/>-->
                         <div id="map-canvas" class="width-100-perc" style="height: 300px;"></div>
                         <h4 class="location">Company Locations</h4>
                    </div><!-- /* Map holder ends */ -->
                    
                    <div class="profile-img-holder">
                    	<!-- /* Img and info */ -->
                        <div class="media">
                            <div class="pull-left">
                                <div class="user-img">
                                	<img src="http://placehold.it/150x150&text=User image" class="img-responsive"/>
                                    <div class="controls">
                                    	<a href="" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Size of file must be small than 1024 KMByte. And File type must be jpeg and png."><i class="fa fa-upload"></i></a>
                                        <a href="" class="btn btn-default btn-sm"><i class="fa fa-times-circle "></i></a>
                                        
                                        <script>
											jQuery(function () {
												jQuery('[data-toggle=tooltip]').tooltip();
											});
										</script>
                                    </div>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">User Name</h4>
                                <p>Membership Type : Member</p>
                            </div>
                        </div><!-- /* Img and info ends */ -->
                    </div>
                    
                    <!-- /* Edit containers */ -->
                    <div class="edit-container">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="edit-holder">
                                
                        <?php wp_nonce_field('get-state-itlocation', 'get-state-security'); ?>
                        <?php wp_nonce_field('check-email-itlocation', 'check-email-security'); ?>
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
                        <h4 class="box-title"><i class="fa fa-user "></i> <?php _e('User Info', 'twentyten') ?></h4>
                        
                        <div class="form-group">
                            <label class="control-label" for="username"><?php _e('Username', 'twentyten') ?></label>
                            <input type="text" id="username" class="form-control" name="username" readonly value="<?php echo $current_user->user_login; ?>">
                        </div>
                        <?php
                        $user_metas = array();    
                        $user_metas['first_name'] = 'First Name';
                        $user_metas['last_name'] = 'Last Name';
                        $user_metas['user_title'] = 'Title';
                        foreach ($user_metas as $key => $user_meta):
                            ?>
                            <div class="form-group">
                                <label class="control-label" for="<?php echo $key; ?>"><?php _e($user_meta); ?> <span class="imp_star_mark">*</span></label>
                                 <input type="text" class="form-control" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php _e(get_user_meta($current_user->ID, $key, true)) ?>" placeholder="<?php _e($user_meta); ?>">
                                <div class="alert alert-danger">
                                    <small>Please complete you details</small>
                                </div>
                            </div>
                            <?php
                        endforeach;
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="user_email"><?php _e('Email', 'twentyten'); ?> <span class="imp_star_mark">*</span></label>
                            <div class="row">
                            <div class="col-sm-9">
                                <input class="form-control" type="text" id="user_email" name="user_email" value="<?php echo $current_user->user_email; ?>" placeholder="<?php _e('Email', 'twentyten'); ?>">
                                <div class="loading pull-left" style="display:none;"></div>
                                </div>
                                <div class="col-sm-3">
                                	<span class="font-size-14 tMx"><input type="checkbox" name="user_email_show_fg" value="1" <?php echo ( get_user_meta($current_user->ID, 'user_email_show_fg', true) == '1') ? 'checked="checked"' : '' ?> class="hidden-show"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="phoneprim"><?php _e('Phone', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="phoneprim" name="phoneprim" value="<?php _e(get_user_meta($current_user->ID, 'phoneprim', true)) ?>" placeholder="<?php _e('Phone', 'twentyten') ?>">   
                                </div>
                                <div class="col-sm-3">
                                <span class="font-size-14 tMx"><input type="checkbox" name="user_phone_show_fg" value="1" <?php echo (get_user_meta($current_user->ID, 'user_phone_show_fg', true)) ? '' : 'checked="checked"' ?> class="hidden-show"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="new_pass"><?php _e('New Password', 'twentyten') ?></label>
                            <input class="form-control" type="password" id="new_pass" name="new_pass" value="" placeholder="<?php _e('New Password', 'twentyten') ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="conform_pass"><?php _e('Confirm Password', 'twentyten') ?></label>
                            <input class="form-control" type="password" id="conform_pass" name="conform_pass" placeholder="<?php _e('Confirm Password', 'twentyten') ?>">  
                        </div>
                        <div class="form-group hidden">
                            <label class="control-label" for="user_photo"><?php _e('User Photo', 'twentyten') ?></label>
                            <div class="" id="user-photo-grp">
                                <?php
                                $user_photo_nm = get_user_meta($current_user->ID, 'user_photo_nm', true);
                                if ($user_photo_nm) {
                                    $img_url = $upload_dir["baseurl"] . "/user_photos/" . $user_photo_nm; ?>
                                    <input type="hidden" name="image_exit_fg" value="1" />
                                    <div class="fileupload fileupload-exists" data-provides="fileupload">
                                    <input type="hidden" value="" name="">
                                        <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail max-height-200 max-height-150 line-height-20"><img src="<?php echo $img_url; ?>" class="max-height-150" style="max-height: 150px;"></div>
                                        <div>
                                            <label>Select image</label>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-times-circle"></i></a>
                                            <input type="file" name="user_photo" id="user_photo">
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    
                                    <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
                                        <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail max-height-200 max-height-150 line-height-20"></div>
                                        <div>
                                            <label>Select image</label>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-times-circle"></i></a>
                                            <input type="file" name="user_photo" id="user_photo">
                                        </div>
                                    </div>
                                    <?php } ?>
                                
                                <span class="help-block">
                                    <small><?php _e('Size of file must be small than ' . $fsize_limit . ' KMByte.', 'twentyten'); ?> <?php _e('And File type must be jpeg and png.', 'twentyten'); ?></small>
                                 </span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                        <label class="control-label" for="Content Updates"><?php _e('Subscribe me to IT Locator Content Updates', 'twentyten') ?></label>
                            <?php if($current_company_a['content_updates']==1)
                            { $checked="checked"; }
                            else
                            { $checked=""; } ?>
                            <input type="checkbox" <?php echo $checked;?> value="<?php echo $current_company_a['content_updates'] ?>"  name="contentupdates" id="contentupdates"/>
                           
                        </div>
                                <!-- end -->
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6">
                            	<div class="edit-holder">
                                
                                 <h4 class="box-title"><i class="fa fa-briefcase"></i> <?php _e('Company Info', 'twentyten') ?></h4>
                        
                                <div class="form-group">
                                    <label class="control-label" for="companyname"><?php _e('Company Name', 'twentyten'); ?> <span class="imp_star_mark">*</span></label>
                                    <input class="form-control" type="text" id="companyname" name="companyname" value="<?php echo $current_company_a['companyname'] ?>" placeholder="<?php _e('Company Name', 'twentyten'); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label" for="address1">Address Line 1 <span class="imp_star_mark">*</span></label>
                                    <input class="form-control" type="text" id="address1" name="address1" value="<?php echo $current_company_a['address1']; ?>" placeholder="Address Line 1">
                                </div>
                        <?php
                            // echo $current_company->user_role;
                            if( $current_company->user_role > 0 ){
                        ?>
                        <div class="form-group">
                           <label class="control-label" for="address2">Address Line 2</label>
                           <input class="form-control" type="text" id="address2" name="address2" value="<?php echo $current_company_a['address2']; ?>" placeholder="Address Line 2">
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="control-label" for="comp_country"><?php _e('Country', 'twentyten') ?></label>                            
                                <?php
                                $opton_tmp = 'US';
                                if ($current_company_a['country'])
                                    $opton_tmp = $current_company_a['country'];
                                echo do_shortcode('[countries-ctrl-itlocation id="comp_country" class="pull-left width-220" style="" cutomize_js_fg="yes" selected_option="' . $opton_tmp . '" /]');
                                ?>
                                
                        </div>
                        <div class="form-group" id="comp_state_grp">
                            <label class="control-label" for="comp_state"><?php _e('State/Province', 'twentyten') ?></label>
                            <div class="loading" style="display: none"></div>    
                            <div id="comp_state_ctrl">
                                <input class="form-control" type="text" id="comp_state" name="comp_state" value="<?php echo $current_company_a['comp_state'] ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label" for="comp_city"><?php _e('City', 'twentyten') ?></label>
                            <div class="loading" style="display: none"></div>
                            <div id="comp_state_ctrl">
                                <input class="form-control" type="text" id="comp_city" name="comp_city" value="<?php echo $current_company_a['city'] ?>" placeholder="City">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label" for="comp_zip_code"><?php _e('Zip Code/PC', 'twentyten'); ?></label>
                            
                                <input class="form-control" type="text" placeholder="<?php _e('Zip Code', 'twentyten') ?>" name="comp_zip_code" id="comp_zip_code" class="required" value="<?php echo $current_company_a['zip_code'] ?>">
                                <br />
                                <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" id="main_address_setting_lat_lng" />
                           
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="latitude"><?php _e('Latitude', 'twentyten') ?></label>
                            <div id="comp_state_ctrl">
                                <input class="form-control" type="text" id="latitude" name="latitude" value="<?php echo $current_company_a['latitude'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="longitude"><?php _e('Longitude', 'twentyten') ?></label>
                            <div id="comp_state_ctrl">
                                <input class="form-control" type="text" id="longitude" name="longitude" value="<?php echo $current_company_a['longitude'] ?>">
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label class="control-label" for="comp_time_zone"><?php _e('Time Zone', 'twentyten') ?></label>
                            <select id="comp_time_zone" name="comp_time_zone" class="form-control">
								<?php
                                foreach ($time_zone as $key => $val) {
                                    $tmp_sel = '';
                                    if ($current_company_a['time_zone']) {
                                        if ($current_company_a['time_zone'] == $key)
                                            $tmp_sel = 'selected';
                                    } elseif ($key == 'GMT-05:00') {
                                        $tmp_sel = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $tmp_sel; ?>><?php echo $val; ?></option>
                                    <?php  } ?>
                            </select>
                        </div>
                        <?php
                        $company_metas = array();
                        $company_metas['companyurl'] = 'URL';
                        $company_metas['phonescond'] = 'Phone';
                        $company_metas['twitter'] = 'Twitter';
                        $company_metas['linkedin'] = 'Linkedin';
                        $company_metas['googleplus'] = 'Google+';
                        $company_metas['facebook'] = 'Facebook';
    
                        foreach ($company_metas as $key => $company_meta):
                            ?>
                            <div class="form-group">
                                <label class="control-label" for="<?php echo $key; ?>"><?php _e($company_meta); ?></label>
                                 <input class="form-control" type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $current_company_a[$key] ?>" placeholder="<?php echo _e($company_meta); ?>">
                            </div>
                            <?php endforeach;  ?>
                                <!-- end -->
                                </div>
                            </div>
                        </div>
                    </div><!-- /* Edit containers ends */ -->
                  
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="edit-holder">
                            <h4 class="box-title"><i class="fa fa-building-o"></i> <?php _e('Company Profile', 'twentyten') ?></h4>
                  <?php if( $current_company->user_role > 0 ){ ?>
                        <div class="form-group">
                            <label class="control-label" for="comp_description"><?php _e('Description', 'twentyten') ?></label>
                            <div class="">
                                <textarea class="form-control" name="comp_description" id="comp_description" rows="10"><?php echo $current_company_a['description'] ?></textarea>
                                <div class="clearfix"></div>
                                <div class="span11">
                                    <?php
                                    $tmp = 1000 - strlen($current_company_a['description']);
                                    ?>
                                    <span class="pull-right"><span id="desc_number"><?php echo $tmp; ?></span> characters left.</span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php
                        $limit = $functions_ph->get_default_member_limit('services', $current_company->user_role);
                        if ($limit != '0') { ?>
                            <div class="form-group service-diff">
                                <label class="control-label" for="services"><?php _e('Services') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[services-ctrl-itlocation kind="services" id="services" style="" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Services"/]');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        $limit = $functions_ph->get_default_member_limit('industries', $current_company->user_role);
                        if ($limit != '0') { ?>
                            <div class="form-group service-diff">
                                <label class="control-label" for="industries"><?php _e('Industries') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[services-ctrl-itlocation kind="industries" id="industries" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Industries"/]');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="control-label" for="user_photo"><?php _e('Company Logo', 'twentyten') ?></label>
                            <div class="">
                                <?php
                                if ($current_company->logo_file_nm) {
                                    $img_url = $upload_dir["baseurl"] . "/comp_logo/" . $current_company->logo_file_nm;
                                    ?>
                                    <div class="fileupload fileupload-exists" data-provides="fileupload"><input type="hidden" value="" name="">
                                        <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20"><img src="<?php echo $img_url; ?>" class="max-height-150"></div>
                                        <div>
                                            <label>Select image</label>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-times-circle"></i></a>
                                            <input type="file" name="user_photo" id="user_photo">
                                        </div>
                                    </div>
                                    <?php  } else { ?>
                                    <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
                                        <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                        <div class="fileupload-preview fileupload-exists thumbnail max-height-200 max-height-150 line-height-20"></div>
                                        <div>
                                            <label>Select image</label>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="fileupload-exists" data-dismiss="fileupload"><i class="fa fa-times-circle"></i></a>
                                            <input type="file" name="user_photo" id="user_photo">
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                <div class="clearfix"></div>
                                <?php ?>
                                <span class="help-block">
                                    <small><?php _e('Size of file must be small than ' . $fsize_limit . ' KByte.', 'twentyten'); ?> <?php _e('And File type must be jpeg and png.', 'twentyten'); ?></small>
                                </span>
                            </div>
                        </div>

                        <?php
                        $limit = $functions_ph->get_default_member_limit('certifications', $current_company->user_role);
                        if ($limit != '0') { ?>
                            <div class="form-group service-diff">
                                <label class="control-label" for="certifications"><?php _e('Certifications') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[services-ctrl-itlocation kind="certifications" id="certifications" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Certifications"/]');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        $limit = $functions_ph->get_default_member_limit('partners', $current_company->user_role);
                        if ($limit != '0') { ?>
                            <div class="form-group service-diff">
                                <label class="control-label" for="partners"><?php _e('Partners') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[services-ctrl-itlocation kind="partners" id="partners" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Partners"/]');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
    
                        <?php
                        $file_num = $functions_ph->get_default_member_limit('collateral', $current_company->user_role);
                        if ($file_num != '0') {
                            ?>
                            <div class="form-group">
                                <label class="control-label"><?php _e('Collateral', 'twentyten') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[file-mgn-itlocation modal_id="collateral" file_type="collateral" comp_id="' . $current_company->id . '" default="0, 3, -1"/]');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php
                        $file_num = $functions_ph->get_default_member_limit('case_studies', $current_company->user_role);
                        if ($file_num != '0') { ?>
                            <div class="form-group">
                                <label class="control-label"><?php _e('Case Studies', 'twentyten') ?></label>
                                <div class="">
                                    <?php
                                    echo do_shortcode('[file-mgn-itlocation modal_id="case_studies" file_type="case_studies" comp_id="' . $current_company->id . '" default="0, 1, -1"/]');
                                    ?>
                                </div>
                            </div>
    
                        <?php } ?>
                        </div>
                    </div>
                  </div>
                    
                    
                    <div class="form-group">
						<input type="submit" name="submit" id="edit-myprofile-btn" value="<?php _e('Save Details', 'twentyten') ?>" class="btn btn-success btn-lg">
                    </div>
                    
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 base-secondary page-margin">
                        <div class="row-fluid">
                            <h4 class="page-title-diff dMx"><?php _e('Membership Status', 'twentyten') ?></h4>
                            
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><?php _e(S2MEMBER_CURRENT_USER_ACCESS_LABEL, 'twentyten') ?></td>
                                        <td><div class="<?php echo $functions_ph->get_icon_member_role(S2MEMBER_CURRENT_USER_ACCESS_LEVEL) ?>"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Status', 'twentyten') ?> : </td>
                                        <td><?php _e('Active', 'twentyten') ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Renew Date', 'twentyten') ?> : </td>
                                        <td><?php 
                                    if( strtotime($current_company->renew_date) != '' ){
                                        echo ( $current_company->renew_date ) ? date( get_option( 'date_format' ), strtotime( $current_company->renew_date ) ) : '';
                                    }
                                ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Auto Renew', 'twentyten') ?></td>
                                        <td><input type="checkbox" name="auto_renew" value="1" <?php echo ( $current_company->auto_renew == '1' ) ? 'checked="checked"' : '' ?>></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php
                            if (get_option('itlocation_generals_my_payment_page')) {
                                $pid = get_option('itlocation_generals_my_payment_page');
                                $tmp_url = get_permalink($pid);
                                if (S2MEMBER_CURRENT_USER_ACCESS_LEVEL != 2) { ?>
                                    <a href="<?php echo $tmp_url ?>" class="btn btn-success btn-block"><?php _e('Upgrade Membership', 'twentyten'); ?></a>
                                    <?php } } ?>
                               
                               <br /><br />
                                    
                            <?php
                            $num = $functions_ph->get_default_member_limit('locations', $current_company->user_role);
                            
                            // if ($num != 0) {
                            
                                ?>
                                <!--<h3 class="page-title-diff"><?php _e('Company Locations', 'twentyten') ?></h3>-->
                                <?php echo do_shortcode('[company-map-itlocation company_id="' . $current_company->id . '" address_ctrl_fg="1" /]'); ?>
                            <?php 
                            //} 
                            ?>
                        </div>
                    </div>
                </form>
            
             <!-- /* basic container ends */ -->
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        jQuery('input:checkbox:not([safari])').checkbox();
        jQuery('input.hidden-show:checkbox:not([safari])').checkbox({
            cls:'jquery-checkbox-show-hidden'
        });

        $data = "action=get-state-itlocation&security=" + jQuery('#get-state-security').val() + "&country_id=" + jQuery('#comp_country').val() + "&state_id=<?php echo $current_company_a['state'] ?>";
        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                jQuery('#comp_state_ctrl').html(response);
                jQuery('#comp_state_ctrl').show();
                jQuery('#state_loading').hide();
            }
        });
    });
            
    //check_phone_format("user_phone");
</script>
<?php get_footer(); ?>
