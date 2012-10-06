<?php

/**
 * Class registration file for the ValueParser library.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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
	'ValueParsers\Error' => 'includes/Error.php',
	'ValueParsers\ErrorObject' => 'includes/ErrorObject.php',
	'ValueParsers\ParserOptions' => 'includes/ParserOptions.php',
	'ValueParsers\Result' => 'includes/Result.php',
	'ValueParsers\ResultObject' => 'includes/ResultObject.php',
	'ValueParsers\SimpleParserOptions' => 'includes/SimpleParserOptions.php',

	'ValueParsers\GeoParserOptions' => 'includes/options/GeoParserOptions.php',

	'ValueParsers\BoolParser' => 'valueparser/BoolParser.php',
	'ValueParsers\GeoCoordinateParser' => 'valueparser/GeoCoordinateParser.php',
	'ValueParsers\FloatParser' => 'valueparser/FloatParser.php',
	'ValueParsers\IntParser' => 'valueparser/IntParser.php',
	'ValueParsers\NullParser' => 'valueparser/NullParser.php',
	'ValueParsers\StringValueParser' => 'valueparser/StringValueParser.php',
	'ValueParsers\TitleParser' => 'valueparser/TitleParser.php',
	'ValueParsers\ValueParser' => 'valueparser/ValueParser.php',

	'ValueParsers\Test\StringValueParserTest' => 'tests/valueparser/StringValueParserTest.php',
	'ValueParsers\Test\ValueParserTestBase' => 'tests/valueparser/ValueParserTestBase.php',
);
