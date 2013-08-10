<?php

namespace ValueParsers\Test;

use DataValues\GeoCoordinateValue;
use ValueParsers\DdCoordinateParser;

/**
 * Unit tests for the ValueParsers\DdCoordinateParser class.
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
class DdCoordinateParserTest extends StringValueParserTest {

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
			'55.7557860° N, 37.6176330° W' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55.7557860°, -37.6176330°' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55° S, 37.6176330 ° W' => array( -55, -37.6176330, 0.000001 ),
			'-55°, -37.6176330 °' => array( -55, -37.6176330, 0.000001 ),
			'5.5°S,37°W ' => array( -5.5, -37, 0.1 ),
			'-5.5°,-37° ' => array( -5.5, -37, 0.1 ),

			// Coordinate strings without separator:
			'55.7557860° N 37.6176330° W' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55.7557860° -37.6176330°' => array( 55.7557860, -37.6176330, 0.000001 ),
			'55° S 37.6176330 ° W' => array( -55, -37.6176330, 0.000001 ),
			'-55° -37.6176330 °' => array( -55, -37.6176330, 0.000001 ),
			'5.5°S 37°W ' => array( -5.5, -37, 0.1 ),
			'-5.5° -37° ' => array( -5.5, -37, 0.1 ),

			// Coordinate string starting with direction character:
			'N5.5° W37°' => array( 5.5, -37, 0.1 ),
			'S 5.5° E 37°' => array( -5.5, 37, 0.1 ),
		);

		foreach ( $valid as $value => $expected ) {
			$expected = new GeoCoordinateValue( $expected[0], $expected[1], null, $expected[2] );
			$argLists[] = array( (string)$value, $expected );
		}

		// Checking whether precision gets set via the parser options:
		$parser = $this->getInstance();
		$parser->getOptions()->setOption( 'precision', 0.1 );
		$expected = new GeoCoordinateValue( 1, 1, null, 0.1 );
		$argLists[] = array( '1°, 1°', $expected, $parser );

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
		return 'ValueParsers\DdCoordinateParser';
	}

}
