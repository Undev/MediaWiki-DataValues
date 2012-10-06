<?php

namespace ValueParsers;

/**
 * Options for parsers that deal with geographical values.
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
class GeoParserOptions extends SimpleParserOptions {

	/**
	 * Returns the symbol used to indicate the north.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getNorthSymbol() {
		return 'N'; // TODO: i18n using $this->language
	}

	/**
	 * Returns the symbol used to indicate the east.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getEastSymbol() {
		return 'E'; // TODO: i18n using $this->language
	}

	/**
	 * Returns the symbol used to indicate the south.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getSouthSymbol() {
		return 'S'; // TODO: i18n using $this->language
	}

	/**
	 * Returns the symbol used to indicate the west.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getWestSymbol() {
		return 'W'; // TODO: i18n using $this->language
	}

	/**
	 * Returns the symbol used to indicate degrees.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getDegreeSymbol() {
		return '°'; // TODO: setter
	}

	/**
	 * Returns the symbol used to indicate degrees.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getMinuteSymbol() {
		return "'"; // TODO: setter
	}

	/**
	 * Returns the symbol used to indicate degrees.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getSecondSymbol() {
		return '"'; // TODO: setter
	}

	/**
	 * Returns the symbol used to separate latitude from longitude.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getSeparatorSymbol() {
		return ','; // TODO: setter
	}

}
