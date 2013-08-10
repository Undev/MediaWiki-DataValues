<?php

/**
 * Entry point for the ValueValidators extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:ValueValidators
 * Support					https://www.mediawiki.org/wiki/Extension_talk:ValueValidators
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DataValues.git
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueValidators
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueValidators extension.
 *
 * @defgroup ValueValidators ValueValidators
 */

/**
 * Tests part of the ValueValidators extension.
 *
 * @defgroup ValueValidatorTests ValueValidatorTests
 * @ingroup ValueValidators
 * @ingroup Test
 */

if ( defined( 'ValueValidators_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'ValueValidators_VERSION', '0.1 alpha' );

global $wgValueValidators;

/**
 * @deprecated since 0.1 This is a global registry that provides no control over object lifecycle
 */
$wgValueValidators = array();

$wgValueValidators['range'] = 'ValueValidators\RangeValidator';

spl_autoload_register( function ( $className ) {
	// @codeCoverageIgnoreStart
	static $classes = false;

	if ( $classes === false ) {
		$classes = include( __DIR__ . '/' . 'ValueValidators.classes.php' );
	}

	if ( array_key_exists( $className, $classes ) ) {
		include_once __DIR__ . '/' . $classes[$className];
	}
	// @codeCoverageIgnoreEnd
} );

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/ValueValidators.mw.php';
}
