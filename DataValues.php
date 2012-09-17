<?php

/**
 * Collective entry point for the extensions contained within the DataValues repository.
 *
 * @file
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

call_user_func( function() {
	$components = array(
		'DataValues',
		'ValueParser',
		'ValueValidator',
		'ValueFormatter',
		'DataTypes',
	);

	$extension = defined( 'MEDIAWIKI' ) ? '.mw.php' : '.php';

	foreach ( $components as $component ) {
		include __DIR__ . '/' . $component . '/' . $component . $extension;
	}

} );
