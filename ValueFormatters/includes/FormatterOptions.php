<?php

namespace ValueFormatters;

use InvalidArgumentException;
use RuntimeException;

/**
 * Object holding options for a formatter.
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
final class FormatterOptions {

	/**
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $options = array() ) {
		foreach ( array_keys( $options ) as $option ) {
			if ( !is_string( $option ) ) {
				throw new InvalidArgumentException( 'Option names need to be strings' );
			}
		}

		$this->options = $options;
	}

	/**
	 * Sets the value of the specified option.
	 *
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
	 * Returns the value of the specified option. If the option is not set,
	 * an InvalidArgumentException is thrown.
	 *
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
	 * Returns if the specified option is set or not.
	 *
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
	 * Sets the value of an option to the provided default in case the option is not set yet.
	 *
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
	 * Requires an option to be set.
	 * If it's not set, a RuntimeException is thrown.
	 *
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
