<?php

namespace ValueFormatters;
use DataValues\GeoCoordinateValue, InvalidArgumentException;

/**
 * Geographical coordinates formatter.
 *
 * Supports the following notations:
 * - Degree minute second
 * - Decimal degrees
 * - Decimal minutes
 * - Float
 *
 * TODO: support directional notation
 *
 * Some code in this class has been borrowed from the
 * MapsCoordinateParser class of the Maps extension for MediaWiki.
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
	 * @var GeoFormatterOptions $options
	 */
	protected $options;

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


		$latitude = $this->formatCoordinate( $value->getLatitude() );
		$longitude = $this->formatCoordinate( $value->getLongitude() );

		$formatted = implode( $this->options->getSeparatorSymbol() . ' ', array( $latitude, $longitude ) );

		return $this->newSuccess( $formatted );
	}

	/**
	 * Formats a single coordinate
	 *
	 * @param string $coordinate
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function formatCoordinate( $coordinate ) {
		$options = $this->options;

		switch ( $options->getFormat() ) {
			case GeoFormatterOptions::TYPE_FLOAT:
				return (string)$coordinate;
			case GeoFormatterOptions::TYPE_DMS:
				$isNegative = $coordinate < 0;
				$coordinate = abs( $coordinate );

				$degrees = floor( $coordinate );
				$minutes = ( $coordinate - $degrees ) * 60;
				$seconds = ( $minutes - floor( $minutes ) ) * 60;

				$minutes = floor( $minutes );
				$seconds = round( $seconds, $options->getPrecision() );

				$result = $degrees . $options->getDegreeSymbol()
					. ' ' . $minutes . $options->getMinuteSymbol()
					. ' ' . $seconds . $options->getSecondSymbol();

				if ( $isNegative ) {
					$result = '-' . $result;
				}

				return $result;
			case GeoFormatterOptions::TYPE_DD:
				return $coordinate . $options->getDegreeSymbol();
			case GeoFormatterOptions::TYPE_DM:
				$isNegative = $coordinate < 0;
				$coordinate = abs( $coordinate );
				$degrees = floor( $coordinate );

				$minutes = round( ( $coordinate - $degrees ) * 60, $options->getPrecision() );

				return sprintf(
					"%s%d%s %s%s",
					$isNegative ? '-' : '',
					$degrees,
					$options->getDegreeSymbol(),
					$minutes,
					$options->getMinuteSymbol()
				);
			default:
				throw new InvalidArgumentException( 'Invalid coordinate format specified in the options' );
		}
	}

}
