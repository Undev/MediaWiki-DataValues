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
 * In case of another PHP application, you will need to set the DATAVALUES
 * constant (this is required for security reasons).
 *
 * @file
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

call_user_func( function() {
	// @codeCoverageIgnoreStart
	$components = array(
		'DataValues',
		'ValueParsers',
		'ValueValidators',
		'ValueFormatters',
		'DataTypes',
	);

	foreach ( $components as $component ) {
		// Load extensions in non-global scope.
		call_user_func( function() use ( $component ) {
			require_once __DIR__ . '/' . $component . '/' . $component . '.php';
		} );
	}
	// @codeCoverageIgnoreEnd
} );
