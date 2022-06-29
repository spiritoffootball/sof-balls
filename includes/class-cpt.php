<?php
/**
 * CPT Class.
 *
 * Handles CPT functionality by loading classes that provide functionality for
 * individual CPTs.
 *
 * @package SOF_Balls
 * @since 1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * CPT Class.
 *
 * A class that encapsulates CPT functionality.
 *
 * @package SOF_Balls
 */
class SOF_Balls_CPT {

	/**
	 * Plugin object.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Balls CPT object.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $balls The Balls CPT object.
	 */
	public $balls;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param object $plugin The plugin object.
	 */
	public function __construct( $plugin ) {

		// Store reference to plugin.
		$this->plugin = $plugin;

		// Init when this plugin is loaded.
		add_action( 'sof_balls/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this object.
	 *
	 * @since 1.0
	 */
	public function initialise() {

		// Bootstrap class.
		$this->include_files();
		$this->setup_objects();
		$this->register_hooks();

		/**
		 * Broadcast that this class is active.
		 *
		 * @since 1.0
		 */
		do_action( 'sof_balls/cpt/loaded' );

	}

	/**
	 * Includes files.
	 *
	 * @since 1.0
	 */
	public function include_files() {

		include SOF_BALLS_PATH . 'includes/class-cpt-balls.php';

	}

	/**
	 * Instantiates objects.
	 *
	 * @since 1.0
	 */
	public function setup_objects() {

		$this->balls = new SOF_Balls_CPT_Balls( $this );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

	}

}
