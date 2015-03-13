<?php
global $comp_id, $modal_id, $file_type, $file_info_list, $default;
if ($modal_id) {
    ?>
    <a href="#<?php echo $modal_id ?>_modal" role="button" class="btn btn-primary btn-sm" data-toggle="modal"><?php _e('Add', 'twentyten') ?></a>
    
    <script type="text/javascript" src="<?php echo get_bloginfo('template_url') ?>/plugins/fileupload/js/ajaxupload.3.5.js" ></script>
    <script>
        jQuery(document).ready(function() {
            var btnUpload=jQuery('#<?php echo $modal_id ?>-file-upload-ctrl');
            new AjaxUpload(btnUpload, {
                action: '<?php echo get_bloginfo('template_url') ?>/inc/shortcodes/upload-file.php?comp_id=<?php echo $comp_id; ?>&file_type=<?php echo $file_type; ?>&default=<?php echo $default; ?>',
                name: 'uploadfile',
                dataType : "json",
                onSubmit: function(file, ext){
                    jQuery('#<?php echo $modal_id ?>_modal .upload-status').show();
                    jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>-file-upload-ctrl').hide();
                },
                onComplete: function(file, response){
                    response = jQuery.parseJSON(response);

                    jQuery('#<?php echo $modal_id ?>_modal .upload-status').hide();
                    if(response.error) {
                        jQuery('#<?php echo $modal_id ?>_modal .file-upload-error').html(response.error);
                        jQuery('#<?php echo $modal_id ?>_modal .file-upload-error').css('display', 'inline-block');

                        jQuery('#<?php echo $modal_id ?>_modal .file-upload-error').fadeOut(4000, function() {
                            jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>-file-upload-ctrl').show();
                        });
                    } else {
                        jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>-file-upload-ctrl').hide();
                        jQuery('#<?php echo $modal_id ?>_modal .file-name').html(file);
                        jQuery('#<?php echo $modal_id ?>_modal .file-grp').show();
                        jQuery('#<?php echo $modal_id ?>_modal .file-grp #<?php echo $modal_id ?>_delete_comp_file').attr('file_id', response.last_id);
                    }
                }
            });
                                                                                                                                                                                
            jQuery("#<?php echo $modal_id ?>_delete_comp_file").live('click', function () {
                $obj = jQuery(this).parent().parent();
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'delete-profile-comp-file-itlocation',
                        'security':jQuery('#delete-profile-comp-file-security').val(), 
                        'file_id':jQuery(this).attr('file_id')
                    },
                    success: function(response) {
                        jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>-file-upload-ctrl').show();
                        jQuery('.file-grp', jQuery($obj)).hide();
                        jQuery('.file-name', jQuery($obj)).html('');
                    }
                });
            });

            jQuery("#<?php echo $modal_id ?>_comp_file_desc").live('keyup', function(){
                var str=jQuery(this).val();
                strlength = str.length;
                jQuery(".comp_file_desc_number", jQuery(this).parent()).html(300-strlength);
            });
                                                                                                                                                                                        
            jQuery("#<?php echo $modal_id ?>_modale-comp-file-upload-ok").live('click', function () {
                jQuery('#<?php echo $modal_id ?>_modal').modal('hide');
            });
                                                                                                                                                                                    
            jQuery('#<?php echo $modal_id ?>_modal').on('hide', function () {
                if(jQuery('#<?php echo $modal_id ?>_modal .file-name').html()) {
                    jQuery.ajax({
                        type : "post",
                        dataType : "json",
                        url : admin_ajax.url,
                        data: {
                            'action':'update-profile-comp-file-itlocation',
                            'security':jQuery('#update-profile-comp-file-security').val(), 
                            'file_id':jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_delete_comp_file').attr('file_id'),
                            'title':jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_title').val(),
                            'desc':jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_desc').val(),
                            'filetype':'<?php echo $file_type ?>'
                        },
                        success: function(response) {
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp .title').html(response.title);
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp .file_size').html(response.filesize);
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp .file_ext').html(response.extension);
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp .desc').html(response.description);
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp .delete_comp_file').attr('file_id', response.id);
                            jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp img').attr('src', response.icon_url);

                            jQuery('#comp-file-list-<?php echo $modal_id ?>').append(jQuery('#<?php echo $modal_id ?>-comp-file-list-tmp').html());
                            jQuery('#<?php echo $modal_id ?>_modal .file-grp').hide();
                            jQuery('#<?php echo $modal_id ?>_modal .file-grp .file-name').html('');
                            jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_delete_comp_file').attr('file_id','');
                            jQuery('#<?php echo $modal_id ?>_modal .upload-status').hide();
                            jQuery('#<?php echo $modal_id ?>_modal .file-upload-error').hide();
                            jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>-file-upload-ctrl').show();
                                                                                            
                            jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_title').val('');
                            jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_desc').val('');
                        }
                    });
                }
                jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_title').val('');
                jQuery('#<?php echo $modal_id ?>_modal #<?php echo $modal_id ?>_comp_file_desc').val('');
            })
        });
    </script>

    <!--<div id="<?php echo $modal_id ?>_modal" class="modal hide fade file_upload_modal" role="dialog" aria-labelledby="<?php echo $modal_id ?>Label" aria-hidden="true">-->
    <div class="modal fade file_upload_modal" id="<?php echo $modal_id ?>_modal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $modal_id ?>Label" aria-hidden="true">
        <?php wp_nonce_field('delete-profile-comp-file-itlocation', 'delete-profile-comp-file-security'); ?>
        <?php wp_nonce_field('update-profile-comp-file-itlocation', 'update-profile-comp-file-security'); ?>
        
        
        <div class="modal-dialog pop-up">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 id="<?php echo $modal_id ?>Label" class="model-title"><?php _e('Upload file', 'twentyten') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="<?php echo $modal_id ?>-file-upload-ctrl" class="control-label"><?php _e('File', 'twentyten') ?></label>
                            <div id="<?php echo $modal_id ?>-file-upload-ctrl" class="btn btn-primary"><?php _e('Select File', 'twentyten') ?></div>
                            <div class="upload-status margin-only-top-5" style="display:none"><img src="<?php echo get_bloginfo('template_url') ?>/plugins/fileupload/images/loader.gif"></div>
                            <div class="file-grp font-size-16 margin-only-top-5" style="display:none"><div class="file-name pull-left"></div> &nbsp;<i id="<?php echo $modal_id ?>_delete_comp_file" class="iconic-o-x cursor-pointer" file_id=""></i></div>
                            <div class="file-upload-error alert alert-error span7 pull-left padding-only-bottom-5 padding-only-top-5 margin-bottom-0 margin-left-0" style="display: none;"></div>
                    </div>
                        <div class="form-group">
                            <label for="comp_file_title" class="control-label"><?php _e('Title', 'twentyten') ?></label>
                            <input type="text" class="form-control" name="comp_file_title" id="<?php echo $modal_id ?>_comp_file_title" maxlength="100">
                        </div>
        
                        <div class="form-group">
                            <label for="comp_file_desc" maxlength="300" class="control-label"><?php _e('Description', 'twentyten') ?></label>
                            <textarea class="form-control" name="comp_file_desc" id="<?php echo $modal_id ?>_comp_file_desc"></textarea>
                            <span class="help-block aRight"><span class="comp_file_desc_number">300</span> characters left.</span>
                        </div>
                        <div class="form-group">
                            <input type="button" name="modale-comp-file-upload-ok" id="<?php echo $modal_id ?>_modale-comp-file-upload-ok" class="btn btn-sm btn-primary" value="<?php _e('Upload', 'twentyten') ?>">
                        </div>
                </div>
                
            </div>
        </div>
        
        
    </div><br/>
    <?php } ?>
<table class="table table-striped" id="comp-file-list-<?php echo $modal_id; ?>">
    <?php
    if (count($file_info_list)) {
        foreach ($file_info_list as $file_info) {
            ?>
            <tr>
                <td>
                    <div>
                        <span class="pull-left title"><?php _e($file_info->title); ?></span>
                        <span class="pull-right">Size: <span class="file_size"><?php _e($file_info->filesize); ?></span>KByte | Type: <span class="file_ext"><?php _e(strtoupper($file_info->extension)); ?></span></span>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <span class="pull-left">
                            <?php
                            $extension = 'default';
                            if ($file_info->extension)
                                $extension = $file_info->extension;
                            ?>
                            <img src="<?php echo get_bloginfo('template_url') ?>/images/<?php echo strtolower($extension) ?>-icon.png" class="margin-only-right-10">
                        </span>
                        <span class="pull-left desc"><?php _e($file_info->description); ?></span>
                        <span class="pull-right">
                            <?php
                            if ($modal_id) {
                                ?>
                                <i class="iconic-o-x delete_comp_file font-size-16 cursor-pointer" file_id="<?php echo $file_info->id ?>"></i>
                                <?php
                            } else {
                                //$upload_dir = wp_upload_dir();
                                $upload_url = get_bloginfo('template_url') . '/inc/file-download.php?file_id='.$file_info->id;
                                //$upload_url .= $upload_dir["basedir"] . "/comp_files/" . $comp_id . '/' . $file_info->real_filename;
                                //$upload_url .= '&orig_nm=' . $file_info->filename;
                                ?>
                                <a href="<?php echo $upload_url ?>"><i class="iconic-download font-size-14"></i></a>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<?php if ($modal_id) { ?>
    <table class="display-none" id="<?php echo $modal_id ?>-comp-file-list-tmp">
        <tr>
            <td>
                <div>
                    <span class="pull-left title">title</span>
                    <span class="pull-right">Size: <span class="file_size">0</span>KByte | Type: <span class="file_ext">JPG</span></span>
                </div>
                <div class="clearfix"></div>
                <div>
                    <span class="pull-left">
                        <img src="http://192.168.1.102/wp-mike/wp-content/themes/itlocation/images/default-icon.png" class="margin-only-right-10">
                    </span>
                    <span class="pull-left desc">description</span>
                    <span class="pull-right">
                        <i class="iconic-o-x delete_comp_file font-size-16 cursor-pointer" file_id=""></i>
                    </span>
                </div>
            </td>
        </tr>
    </table>
    <?php } ?>