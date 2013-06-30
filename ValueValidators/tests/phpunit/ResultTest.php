<?php

namespace ValueValidators\Test;

use ValueValidators\Error;
use ValueValidators\Result;

/**
 * Unit tests for the ValueParsers\Result class.
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
 * @ingroup ValueValidatorsTest
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
class ResultTest extends \PHPUnit_Framework_TestCase {

	public function testNewSuccess() {
		$result = Result::newSuccess();

		$this->assertTrue( $result->isValid() );
		$this->assertEmpty( $result->getErrors() );
	}

	public function testNewError() {
		$result = Result::newError( array(
			Error::newError( 'foo' ),
			Error::newError( 'bar' ),
		) );

		$this->assertFalse( $result->isValid() );
		$this->assertCount( 2, $result->getErrors() );
	}

	public static function provideMerge() {
		$errors = array(
			Error::newError( 'foo' ),
			Error::newError( 'bar' ),
		);

		return array(
			array(
				Result::newSuccess(),
				Result::newSuccess(),
				true,
				0,
				'success + success'
			),
			array(
				Result::newSuccess(),
				Result::newError( $errors ),
				false,
				2,
				'success + error'
			),
			array(
				Result::newSuccess(),
				Result::newError( $errors ),
				false,
				2,
				'error + success'
			),
			array(
				Result::newError( $errors ),
				Result::newError( $errors ),
				false,
				4,
				'error + error'
			),
		);
	}

	/**
	 * @dataProvider provideMerge
	 */
	public function testMerge( $a, $b, $expectedValid, $expectedErrorCount, $message ) {
		$result = Result::merge( $a, $b );

		$this->assertEquals( $expectedValid, $result->isValid(), $message );
		$this->assertCount( $expectedErrorCount, $result->getErrors(), $message );
	}

}
