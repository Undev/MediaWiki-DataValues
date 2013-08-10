<?php

namespace ValueParsers\Test;

use DataValues\GeoCoordinateValue;
use ValueParsers\DmCoordinateParser;

/**
 * Unit tests for the ValueParsers\DmCoordinateParser class.
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
class DmCoordinateParserTest extends StringValueParserTest {

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
			"55° 0', 37° 0'" => array( 55, 37, 1 / 60 ),
			"55° 30', 37° 30'" => array( 55.5, 37.5, 1 / 60 ),
			"0° 0', 0° 0'" => array( 0, 0, 1 / 60 ),
			"-55° 30', -37° 30'" => array( -55.5, -37.5, 1 / 60 ),
			"0° 0.3' S, 0° 0.3' W" => array( -0.005, -0.005, 1 / 3600 ),
			"55° 30′, 37° 30′" => array( 55.5, 37.5, 1 / 60 ),

			// Coordinate strings without separator:
			"55° 0' 37° 0'" => array( 55, 37, 1 / 60 ),
			"55 ° 30 ' 37 ° 30 '" => array( 55.5, 37.5, 1 / 60 ),
			"0° 0' 0° 0'" => array( 0, 0, 1 / 60 ),
			"-55° 30 ' -37 ° 30'" => array( -55.5, -37.5, 1 / 60 ),
			"0° 0.3' S 0° 0.3' W" => array( -0.005, -0.005, 1 / 3600 ),
			"55° 30′ 37° 30′" => array( 55.5, 37.5, 1 / 60 ),

			// Coordinate string starting with direction character:
			"S 0° 0.3', W 0° 0.3'" => array( -0.005, -0.005, 1 / 3600 ),
			"N 0° 0.3' E 0° 0.3'" => array( 0.005, 0.005, 1 / 3600 ),
		);

		foreach ( $valid as $value => $expected ) {
			$expected = new GeoCoordinateValue( $expected[0], $expected[1], null, $expected[2] );
			$argLists[] = array( (string)$value, $expected );
		}

		// Checking whether precision gets set via the parser options:
		$parser = $this->getInstance();
		$parser->getOptions()->setOption( 'precision', 0.1 );
		$expected = new GeoCoordinateValue( 1.2, 1.2, null, 0.1 );
		$argLists[] = array( '1° 12\', 1° 12\'', $expected, $parser );

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
		return 'ValueParsers\DmCoordinateParser';
	}

}
