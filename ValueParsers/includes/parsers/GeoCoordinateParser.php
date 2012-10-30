<?php

namespace ValueParsers;
use Exception, InvalidArgumentException;

/**
 * ValueParser that parses the string representation of a geographical coordinate.
 *
 * Supports the following notations:
 * - Degree minute second
 * - Decimal degrees
 * - Decimal minutes
 * - Float
 *
 * And for all these notations direction can be indicated either with
 * + and - or with N/E/S/W, the later depending on the set options.
 *
 * The delimiter between latitude and longitude can be set in the options.
 * So can the symbols used for degrees, minutes and seconds.
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
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GeoCoordinateParser extends StringValueParser {

	const TYPE_FLOAT = 'float';
	const TYPE_DMS = 'dms';
	const TYPE_DM = 'dm';
	const TYPE_DD = 'dd';

	/**
	 * @since 0.1
	 *
	 * @var GeoParserOptions
	 */
	protected $options;

	/**
	 * Sets the options to use during the parsing process.
	 *
	 * @since 0.1
	 *
	 * @param GeoParserOptions $options
	 */
	public function setOptions( GeoParserOptions $options ) {
		$this->options = $options;
	}

	/**
	 * Returns the options to use during the parsing process.
	 *
	 * @since 0.1
	 *
	 * @return GeoParserOptions
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @see StringValueParser::stringParse
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return Result
	 * @throws Exception
	 */
	public function stringParse( $value ) {
		// TODO: improve options interface
		if ( !isset( $this->options ) ) {
			$this->options = new GeoParserOptions();
		}

		$value = $this->getNormalizedNotation( $value );

		$notationType = $this->getCoordinatesType( $value );

		if ( $notationType === false ) {
			return $this->newErrorResult( 'Not a geographical coordinate' );
		}

		$coordinates = explode( $this->options->getSeparatorSymbol(), $value );

		if ( count( $coordinates ) !== 2 ) {
			throw new Exception( 'A coordinates string with an incorrect segment count has made it through validation' );
		}

		list( $latitude, $longitude ) = $coordinates;

		$latitude = $this->getParsedCoordinate( $notationType, $latitude );
		$longitude = $this->getParsedCoordinate( $notationType, $longitude );

		$coordinate = new \DataValues\GeoCoordinateValue( $latitude, $longitude );

		return ResultObject::newSuccess( $coordinate );
	}

	/**
	 * Parsers a single coordinate (either latitude or longitude) and returns it as a float.
	 *
	 * @since 0.1
	 *
	 * @param string $notationType
	 * @param string $coordinate
	 *
	 * @return float
	 *
	 * @throws InvalidArgumentException
	 */
	protected function getParsedCoordinate( $notationType, $coordinate ) {
		$coordinate = $this->resolveDirection( $coordinate );

		switch ( $notationType ) {
			case self::TYPE_FLOAT:
				return (float)$coordinate;
			case self::TYPE_DD:
				return $this->parseDDCoordinate( $coordinate );
			case self::TYPE_DM:
				return $this->parseDMCoordinate( $coordinate );
			case self::TYPE_DMS:
				return $this->parseDMSCoordinate( $coordinate );
			default:
				throw new InvalidArgumentException( 'Invalid coordinate type specified' );
		}
	}

	/**
	 * Returns the type of the provided coordinates, or false if they are invalid.
	 * You can use this as validation function, but be sure to use ===, since 0 can be returned.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return integer or false
	 */
	public function getCoordinatesType( $coordinates ) {
		switch ( true ) {
			case $this->areFloatCoordinates( $coordinates ):
				return self::TYPE_FLOAT;
				break;
			case $this->areDMSCoordinates( $coordinates ):
				return self::TYPE_DMS;
				break;
			case $this->areDDCoordinates( $coordinates ):
				return self::TYPE_DD;
				break;
			case $this->areDMCoordinates( $coordinates ):
				return self::TYPE_DM;
				break;
			default:
				return false;
		}
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

		$n = $this->options->getNorthSymbol();
		$e = $this->options->getEastSymbol();
		$s = $this->options->getSouthSymbol();
		$w = $this->options->getWestSymbol();

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
		$second = $this->options->getSecondSymbol();
		$minute = $this->options->getMinuteSymbol();

		$coordinates = str_replace( array( '&#176;', '&deg;' ), $this->options->getDegreeSymbol(), $coordinates );
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
	 * Takes a set of coordinates in DMS representation, and returns them in float representation.
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

		$degreePosition = strpos( $coordinate, $this->options->getDegreeSymbol() );
		$degrees = substr( $coordinate, 0, $degreePosition );

		$minutePosition = strpos( $coordinate, $this->options->getMinuteSymbol() );

		if ( $minutePosition === false ) {
			$minutes = 0;
		}
		else {
			$degSignLength = strlen( $this->options->getDegreeSymbol() );
			$minuteLength = $minutePosition - $degreePosition - $degSignLength;
			$minutes = substr( $coordinate, $degreePosition + $degSignLength, $minuteLength );
		}

		$secondPosition = strpos( $coordinate, $this->options->getSecondSymbol() );

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
	 * Takes a set of coordinates in Decimal Degree representation, and returns them in float representation.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function parseDDCoordinate( $coordinate ) {
		return (float)str_replace( $this->options->getDegreeSymbol(), '', $coordinate );
	}

	/**
	 * Takes a set of coordinates in Decimal Minute representation, and returns them in float representation.
	 *
	 * @since 0.1
	 *
	 * @param string $coordinate
	 *
	 * @return float
	 */
	protected function parseDMCoordinate( $coordinate ) {
		$isNegative = $coordinate{0} == '-';

		if ( $isNegative ) {
			$coordinate = substr( $coordinate, 1 );
		}

		list( $degrees, $minutes ) = explode( $this->options->getDegreeSymbol(), $coordinate );

		$minutes = substr( $minutes, 0, -1 );

		$coordinate = $degrees + $minutes / 60;

		if ( $isNegative ) {
			$coordinate *= -1;
		}

		return (float)$coordinate;
	}

	/**
	 * returns whether the coordinates are in float representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return boolean
	 */
	protected function areFloatCoordinates( $coordinates ) {
		$sep = $this->options->getSeparatorSymbol();

		$match = preg_match( '/^(-)?\d{1,3}(\.\d{1,20})?' . $sep . '(-)?\d{1,3}(\.\d{1,20})?$/i', $coordinates ) // Non-directional
			|| preg_match( '/^\d{1,3}(\.\d{1,20})?(N|S)' . $sep . '\d{1,3}(\.\d{1,20})?(E|W)$/i', $coordinates ); // Directional;

		return $match;
	}

	/**
	 * returns whether the coordinates are in DMS representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return boolean
	 */
	protected function areDMSCoordinates( $coordinates ) {
		$sep = $this->options->getSeparatorSymbol();

		$match = preg_match( '/^(-)?(\d{1,3}°)(\d{1,2}(\′|\'))?((\d{1,2}(″|"))?|(\d{1,2}\.\d{1,20}(″|"))?)'
			. $sep . '(-)?(\d{1,3}°)(\d{1,2}(\′|\'))?((\d{1,2}(″|"))?|(\d{1,2}\.\d{1,20}(″|"))?)$/i', $coordinates ) // Non-directional
			|| preg_match( '/^(\d{1,3}°)(\d{1,2}(\′|\'))?((\d{1,2}(″|"))?|(\d{1,2}\.\d{1,20}(″|"))?)(N|S)'
				. $sep . '(\d{1,3}°)(\d{1,2}(\′|\'))?((\d{1,2}(″|"))?|(\d{1,2}\.\d{1,20}(″|"))?)(E|W)$/i', $coordinates ); // Directional

		return $match;
	}

	/**
	 * returns whether the coordinates are in Decimal Degree representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return boolean
	 */
	protected function areDDCoordinates( $coordinates ) {
		$sep = $this->options->getSeparatorSymbol();

		$match = preg_match( '/^(-)?\d{1,3}(|\.\d{1,20})°' . $sep . '(-)?\d{1,3}(|\.\d{1,20})°$/i', $coordinates ) // Non-directional
			|| preg_match( '/^\d{1,3}(|\.\d{1,20})°(N|S)' . $sep . '\d{1,3}(|\.\d{1,20})°(E|W)?$/i', $coordinates ); // Directional

		return $match;
	}

	/**
	 * returns whether the coordinates are in Decimal Minute representation.
	 * TODO: nicify
	 *
	 * @since 0.1
	 *
	 * @param string $coordinates
	 *
	 * @return boolean
	 */
	protected function areDMCoordinates( $coordinates ) {
		$sep = $this->options->getSeparatorSymbol();

		$match = preg_match( '/(-)?\d{1,3}°(\d{1,2}(\.\d{1,20}\')?)?' . $sep . '(-)?\d{1,3}°(\d{1,2}(\.\d{1,20}\')?)?$/i', $coordinates ) // Non-directional
			|| preg_match( '/\d{1,3}°(\d{1,2}(\.\d{1,20}\')?)?(N|S)' . $sep . '\d{1,3}°(\d{1,2}(\.\d{1,20}\')?)?(E|W)?$/i', $coordinates ); // Directional

		return $match;
	}

	/**
	 * Convenience function for determining if something is a valid coordinate string.
	 * Analogous to creating an instance of the parser, parsing the string and checking isValid on the result.
	 *
	 * @since 0.1
	 *
	 * @param string $string
	 */
	public static function areCoordinates( $string ) {
		static $parser = null;

		if ( $parser === null ) {
			$parser = new self();
		}

		return $parser->parse( $string )->isValid();
	}

}
