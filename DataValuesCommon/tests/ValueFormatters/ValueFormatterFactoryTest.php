<?php

namespace ValueFormatters\Test;

use ValueFormatters\StringFormatter;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatterFactory;

/**
 * @covers ValueFormatters\ValueFormatterFactory
 *
 * @since 0.1
 *
 * @group ValueFormatters
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class ValueFormatterFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @since 0.1
	 *
	 * @return ValueFormatterFactory
	 */
	protected function newFactory() {
		return new ValueFormatterFactory( array(
			'string' => 'ValueFormatters\StringFormatter',
			'dummy' => function( FormatterOptions $options ) {
					return new StringFormatter( $options );
				}
		) );
	}

	public function testGetFormatterIds() {
		$ids = $this->newFactory()->getFormatterIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertEquals( array_unique( $ids ), $ids );
	}

	public function testGetFormatter() {
		$factory = $this->newFactory();

		$options = new FormatterOptions();

		foreach ( $factory->getFormatterIds() as $id ) {
			$formatter = $factory->newFormatter( $id, $options );
			$this->assertInstanceOf( 'ValueFormatters\ValueFormatter', $formatter );
		}

		$this->setExpectedException( 'OutOfBoundsException' );
		$factory->newFormatter( "I'm in your tests, being rather silly ~=[,,_,,]:3", $options );
	}

	public function testGetFormatterBuilder() {
		$factory = $this->newFactory();

		foreach ( $factory->getFormatterIds() as $id ) {
			$builder = $factory->getFormatterBuilder( $id );

			if ( !is_callable( $builder ) ) {
				$this->assertInternalType( 'string', $builder );
			}
		}

		$builder = $factory->getFormatterBuilder( "I'm in your tests, being rather silly ~=[,,_,,]:3" );
		$this->assertInternalType( 'null', $builder );
	}

}
