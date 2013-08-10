<?php

namespace ValueFormatters\Test;

use ValueFormatters\ValueFormatterFactory;

/**
 * Unit tests for the ValueFormatterFactory class.
 *
 * @file
 * @since 0.1
 *
 * @ingroup ValueFormattersTest
 *
 * @group ValueFormatters
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueFormatterFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var null|ValueFormatterFactory
	 */
	protected $factory = null;

	/**
	 * @since 0.1
	 *
	 * @return ValueFormatterFactory
	 */
	protected function getFactoryFromGlobals() {
		if ( $this->factory === null ) {
			$this->factory = new ValueFormatterFactory( $GLOBALS['wgValueFormatters'] );
		}

		return $this->factory;
	}

	public function testGetFormatterIds() {
		$ids = $this->getFactoryFromGlobals()->getFormatterIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertEquals( array_unique( $ids ), $ids );
	}

	public function testGetFormatter() {
		$factory = $this->getFactoryFromGlobals();

		$options = new \ValueFormatters\FormatterOptions();

		foreach ( $factory->getFormatterIds() as $id ) {
			$this->assertInstanceOf( 'ValueFormatters\ValueFormatter', $factory->newFormatter( $id, $options ) );
		}

		$this->assertInternalType( 'null', $factory->newFormatter( "I'm in your tests, being rather silly ~=[,,_,,]:3", $options ) );
	}

	public function testGetFormatterClass() {
		$factory = $this->getFactoryFromGlobals();

		foreach ( $factory->getFormatterIds() as $id ) {
			$this->assertInternalType( 'string', $factory->getFormatterClass( $id ) );
		}

		$this->assertInternalType( 'null', $factory->getFormatterClass( "I'm in your tests, being rather silly ~=[,,_,,]:3" ) );
	}

}
