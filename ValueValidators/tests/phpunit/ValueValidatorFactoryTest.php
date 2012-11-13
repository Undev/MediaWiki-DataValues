<?php

namespace ValueValidators\Test;
use ValueValidators\ValueValidatorFactory;

/**
 * Unit tests for the ValueValidatorFactory class.
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
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueValidatorFactoryTest extends \MediaWikiTestCase {

	public function testSingleton() {
		$this->assertInstanceOf( 'ValueValidators\ValueValidatorFactory', ValueValidatorFactory::singleton() );
		$this->assertTrue( ValueValidatorFactory::singleton() === ValueValidatorFactory::singleton() );
	}

	public function testGetValidatorIds() {
		$ids = ValueValidatorFactory::singleton()->getValidatorIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertArrayEquals( array_unique( $ids ), $ids );
	}

	public function testGetValidator() {
		$factory = ValueValidatorFactory::singleton();

		foreach ( $factory->getValidatorIds() as $id ) {
			$this->assertInstanceOf( 'ValueValidators\ValueValidator', $factory->getValidator( $id ) );
		}

		$this->assertInternalType( 'null', $factory->getValidator( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

	public function testGetValidatorClass() {
		$factory = ValueValidatorFactory::singleton();

		foreach ( $factory->getValidatorIds() as $id ) {
			$this->assertInternalType( 'string', $factory->getValidatorClass( $id ) );
		}

		$this->assertInternalType( 'null', $factory->getValidatorClass( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

}
