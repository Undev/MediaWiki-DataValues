<?php

namespace DataTypes\Test;

use DataTypes\DataType;
use DataTypes\DataTypeFactory;

/**
 * Unit tests for DataType implementations.
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
 * @ingroup DataTypesTest
 *
 * @group DataTypes
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DataTypeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @return DataType[]
	 */
	protected function getInstances() {
		$factory = new DataTypeFactory( $GLOBALS['wgDataTypes'] );
		return $factory->getTypes();
	}

	public function instanceProvider() {
		$argLists = array();

		foreach ( $this->getInstances() as $instance ) {
			$argLists[] = array( $instance );
		}

		return $argLists;
	}

	/**
	 * @dataProvider instanceProvider
	 * @param DataType $type
	 */
	public function testGetId( DataType $type ) {
		$this->assertInternalType( 'string', $type->getId() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param DataType $type
	 */
	public function testGetDataValueType( DataType $type ) {
		$this->assertInternalType( 'string', $type->getDataValueType() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param DataType $type
	 */
	public function testGetFormatter( DataType $type ) {
		$this->assertInternalType( 'array', $type->getFormatters() );
		$this->assertContainsOnlyInstancesOf( 'ValueFormatters\ValueFormatter', $type->getFormatters() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param DataType $type
	 */
	public function testGetValidators( DataType $type ) {
		$this->assertInternalType( 'array', $type->getValidators() );
		$this->assertContainsOnlyInstancesOf( 'ValueValidators\ValueValidator', $type->getValidators() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param DataType $type
	 */
	public function testGetLabel( DataType $type ) {
		foreach ( array( 'en', 'de', 'nl', 'o_O' ) as $langCode ) {
			$actual = $type->getLabel( $langCode );
			$this->assertTrue( $actual === null || is_string( $actual ) );
		}
	}

}