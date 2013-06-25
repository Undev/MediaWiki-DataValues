<?php

namespace ValueParsers;

use DataValues\GeoCoordinateValue;
use LogicException;

/**
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
class FloatCoordinateParser extends StringValueParser {

	/**
	 * The symbols representing the different directions for usage in directional notation.
	 * @since 0.1
	 */
	const OPT_NORTH_SYMBOL = 'north';
	const OPT_EAST_SYMBOL = 'east';
	const OPT_SOUTH_SYMBOL = 'south';
	const OPT_WEST_SYMBOL = 'west';

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
		$value = $this->removeInvalidChars( $value );

		if ( !$this->areFloatCoordinates( $value ) ) {
			throw new ParseException( 'Not a geographical coordinate in float format' );
		}

		$coordinates = $this->splitString( $value );

		if ( count( $coordinates ) !== 2 ) {
			throw new LogicException( 'A coordinates string with an incorrect segment count has made it through validation' );
		}

		list( $latitude, $longitude ) = $coordinates;

		$latitude = $this->getParsedCoordinate( $latitude );
		$longitude = $this->getParsedCoordinate( $longitude );

		$precision = ( $this->options->hasOption( 'precision' ) )
			? $this->options->getOption( 'precision' )
			: min( $this->detectPrecision( $latitude ), $this->detectPrecision( $longitude ) );

		$coordinate = new GeoCoordinateValue(
			$latitude,
			$longitude,
			null,
			$precision
		);

		return $coordinate;
	}

	/**
	 * Parses a single coordinate (either latitude or longitude) and returns it as a float.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function getParsedCoordinate( $coordinate ) {
		return (float)$this->resolveDirection( str_replace( ' ', '', $coordinate ) );
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
		$n = $this->getOption( self::OPT_NORTH_SYMBOL );
		$e = $this->getOption( self::OPT_EAST_SYMBOL );
		$s = $this->getOption( self::OPT_SOUTH_SYMBOL );
		$w = $this->getOption( self::OPT_WEST_SYMBOL );

		// If there is a direction indicator, remove it, and prepend a minus sign for south and west
		// directions. If there is no direction indicator, the coordinate is already non-directional
		// and no work is required.
		foreach( array( $n, $e, $s, $w ) as $direction ) {
			// The coordinate segment may either start or end with a direction symbol.
			preg_match(
				'/^(' . $direction . '|)([^' . $direction . ']+)(' . $direction . '|)$/i',
				$coordinate,
				$matches
			);

			if( $matches[1] === $direction || $matches[3] === $direction ) {
				$coordinate = $matches[2];

				if ( in_array( $direction, array( $s, $w ) ) ) {
					$coordinate = '-' . $coordinate;
				}

				return $coordinate;
			}
		}

		// Coordinate segment does not include a direction symbol.
		return $coordinate;
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
	 * Returns a string trimmed and with control characters and characters with ASCII values above
	 * 126 removed. SPACE characters within the string are not removed to retain the option to split
	 * the string using that character.
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

			if ( ( $asciiValue >= 32 && $asciiValue < 127 ) || $asciiValue == 194 || $asciiValue == 176 ) {
				$filtered[] = $character;
			}
		}

		return trim( implode( '', $filtered ) );
	}

	/**
	 * Splits a string into two strings using the separator specified in the options. If the string
	 * could not be split using the separator, the method will try to split the string by analyzing
	 * the used symbols. If the string could not be split into two parts, an empty array is
	 * returned.
	 *
	 * @param string $normalizedCoordinateString
	 * @return string[]
	 */
	protected function splitString( $normalizedCoordinateString ) {
		$separator = $this->getOption( self::OPT_SEPARATOR_SYMBOL );

		$normalizedCoordinateSegments = explode( $separator, $normalizedCoordinateString );

		if( count( $normalizedCoordinateSegments ) !== 2 ) {
			// Separator not present within the string, trying to figure out the segments by
			// splitting at the the first SPACE after the first direction character or digit:
			$numberRegEx = '-?\d{1,3}(\.\d{1,20})?';

			$ns = '('
				. $this->getOption( self::OPT_NORTH_SYMBOL ) . '|'
				. $this->getOption( self::OPT_SOUTH_SYMBOL ) .')';

			$latitudeRegEx = '(' . $ns . '\s*)?' . $numberRegEx . '(\s*' . $ns . ')?';

			$ew = '('
				. $this->getOption( self::OPT_EAST_SYMBOL ) . '|'
				. $this->getOption( self::OPT_WEST_SYMBOL ) .')';

			$longitudeRegEx = '(' . $ew . '\s*)?' . $numberRegEx . '(\s*' . $ew . ')?';

			$match = preg_match(
				'/^(' . $latitudeRegEx . ') (' . $longitudeRegEx . ')$/i',
				$normalizedCoordinateString,
				$matches
			);

			if( $match ) {
				$normalizedCoordinateSegments = array( $matches[1], $matches[7] );
			}
		}

		return $normalizedCoordinateSegments;
	}

	/**
	 * Returns whether the coordinate is in float representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $rawCoordinateString
	 *
	 * @return boolean
	 */
	protected function areFloatCoordinates( $rawCoordinateString ) {
		$rawCoordinateSegments = $this->splitString( $rawCoordinateString );

		if( count( $rawCoordinateSegments ) !== 2 ) {
			return false;
		}

		$baseRegExp = '\d{1,3}(\.\d{1,20})?';

		// Cache whether the coordinates are specified in directional format (a mixture of
		// directional and non-directional is regarded invalid).
		$directional = false;

		$match = false;

		foreach( $rawCoordinateSegments as $i => $segment ) {
			$segment = str_replace( ' ', '', $segment );

			$direction = '('
				. $this->getOption( self::OPT_NORTH_SYMBOL ) . '|'
				. $this->getOption( self::OPT_SOUTH_SYMBOL ) . ')';

			if( $i === 1 ) {
				$direction = '('
					. $this->getOption( self::OPT_EAST_SYMBOL ) . '|'
					. $this->getOption( self::OPT_WEST_SYMBOL ) . ')';
			}

			$match = preg_match(
				'/^(' . $baseRegExp . $direction . '|' . $direction . $baseRegExp . ')$/i',
				$segment
			);

			if( $directional && !$match ) {
				// Latitude is directional, longitude not.
				break;
			} elseif( $match ) {
				continue;
			}

			$match = preg_match( '/^(-)?' . $baseRegExp . '$/i', $segment );

			if( !$match ) {
				// Does neither match directional nor non-directional.
				break;
			}
		}

		return $match;
	}

}
