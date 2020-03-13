<div class="wrap about-wrap">
    <h1><?php esc_html_e( 'weForms', 'wp-user-frontend' ); ?></h1>

    <p class="about-text"><?php esc_html_e( 'The Easiest &amp; Fastest Contact Form Plugin on WordPress', 'wp-user-frontend' ); ?></p>

    <hr>
    <p><?php echo wp_kses_post( __( 'Quickly create rich contact forms to generate leads, taking feedbacks, onboarding visitors and flourishing <br /> your imagination! Comes with the best frontend post submission plugin for WordPress, WP User Frontend.', 'wp-user-frontend' ) ); ?>


    <div class="install" id="wpuf-weforms-installer-notice" style="padding: 1em 0; position: relative;">
        <p>
            <button id="wpuf-weforms-installer" class="button button-primary"><?php esc_html_e( 'Install Now', 'wp-user-frontend' ); ?></button>
        </p>
    </div>

    <figure class="we-gif" style="width: 944px;">
        <img class="img-responsive inline-block image-gif shadow" src="<?php echo esc_url("https://wedevs-com-wedevs.netdna-ssl.com/wp-content/uploads/2017/08/weforms-final-promo-video.gif" ) ?>" >
    </figure>
</div>

<script type="text/javascript">
    (function ($) {
        var wrapper = $('#wpuf-weforms-installer-notice');

        wrapper.on('click', '#wpuf-weforms-installer', function (e) {
            var self = $(this);

            e.preventDefault();
            self.addClass('install-now updating-message');
            self.text('<?php echo esc_html__( 'Installing...', 'weforms' ); ?>');
            var data = {
                action: 'wpuf_weforms_install',
                _wpnonce: '<?php echo esc_html( wp_create_nonce( 'wpuf-weforms-installer-nonce' ) ); ?>'
            };

            $.post(ajaxurl, data, function (response) {
                if (response.success) {
                    self.attr('disabled', 'disabled');
                    self.removeClass('install-now updating-message');
                    self.text('<?php echo esc_html__( 'Installed', 'weforms' ); ?>');

                    window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=weforms' ) ); ?>';
                }
            });
        });
    })(jQuery);
</script>

<style>
    .widget-wrap{
        width: 100%;
        text-align: center;
        align-content: center;
    }

</style>
