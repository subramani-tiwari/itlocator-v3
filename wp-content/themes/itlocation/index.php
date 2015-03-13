<?php get_header() ?>

<!-- /* feature area starts */ -->
    <div class="is-section is-client">
    	<div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="client-wrap aCenter">
                    	<h2 class="page-title">Make Sure Your IT Provider Business is Found &amp; Appreciated</h2>
                        <ul class="list-inline client-nav">
                        <!---- <li><a href=""><a href="http://www.hp.com"><img src="<?php echo get_bloginfo('template_url') ?>/img/client-icons-1.png" class="img-responsive"></a></li>
                            <li><a href=""><a href="http://www.lenovo.com"><img src="<?php echo get_bloginfo('template_url') ?>/img/client-icons-2.png" class="img-responsive"></a></li>
                            <li><a href=""><a href="http://www.kaseya.com"><img src="<?php echo get_bloginfo('template_url') ?>/img/client-icons-3.png" class="img-responsive"></a></li>
                            <li><a href=""><img src="<?php echo get_bloginfo('template_url') ?>/img/client-icons-4.png" class="img-responsive"></a></li>
                            <li><a href=""><img src="<?php echo get_bloginfo('template_url') ?>/img/client-icons-5.png" class="img-responsive"></a></li>---->
                        </ul>
                    </div>
                    
                    <div class="displayB aCenter tMxx">
						<?php
							$tmp_url = '';
							if( get_option( 'itlocation_generals_signup_page' ) ){
							$pid = get_option( 'itlocation_generals_signup_page' );
							$tmp_url = get_permalink( $pid );
							} if( !is_user_logged_in() ){ ?>
							
							<a href="<?php echo $tmp_url; ?>" class="btn btn-success btn-lg"><?php _e('Get Started Now', 'twentyten') ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /* Feature area Ends */ -->

<?php

if (is_front_page() || is_home()) {
    if (!is_user_logged_in()) {
        if ($_COOKIE['signup_subscriber_itlocation'] == '' && $_COOKIE['not_now_subscriber_itlocation'] == '') {
           if ($_COOKIE['address_itlocation'] != '') { ?>
                <script>
                    jQuery(document).ready(function() {
                        jQuery('#subscribe').modal('show');
                    });
                </script>
            <?php  } ?>
            
       		<!-- Modal -->
            <div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-newsletter">
                    <div class="modal-content">
                        <!--<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                             <h4 class="modal-title" id="myModalLabel"></h4>
                        </div> -->
                        <div class="modal-body">
                        	<div class="mail-feel">
                            	<h2 class="noMargin dMx"><?php _e('Get the Latest on Tech', 'twentyten'); ?></h2>
                                <h2 class="page-title">
                                <?php _e('Give us your email to stay up on the latest cost saving and revenue generating technology advancements from the largest independent technology reseller site on the internet.', 'twentyten'); ?></h2>
                                
                                
                                <form action="" method="POST" id="subscriber_itlocation_modal_form">
                                    <?php wp_nonce_field('subscriber-modal-itlocation', 'subscriber-modal-itlocation-security'); ?>
                                    <div class="form-group">
                                        <label class="sr-only">Email address</label>
                                        <input type="text" name="subscriber_email_itlocation" placeholder="Enter your email" class="form-control subscriber_email_itlocation" />
                                        <span class="help-block"><small><?php _e('We Never Spam!  We Never Rent!', 'twentyten'); ?></small></span>
                                    </div>
                                    <div class="form-group noMargin">
                                         <input type="submit" name="signup_subscriber_itlocation_btn" value="<?php _e('Subscribe', 'twentyten'); ?>" class="btn btn-success signup_btn" /> 
                                         
                                           <input type="button" value="<?php _e('Not Now', 'twentyten'); ?>" name="not_now_subscriber_itlocation_btn" id="not_now_subscriber_itlocation_btn" class="btn" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer hidden">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php

        }

    }

}

?>


<div class="is-section is-blog">
    <h2 class="page-title">The Latest Strategies/ Advice/ Vision for Applying IT Technologies &amp; Services</h2>
    	<div class="container">
            <div class="row">
            
                <?php
                $args = array(
                    'posts_per_page' => 6,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => 'member-contributions',
                    'post_status' => 'publish',
                    'suppress_filters' => true);
                $member_contributions = get_posts($args);

                if (!count($member_contributions)):
                    ?>
                    <div id="post-0" class="post error404 not-found">
                        <h1 class="entry-title"><?php _e('Not Found', 'twentyten'); ?></h1>
                        <div class="entry-content">
                            <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
                            <?php get_search_form(); ?>
                        </div><!-- .entry-content -->
                    </div><!-- #post-0 -->
                    <?php
                else:
                    $idx = 0;
                    foreach ($member_contributions as $post) : setup_postdata($post);
                        if (($idx % 3) == 0) {
                            if ($idx != 0)
                                echo ' <div class="clearfix visible-lg-block visible-md-block"></div>';  } ?>
                        
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="blog-cont">
                                  <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('thumb-300*200');
                                    } else { ?>
                                        <img data-src="holder.js/300x200" alt="300x200" width="300" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">
                                   <?php } ?>
                                </a>
                                    <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                  </div>
                                  <div class="blog-content">
                                    <p><?php _e($functions_ph->string_max_length(get_the_excerpt(), 100)); ?></p>
                                  </div>
                                  <div class="more-btn">
                                  	<a href="<?php the_permalink(); ?>">&nbsp;</a>
                                  </div>
                            </div>
                        </div>
                        
                        <?php
                        ++$idx;
                        if ($idx == count($member_contributions)) {
                            echo ' <div class="clearfix visible-lg-block visible-md-block"></div>';
                        }
                    endforeach;
                endif;
                 wp_reset_postdata();
                ?>
                
            </div>
        </div>
    </div>

	<!-- /* ADS area starts */ -->
    <div class="is-section is-ad">
    	<div class="container">
            <div class="row">
            
				<?php
                $ads = '';
                if (get_option('itlocation_ads_txt_1_index')) { $ads = stripslashes(get_option('itlocation_ads_txt_1_index')); } ?>
                <div class="col-sm-4 <?php echo ($ads) ? '' : 'position-relative' ?>">
                    <?php  if ($ads) { echo $ads; } else { ?>
                        <div class="position-absolute"><?php _e("Please insert your ads code in Appearance -> Theme Options -> tab 'Ads' in admin") ?></div>
                        <img src="http://www.placehold.it/300x100/EFEFEF/AAAAAA">
                    <?php } ?>
                </div>
        
                <?php
                $ads = '';
                if (get_option('itlocation_ads_txt_2_index')) { $ads = stripslashes(get_option('itlocation_ads_txt_2_index')); } ?>
                <div class="col-sm-4 <?php echo ($ads) ? '' : 'position-relative' ?>">
                    <?php
                    if ($ads) { echo $ads; } else { ?>
                        <div class="position-absolute"><?php _e("Please insert your ads code in Appearance -> Theme Options -> tab 'Ads' in admin") ?></div>
                        <img src="http://www.placehold.it/300x100/EFEFEF/AAAAAA">
                    <?php } ?>
                </div>
        
                <?php
                $ads = '';
                if (get_option('itlocation_ads_txt_3_index')) { $ads = stripslashes(get_option('itlocation_ads_txt_3_index')); } ?>
                <div class="col-sm-4 <?php echo ($ads) ? '' : 'position-relative' ?>">
                    <?php if ($ads) { echo $ads; } else { ?>
                        <div class="position-absolute"><?php _e("Please insert your ads code in Appearance -> Theme Options -> tab 'Ads' in admin") ?></div>
                        <img src="http://www.placehold.it/300x100/EFEFEF/AAAAAA">
                    <?php } ?>
                </div>
                
            </div>
        </div>
    </div><!-- /* ADS area Ends */ -->
    
    <div class="is-section is-blog">
    	<div class="container">
            <div class="row">
            
                <?php
                $args = array(
                    'posts_per_page' => 6,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => 'industry-news-trends',
                    'post_status' => 'publish',
                    'suppress_filters' => true);
                $member_contributions = get_posts($args);

                if (!count($member_contributions)):
                    ?>
                    <div id="post-0" class="post error404 not-found">
                        <h1 class="entry-title"><?php _e('Not Found', 'twentyten'); ?></h1>
                        <div class="entry-content">
                            <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
                            <?php get_search_form(); ?>
                        </div><!-- .entry-content -->
                    </div><!-- #post-0 -->
                    <?php
                else:
                    $idx = 0;
                    foreach ($member_contributions as $post) : setup_postdata($post);
                        if (($idx % 3) == 0) {
                            if ($idx != 0)
                                echo ' <div class="clearfix visible-lg-block visible-md-block"></div>'; }
                        ?>
                        
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="blog-cont">
                                  <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('thumb-300*200');
                                    } else {
                                        ?>
                                        <img data-src="holder.js/300x200" alt="300x200" width="300" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">
                                        <?php
                                    }
                                    ?>
                                </a>
                                    <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                  </div>
                                  <div class="blog-content">
                                    <p><?php _e($functions_ph->string_max_length(get_the_excerpt(), 100)); ?></p>
                                  </div>
                                  <div class="more-btn">
                                  	<a href="<?php the_permalink(); ?>">&nbsp;</a>
                                  </div>                   
                            </div>
                        </div>
                        
                        <?php
                        ++$idx;
                        if ($idx == count($member_contributions)) {
                            echo ' <div class="clearfix visible-lg-block visible-md-block"></div>';
                        }
                    endforeach;
                endif;
                 wp_reset_postdata();
                ?>
                
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>