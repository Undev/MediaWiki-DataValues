<?php

/**
 * MediaWiki setup for the ValueFormatters extension.
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

global $wgExtensionCredits, $wgExtensionMessagesFiles, $wgHooks;

$wgExtensionCredits['datavalues'][] = array(
	'path' => __DIR__,
	'name' => 'ValueFormatters',
	'version' => ValueFormatters_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:ValueFormatters',
	'descriptionmsg' => 'valueformatters-desc',
);

$wgExtensionMessagesFiles['ValueFormatters'] = __DIR__ . '/ValueFormatters.i18n.php';

if ( defined( 'MW_PHPUNIT_TEST' ) ) {
	require_once __DIR__ . '/tests/testLoader.php';
}

$wgValueFormatters = array(
	\DataValues\GeoCoordinateValue::getType() => 'ValueFormatters\GeoCoordinateFormatter',
	\DataValues\TimeValue::getType() => 'ValueFormatters\TimeFormatter',
);

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
