<?php

/**
 * Collective entry point for the extensions contained within the DataValues repository.
 *
 * The entry point can be used by both MediaWiki and other PHP applications.
 *
 * In case of MediaWiki, the MEDIAWIKI constant needs to be defined. This
 * will be the case when the entry point is included via LocalSettings.php,
 * just like done with regular extensions.
 *
 * @file
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

// @codeCoverageIgnoreStart
call_user_func( function() {
	$components = array(
		'DataValues',
		'DataValuesInterfaces',
		'DataValuesCommon',
		'ValueView',
	);

	foreach ( $components as $component ) {
		// Load components in non-global scope.
		call_user_func( function() use ( $component ) {
			require_once __DIR__ . '/' . $component . '/' . $component . '.php';
		} );
	}

} );
// @codeCoverageIgnoreEnd
