<?php

namespace ValueParsers\Test;

use DataValues\GeoCoordinateValue;
use ValueParsers\FloatCoordinateParser;

/**
 * Unit tests for the ValueParsers\FloatCoordinateParser class.
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
class FloatCoordinateParserTest extends StringValueParserTest {

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
			'55.7557860 N, 37.6176330 W' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55.7557860, -37.6176330' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55 S, 37.6176330 W' => array( -55, -37.6176330, 0.000001 ),
			'-55, -37.6176330' => array( -55, -37.6176330, 0.000001 ),
			'5.5S,37W ' => array( -5.5, -37, 0.1 ),
			'-5.5,-37 ' => array( -5.5, -37, 0.1 ),
			'4,2' => array( 4, 2, 1 ),
			'55.7557860 N 37.6176330 W' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55.7557860 -37.6176330' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55 S 37.6176330 W' => array( -55, -37.6176330, 0.000001 ),
			'-55 -37.6176330' => array( -55, -37.6176330, 0.000001 ),
			'5.5S 37W ' => array( -5.5, -37, 0.1 ),
			'-5.5 -37 ' => array( -5.5, -37, 0.1 ),
			'4 2' => array( 4, 2, 1 ),
		);

		foreach ( $valid as $value => $expected ) {
			$expected = new GeoCoordinateValue( $expected[0], $expected[1], null, $expected[2] );
			$argLists[] = array( (string)$value, $expected );
		}

		// Checking whether precision gets set via the parser options:
		$parser = $this->getInstance();
		$parser->getOptions()->setOption( 'precision', 0.1 );
		$expected = new GeoCoordinateValue( 1, 1, null, 0.1 );
		$argLists[] = array( '1, 1', $expected, $parser );

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
		return 'ValueParsers\FloatCoordinateParser';
	}

}
