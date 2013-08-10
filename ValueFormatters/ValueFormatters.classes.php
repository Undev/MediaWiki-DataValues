<?php

/**
 * Class registration file for the ValueFormatters library.
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return array(
	'ValueFormatters\FormatterOptions' => 'includes/FormatterOptions.php',
	'ValueFormatters\FormattingException' => 'includes/FormattingException.php',
	'ValueFormatters\ValueFormatter' => 'includes/ValueFormatter.php',
	'ValueFormatters\ValueFormatterBase' => 'includes/ValueFormatterBase.php',
	'ValueFormatters\ValueFormatterFactory' => 'includes/ValueFormatterFactory.php',

	'ValueFormatters\GeoCoordinateFormatter' => 'includes/formatters/GeoCoordinateFormatter.php',
	'ValueFormatters\IriFormatter' => 'includes/formatters/IriFormatter.php',
	'ValueFormatters\StringFormatter' => 'includes/formatters/StringFormatter.php',

	'ValueFormatters\Test\ValueFormatterFactoryTest' => 'tests/phpunit/ValueFormatterFactoryTest.php',
	'ValueFormatters\Test\ValueFormatterTestBase' => 'tests/phpunit/ValueFormatterTestBase.php',
);
