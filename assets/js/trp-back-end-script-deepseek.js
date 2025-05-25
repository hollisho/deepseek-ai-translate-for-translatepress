jQuery(document).on('trpInitFieldToggler', function() {
    var deepseekTranslateKey = TRP_Field_Toggler();
    deepseekTranslateKey.init('.trp-translation-engine', '#trp-deepseek-api-key', 'deepseek_translate' );

    function TRP_show_hide_machine_translation_options(){
        if( jQuery( '#trp-machine-translation-enabled' ).val() != 'yes' )
            jQuery( '.trp-machine-translation-options tbody tr:not(:first-child)').hide()
        else
            jQuery( '.trp-machine-translation-options tbody tr:not(:first-child)').show()

        if( jQuery( '#trp-machine-translation-enabled' ).val() == 'yes' )
            jQuery('.trp-translation-engine:checked').trigger('change')
    }

    TRP_show_hide_machine_translation_options();
})
