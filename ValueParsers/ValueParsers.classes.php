<?php

/**
 * Class registration file for the ValueParser library.
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return array(
	'ValueParsers\ParseException' => 'includes/ParseException.php',
	'ValueParsers\ParserOptions' => 'includes/ParserOptions.php',
	'ValueParsers\ValueParser' => 'includes/ValueParser.php',
	'ValueParsers\ValueParserFactory' => 'includes/ValueParserFactory.php',

	'ValueParsers\ApiParseValue' => 'includes/api/ApiParseValue.php',

	'ValueParsers\BoolParser' => 'includes/parsers/BoolParser.php',
	'ValueParsers\DdCoordinateParser' => 'includes/parsers/DdCoordinateParser.php',
	'ValueParsers\DmCoordinateParser' => 'includes/parsers/DmCoordinateParser.php',
	'ValueParsers\DmsCoordinateParser' => 'includes/parsers/DmsCoordinateParser.php',
	'ValueParsers\FloatCoordinateParser' => 'includes/parsers/FloatCoordinateParser.php',
	'ValueParsers\GeoCoordinateParser' => 'includes/parsers/GeoCoordinateParser.php',
	'ValueParsers\GeoCoordinateParserBase' => 'includes/parsers/GeoCoordinateParserBase.php',
	'ValueParsers\FloatParser' => 'includes/parsers/FloatParser.php',
	'ValueParsers\IntParser' => 'includes/parsers/IntParser.php',
	'ValueParsers\NullParser' => 'includes/parsers/NullParser.php',
	'ValueParsers\StringValueParser' => 'includes/parsers/StringValueParser.php',

	'ValueParsers\Test\StringValueParserTest' => 'tests/phpunit/parsers/StringValueParserTest.php',
	'ValueParsers\Test\ValueParserTestBase' => 'tests/phpunit/parsers/ValueParserTestBase.php',
);
