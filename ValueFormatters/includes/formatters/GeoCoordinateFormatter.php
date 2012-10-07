<?php

namespace ValueFormatters;
use DataValues\GeoCoordinateValue, InvalidArgumentException;

/**
 * Geographical coordinates formatter.
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
class GeoCoordinateFormatter extends ValueFormatterBase {

	/**
	 * @see ValueFormatter::format
	 *
	 * @since 0.1
	 *
	 * @param mixed $value The value to format
	 *
	 * @return Result
	 * @throws InvalidArgumentException
	 */
	public function format( $value ) {
		if ( !( $value instanceof GeoCoordinateValue ) ) {
			throw new InvalidArgumentException( 'The ValueFormatters\GeoCoordinateFormatter cam only format instances of DataValues\GeoCoordinateValue' );
		}

		// TODO
		return $this->newSuccess( '' );
	}

}


