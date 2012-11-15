<?php

namespace ValueFormatters;

/**
 * Factory for creating ValueFormatter objects.
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
 * @ingroup ValueFormatters
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueFormatterFactory {

	/**
	 * Maps parser id to ValueFormatter class.
	 *
	 * @since 0.1
	 *
	 * @var ValueFormatter[]
	 */
	protected $formatters = array();

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	protected function __construct() {
		// enforces singleton
	}

	/**
	 * Returns the global instance of the factory.
	 *
	 * @since 0.1
	 *
	 * @return ValueFormatterFactory
	 */
	public static function singleton() {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new static();
			$instance->initialize();
		}


		return $instance;
	}

	/**
	 * Initializes the factory.
	 *
	 * @since 0.1
	 */
	protected function initialize() {
		global $wgValueFormatters;

		foreach ( $wgValueFormatters as $fomatterId => $formatterClass ) {
			$this->formatters[$fomatterId] = $formatterClass;
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
	 * Returns class of the ValueFormatter with the provided id or null if there is no such ValueFormatter.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 *
	 * @return string|null
	 */
	public function getFormatterClass( $formatterId ) {
		return array_key_exists( $formatterId, $this->formatters ) ? $this->formatters[$formatterId] : null;
	}

	/**
	 * Returns an instance of the ValueFormatter with the provided id or null if there is no such ValueFormatter.
	 *
	 * @since 0.1
	 *
	 * @param string $formatterId
	 *
	 * @return ValueFormatter|null
	 */
	public function newFormatter( $formatterId ) {
		return array_key_exists( $formatterId, $this->formatters ) ? new $this->formatters[$formatterId]() : null;
	}

}
