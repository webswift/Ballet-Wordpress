<?php

/**
 * Class FLBuilderBoosterCustomizer
 */
final class FLBuilderBoosterCustomizer {

    /**
     * FLBuilderBoosterCustomizer constructor.
     */
    public function __construct() {

        add_action( 'customize_controls_print_styles',         array( $this, 'print_styles' ) );
        add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_script_templates' ) );

    }

    /**
     * Print styles for our new section
     */
    public function print_styles() {

        ?>
        <style type="text/css">
            #accordion-section-bb-launcher {
                background: #fff;
                color: #555;
            }
            #accordion-section-bb-launcher p {
                padding: 14px 14px 0;
            }
            #accordion-section-bb-launcher a {
                margin: 0 14px 14px;
            }
            #accordion-section-bb-launcher h3 {
                color: #555;
            }
        </style>
        <?php

    }

    /**
     * Print template and necessary JS to show new section in customizer
     */
    public function print_script_templates() {

        global $wp_customize;

        $post_id = url_to_postid( $wp_customize->get_preview_url() );
        $url     = add_query_arg( 'fl_builder', '', $wp_customize->get_preview_url() );

        ?>
        <script type="text/html" id="bb-launcher">
            <li id="accordion-section-bb-launcher" class="accordion-section control-section control-section-themes" style="display:none;">
                <p style="margin-top:0;"><?php _e( 'Use an easy drag-and-drop builder to edit content on this page.', 'bb-booster' ); ?></p>
                <a href="<?php echo esc_url( $url ) ?>" id="bb-launcher-button" class="button button-primary"><?php _e( 'Launch Page Builder', 'bb-booster' ); ?></a>
            </li>
        </script>

        <script type="text/javascript">

            ( function( $, api ) {

                var bb_launcher = {

                    $el: false,

                    init: function() {

                        if ( this.$el ) {

                            return;

                        }

                        this.$el = $( '#accordion-section-bb-launcher' );

                        this.show();

                    },

                    destroy: function() {

                        if ( this.$el ) {

                            this.$el.hide();

                            delete this.$el;

                        }

                    },

                    show: function() {

                        bb_launcher.$el.show();

                    },

                    hide: function() {

                        bb_launcher.$el.hide();

                    }

                };

                function maybeShowBBLauncher() {

                    $( '#accordion-section-bb-launcher' ).hide();

                    var url = this.previewUrl();

                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'bb_booster_post_previewed_is_editable',
                            _ajax_nonce: '<?php echo esc_js( wp_create_nonce( 'bb_booster_post_previewed_is_editable' ) ); ?>',
                            url: url
                        },
                        dataType: 'json',
                        success: function ( response ) {

                            if ( true === response.success ) {

                                bb_launcher.init();

                                bb_launcher.$el.find( '#bb-launcher-button' ).prop( 'href', url + ( url.indexOf( '?' ) === -1 ? '?' : '&' ) + 'fl_builder' );

                                return;

                            }

                            bb_launcher.destroy();

                        }
                    });

                }

                $( document ).ready( function() {

                    $( '#accordion-section-themes' ).before( $.trim( $( '#bb-launcher' ).html() ) );

                    wp.customize.previewer.bind( 'url', maybeShowBBLauncher );

                    <?php if ( FLBuilderBooster::is_post_editable( $post_id ) ) : ?>

                    bb_launcher.init();

                    <?php endif; ?>

                });

            }) ( jQuery, wp.customize );

        </script>
        <?php

    }

}
