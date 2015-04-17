<?php
/*
Plugin Name: Inbound Mailer
Plugin URI: http://www.inboundnow.com/
Description: Email marketing component developed for Inbound Now tools.
Version: 1.0.1
Author: Inbound Now
Author URI: http://www.inboundnow.com/
Text Domain: inbound-email
Domain Path: lang
*/

if ( !class_exists('Inbound_Mailer_Plugin')	) {

	final class Inbound_Mailer_Plugin {

		/* START PHP VERSION CHECKS */
		/**
		 * Admin notices, collected and displayed on proper action
		 *
		 * @var array
		 */
		public static $notices = array();

		/**
		 * Whether the current PHP version meets the minimum requirements
		 *
		 * @return bool
		 */
		public static function is_valid_php_version() {
			return version_compare( PHP_VERSION, '5.3', '>=' );
		}

		/**
		 * Invoked when the PHP version check fails. Load up the translations and
		 * add the error message to the admin notices
		 */
		static function fail_php_version() {
			self::notice( __( 'Inbound Email Component requires PHP version 5.3+, plugin is currently NOT ACTIVE.', 'inbound-email' ) );
		}

		/**
		 * Handle notice messages according to the appropriate context (WP-CLI or the WP Admin)
		 *
		 * @param string $message
		 * @param bool $is_error
		 * @return void
		 */
		public static function notice( $message, $is_error = true ) {
			if ( defined( 'WP_CLI' ) ) {
				$message = strip_tags( $message );
				if ( $is_error ) {
					WP_CLI::warning( $message );
				} else {
					WP_CLI::success( $message );
				}
			} else {
				// Trigger admin notices
				add_action( 'all_admin_notices', array( __CLASS__, 'admin_notices' ) );

				self::$notices[] = compact( 'message', 'is_error' );
			}
		}

		/**
		 * Show an error or other message in the WP Admin
		 *
		 * @action all_admin_notices
		 * @return void
		 */
		public static function admin_notices() {
			foreach ( self::$notices as $notice ) {
				$class_name	= empty( $notice['is_error'] ) ? 'updated' : 'error';
				$html_message = sprintf( '<div class="%s">%s</div>', esc_attr( $class_name ), wpautop( $notice['message'] ) );
				echo wp_kses_post( $html_message );
			}
		}
		/* END PHP VERSION CHECKS */

		/**
		* Main Inbound_Mailer_Plugin Instance
		*/
		public function __construct() {
			self::define_constants();
			self::includes();
			self::load_text_domain_init();
		}

		/*
		* Setup plugin constants
		*
		*/
		private static function define_constants() {

			/* this is for testing - the real api key will be served by inboundnow */
			define('MANDRILL_APIKEY', 'pQrhb6UM1EFJ2sB_ikLVXA' );


			define('INBOUND_EMAIL_CURRENT_VERSION', '2.2.1' );
			define('INBOUND_EMAIL_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
			define('INBOUND_EMAIL_PATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );
			define('INBOUND_EMAIL_SLUG', plugin_basename( dirname(__FILE__) ) );
			define('INBOUND_EMAIL_FILE', __FILE__ );

			$uploads = wp_upload_dir();
			define('INBOUND_EMAIL_UPLOADS_PATH', $uploads['basedir'].'/inbound-email/templates/' );
			define('INBOUND_EMAIL_UPLOADS_URLPATH', $uploads['baseurl'].'/inbound-email/templates/' );
			define('INBOUND_EMAIL_STORE_URL', 'http://www.inboundnow.com/market/' );

		}

		/* Include required plugin files */
		private static function includes() {

			switch (is_admin()) :
				case true :
					/* loads admin files */
					include_once('classes/class.inboundnow.php');
					include_once('classes/class.activation.php');
					include_once('classes/class.activation.database-routines.php');
					include_once('classes/class.inboundnow.php');
					include_once('classes/class.options-api.php');
					include_once('classes/class.postmeta.php');
					include_once('classes/class.post-type.inbound-email.php');
					include_once('classes/class.extension.wp-lead.php');
					include_once('classes/class.extension.wordpress-seo.php');
					include_once('classes/class.metaboxes.inbound-email.php');
					include_once('classes/class.token-engine.php');
					include_once('classes/class.menus.php');
					include_once('classes/class.ajax.listeners.php');
					include_once('classes/class.enqueues.php');
					include_once('classes/class.settings.php');
					include_once('classes/class.notifications.php');
					include_once('classes/class.clone-post.php');
					include_once('classes/class.acf-integration.php');
					include_once('classes/class.variations.php');
					include_once('classes/class.load.email-settings.php');
					include_once('classes/class.load.email-templates.php');
					include_once('classes/class.templates.list-table.php');
					include_once('classes/class.templates.manage.php');
					include_once('modules/module.utils.php');
					include_once('classes/class.customizer.php');
					include_once('classes/class.tracking.php');
					include_once('classes/class.statistics.php');
					include_once('classes/class.scheduling.php');
					include_once('classes/class.cron-api.php');
					include_once('classes/class.sending.php');
					include_once('classes/class.mandrill.php');
					include_once('classes/class.unsubscribe.php');

					BREAK;

				case false :
					/* load front-end files */
					include_once('classes/class.inboundnow.php');
					include_once('classes/class.options-api.php');
					include_once('classes/class.postmeta.php');;
					include_once('classes/class.load.email-templates.php');
					include_once('classes/class.post-type.inbound-email.php');
					include_once('classes/class.extension.wp-lead.php');
					include_once('classes/class.extension.wordpress-seo.php');
					include_once('classes/class.enqueues.php');
					include_once('classes/class.tracking.php');
					include_once('classes/class.ajax.listeners.php');
					include_once('classes/class.variations.php');
					include_once('classes/class.templates.preview.php');
					include_once('classes/class.unsubscribe.php');
					include_once('classes/class.acf-integration.php');
					include_once('modules/module.utils.php');
					include_once('classes/class.customizer.php');
					include_once('classes/class.token-engine.php');
					include_once('classes/class.cron-api.php');
					include_once('classes/class.sending.php');
					include_once('classes/class.mandrill.php');
					include_once('classes/class.scheduling.php');
					include_once('classes/class.settings.php');

					BREAK;
			endswitch;
		}

		/**
		*	Loads the correct .mo file for this plugin
		*
		*/
		private static function load_text_domain_init() {
			add_action( 'init' , array( __CLASS__ , 'load_text_domain' ) );
		}

		public static function load_text_domain() {
			load_plugin_textdomain( 'inbound-email' , false , INBOUND_EMAIL_SLUG . '/lang/' );
		}


	}

	/* Initiate Plugin */
	if ( Inbound_Mailer_Plugin::is_valid_php_version() ) {
		// Get Inbound Now Running
		$GLOBALS['Inbound_Mailer_Plugin'] = new Inbound_Mailer_Plugin;
	} else {
		// Show Fail
		Inbound_Mailer_Plugin::fail_php_version();
	}

	/**
	*  Checks if inbound-mailer plugin is active 
	*/
	function mailer_check_active() {
		return 1;
	}
}
