<?php

namespace ValueParsers\Test;
use ValueParsers\ValueParserFactory;

/**
 * Unit tests for the ValueParserFactory class.
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
class ValueParserFactoryTest extends \MediaWikiTestCase {

	public function testSingleton() {
		$this->assertInstanceOf( 'ValueParsers\ValueParserFactory', ValueParserFactory::singleton() );
		$this->assertTrue( ValueParserFactory::singleton() === ValueParserFactory::singleton() );
	}

	public function testGetParserIds() {
		$ids = ValueParserFactory::singleton()->getParserIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertArrayEquals( array_unique( $ids ), $ids );
	}

	public function testGetParser() {
		$factory = ValueParserFactory::singleton();

		foreach ( $factory->getParserIds() as $id ) {
			$this->assertInstanceOf( 'ValueParsers\ValueParser', $factory->newParser( $id ) );
		}

		$this->assertInternalType( 'null', $factory->newParser( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

	public function testGetParserClass() {
		$factory = ValueParserFactory::singleton();

		foreach ( $factory->getParserIds() as $id ) {
			$this->assertInternalType( 'string', $factory->getParserClass( $id ) );
		}

		$this->assertInternalType( 'null', $factory->getParserClass( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

}
