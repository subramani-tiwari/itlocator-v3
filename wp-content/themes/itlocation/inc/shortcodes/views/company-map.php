<?php
global $company_id, $address_ctrl_fg;
$model = new companyModelItlocation();
$addresses = $model->get_a_comp_address_by_comp_id( $company_id );
$current_company = $model->get_by_id($company_id );

$info = array();

//echo '<pre>';print_r( $addresses );echo '</pre>';

foreach( $addresses as $address ){

	$info[] = array(

		'x' 		=> $address->lat,

		'y' 		=> $address->lng,

		'user_role' => $address->user_role,

	);
}


//$icon_url = get_bloginfo('template_url') . '/images/marker-free.png';

if( $current_company->user_role == '0' ){

   $icon_url = get_bloginfo('template_url') . '/images/marker-free.png';
} else if ($current_company->user_role == '1'){

  $icon_url = get_bloginfo('template_url') . '/images/marker-basic.png';
} 
 else if ($current_company->user_role == '2'){

    $icon_url = get_bloginfo('template_url') . '/images/marker-premium.png';
}


?>


<div class="row-fluid">
    <div class="span12">
        <script language="javascript">
            function initialize() {		
                var myOptions = {                
                    zoom:  <?php if( count( $info ) ){echo "14";}else{echo "5";}?>,
				   center: new google.maps.LatLng(37.09024, -95.712891),

                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },

                    navigationControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                $map = new google.maps.Map( document.getElementById( "map-canvas" ), myOptions );

			<?php
				if( count( $info ) ){
			?>

                $userdata = <?php echo json_encode($info); ?>;
                make_marker_no_desc();

			<?php

				}

			?>

				if( $markerBounds != null ) {

					$map.setCenter( $markerBounds.getCenter() );
                    $map.initialZoom = true;
				    //$map.fitBounds( $markerBounds );

				}

			}
		
			google.maps.event.addDomListener(window, 'load', initialize);

        </script>
        <div id="map-canvas" class="width-100-perc" style="height: 200px;"></div>
    </div>
</div>

<?php
if( $address_ctrl_fg ){
    if( is_user_logged_in() ){
        global $functions_ph;	
        $address_model = new addressModelItlocation();
        $addresses = $address_model->get_by_comp_id($current_company->id, 1, false);
        $num = $functions_ph->get_default_member_limit('locations', $current_company->user_role);
        if( $num != 0 ){

?>

 					<h4 class="page-title-diff"><?php _e('Additional Locations', 'twentyten') ?></h4>

                    <?php
						if( $num == -1 ){
							for( $i = 0; $i < count($addresses); $i++ ){ ?>

								<div class="form-group">
									<label><?php _e('Location', 'twentyten') ?></label>
									<input type="text" name="locations[]" class="locations form-control" value="<?php echo $addresses[$i]->address; ?>"> <i class="iconic-o-x delete_comp_address font-size-20 cursor-pointer" role="<?php echo $current_company->user_role; ?>"></i><br />
									<input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" />
                                    </div>
									
                                    <div class="form-group">
                                        <label><?php _e('Latitude', 'twentyten') ?></label>
                                        <input type="text" name="locations_lat[]" class="locations_lat form-control" value="<?php echo $addresses[$i]->lat; ?>" />
                                    </div>
									
                                    <div class="form-group">
                                        <label><?php _e('Longitude', 'twentyten') ?></label>
                                        <input type="text" name="locations_lng[]" class="locations_lng form-control" value="<?php echo $addresses[$i]->lng; ?>" />
                                    </div>
								</div>

					<?php } } else {
							$idx = 0;
							for ($i = 0; $i < $num; $i++) { ?>

								<div class="form-group">
									<label><?php _e('Location', 'twentyten') ?></label>
									<input type="text" name="locations[]" class="locations form-control" value="<?php echo $addresses[$idx]->address; ?>"><br />
                                     <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>"/>
                                 </div>
                                 
                                 <div class="form-group">
									<label><?php _e('Latitude', 'twentyten') ?></label>
									<input type="text" name="locations_lat[]" class="locations_lat form-control" value="<?php echo $addresses[$idx]->lat; ?>" />
                                 </div>
                                 
                                 <div class="form-group">
									<label><?php _e('Longitude', 'twentyten') ?></label>
									<input type="text" name="locations_lng[]" class="locations_lng form-control" value="<?php echo $addresses[$idx]->lng; ?>" />
                                 </div>
                                 
					<?php ++$idx; } } ?>
                    

		<?php if( $num == -1 ){ ?>

			<div class="ctrl-group">
				<input type="button" value="Add Other Address" class="btn btn-sm btn-primary" id="add-other-address" />
			</div>

			<div id="ctrl-group-company-address" class="display-none">
				<div class="form-control">
					<label><?php _e('Location', 'twentyten') ?></label>
					<input type="text" name="locations[]" class="locations form-control" value=""> <i class="iconic-o-x delete_comp_address font-size-20 cursor-pointer" role="<?php echo $current_company->user_role; ?>"></i><br />
                    <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="setting_lat_lng btn btn-sm btn-primary" role="<?php echo $current_company->user_role; ?>" />
               </div>
    
                <div class="form-control">
                    <label><?php _e('Latitude', 'twentyten') ?></label>
                    <input type="text" name="locations_lat[]" class="locations_lat form-control" value="">
                </div>
    
                <div class="form-control">
                    <label><?php _e('Longitude', 'twentyten') ?></label>
                    <input type="text" name="locations_lng[]" class="locations_lng form-control" value="">
                </div>
			</div>

<?php } } } } ?>