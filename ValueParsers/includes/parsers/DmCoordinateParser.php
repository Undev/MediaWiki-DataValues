<?php

namespace ValueParsers;

use DataValues\GeoCoordinateValue;
use LogicException;

/**
 * Parser for geographical coordinates in Decimal Minute notation.
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
class DmCoordinateParser extends DdCoordinateParser {

	/**
	 * The symbols representing minutes.
	 * @since 0.1
	 */
	const OPT_MINUTE_SYMBOL = 'minute';

	/**
	 * @since 0.1
	 *
	 * @param ParserOptions|null $options
	 */
	public function __construct( ParserOptions $options = null ) {
		parent::__construct( $options );

		$this->defaultOption( self::OPT_MINUTE_SYMBOL, "'" );

		$this->defaultDelimiters = array( $this->getOption( self::OPT_MINUTE_SYMBOL ) );
	}

	/**
	 * @see GeoCoordinateParserBase::detectPrecision
	 */
	protected function detectPrecision( $number ) {
		$minutes = $number * 60;

		// Since we are in the DM parser, we know that precision needs at least to be an arcminute:
		$precision = 1 / 60;

		// The minute may be a float; In order to detect a proper precision, we convert the minutes
		// to seconds.
		if( $minutes - floor( $minutes ) > 0 ) {
			$seconds = $minutes * 60;

			$precision = 1 / 3600;

			// TODO: Implement localized decimal separator.
			$secondsSplit = explode( '.', $seconds );

			if( isset( $secondsSplit[1] ) ) {
				$precision *= pow( 10, -1 * strlen( $secondsSplit[1] ) );
			}
		}

		return $precision;
	}

	/**
	 * @see GeoCoordinateParserBase::areValidCoordinates
	 */
	protected function areValidCoordinates( $normalizedCoordinateSegments ) {
		// At least one coordinate segment needs to have minutes specified.
		$regExpStrict = '\d{1,3}'
			. preg_quote( $this->getOption( self::OPT_DEGREE_SYMBOL ) )
			// TODO: Implement localized decimal separator.
			. '(\d{1,2}(\.\d{1,20})?'
			. preg_quote( $this->getOption( self::OPT_MINUTE_SYMBOL ) )
			. ')';
		$regExpLoose = $regExpStrict . '?';

		// Cache whether minutes have been detected within the coordinate:
		$detectedMinute = false;

		// Cache whether the coordinates are specified in directional format (a mixture of
		// directional and non-directional is regarded invalid).
		$directional = false;

		foreach( $normalizedCoordinateSegments as $i => $segment ) {
			$direction = '('
				. $this->getOption( self::OPT_NORTH_SYMBOL ) . '|'
				. $this->getOption( self::OPT_SOUTH_SYMBOL ) . ')';

			if( $i === 1 ) {
				$direction = '('
					. $this->getOption( self::OPT_EAST_SYMBOL ) . '|'
					. $this->getOption( self::OPT_WEST_SYMBOL ) . ')';
			}

			$match = preg_match(
				'/^(' . $regExpStrict . $direction . '|' . $direction . $regExpStrict . ')$/i',
				$segment
			);

			if( $match ) {
				$detectedMinute = true;
			} else {
				$match = preg_match(
					'/^(' . $regExpLoose . $direction . '|' . $direction . $regExpLoose . ')$/i',
					$segment
				);
			}

			if( $match ) {
				$directional = true;
			} elseif ( !$directional ) {
				$match = preg_match( '/^(-)?' . $regExpStrict . '$/i', $segment );

				if( $match ) {
					$detectedMinute = true;
				} else  {
					$match = preg_match( '/^(-)?' . $regExpLoose . '$/i', $segment );
				}
			}

			if( !$match ) {
				return false;
			}
		}

		return $detectedMinute;
	}

	/**
	 * @see DdCoordinateParser::getNormalizedNotation
	 */
	protected function getNormalizedNotation( $coordinates ) {
		$minute = $this->getOption( self::OPT_MINUTE_SYMBOL );

		$coordinates = str_replace( array( '&#8242;', '&prime;', '´', '′' ), $minute, $coordinates );

		$coordinates = parent::getNormalizedNotation( $coordinates );

		$coordinates = $this->removeInvalidChars( $coordinates );

		return $coordinates;
	}

	/**
	 * @see DdCoordinateParser::parseCoordinate
	 */
	protected function parseCoordinate( $coordinateSegment ) {
		$isNegative = $coordinateSegment{0} == '-';

		if ( $isNegative ) {
			$coordinateSegment = substr( $coordinateSegment, 1 );
		}

		$exploded = explode( $this->getOption( self::OPT_DEGREE_SYMBOL ), $coordinateSegment );

		if( count( $exploded ) !== 2 ) {
			throw new ParseException( 'Unable to explode coordinate segment ' . $coordinateSegment
				. ' by degree symbol (' . $this->getOption( self::OPT_DEGREE_SYMBOL ) . ')' );
		}

		list( $degrees, $minutes ) = $exploded;

		$minutes = substr( $minutes, 0, -1 );

		$coordinateSegment = $degrees + $minutes / 60;

		if ( $isNegative ) {
			$coordinateSegment *= -1;
		}

		return ( float )$coordinateSegment;
	}

}
