/* global jQuery:false */
/* global JACQUELINE_STORAGE:false */

jQuery( document ).ready(
    function() {
        "use strict";

        setTimeout( function() {
            jQuery('.editor-block-list__layout [class^="wp-block-"]').addClass('scheme_' + JACQUELINE_STORAGE['color_scheme']);

            jacqueline_init_media_elements(jQuery('body'));
        }, 100 );
    }
);

function jacqueline_init_media_elements(cont) {
    "use strict";
    if (JACQUELINE_STORAGE['media_elements_enabled'] && cont.find('audio,video').length > 0) {
        if (window.mejs) {
            if (typeof window.mejs.MepDefaults != 'undefined') window.mejs.MepDefaults.enableAutosize = false;
            if (typeof window.mejs.MediaElementDefaults != 'undefined') window.mejs.MediaElementDefaults.enableAutosize = false;
            cont.find('audio:not(.wp-audio-shortcode),video:not(.wp-video-shortcode)').each(function() {
                if (jQuery(this).parents('.mejs-mediaelement').length == 0) {
                    var media_tag = jQuery(this);
                    var settings = {
                        enableAutosize: true,
                        videoWidth: -1,		// if set, overrides <video width>
                        videoHeight: -1,	// if set, overrides <video height>
                        audioWidth: '100%',	// width of audio player
                        audioHeight: 30,	// height of audio player
                        success: function(mejs) {
                            var autoplay, loop;
                            if ( 'flash' === mejs.pluginType ) {
                                autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
                                loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;
                                autoplay && mejs.addEventListener( 'canplay', function () {
                                    mejs.play();
                                }, false );
                                loop && mejs.addEventListener( 'ended', function () {
                                    mejs.play();
                                }, false );
                            }
                            media_tag.parents('.sc_audio,.sc_video').addClass('inited sc_show');
                        }
                    };
                    jQuery(this).mediaelementplayer(settings);
                }
            });
        } else
            setTimeout(function() { jacqueline_init_media_elements(cont); }, 400);
    }
}
