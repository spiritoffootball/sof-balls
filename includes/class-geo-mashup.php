<?php
/**
 * Geo Mashup Class.
 *
 * Handles Geo Mashup functionality.
 *
 * @package SOF_Balls
 * @since 1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Geo Mashup Class.
 *
 * A class that encapsulates Geo Mashup functionality.
 *
 * @package SOF_Balls
 */
class SOF_Balls_Geo_Mashup {

	/**
	 * Plugin object.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

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
		$this->register_hooks();

		/**
		 * Broadcast that this class is active.
		 *
		 * @since 1.0
		 */
		do_action( 'sof_balls/cpt/loaded' );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		//add_filter( 'wpcv_eo_maps/geo_mashup_custom/dir_path', [ $this, 'filter_dir_path' ], 10 );
		//add_filter( 'wpcv_eo_maps/geo_mashup_custom/url_path', [ $this, 'filter_url_path' ], 10 );

		add_filter( 'wpcv_eo_maps/geo_mashup_custom/file_url', [ $this, 'filter_file_url' ], 10, 3 );

		add_filter( 'geo_mashup_locations_json_object', [ $this, 'filter_locations_json' ], 10, 2 );

	}

	/**
	 * Filters the URL of a custom file if it exists.
	 *
	 * @since 1.0
	 *
	 * @param string $url The URL of the custom file.
	 * @param string $file The custom file being checked.
	 * @param array $files The full array of custom files.
	 * @param string $url The modified URL of the custom file.
	 */
	public function filter_file_url( $url, $file, $files ) {

		// Bail if not the Custom Javascript file.
		if ( 'custom.js' !== $file ) {
			return $url;
		}

		// Target with our custom js file.
		$url = SOF_BALLS_URL . 'assets/templates/geo-mashup/custom.js';

		///*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'url' => $url,
			'file' => $file,
			'files' => $files,
			//'backtrace' => $trace,
		], true ) );
		//*/

		// --<
		return $url;

	}

	/**
	 * Filters the path to the directory holding the templates.
	 *
	 * @since 1.0
	 *
	 * @param string $dir_path The path to the directory holding the templates.
	 * @return string $dir_path The modified path to the directory holding the templates.
	 */
	public function filter_dir_path( $dir_path ) {

		///*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'dir_path' => $dir_path,
			//'backtrace' => $trace,
		], true ) );
		//*/

		// --<
		return $dir_path;

	}

	/**
	 * Filters the URL to the directory holding the templates.
	 *
	 * @since 1.0
	 *
	 * @param string $url_path The URL to the directory holding the templates.
	 * @return string $url_path The modified URL to the directory holding the templates.
	 */
	public function filter_url_path( $url_path ) {

		///*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'url_path' => $url_path,
			//'backtrace' => $trace,
		], true ) );
		//*/

		// --<
		return $url_path;

	}

	/**
	 * Filters the JSON object.
	 *
	 * @since 1.0
	 *
	 * @param array $json_properties The JSON properties object.
	 * @param WP_Post $queried_object The WordPress Post object.
	 * @return array $json_properties The modified JSON object.
	 */
	public function filter_locations_json( $json_properties, $queried_object ) {

		// Bail if not a Post.
		if ( empty( $queried_object->object_id ) ) {
			return $json_properties;
		}

		// Get the Post ID.
		$post_id = $queried_object->object_id;

		// Bail if empty.
		if ( empty( $post_id ) ) {
			return $json_properties;
		}

		// Bail if not our Post Type.
		if ( $this->plugin->cpt->balls->post_type_name !== get_post_type( $post_id ) ) {
			return $json_properties;
		}

		// Add an identifier.
		$json_properties['is_ball'] = 1;
		//$json_properties['ball_icon'] = SOF_BALLS_URL . 'assets/images/geo-mashup/mm_ball_36_black.png';
		//$json_properties['ball_icon'] = SOF_BALLS_URL . 'assets/images/geo-mashup/mm_ball_36_white.png';
		$json_properties['ball_icon'] = SOF_BALLS_URL . 'assets/images/geo-mashup/mm_ball_36_outline.png';

		///*
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'post_id' => $post_id,
			'json_properties' => $json_properties,
			//'backtrace' => $trace,
		], true ) );
		//*/

		/*
		$key = 'KEY_OF_YOUR_DATE_CUSTOM_FIELD';

		$meta_field = get_post_meta( $post_id, $key, true );

		$complete_date = strtotime( $meta_field );
		$todays_date = time();

		if ( $todays_date > $complete_date ) {
			$json_properties['my_complete'] = 1;
		} else {
			$json_properties['my_complete'] = 0;
		}
		*/

		// --<
		return $json_properties;

	}

}
