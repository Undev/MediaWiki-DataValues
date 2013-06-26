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
class FloatCoordinateParser extends GeoCoordinateParserBase {

	/**
	 * @since 0.1
	 *
	 * @param ParserOptions|null $options
	 */
	public function __construct( ParserOptions $options = null ) {
		parent::__construct( $options );
	}

	/**
	 * @see GeoCoordinateParserBase::getParsedCoordinate
	 */
	protected function getParsedCoordinate( $coordinateSegment ) {
		return ( float )$this->resolveDirection( str_replace( ' ', '', $coordinateSegment ) );
	}

	/**
	 * @see GeoCoordinateParserBase::detectPrecision
	 */
	protected function detectPrecision( $number ) {
		// TODO: Implement localized decimal separator.
		$split = explode( '.', $number );

		$precision = 1;

		if( isset( $split[1] ) ) {
			$precision = pow( 10, -1 * strlen( $split[1] ) );
		}

		return $precision;
	}

	/**
	 * @see GeoCoordinateParserBase::areValidCoordinates
	 */
	protected function areValidCoordinates( $normalizedCoordinateSegments ) {
		// TODO: Implement localized decimal separator.
		$baseRegExp = '\d{1,3}(\.\d{1,20})?';

		// Cache whether the coordinates are specified in directional format (a mixture of
		// directional and non-directional is regarded invalid).
		$directional = false;

		$match = false;

		foreach( $normalizedCoordinateSegments as $i => $segment ) {
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

	/**
	 * @see GeoCoordinateParserBase::splitString
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

		if( count( $normalizedCoordinateSegments ) !== 2 ) {
			throw new ParseException( __CLASS__ . ': Unable to split string '
			. $normalizedCoordinateString . ' into two coordinate segments' );
		}

		return $normalizedCoordinateSegments;
	}

}
