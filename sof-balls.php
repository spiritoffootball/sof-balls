<?php
/**
 * SOF Balls
 *
 * Plugin Name:       SOF Balls
 * Description:       Provides a Ball Custom Post Type for The Ball website.
 * Version:           1.1.2a
 * Plugin URI:        https://github.com/spiritoffootball/sof-balls
 * GitHub Plugin URI: https://github.com/spiritoffootball/sof-balls
 * Author:            Christian Wach
 * Author URI:        https://haystack.co.uk
 * Text Domain:       sof-balls
 * Domain Path:       /languages
 *
 * @package SOF_Balls
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'SOF_BALLS_VERSION', '1.1.2a' );

// Store reference to this file.
if ( ! defined( 'SOF_BALLS_FILE' ) ) {
	define( 'SOF_BALLS_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'SOF_BALLS_URL' ) ) {
	define( 'SOF_BALLS_URL', plugin_dir_url( SOF_BALLS_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'SOF_BALLS_PATH' ) ) {
	define( 'SOF_BALLS_PATH', plugin_dir_path( SOF_BALLS_FILE ) );
}

/**
 * Main Plugin Class.
 *
 * A class that encapsulates plugin functionality.
 *
 * @since 1.0
 */
class SOF_Balls {

	/**
	 * Custom Post Type loader object.
	 *
	 * @since 1.0
	 * @access public
	 * @var SOF_Balls_CPT
	 */
	public $cpt;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// Initialise on "plugins_loaded".
		add_action( 'plugins_loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Do stuff on plugin init.
	 *
	 * @since 1.0
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && true === $done ) {
			return;
		}

		// Bootstrap plugin.
		$this->include_files();
		$this->setup_objects();
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is now loaded.
		 *
		 * @since 1.0
		 */
		do_action( 'sof_balls/loaded' );

		// We're done.
		$done = true;

	}

	/**
	 * Include files.
	 *
	 * @since 1.0
	 */
	private function include_files() {

		// Include class files.
		include SOF_BALLS_PATH . 'includes/class-cpt.php';

	}

	/**
	 * Set up this plugin's objects.
	 *
	 * @since 1.0
	 */
	private function setup_objects() {

		// Init objects.
		$this->cpt = new SOF_Balls_CPT( $this );

	}

	/**
	 * Registers hook callbacks.
	 *
	 * @since 1.1.1
	 */
	private function register_hooks() {

		// Use translation.
		add_action( 'init', [ $this, 'translation' ] );

	}

	/**
	 * Enable translation.
	 *
	 * @since 1.0
	 */
	public function translation() {

		// Load translations.
		// phpcs:ignore WordPress.WP.DeprecatedParameters.Load_plugin_textdomainParam2Found
		load_plugin_textdomain(
			'sof-balls', // Unique name.
			false, // Deprecated argument.
			dirname( plugin_basename( SOF_BALLS_FILE ) ) . '/languages/' // Relative path to files.
		);

	}

	/**
	 * Perform plugin activation tasks.
	 *
	 * @since 1.0
	 */
	public function activate() {

		// Maybe init.
		$this->initialise();

		/**
		 * Broadcast plugin activation.
		 *
		 * @since 1.0
		 */
		do_action( 'sof_balls/activate' );

	}

	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 1.0
	 */
	public function deactivate() {

		// Maybe init.
		$this->initialise();

		/**
		 * Broadcast plugin deactivation.
		 *
		 * @since 1.0
		 */
		do_action( 'sof_balls/deactivate' );

	}

}

/**
 * Utility to get a reference to this plugin.
 *
 * @since 1.0
 *
 * @return SOF_Balls $sof_balls The plugin reference.
 */
function sof_balls() {

	// Store instance in static variable.
	static $sof_balls = false;

	// Maybe return instance.
	if ( false === $sof_balls ) {
		$sof_balls = new SOF_Balls();
	}

	// --<
	return $sof_balls;

}

// Initialise plugin now.
sof_balls();

// Activation.
register_activation_hook( __FILE__, [ sof_balls(), 'activate' ] );

// Deactivation.
register_deactivation_hook( __FILE__, [ sof_balls(), 'deactivate' ] );

/*
 * Uninstall uses the 'uninstall.php' method.
 *
 * @see https://codex.wordpress.org/Function_Reference/register_uninstall_hook
 */
