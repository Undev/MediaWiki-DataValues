<?php

namespace ValueParsers;
use InvalidArgumentException, RuntimeException;

/**
 * Options interface for parsers.
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
 * @since 0.1
 *
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class ParserOptions {

	/**
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * @since 0.1
	 */
	public function __construct( array $options = array() ) {
		foreach ( array_keys( $options ) as $option ) {
			assert( is_string( $option ) );
		}

		$this->options = $options;
	}

	/**
	 * @since 0.1
	 *
	 * @param string $option
	 * @param mixed $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function setOption( $option, $value ) {
		if ( !is_string( $option ) ) {
			throw new InvalidArgumentException( 'Option name needs to be a string' );
		}

		$this->options[$option] = $value;
	}

	/**
	 * @since 0.1
	 *
	 * @param string $option
	 *
	 * @throws InvalidArgumentException
	 */
	public function getOption( $option ) {
		if ( !array_key_exists( $option, $this->options ) ) {
			throw new InvalidArgumentException();
		}

		return $this->options[$option];
	}

	/**
	 * @since 0.1
	 *
	 * @param string $option
	 *
	 * @return boolean
	 */
	public function hasOption( $option ) {
		return array_key_exists( $option, $this->options );
	}

	/**
	 * @since 0.1
	 *
	 * @param string $option
	 * @param mixed $default
	 */
	public function defaultOption( $option, $default ) {
		if ( !$this->hasOption( $option ) ) {
			$this->setOption( $option, $default );
		}
	}

	/**
	 * @since 0.1
	 *
	 * @param string $option
	 *
	 * @throws RuntimeException
	 */
	public function requireOption( $option ) {
		if ( !$this->hasOption( $option ) ) {
			throw new RuntimeException( 'Required option"' . $option . '" is not set' );
		}
	}

}
