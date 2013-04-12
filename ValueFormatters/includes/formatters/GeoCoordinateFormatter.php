<?php

namespace ValueFormatters;

use DataValues\GeoCoordinateValue;
use InvalidArgumentException;

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

	const TYPE_FLOAT = 'float';
	const TYPE_DMS = 'dms';
	const TYPE_DM = 'dm';
	const TYPE_DD = 'dd';

	/**
	 * The symbols representing the different directions for usage in directional notation.
	 * @since 0.1
	 */
	const OPT_NORTH_SYMBOL = 'north';
	const OPT_EAST_SYMBOL = 'east';
	const OPT_SOUTH_SYMBOL = 'south';
	const OPT_WEST_SYMBOL = 'west';

	/**
	 * The symbols representing degrees, minutes and seconds.
	 * @since 0.1
	 */
	const OPT_DEGREE_SYMBOL = 'degree';
	const OPT_MINUTE_SYMBOL = 'minute';
	const OPT_SECOND_SYMBOL = 'second';

	const OPT_FORMAT = 'geoformat';
	const OPT_SEPARATOR_SYMBOL = 'separator';
	const OPT_PRECISION = 'precision';

	/**
	 * @since 0.1
	 *
	 * @param FormatterOptions $options
	 */
	public function __construct( FormatterOptions $options ) {
		parent::__construct( $options );

		$this->defaultOption( self::OPT_NORTH_SYMBOL, 'N' );
		$this->defaultOption( self::OPT_EAST_SYMBOL, 'E' );
		$this->defaultOption( self::OPT_SOUTH_SYMBOL, 'S' );
		$this->defaultOption( self::OPT_WEST_SYMBOL, 'W' );

		$this->defaultOption( self::OPT_DEGREE_SYMBOL, '°' );
		$this->defaultOption( self::OPT_MINUTE_SYMBOL, "'" );
		$this->defaultOption( self::OPT_SECOND_SYMBOL, '"' );

		$this->defaultOption( self::OPT_FORMAT, self::TYPE_FLOAT );
		$this->defaultOption( self::OPT_SEPARATOR_SYMBOL, ',' );
		$this->defaultOption( self::OPT_PRECISION, 4 );
	}

	/**
	 * @see ValueFormatter::format
	 *
	 * @since 0.1
	 *
	 * @param mixed $value The value to format
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function format( $value ) {
		if ( !( $value instanceof GeoCoordinateValue ) ) {
			throw new InvalidArgumentException( 'The ValueFormatters\GeoCoordinateFormatter cam only format instances of DataValues\GeoCoordinateValue' );
		}


		$latitude = $this->formatCoordinate( $value->getLatitude() );
		$longitude = $this->formatCoordinate( $value->getLongitude() );

		$formatted = implode( $this->getOption( self::OPT_SEPARATOR_SYMBOL ) . ' ', array( $latitude, $longitude ) );

		return $formatted;
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

		switch ( $this->getOption( self::OPT_FORMAT ) ) {
			case self::TYPE_FLOAT:
				return (string)$coordinate;
			case self::TYPE_DMS:
				$isNegative = $coordinate < 0;
				$coordinate = abs( $coordinate );

				$degrees = floor( $coordinate );
				$minutes = ( $coordinate - $degrees ) * 60;
				$seconds = ( $minutes - floor( $minutes ) ) * 60;

				$minutes = floor( $minutes );
				$seconds = round( $seconds, $options->getOption( self::OPT_PRECISION ) );

				$result = $degrees . $options->getOption( self::OPT_DEGREE_SYMBOL )
					. ' ' . $minutes . $options->getOption( self::OPT_MINUTE_SYMBOL )
					. ' ' . $seconds . $options->getOption( self::OPT_SECOND_SYMBOL );

				if ( $isNegative ) {
					$result = '-' . $result;
				}

				return $result;
			case self::TYPE_DD:
				return $coordinate . $options->getOption( self::OPT_DEGREE_SYMBOL );
			case self::TYPE_DM:
				$isNegative = $coordinate < 0;
				$coordinate = abs( $coordinate );
				$degrees = floor( $coordinate );

				$minutes = round( ( $coordinate - $degrees ) * 60, $options->getOption( self::OPT_PRECISION ) );

				return sprintf(
					"%s%d%s %s%s",
					$isNegative ? '-' : '',
					$degrees,
					$options->getOption( self::OPT_DEGREE_SYMBOL ),
					$minutes,
					$options->getOption( self::OPT_MINUTE_SYMBOL )
				);
			default:
				throw new InvalidArgumentException( 'Invalid coordinate format specified in the options' );
		}
	}

}
