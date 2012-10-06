<?php

/**
 * Class registration file for the DataValues library.
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
 * @ingroup DataValues
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return array(
	'DataValues\BooleanValue' => 'datavalue/BooleanValue.php',
	'DataValues\DataValue' => 'datavalue/DataValue.php',
	'DataValues\DataValueObject' => 'datavalue/DataValueObject.php',
	'DataValues\GeoCoordinateValue' => 'datavalue/GeoCoordinateValue.php',
	'DataValues\MonolingualTextValue' => 'datavalue/MonolingualTextValue.php',
	'DataValues\MultilingualTextValue' => 'datavalue/MultilingualTextValue.php',
	'DataValues\MediaWikiTitleValue' => 'datavalue/MediaWikiTitleValue.php',
//	'DataValues\MultilingualTextValue' => 'datavalue/MultilingualTextValue.php',
	'DataValues\NumberValue' => 'datavalue/NumberValue.php',
	'DataValues\QuantityValue' => 'datavalue/QuantityValue.php',
	'DataValues\StringValue' => 'datavalue/StringValue.php',
//	'DataValues\TimeValue' => 'datavalue/TimeValue.php',
	'DataValues\UnknownValue' => 'datavalue/UnknownValue.php',

	'Comparable' => 'includes/Comparable.php',
	'Copyable' => 'includes/Copyable.php',
	'Hashable' => 'includes/Hashable.php',
	'Immutable' => 'includes/Immutable.php',

	'DataValues\Test\DataValueTest' => 'tests/datavalue/DataValueTest.php',
);
