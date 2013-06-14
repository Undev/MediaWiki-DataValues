<?php

namespace DataValues;

/**
 * Class representing a geographical coordinate value.
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
 * @ingroup DataValue
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GeoCoordinateValue extends DataValueObject {

	/**
	 * The locations latitude.
	 *
	 * @since 0.1
	 *
	 * @var float
	 */
	protected $latitude;

	/**
	 * The locations longitude.
	 *
	 * @since 0.1
	 *
	 * @var float
	 */
	protected $longitude;

	/**
	 * The locations altitude or null if it's not known.
	 *
	 * @since 0.1
	 *
	 * @var float|null
	 */
	protected $altitude;

	/**
	 * The precision of the coordinate.
	 *
	 * @since 0.1
	 *
	 * @var float|null
	 */
	protected $precision;

	/**
	 * The globe on which the location resides.
	 *
	 * @since 0.1
	 *
	 * @var string|null
	 */
	protected $globe;

	/**
	 * @since 0.1
	 *
	 * @param float|int $latitude
	 * @param float|int $longitude
	 * @param float|int|null $altitude
	 * @param float|int|null $precision
	 * @param string|null $globe
	 *
	 * @throws IllegalValueException
	 */
	public function __construct( $latitude, $longitude, $altitude = null, $precision = null, $globe = null ) {
		// TODO: validate those values!
		if ( is_int( $latitude ) ) {
			$latitude = (float)$latitude;
		}

		if ( is_int( $longitude ) ) {
			$longitude = (float)$longitude;
		}

		if ( is_int( $altitude ) ) {
			$altitude = (float)$altitude;
		}

		if( is_int( $precision ) ) {
			$precision = (float)$precision;
		}

		if ( !is_float( $latitude ) ) {
			throw new IllegalValueException( 'Can only construct GeoCoordinateValue with a numeric latitude' );
		}

		if ( !is_float( $longitude ) ) {
			throw new IllegalValueException( 'Can only construct GeoCoordinateValue with a numeric longitude' );
		}

		if ( $altitude !== null && !is_float( $altitude ) ) {
			throw new IllegalValueException( 'Can only construct GeoCoordinateValue with a numeric altitude' );
		}

		if ( $precision !== null && !is_float( $precision ) ) {
			throw new IllegalValueException( 'Can only construct GeoCoordinateValue with a numeric precision' );
		}

		if ( !is_string( $globe ) && $globe !== null ) {
			throw new IllegalValueException( 'Can only construct GeoCoordinateValue with a string or null globe parameter' );
		}

		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->altitude = $altitude;
		$this->precision = $precision;
		$this->globe = ( is_null( $globe ) ) ? 'http://www.wikidata.org/entity/Q2' : $globe;
	}

	/**
	 * @see Serializable::serialize
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function serialize() {
		return json_encode( array_values( $this->getArrayValue() ) );
	}

	/**
	 * @see Serializable::unserialize
	 *
	 * @since 0.1
	 *
	 * @param string $value
	 *
	 * @return GeoCoordinateValue
	 * @throws IllegalValueException
	 */
	public function unserialize( $value ) {
		list( $latitude, $longitude, $altitude, $precision, $globe ) = json_decode( $value );
		$this->__construct( $latitude, $longitude, $altitude, $precision, $globe );
	}

	/**
	 * @see DataValue::getType
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public static function getType() {
		return 'globecoordinate';
	}

	/**
	 * @see DataValue::getSortKey
	 *
	 * @since 0.1
	 *
	 * @return string|float|int
	 */
	public function getSortKey() {
		return $this->latitude;
	}

	/**
	 * Returns the text.
	 * @see DataValue::getValue
	 *
	 * @since 0.1
	 *
	 * @return GeoCoordinateValue
	 */
	public function getValue() {
		return $this;
	}

	/**
	 * Returns the latitude.
	 *
	 * @since 0.1
	 *
	 * @return float
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Returns the longitude.
	 *
	 * @since 0.1
	 *
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Returns the altitude.
	 *
	 * @since 0.1
	 *
	 * @return float|null
	 */
	public function getAltitude() {
		return $this->altitude;
	}

	/**
	 * Returns the precision of the coordinate.
	 *
	 * TODO: Introduce some constants holding the different precisions and document. Sync with JS.
	 *  Take Time as an example how to do this.
	 *
	 * @since 0.1
	 *
	 * @return float|null
	 */
	public function getPrecision() {
		return $this->precision;
	}

	/**
	 * Returns the globe on which the location resides.
	 *
	 * @since 0.1
	 *
	 * @return string|null
	 */
	public function getGlobe() {
		return $this->globe;
	}

	/**
	 * @see DataValue::getArrayValue
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 */
	public function getArrayValue() {
		return array(
			'latitude' => $this->latitude,
			'longitude' => $this->longitude,
			'altitude' => $this->altitude,
			'precision' => $this->precision,
			'globe' => $this->globe,
		);
	}

	/**
	 * Constructs a new instance of the DataValue from the provided data.
	 * This can round-trip with @see getArrayValue
	 *
	 * @since 0.1
	 *
	 * @param mixed $data
	 *
	 * @return GeoCoordinateValue
	 * @throws IllegalValueException
	 */
	public static function newFromArray( $data ) {
		self::requireArrayFields( $data, array( 'latitude', 'longitude' ) );

		return new static(
			$data['latitude'],
			$data['longitude'],
			( isset( $data['altitude'] ) ) ? $data['altitude'] : null,
			( isset( $data['precision'] ) ) ? $data['precision'] : null,
			( isset( $data['globe'] ) ) ? $data['globe'] : null
		);
	}

}
