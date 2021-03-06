jQuery(document).ready(function() {
   jQuery("#comp_description").live('keyup', function(){
        var str=jQuery(this).val();
        strlength = str.length;
        jQuery("#desc_number").html(1000-strlength);
    });
    
    jQuery("#comp_country").select2({
        formatResult: countries_format,
        formatSelection: countries_format,
        placeholder: "Select Country",
        allowClear: true,
        escapeMarkup: function(m) {
            return m;
        }
    }).on("change", function(e) { 
        jQuery('#comp_state_ctrl').hide();
        jQuery('#comp_state_ctrl').prev().show();

        $data = "action=get-state-itlocation&security=" + jQuery('#get-state-security').val() + "&country_id=" + e.val;
        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                jQuery('#comp_state_ctrl').html(response);
                jQuery('#comp_state_ctrl').show();
                jQuery('#comp_state_ctrl').prev().hide();
            }
        });
    });
    
    jQuery('#user-login').on('hide', function () {
        jQuery('#username', jQuery('#user-login')).val('');
        jQuery('#user_password', jQuery('#user-login')).val('');
    });
        
    jQuery('#go-signup-btn', jQuery('#user-login')).live('click', function() {
              jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Signup for Basic IT Locator Listing – It’s Free!'); 
              jQuery('#user-login').modal('hide');
    });
    
    jQuery('.btn_for_modal').live('click', function () {
        if(jQuery(this).attr('val') == 'free') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Signup for Basic IT Locator Listing – It’s Free!');
            jQuery('#member_level', jQuery('#signup-modal')).val('0');
        }
        if(jQuery(this).attr('val') == 'member') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Become an IT Locator Member and Stand Out From Your Competition!');
            jQuery('#member_level', jQuery('#signup-modal')).val('1');
        }
        if(jQuery(this).attr('val') == 'premium') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Become a Premium IT Locator Member and Maximize Your Lead Generation!');
            jQuery('#member_level', jQuery('#signup-modal')).val('2');
        }
    });

    jQuery('#go-login-btn', jQuery('#signup-modal')).live('click', function() {
        jQuery('#signup-modal').modal('hide');
    });
    
    jQuery('#login-modal').on('hide', function () {
        jQuery('#full_name', jQuery('#signup-modal')).val('');
        jQuery('#user_email', jQuery('#signup-modal')).val('');
        jQuery('#username', jQuery('#signup-modal')).val('');
        jQuery('#user_password', jQuery('#signup-modal')).val('');
    });

    jQuery("#subscriber_itlocation_modal_form").live('submit', function () {
        jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '');
        val = jQuery('.subscriber_email_itlocation', jQuery(this)).val();
        val = jQuery.trim(val);
        if(val) {
            if(val.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                jQuery('#subscriber-itlocation-modal .subscriber_email_itlocation').attr('readonly', 'readonly');
                jQuery('#subscriber-itlocation-modal .signup_btn').attr('disabled', 'disabled');
                jQuery('#subscriber-itlocation-modal #not_now_subscriber_itlocation_btn').attr('disabled', 'disabled');
                
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'subscriber-modal-itlocation',
                        'security':jQuery('#subscriber-modal-itlocation-security').val(),
                        'subscriber_email':val
                    },
                    success: function(response) {
                        jQuery('#subscriber-itlocation-modal .subscriber_email_itlocation').removeAttr('readonly');
                        jQuery('#subscriber-itlocation-modal .signup_btn').removeAttr('disabled');
                        jQuery('#subscriber-itlocation-modal #not_now_subscriber_itlocation_btn').removeAttr('disabled');

                        jQuery('#subscriber-itlocation-modal').modal('hide');
                        jQuery('#subscriber-alert-itlocation-modal').modal('show');
                        jQuery('#subscriber-alert-itlocation-modal #subscriber-alert-email').html(val);
                    }
                });
            } else {
                jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '#B94A48');
            }
        } else {
            jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '#B94A48');
            jQuery('.subscriber_email_itlocation', jQuery(this)).val('');
        }

        return false;
    });
    
    jQuery("#not_now_subscriber_itlocation_btn").live('click', function () {
        jQuery('#subscribe').modal('hide');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'subscriber-modal-itlocation',
                'security':jQuery('#subscriber-modal-itlocation-security').val()
            },                
            success: function(response) {
            }
        });
        return false;
    });
    
	jQuery("#subscriber_alert_close_btn").live('click', function () {
        jQuery('#subscriber-alert-itlocation-modal').modal('hide');
    });
    
    jQuery("#contact-us-form").live('submit', function () {
        jQuery('#send-email-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'send-email-itlocation',
                'security':jQuery('#send-email-itlocation-security', jQuery(this)).val(),
                'from_email':jQuery('#from_email', jQuery(this)).val(),
                'title':jQuery('#email_title', jQuery(this)).val(),
                'content':jQuery('#email_content', jQuery(this)).val()
            },
            success: function(response) {
                jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                if(!response.error) {
                    jQuery('#email_title', jQuery($this)).val('');
                    jQuery('#email_content', jQuery($this)).val('');
                    jQuery('#from_email', jQuery($this)).val('');
                    jQuery('#alert-error', jQuery($this)).show();
                    jQuery('#alert-error', jQuery($this)).fadeOut(4000);
                }
	
            }
        });
        return false;
    });
	
	jQuery("#support-us-form").live('submit', function () {
        jQuery('#send-email-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'send-support-email-itlocation',
                'security':jQuery('#send-email-itlocation-security', jQuery(this)).val(),
                'from_email':jQuery('#from_email', jQuery(this)).val(),
                'title':jQuery('#email_title', jQuery(this)).val(),
                'content':jQuery('#email_content', jQuery(this)).val()
            },
            success: function(response) {
                jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                if(!response.error) {
                    jQuery('#email_title', jQuery($this)).val('');
                    jQuery('#email_content', jQuery($this)).val('');
                    jQuery('#from_email', jQuery($this)).val('');
                    jQuery('#alert-error', jQuery($this)).show();
                    jQuery('#alert-error', jQuery($this)).fadeOut(4000);
                }
            }
        });
        return false;
    });
	
    jQuery("#delete-contributions-btn").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            jQuery(this).attr('disabled', 'disabled');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-contributions-itlocation',
                    'security':jQuery('#delete-contributions-itlocation-security').val(),
                    'pid':jQuery(this).attr('pid')
                },
                success: function(response) {
                    location.href = response.r_url;
                }
            });
        }
        return false;
    });
    
    jQuery(".delete-contributions-btn").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            jQuery(this).attr('disabled', 'disabled');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-contributions-itlocation',
                    'security':jQuery('#delete-contributions-itlocation-security').val(),
                    'pid':jQuery(this).attr('pid')
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
        return false;
    });
});

function countries_format(state) {
    if (!state.id) return state.text; // optgroup
    return "<img class='flag' src='"+images.url+"/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
}