<?php
/**
 * Balls Custom Post Type Class.
 *
 * Handles providing a "Balls" Custom Post Type.
 *
 * @package SOF_Balls
 * @since 1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Balls Custom Post Type Class.
 *
 * A class that encapsulates a "Balls" Custom Post Type.
 *
 * @since 1.0
 */
class SOF_Balls_CPT_Balls {

	/**
	 * Plugin object.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Custom Post Type object.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $cpt The Custom Post Type object.
	 */
	public $cpt;

	/**
	 * Custom Post Type name.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $cpt The name of the Custom Post Type.
	 */
	public $post_type_name = 'ball';

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 1.0
	 * @access public
	 * @var object $cpt The REST base of the Custom Post Type.
	 */
	public $post_type_rest_base = 'balls';

	/**
	 * Taxonomy name.
	 *
	 * @since 1.0
	 * @access public
	 * @var str $taxonomy_name The name of the Custom Taxonomy.
	 */
	public $taxonomy_name = 'ball-type';

	/**
	 * Taxonomy REST base.
	 *
	 * @since 1.0
	 * @access public
	 * @var str $taxonomy_rest_base The REST base of the Custom Taxonomy.
	 */
	public $taxonomy_rest_base = 'ball-type';

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// Store references.
		$this->plugin = $parent->plugin;
		$this->cpt = $parent;

		// Init when this plugin is loaded.
		add_action( 'sof_balls/cpt/loaded', [ $this, 'register_hooks' ] );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		// Activation and deactivation.
		add_action( 'sof_balls/activate', [ $this, 'activate' ] );
		add_action( 'sof_balls/deactivate', [ $this, 'deactivate' ] );

		// Always create post type.
		add_action( 'init', [ $this, 'post_type_create' ], 20 );

		// Make sure our feedback is appropriate.
		add_filter( 'post_updated_messages', [ $this, 'post_type_messages' ] );

		// Make sure our UI text is appropriate.
		add_filter( 'enter_title_here', [ $this, 'post_type_title' ] );

		// Create primary taxonomy.
		add_action( 'init', [ $this, 'taxonomy_create' ] );
		add_filter( 'wp_terms_checklist_args', [ $this, 'taxonomy_fix_metabox' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'taxonomy_filter_post_type' ] );

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 1.0
	 */
	public function activate() {

		// Pass through.
		$this->post_type_create();
		$this->taxonomy_create();

		// Go ahead and flush.
		flush_rewrite_rules();

	}

	/**
	 * Actions to perform on plugin deactivation (NOT deletion).
	 *
	 * @since 1.0
	 */
	public function deactivate() {

		// Flush rules to reset.
		flush_rewrite_rules();

	}

	// -------------------------------------------------------------------------

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 1.0
	 */
	public function post_type_create() {

		// Only call this once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Set up the post type called "Ball".
		register_post_type( $this->post_type_name, [

			// Labels.
			'labels' => [
				'name'               => __( 'Balls', 'sof-balls' ),
				'singular_name'      => __( 'Ball', 'sof-balls' ),
				'add_new'            => __( 'Add New', 'sof-balls' ),
				'add_new_item'       => __( 'Add New Ball', 'sof-balls' ),
				'edit_item'          => __( 'Edit Ball', 'sof-balls' ),
				'new_item'           => __( 'New Ball', 'sof-balls' ),
				'all_items'          => __( 'All Balls', 'sof-balls' ),
				'view_item'          => __( 'View Ball', 'sof-balls' ),
				'search_items'       => __( 'Search Balls', 'sof-balls' ),
				'not_found'          => __( 'No matching Ball found', 'sof-balls' ),
				'not_found_in_trash' => __( 'No Balls found in Trash', 'sof-balls' ),
				'menu_name'          => __( 'Balls', 'sof-balls' ),
			],

			// Defaults.
			'menu_icon' => 'dashicons-admin-site-alt3',
			'description' => __( 'A ball post type', 'sof-balls' ),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'has_archive' => false,
			'query_var' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 20,
			'map_meta_cap' => true,

			// Rewrite.
			'rewrite' => [
				'slug' => 'balls',
				'with_front' => false,
			],

			// Supports.
			'supports' => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
			],

			// REST setup.
			'show_in_rest' => true,
			'rest_base' => $this->post_type_rest_base,

		] );

		// Flag done.
		$registered = true;

	}

	/**
	 * Override messages for a Custom Post Type.
	 *
	 * @since 1.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// Access relevant globals.
		global $post, $post_ID;

		// Define custom messages for our Custom Post Type.
		$messages[ $this->post_type_name ] = [

			// Unused - messages start at index 1.
			0 => '',

			// Item updated.
			1 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Ball updated. <a href="%s">View Ball</a>', 'sof-balls' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2 => __( 'Custom field updated.', 'sof-balls' ),
			3 => __( 'Custom field deleted.', 'sof-balls' ),
			4 => __( 'Ball updated.', 'sof-balls' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5 => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: The date and time of the revision. */
					__( 'Ball restored to revision from %s', 'sof-balls' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Ball published. <a href="%s">View Ball</a>', 'sof-balls' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7 => __( 'Ball saved.', 'sof-balls' ),

			// Item submitted.
			8 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Ball submitted. <a target="_blank" href="%s">Preview Ball</a>', 'sof-balls' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9 => sprintf(
				/* translators: 1: The date, 2: The permalink. */
				__( 'Ball scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Ball</a>', 'sof-balls' ),
				/* translators: Publish box date format - see https://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'sof-balls' ), strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Ball draft updated. <a target="_blank" href="%s">Preview Ball</a>', 'sof-balls' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

		];

		// --<
		return $messages;

	}

	/**
	 * Override the "Add title" label.
	 *
	 * @since 1.0
	 *
	 * @param str $title The existing title - usually "Add title".
	 * @return str $title The modified title.
	 */
	public function post_type_title( $title ) {

		// Bail if not our post type.
		if ( $this->post_type_name !== get_post_type() ) {
			return $title;
		}

		// Overwrite with our string.
		$title = __( 'Add the name of the Ball', 'sof-balls' );

		// --<
		return $title;

	}

	// -------------------------------------------------------------------------

	/**
	 * Create our Custom Taxonomy.
	 *
	 * @since 1.0
	 */
	public function taxonomy_create() {

		// Only register once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Arguments.
		$args = [

			// Same as "category".
			'hierarchical' => true,

			// Labels.
			'labels' => [
				'name'              => _x( 'Ball Types', 'taxonomy general name', 'sof-balls' ),
				'singular_name'     => _x( 'Ball Type', 'taxonomy singular name', 'sof-balls' ),
				'search_items'      => __( 'Search Ball Types', 'sof-balls' ),
				'all_items'         => __( 'All Ball Types', 'sof-balls' ),
				'parent_item'       => __( 'Parent Ball Type', 'sof-balls' ),
				'parent_item_colon' => __( 'Parent Ball Type:', 'sof-balls' ),
				'edit_item'         => __( 'Edit Ball Type', 'sof-balls' ),
				'update_item'       => __( 'Update Ball Type', 'sof-balls' ),
				'add_new_item'      => __( 'Add New Ball Type', 'sof-balls' ),
				'new_item_name'     => __( 'New Ball Type Name', 'sof-balls' ),
				'menu_name'         => __( 'Ball Types', 'sof-balls' ),
				'not_found'         => __( 'No Ball Types found', 'sof-balls' ),
			],

			// Rewrite rules.
			'rewrite' => [
				'slug' => 'ball-types',
			],

			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui' => true,

			// REST setup.
			'show_in_rest' => true,
			'rest_base' => $this->taxonomy_rest_base,

		];

		// Register a taxonomy for this CPT.
		register_taxonomy( $this->taxonomy_name, $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

	/**
	 * Fix the Custom Taxonomy metabox.
	 *
	 * @see https://core.trac.wordpress.org/ticket/10982
	 *
	 * @since 1.0
	 *
	 * @param array $args The existing arguments.
	 * @param int $post_id The WordPress post ID.
	 */
	public function taxonomy_fix_metabox( $args, $post_id ) {

		// If rendering metabox for our taxonomy.
		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] === $this->taxonomy_name ) {

			// Setting 'checked_ontop' to false seems to fix this.
			$args['checked_ontop'] = false;

		}

		// --<
		return $args;

	}

	/**
	 * Add a filter for this Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 1.0
	 */
	public function taxonomy_filter_post_type() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow != $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->taxonomy_name );

		// Show a dropdown.
		wp_dropdown_categories( [
			/* translators: %s: The plural name of the taxonomy terms. */
			'show_option_all' => sprintf( __( 'Show All %s', 'sof-balls' ), $taxonomy->label ),
			'taxonomy' => $this->taxonomy_name,
			'name' => $this->taxonomy_name,
			'orderby' => 'name',
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			'selected' => isset( $_GET[ $this->taxonomy_name ] ) ? wp_unslash( $_GET[ $this->taxonomy_name ] ) : '',
			'show_count' => true,
			'hide_empty' => true,
			'value_field' => 'slug',
			'hierarchical' => 1,
		] );

	}

}
