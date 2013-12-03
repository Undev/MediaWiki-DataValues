<?php

namespace ValueParsers\Test;

use ValueParsers\NullParser;
use ValueParsers\ParserOptions;
use ValueParsers\ValueParserFactory;

/**
 * @covers ValueParsers\ValueParserFactory
 *
 * @since 0.1
 *
 * @group ValueParsers
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class ValueParserFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @since 0.1
	 *
	 * @return ValueParserFactory
	 */
	protected function newFactory() {
		return new ValueParserFactory( array(
			'null' => 'ValueParsers\NullParser',
			'dummy' => function( ParserOptions $options ) {
					return new NullParser( $options );
				}
		) );
	}

	public function testGetParserIds() {
		$ids = $this->newFactory()->getParserIds();
		$this->assertInternalType( 'array', $ids );

		foreach ( $ids as $id ) {
			$this->assertInternalType( 'string', $id );
		}

		$this->assertEquals( array_unique( $ids ), $ids );
	}

	public function testGetParser() {
		$factory = $this->newFactory();

		$options = new ParserOptions();

		foreach ( $factory->getParserIds() as $id ) {
			$parser = $factory->newParser( $id, $options );
			$this->assertInstanceOf( 'ValueParsers\ValueParser', $parser );
		}

		$this->setExpectedException( 'OutOfBoundsException' );
		$factory->newParser( "I'm in your tests, being rather silly ~=[,,_,,]:3", $options );
	}

	public function testGetParserBuilder() {
		$factory = $this->newFactory();

		foreach ( $factory->getParserIds() as $id ) {
			$builder = $factory->getParserBuilder( $id );

			if ( !is_callable( $builder ) ) {
				$this->assertInternalType( 'string', $builder );
			}
		}

		$builder = $factory->getParserBuilder( "I'm in your tests, being rather silly ~=[,,_,,]:3" );
		$this->assertInternalType( 'null', $builder );
	}

}
