<?php

namespace ValueParsers;

/**
 * Factory for creating ValueParser objects.
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
class ValueParserFactory {

	/**
	 * Maps parser id to ValueParser class.
	 *
	 * @since 0.1
	 *
	 * @var ValueParser[]
	 */
	protected $parsers = array();

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
	 * @return ValueParserFactory
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
		global $wgValueParsers;

		foreach ( $wgValueParsers as $parserId => $parserClass ) {
			$this->parsers[$parserId] = $parserClass;
		}
	}

	/**
	 * Returns the ValueParser identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[]
	 */
	public function getParserIds() {
		return array_keys( $this->parsers );
	}

	/**
	 * Returns class of the ValueParser with the provided id or null if there is no such ValueParser.
	 *
	 * @since 0.1
	 *
	 * @param string $parserId
	 *
	 * @return string|null
	 */
	public function getParserClass( $parserId ) {
		return array_key_exists( $parserId, $this->parsers ) ? $this->parsers[$parserId] : null;
	}

	/**
	 * Returns an instance of the ValueParser with the provided id or null if there is no such ValueParser.
	 *
	 * @since 0.1
	 *
	 * @param string $parserId
	 *
	 * @return ValueParser|null
	 */
	public function newParser( $parserId ) {
		return array_key_exists( $parserId, $this->parsers ) ? new $this->parsers[$parserId]() : null;
	}

}
