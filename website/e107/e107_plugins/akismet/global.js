jQuery.noConflict();
	jQuery(document).ready(function(){
//jQuery(function() {

        // alert ( jQuery('#akismet_reuse_spam_message').val() );
    // UpdateAskimetSpam();

    jQuery('#select_deselect').click(function() {
        var checked_status = this.checked;
        jQuery('.akismet_spam_delete').each(function() {
            this.checked = checked_status;
        });
    });

    var akisTabContainers = jQuery('div.tabs > div');
    akisTabContainers.hide().filter(':first').fadeIn('fast');

    jQuery('div.tabs ul.tabNavigation a').click(function() {

        if (this.hash == '#second') {
            UpdateAskimetSpam();
        }

        akisTabContainers.hide();
        akisTabContainers.filter(this.hash).fadeIn('fast');
        jQuery('div.tabs ul.tabNavigation a').removeClass('selected');
        jQuery(this).addClass('selected');
        return false;
    }).filter(':first').click();
    // });




});


function UpdateAskimetSpam() {
    jQuery('#akismet_content_spam').hide();
    jQuery.ajax({
        type: 'post',
        url: 'ajax.php',
        data: 'action=akismet_update_spam',
        complete: function(data) {
            jQuery('#akismet_content_spam').html(data.responseText);
            jQuery('#akismet_content_spam').fadeIn('fast');

        },
        error: function() {
            alert('error');
        }
    });

}

function AkismetModButton() {

    var spam_delete = '';

    jQuery('.akismet_spam_delete').each(

    function() {
        if (this.checked) {

            spam_delete += '&akismet_spam_delete[]=' + this.value;

        }
    });

    var spam_update = '';

    jQuery('.akismet_spam_update').each(

    function() {
        if (this.checked) {

            spam_update += '&akismet_spam_update[]=' + this.value;

        }
    });

    var modQuery = spam_delete ? spam_delete: spam_update;

    var query = 'action=akismet_moderate_button' + modQuery;

    jQuery.ajax({
        type: 'post',
        url: 'ajax.php',
        data: query,
        complete: function(data) {

            UpdateAskimetSpam();

        },
        error: function() {
            alert(query);
        }
    });

}