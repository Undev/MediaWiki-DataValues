<?php

namespace ValueFormatters;

/**
 * Base set of options for formatters.
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
class SimpleFormatterOptions implements FormatterOptions {

	/**
	 * The language (as language code) in which the formatting process should happen.
	 *
	 * @since 0.1
	 *
	 * @var string
	 */
	protected $languageCode;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param string $languageCode the language (as language code) in which the formatting process should happen.
	 */
	public function __construct( $languageCode = 'en' ) {
		$this->languageCode = $languageCode;
	}

	/**
	 * @see FormatterOptions::setLanguage
	 *
	 * @since 0.1
	 *
	 * @param string $languageCode
	 */
	public function setLanguage( $languageCode ) {
		$this->languageCode = $languageCode;
	}

	/**
	 * @see FormatterOptions::getLanguage
	 *
	 * @since 0.1
	 *
	 * @return string Language code
	 */
	public function getLanguage() {
		return $this->languageCode;
	}

}
