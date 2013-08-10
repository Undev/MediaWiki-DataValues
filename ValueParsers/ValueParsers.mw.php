<?php

/**
 * MediaWiki setup for the ValueParser extension.
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgHooks, $wgAPIModules, $wgResourceModules;

$wgExtensionCredits['datavalues'][] = array(
	'path' => __DIR__,
	'name' => 'ValueParsers',
	'version' => ValueParsers_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:ValueParsers',
	'descriptionmsg' => 'valueparsers-desc',
);

$wgExtensionMessagesFiles['ValueParsers'] = __DIR__ . '/ValueParsers.i18n.php';

if ( defined( 'MW_PHPUNIT_TEST' ) ) {
	require_once __DIR__ . '/tests/testLoader.php';
}

// API module registration
$wgAPIModules['parsevalue'] = 'ValueParsers\ApiParseValue';

/**
 * Hook to add PHPUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
 *
 * @since 0.1
 *
 * @param array $files
 *
 * @return boolean
 */
$wgHooks['UnitTestsList'][] = function( array &$files ) {
	// @codeCoverageIgnoreStart
	$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/tests/phpunit/' );

	/**
	 * @var SplFileInfo $fileInfo
	 */
	foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
		if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
			$files[] = $fileInfo->getPathname();
		}
	}

	return true;
	// @codeCoverageIgnoreEnd
};

/**
 * Hook to add QUnit test cases.
 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderTestModules
 * @since 0.1
 *
 * @param array &$testModules
 * @param \ResourceLoader &$resourceLoader
 * @return boolean
 */
$wgHooks['ResourceLoaderTestModules'][] = function ( array &$testModules, \ResourceLoader &$resourceLoader ) {
	// @codeCoverageIgnoreStart
	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => 'DataValues/ValueParsers',
	);

	$testModules['qunit']['ext.valueParsers.tests'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/ValueParser.tests.js',
		),
		'dependencies' => array(
			'valueParsers.parsers',
		),
	);

	$testModules['qunit']['ext.valueParsers.factory'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/ValueParserFactory.tests.js',
		),
		'dependencies' => array(
			'valueParsers.factory',
			'valueParsers.parsers',
		),
	);

	$testModules['qunit']['ext.valueParsers.parsers'] = $moduleTemplate + array(
		'scripts' => array(
			'tests/qunit/parsers/BoolParser.tests.js',
			'tests/qunit/parsers/GlobeCoordinateParser.tests.js',
			'tests/qunit/parsers/FloatParser.tests.js',
			'tests/qunit/parsers/IntParser.tests.js',
			'tests/qunit/parsers/StringParser.tests.js',
			'tests/qunit/parsers/TimeParser.tests.js',
			'tests/qunit/parsers/NullParser.tests.js',
		),
		'dependencies' => array(
			'ext.valueParsers.tests',
		),
	);

	return true;
	// @codeCoverageIgnoreEnd
};

// Resource Loader module registration
$wgResourceModules = array_merge(
	$wgResourceModules,
	include( __DIR__ . '/Resources.php' )
);
