<?php

namespace ValueFormatters;
use InvalidArgumentException;

/**
 * Options for formatters that deal with geographical values.
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
class GeoFormatterOptions extends SimpleFormatterOptions {

	const TYPE_FLOAT = 'float';
	const TYPE_DMS = 'dms';
	const TYPE_DM = 'dm';
	const TYPE_DD = 'dd';

	/**
	 * The coordinate format to use.
	 *
	 * @since 0.1
	 *
	 * @var string
	 */
	protected $format = self::TYPE_FLOAT;

	/**
	 * The precision to use.
	 *
	 * @since 0.1
	 *
	 * @var integer
	 */
	protected $precision = 4;

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

	/**
	 * Returns the coordinate format to use.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * Sets the coordinate format to use.
	 *
	 * @since 0.1
	 *
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->format = $format;
	}

	/**
	 * Sets the precision to use.
	 *
	 * @since 0.1
	 *
	 * @param integer $precision
	 * @throws InvalidArgumentException
	 */
	public function setPrecision( $precision ) {
		if ( !is_int( $precision ) || $precision < 0 ) {
			throw new InvalidArgumentException( 'The precision needs to be an unsinged integer' );
		}

		$this->precision = $precision;
	}

	/**
	 * Returns the precision to use.
	 *
	 * @since 0.1
	 *
	 * @return integer
	 */
	public function getPrecision() {
		return $this->precision;
	}

}
