<?php

namespace ValueParsers\Test;

use DataValues\GeoCoordinateValue;
use ValueParsers\DmsCoordinateParser;

/**
 * Unit tests for the ValueParsers\DmsCoordinateParser class.
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
 * @file
 * @since 0.1
 *
 * @ingroup ValueParsersTest
 *
 * @group ValueParsers
 * @group DataValueExtensions
 * @group GeoCoordinateParserTest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DmsCoordinateParserTest extends StringValueParserTest {

	/**
	 * @see ValueParserTestBase::validInputProvider
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public function validInputProvider() {
		$argLists = array();

		// TODO: test with different parser options

		$valid = array(
			'55° 45\' 20.8296", 37° 37\' 3.4788"' => array( 55.755786, 37.617633, 1 / 3600 * 0.0001 ),
			'55° 45\' 20.8296", -37° 37\' 3.4788"' => array( 55.755786, -37.617633, 1 / 3600 * 0.0001 ),
			'-55° 45\' 20.8296", -37° 37\' 3.4788"' => array( -55.755786, -37.617633, 1 / 3600 * 0.0001 ),
			'-55° 45\' 20.8296", 37° 37\' 3.4788"' => array( -55.755786, 37.617633, 1 / 3600 * 0.0001 ),
			'55° 0\' 0", 37° 0\' 0"' => array( 55, 37, 1 / 3600 ),
			'55° 30\' 0", 37° 30\' 0"' => array( 55.5, 37.5, 1 / 3600 ),
			'55° 0\' 18", 37° 0\' 18"' => array( 55.005, 37.005, 1 / 3600 ),
			'0° 0\' 0", 0° 0\' 0"' => array( 0, 0, 1 / 3600 ),
			'0° 0\' 18" N, 0° 0\' 18" E' => array( 0.005, 0.005, 1 / 3600 ),
			' 0° 0\' 18" S  , 0°  0\' 18"  W ' => array( -0.005, -0.005, 1 / 3600 ),
			'55° 0′ 18″, 37° 0′ 18″' => array( 55.005, 37.005, 1 / 3600 ),

			// Coordinate strings without separator:
			'55° 45\' 20.8296" 37° 37\' 3.4788"' => array( 55.755786, 37.617633, 1 / 3600 * 0.0001 ),
			'55 ° 45 \' 20.8296 " -37 ° 37 \' 3.4788 "' => array( 55.755786, -37.617633, 1 / 3600 * 0.0001 ),
			'-55 ° 45 \' 20.8296 " -37° 37\' 3.4788"' => array( -55.755786, -37.617633, 1 / 3600 * 0.0001 ),
			'55° 0′ 18″ 37° 0′ 18″' => array( 55.005, 37.005, 1 / 3600 ),

			// Coordinate string starting with direction character:
			'N 0° 0\' 18", E 0° 0\' 18"' => array( 0.005, 0.005, 1 / 3600 ),
			'S 0° 0\' 18" E 0° 0\' 18"' => array( -0.005, 0.005, 1 / 3600 ),
		);

		foreach ( $valid as $value => $expected ) {
			$expected = new GeoCoordinateValue( $expected[0], $expected[1], null, $expected[2] );
			$argLists[] = array( (string)$value, $expected );
		}

		// Checking whether precision gets set via the parser options:
		$parser = $this->getInstance();
		$parser->getOptions()->setOption( 'precision', 0.1 );
		$expected = new GeoCoordinateValue( 1.02, 1.02, null, 0.1 );
		$argLists[] = array( '1° 1\' 12", 1° 1\' 12"', $expected, $parser );

		return $argLists;
	}

	public function invalidInputProvider() {
		$argLists = parent::invalidInputProvider();

		$invalid = array(
			'~=[,,_,,]:3',
			'ohi there',
		);

		foreach ( $invalid as $value ) {
			$argLists[] = array( $value );
		}

		return $argLists;
	}

	/**
	 * @see ValueParserTestBase::getParserClass
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected function getParserClass() {
		return 'ValueParsers\DmsCoordinateParser';
	}

}
