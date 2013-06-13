<?php

namespace ValueParsers;

use DataValues\GeoCoordinateValue;
use LogicException;

/**
 * Parser for geographical coordinates in Decimal Degree notation.
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
 * @author H. Snater < mediawiki@snater.com >
 */
class DdCoordinateParser extends StringValueParser {

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

	/**
	 * The symbol to use as separator between latitude and longitude.
	 * @since 0.1
	 */
	const OPT_SEPARATOR_SYMBOL = 'separator';

	/**
	 * @since 0.1
	 *
	 * @param ParserOptions|null $options
	 */
	public function __construct( ParserOptions $options = null ) {
		parent::__construct( $options );

		$this->defaultOption( self::OPT_NORTH_SYMBOL, 'N' );
		$this->defaultOption( self::OPT_EAST_SYMBOL, 'E' );
		$this->defaultOption( self::OPT_SOUTH_SYMBOL, 'S' );
		$this->defaultOption( self::OPT_WEST_SYMBOL, 'W' );

		$this->defaultOption( self::OPT_DEGREE_SYMBOL, '°' );

		$this->defaultOption( self::OPT_SEPARATOR_SYMBOL, ',' );
	}

	/**
	 * @see StringValueParser::stringParse
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return GeoCoordinateValue
	 * @throws ParseException
	 * @throws LogicException
	 */
	protected function stringParse( $value ) {
		$value = $this->getNormalizedNotation( $value );

		if ( !$this->areDDCoordinates( $value ) ) {
			throw new ParseException( 'Not a geographical coordinate in DD format' );
		}

		$coordinates = explode( $this->getOption( self::OPT_SEPARATOR_SYMBOL ), $value );

		if ( count( $coordinates ) !== 2 ) {
			throw new LogicException( 'A coordinates string with an incorrect segment count has made it through validation' );
		}

		list( $latitude, $longitude ) = $coordinates;

		$latitude = $this->getParsedCoordinate( $latitude );
		$longitude = $this->getParsedCoordinate( $longitude );

		$precision = ( $this->options->hasOption( 'precision' ) )
			? $this->options->getOption( 'precision' )
			: min( $this->detectPrecision( $latitude ), $this->detectPrecision( $longitude ) );

		return new GeoCoordinateValue(
			$latitude,
			$longitude,
			null,
			$precision
		);
	}

	/**
	 * Parses a coordinate segment (either latitude or longitude) and returns it as a float.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function getParsedCoordinate( $coordinate ) {
		$coordinate = $this->resolveDirection( $coordinate );
		return $this->parseDDCoordinate( $coordinate );
	}

	/**
	 * Turns directional notation (N/E/S/W) of a single coordinate into non-directional notation (+/-).
	 * This method assumes there are no preceding or tailing spaces.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return string
	 */
	protected function resolveDirection( $coordinate ) {
		// Get the last char, which could be a direction indicator
		$lastChar = strtoupper( substr( $coordinate, -1 ) );

		$n = $this->getOption( self::OPT_NORTH_SYMBOL );
		$e = $this->getOption( self::OPT_EAST_SYMBOL );
		$s = $this->getOption( self::OPT_SOUTH_SYMBOL );
		$w = $this->getOption( self::OPT_WEST_SYMBOL );

		// If there is a direction indicator, remove it, and prepend a minus sign for south and west directions.
		// If there is no direction indicator, the coordinate is already non-directional and no work is required.
		if ( in_array( $lastChar, array( $n, $e, $s, $w ) ) ) {
			$coordinate = substr( $coordinate, 0, -1 );

			if ( in_array( $lastChar, array( $s, $w ) ) ) {
				$coordinate = '-' . $coordinate;
			}
		}

		return $coordinate;
	}

	/**
	 * Returns a normalized version of the coordinate string.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return string
	 */
	protected function getNormalizedNotation( $coordinates ) {
		$coordinates = str_replace( array( '&#176;', '&deg;' ), $this->getOption( self::OPT_DEGREE_SYMBOL ), $coordinates );

		$coordinates = $this->removeInvalidChars( $coordinates );

		return $coordinates;
	}

	/**
	 * Returns a string with whitespace, control characters and characters with ASCII values above 126 removed.
	 *
	 * @since 0.1
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected function removeInvalidChars( $string ) {
		$filtered = array();

		foreach ( str_split( $string ) as $character ) {
			$asciiValue = ord( $character );

			if ( ( $asciiValue > 32 && $asciiValue < 127 ) || $asciiValue == 194 || $asciiValue == 176 ) {
				$filtered[] = $character;
			}
		}

		return implode( '', $filtered );
	}

	/**
	 * Takes a coordinate segment in Decimal Degree representation and returns it in float
	 * representation.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function parseDDCoordinate( $coordinate ) {
		return (float)str_replace( $this->getOption( self::OPT_DEGREE_SYMBOL ), '', $coordinate );
	}

	/**
	 * Detects a number's precision.
	 *
	 * @since 0.1
	 *
	 * @param float $number
	 *
	 * @return int|float
	 */
	protected function detectPrecision( $number ) {
		$split = explode( '.', $number );

		$precision = 1;

		if( isset( $split[1] ) ) {
			$precision = pow( 10, -1 * strlen( $split[1] ) );
		}

		return $precision;
	}

	/**
	 * Returns whether the coordinate is in Decimal Degree representation.
	 *
	 * @since 0.1
	 *
	 * @param string $rawCoordinateString
	 *
	 * @return boolean
	 */
	protected function areDDCoordinates( $rawCoordinateString ) {
		$sep = $this->getOption( self::OPT_SEPARATOR_SYMBOL );

		$baseRegExp = '\d{1,3}(|\.\d{1,20})°';

		$nonDirectionalRegExp = '/^(-)?' . $baseRegExp . $sep . '(-)?' . $baseRegExp . '$/i';
		$directionalRegExp = '/^' . $baseRegExp . '('
			. $this->getOption( self::OPT_NORTH_SYMBOL ) . '|'
			. $this->getOption( self::OPT_SOUTH_SYMBOL ) .')' . $sep . $baseRegExp . '('
			. $this->getOption( self::OPT_EAST_SYMBOL ) .'|'
			. $this->getOption( self::OPT_WEST_SYMBOL ) . ')?$/i';

		return preg_match( $nonDirectionalRegExp, $rawCoordinateString )
			|| preg_match( $directionalRegExp, $rawCoordinateString );
	}

}
