<?php

/**
 * Entry point for the ValueParser extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:ValueParsers
 * Support					https://www.mediawiki.org/wiki/Extension_talk:ValueParsers
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DataValues.git
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueParsers extension.
 *
 * @defgroup ValueParsers ValueParsers
 */

/**
 * Tests part of the ValueParsers extension.
 *
 * @defgroup ValueParserTests ValueParserTests
 * @ingroup ValueParsers
 * @ingroup Test
 */

if ( defined( 'ValueParsers_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'ValueParsers_VERSION', '0.1 alpha' );

global $wgValueParsers;

/**
 * @deprecated since 0.1 This is a global registry that provides no control over object lifecycle
 */
$wgValueParsers = array();

$wgValueParsers['bool'] = 'ValueParsers\BoolParser';
$wgValueParsers['float'] = 'ValueParsers\FloatParser';
$wgValueParsers['globecoordinate'] = 'ValueParsers\GlobeCoordinateParser';
$wgValueParsers['int'] = 'ValueParsers\IntParser';
$wgValueParsers['null'] = 'ValueParsers\NullParser';

spl_autoload_register( function ( $className ) {
	// @codeCoverageIgnoreStart
	static $classes = false;

	if ( $classes === false ) {
		$classes = include( __DIR__ . '/' . 'ValueParsers.classes.php' );
	}

	if ( array_key_exists( $className, $classes ) ) {
		include_once __DIR__ . '/' . $classes[$className];
	}
	// @codeCoverageIgnoreEnd
} );

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/ValueParsers.mw.php';
}
