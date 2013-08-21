<?php

/**
 * Entry point for the ValueFormatters extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:ValueFormatters
 * Support					https://www.mediawiki.org/wiki/Extension_talk:ValueFormatters
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DataValues.git
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueFormatters extension.
 *
 * @defgroup ValueFormatters ValueFormatters
 */

/**
 * Tests part of the ValueFormatters extension.
 *
 * @defgroup ValueFormattersTests ValueFormattersTests
 * @ingroup ValueFormatters
 * @ingroup Test
 */

if ( defined( 'ValueFormatters_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'ValueFormatters_VERSION', '0.1 alpha' );

global $wgValueFormatters;

/**
 * @deprecated since 0.1 This is a global registry that provides no control over object lifecycle
 */
$wgValueFormatters = array(
);

spl_autoload_register( function ( $className ) {
	// @codeCoverageIgnoreStart
	static $classes = false;

	if ( $classes === false ) {
		$classes = include(__DIR__ . '/' . 'ValueFormatters.classes.php');
	}

	if ( array_key_exists( $className, $classes ) ) {
		include_once __DIR__ . '/' . $classes[$className];
	}
	// @codeCoverageIgnoreEnd
} );

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/ValueFormatters.mw.php';
}
