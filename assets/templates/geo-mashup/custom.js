/**
 * Geo Mashup Map custom javascript.
 *
 * @package SOF_Balls
 */

/**
 * Actions to take when the Map is loaded.
 *
 * @since 1.0
 *
 * @param {Object} properties The JSON properties object.
 * @param {Object} object The Map Item object.
 */
GeoMashup.addAction( 'objectIcon', function( properties, object ) {

	console.log( 'properties', properties );
	console.log( 'object', object );

	// Use a custom icon when the custom var is set.
	if ( object.is_ball == 1 ) {
		console.log( 'CUSTOM ICON' );
		object.icon.image = object.ball_icon;
		object.icon.iconSize = [ 60, 72 ];
		object.icon.iconAnchor = [ 30, 72 ];
	}

} );

/**
 * Actions to take when the Map is loaded.
 *
 * @since 1.0
 *
 * @param {Object} options The JSON properties object.
 * @param {Object} glow_options The Map Item object.
 */
GeoMashup.addAction( 'glowMarkerIcon', function( options, glow_options ) {

	/*
	console.log( 'options', options );
	console.log( 'glow_options', glow_options );

	// Use a custom icon when the custom var is set.
	if ( object.is_ball == 1 ) {
		console.log( 'CUSTOM ICON' );
		object.icon.image = object.ball_icon;
		//object.icon.prototype.icon_size = [ 60, 72 ];
		//object.icon.prototype.iconAnchor = [ 30, 72 ];
	}
	*/

} );

/**
 * Actions to take when the map is loaded.
 *
 * @since 1.0
 *
 * @param {Object} properties The JSON properties.
 * @param {Object} map The Mapstraction map object.
 */
GeoMashup.addAction( 'loadedMap', function ( properties, map ) {

	/*
	console.log( 'Map Loaded!' );
	console.log( 'properties', properties );
	console.log( 'map', map );
	*/

} );
