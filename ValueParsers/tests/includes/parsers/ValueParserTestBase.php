<?php

namespace ValueParsers\Test;
use ValueParsers\Result;
use ValueParsers\ValueParser;

/**
 * Base for unit tests for ValueParser implementing classes.
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
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ValueParserTestBase extends \MediaWikiTestCase {

	/**
	 * @since 0.1
	 */
	public abstract function parseProvider();

	/**
	 * @since 0.1
	 * @return string
	 */
	protected abstract function getParserClass();

	/**
	 * @since 0.1
	 * @return ValueParser
	 */
	protected function getInstance() {
		$class = $this->getParserClass();
		$options = new \ValueParsers\ParserOptions();
		return new $class( $options );
	}

	/**
	 * @dataProvider parseProvider
	 * @since 0.1
	 * @param $value
	 * @param Result $expected
	 * @param ValueParser|null $parser
	 */
	public function testParse( $value, Result $expected, ValueParser $parser = null ) {
		if ( is_null( $parser ) ) {
			$parser = $this->getInstance();
		}

		$result = $parser->parse( $value );

		$this->assertEquals( $expected->isValid(), $result->isValid(), 'Validity of the value is not valid' );

		if ( $expected->isValid() ) {
			if ( $this->requireDataValue() ) {
				$this->assertInstanceOf( '\DataValues\DataValue', $result->getValue() );
				$this->assertTrue( $expected->getValue()->equals( $result->getValue() ) );
			}
			else {
				$this->assertEquals( $expected->getValue(), $result->getValue() );
			}

			$this->assertNull( $result->getError() );
		}
		else {
			$this->assertTypeOrValue( '\ValueParsers\Error', $result->getError(), null );

			$this->assertException( function() use ( $result ) { $result->getValue(); } );
		}
	}

	/**
	 * Returns if the result of the parsing process should be checked to be a DataValue.
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	protected function requireDataValue() {
		return true;
	}

}
