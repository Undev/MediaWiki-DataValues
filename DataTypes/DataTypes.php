<?php

/**
 * Entry point for the DataTypes extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:DataTypes
 * Support					https://www.mediawiki.org/wiki/Extension_talk:DataTypes
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DataValues.git
 *
 * @since 0.1
 *
 * @file
 * @ingroup DataTypes
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the DataTypes extension.
 *
 * @defgroup DataTypes DataTypes
 */

/**
 * Tests part of the DataTypes extension.
 *
 * @defgroup DataTypesTests DataTypesTests
 * @ingroup DataTypes
 */

namespace DataTypes;

if ( defined( 'DataTypes_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

define( 'DataTypes_VERSION', '0.1 alpha' );

global $wgDataTypes;
$wgDataTypes = array(
	'commonsMedia' => array(
		'datavalue' => 'string',
	),
	'string' => array(
		'datavalue' => 'string',
	),
	'globe-coordinate' => array(
		'datavalue' => 'globecoordinate',
	),
	'quantity' => array(
		'datavalue' => 'quantity',
	),
	'monolingual-text' => array(
		'datavalue' => 'monolingualtext',
	),
	'multilingual-text' => array(
		'datavalue' => 'multilingualtext',
	),
	'time' => array(
		'datavalue' => 'time',
	),

//	'geo' => array(
//		'datavalue' => 'geo-dv',
//		'parser' => 'geo-parser',
//		'formatter' => 'geo-formatter',
//	),
//	'positive-number' => array(
//		'datavalue' => 'numeric-dv',
//		'parser' => 'numeric-parser',
//		'formatter' => 'numeric-formatter',
//		'validators' => array( $rangeValidator ),
//	),
);

// @codeCoverageIgnoreStart

spl_autoload_register( function ( $className ) {
	static $classes = false;

	if ( $classes === false ) {
		$classes = include( __DIR__ . '/' . 'DataTypes.classes.php' );
	}

	if ( array_key_exists( $className, $classes ) ) {
		include_once __DIR__ . '/' . $classes[$className];
	}
} );

if ( defined( 'MEDIAWIKI' ) ) {
	include __DIR__ . '/DataTypes.mw.php';
}

class Message {

	protected static $textFunction = null;

	/**
	 * Sets the function to call from @see text.
	 *
	 * @since 0.1
	 *
	 * @param callable $textFunction
	 * This function should take a message key, a language code, and an optional list of arguments.
	 */
	public static function registerTextFunction( $textFunction ) {
		self::$textFunction = $textFunction;
	}

	public static function text() {
		if ( is_null( self::$textFunction ) ) {
			throw new \Exception( 'No text function set in DataTypes\Message' );
		}
		else {
			return call_user_func_array( self::$textFunction, func_get_args() );
		}
	}

}

// @codeCoverageIgnoreEnd