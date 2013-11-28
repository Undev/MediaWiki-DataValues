<?php

namespace ValueFormatters;

use InvalidArgumentException;
use LogicException;
use OutOfBoundsException;

/**
 * Factory for creating ValueFormatter objects.
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class ValueFormatterFactory {

	/**
	 * Maps formatter id to ValueFormatter class or builder callback.
	 *
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $formatters = array();

	/**
	 * @since 0.1
	 *
	 * @param string|callable[] $valueFormatters An associative array mapping formatter ids to
	 *        class names or callable builders.
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $valueFormatters ) {
		foreach ( $valueFormatters as $formatterId => $formatterBuilder ) {
			if ( !is_string( $formatterId ) ) {
				throw new InvalidArgumentException( 'Formatter id needs to be a string' );
			}

			if ( !is_string( $formatterBuilder ) && !is_callable( $formatterBuilder ) ) {
				throw new InvalidArgumentException( 'Formatter class needs to be a class name or callable' );
			}

			$this->formatters[$formatterId] = $formatterBuilder;
		}
	}

	/**
	 * Returns the ValueFormatter identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[]
	 */
	public function getFormatterIds() {
		return array_keys( $this->formatters );
	}

	/**
	 * Returns the formatter builder (class name or callable) for $formatterId, or null if
	 * no builder was registered for that id.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 *
	 * @return string|callable|null
	 */
	public function getFormatterBuilder( $formatterId ) {
		if ( array_key_exists( $formatterId, $this->formatters ) ) {
			return $this->formatters[$formatterId];
		}

		return null;
	}

	/**
	 * Returns an instance of the ValueFormatter with the provided id or null if there is no such ValueFormatter.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 * @param FormatterOptions $formatterOptions
	 *
	 * @throws OutOfBoundsException If no formatter was registered for $formatterId
	 * @return ValueFormatter
	 */
	public function newFormatter( $formatterId, FormatterOptions $formatterOptions ) {
		if ( !array_key_exists( $formatterId, $this->formatters ) ) {
			throw new OutOfBoundsException( "No builder registered for formatter ID $formatterId" );
		}

		$builder = $this->formatters[$formatterId];
		$formatter = $this->instantiateFormatter( $builder, $formatterOptions );

		return $formatter;
	}

	/**
	 * @param string|callable $builder Either a classname of an implementation of ValueFormatter,
	 *        or a callable that returns a ValueFormatter. $options will be passed to the constructor
	 *        or callable, respectively.
	 * @param FormatterOptions $options
	 *
	 * @throws LogicException if the builder did not create a ValueFormatter
	 * @return ValueFormatter
	 */
	private function instantiateFormatter( $builder, FormatterOptions $options ) {
		if ( is_string( $builder ) ) {
			$formatter = new $builder( $options );
		} else {
			$formatter = call_user_func( $builder, $options );
		}

		if ( !( $formatter instanceof Valueformatter ) ) {
			throw new LogicException( "Invalid formatter builder, did not create an instance of ValueFormatter." );
		}

		return $formatter;
	}

}
