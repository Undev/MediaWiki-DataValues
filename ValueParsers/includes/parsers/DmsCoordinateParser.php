<?php

namespace ValueParsers;

use DataValues\GeoCoordinateValue;
use LogicException;

/**
 * Parser for geographical coordinates in Degree Minute Second notation.
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
class DmsCoordinateParser extends StringValueParser {

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
		$this->defaultOption( self::OPT_MINUTE_SYMBOL, "'" );
		$this->defaultOption( self::OPT_SECOND_SYMBOL, '"' );

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

		if ( !$this->areDMSCoordinates( $value ) ) {
			throw new ParseException( 'Not a geographical coordinate in DMS format' );
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
		return $this->parseDMSCoordinate( $coordinate );
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
		$second = $this->getOption( self::OPT_SECOND_SYMBOL );
		$minute = $this->getOption( self::OPT_MINUTE_SYMBOL );

		$coordinates = str_replace( array( '&#176;', '&deg;' ), $this->getOption( self::OPT_DEGREE_SYMBOL ), $coordinates );
		$coordinates = str_replace( array( '&acute;', '&#180;' ), $second, $coordinates );
		$coordinates = str_replace( array( '&#8242;', '&prime;', '´', '′' ), $minute, $coordinates );
		$coordinates = str_replace( array( '&#8243;', '&Prime;', $minute . $minute, '´´', '′′', '″' ), $second, $coordinates );

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
	 * Takes a coordinate segment in DMS representation, and returns it in float representation.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function parseDMSCoordinate( $coordinate ) {
		$isNegative = $coordinate{0} == '-';

		if ( $isNegative ) {
			$coordinate = substr( $coordinate, 1 );
		}

		$degreePosition = strpos( $coordinate, $this->getOption( self::OPT_DEGREE_SYMBOL ) );
		$degrees = (float)substr( $coordinate, 0, $degreePosition );

		$minutePosition = strpos( $coordinate, $this->getOption( self::OPT_MINUTE_SYMBOL ) );

		if ( $minutePosition === false ) {
			$minutes = 0;
		}
		else {
			$degSignLength = strlen( $this->getOption( self::OPT_DEGREE_SYMBOL ) );
			$minuteLength = $minutePosition - $degreePosition - $degSignLength;
			$minutes = substr( $coordinate, $degreePosition + $degSignLength, $minuteLength );
		}

		$secondPosition = strpos( $coordinate, $this->getOption( self::OPT_SECOND_SYMBOL ) );

		if ( $minutePosition === false ) {
			$seconds = 0;
		}
		else {
			$secondLength = $secondPosition - $minutePosition - 1;
			$seconds = substr( $coordinate, $minutePosition + 1, $secondLength );
		}

		$coordinate = $degrees + ( $minutes + $seconds / 60 ) / 60;

		if ( $isNegative ) {
			$coordinate *= -1;
		}

		return (float)$coordinate;
	}

	/**
	 * Detects the precision of a given number.
	 *
	 * @since 0.1
	 *
	 * @param float $number
	 *
	 * @return float
	 */
	protected function detectPrecision( $number ) {
		$seconds = $number * 3600;

		// Since we are in the DMS parser, we know that precision needs at least to be an arcsecond:
		$precision = 1 / 3600;

		if( $number - floor( $number ) > 0 ) {
			$secondsSplit = explode( '.', $seconds );

			if( isset( $secondsSplit[1] ) ) {
				$precision *= pow( 10, -1 * strlen( $secondsSplit[1] ) );
			}
		}

		return $precision;
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
			// splitting after the first direction character or second symbol:
			$delimiters = array(
				$this->getOption( self::OPT_NORTH_SYMBOL ),
				$this->getOption( self::OPT_SOUTH_SYMBOL ),
				'″',
				'"'
			);

			foreach( $delimiters as $delimiter ) {
				$delimiterPos = mb_strpos( $normalizedCoordinateString, $delimiter );
				if( $delimiterPos !== false ) {
					$delimiterLength = mb_strlen( $delimiter );

					$normalizedCoordinateSegments = array(
						mb_substr( $normalizedCoordinateString, 0, $delimiterPos + $delimiterLength ),
						mb_substr( $normalizedCoordinateString, $delimiterPos + $delimiterLength )
					);
					break;
				}
			}
		}

		return $normalizedCoordinateSegments;
	}

	/**
	 * Returns whether the coordinate is in DMS representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $rawCoordinateString
	 *
	 * @return boolean
	 */
	protected function areDMSCoordinates( $rawCoordinateString ) {
		$rawCoordinates = $this->splitString( $rawCoordinateString );

		if( count( $rawCoordinates ) !== 2 ) {
			return false;
		}

		// At least one coordinate segment needs to have seconds specified (which additionally
		// requires minutes to be specified).
		$regExpLoose = '(\d{1,3}°)(\d{1,2}(′|\'))?((\d{1,2}(″|"))?|(\d{1,2}\.\d{1,20}(″|"))?)';
		$regExpStrict = str_replace( '?', '', $regExpLoose );

		// Cache whether seconds have been detected within the coordinate:
		$detectedSecond = false;

		// Cache whether the coordinates are specified in directional format (a mixture of
		// directional and non-directional is regarded invalid).
		$directional = false;

		foreach( $rawCoordinates as $i => $rawCoordinate ) {
			$direction = '('
				. $this->getOption( self::OPT_NORTH_SYMBOL ) . '|'
				. $this->getOption( self::OPT_SOUTH_SYMBOL ) . ')';

			if( $i === 1 ) {
				$direction = '('
					. $this->getOption( self::OPT_EAST_SYMBOL ) . '|'
					. $this->getOption( self::OPT_WEST_SYMBOL ) . ')';
			}

			$match = preg_match( '/^' . $regExpStrict . $direction . '$/i', $rawCoordinate );

			if( $match ) {
				$detectedSecond = true;
			} else {
				$match = preg_match( '/^' . $regExpLoose . $direction . '$/i', $rawCoordinate );
			}

			if( $match ) {
				$directional = true;
			} elseif ( !$directional ) {
				$match = preg_match( '/^(-)?' . $regExpStrict . '$/i', $rawCoordinate );

				if( $match ) {
					$detectedSecond = true;
				} else  {
					$match = preg_match( '/^(-)?' . $regExpLoose . '$/i', $rawCoordinate );
				}
			}

			if( !$match ) {
				return false;
			}
		}

		return $detectedSecond;
	}

}
