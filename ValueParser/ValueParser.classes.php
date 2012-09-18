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
 * @ingroup ValueParser
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return array(
	'ValueParserError' => 'includes/ValueParserError.php',
	'ValueParserErrorObject' => 'includes/ValueParserErrorObject.php',
	'ValueParserResult' => 'includes/ValueParserResult.php',
	'ValueParserResultObject' => 'includes/ValueParserResultObject.php',

	'BoolParser' => 'valueparser/BoolParser.php',
	'FloatParser' => 'valueparser/FloatParser.php',
	'IntParser' => 'valueparser/IntParser.php',
	'NullParser' => 'valueparser/NullParser.php',
	'StringValueParser' => 'valueparser/StringValueParser.php',
	'TitleParser' => 'valueparser/TitleParser.php',
	'ValueParser' => 'valueparser/ValueParser.php',
);
