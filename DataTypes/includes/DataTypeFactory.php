<?php

namespace DataTypes;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * Factory for creating data types.
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
 * @ingroup DataTypes
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 */
class DataTypeFactory {

	/**
	 * Maps type id to DataType.
	 *
	 * @since 0.1
	 *
	 * @var DataType[]
	 */
	protected $types = array();

	/**
	 * Maps type id to a DataType builder spec.
	 * See buildType() for more information.
	 *
	 * @var array
	 */
	protected $typeBuilders = array();

	/**
	 * @since 0.1
	 *
	 * @param array $typeBuilders An array mapping type IDs to type builders. A type builder
	 *        is a callable that takes a type ID as a parameter and returns a DataType object.
	 *        Alternatively, a DataType object may be provided directly.
	 */
	public function __construct( array $typeBuilders = array() ) {
		//TODO: check element type
		$this->typeBuilders = $typeBuilders;
	}

	/**
	 * @since 0.1
	 *
	 * @param DataType[] $dataTypes
	 *
	 * @return DataTypeFactory
	 */
	public static function newFromTypes( array $dataTypes ) {
		$factory = new self();

		foreach ( $dataTypes as $dataType ) {
			$factory->registerDataType( $dataType );
		}

		return $factory;
	}

	/**
	 * @since 0.1
	 *
	 * @param DataType $dataType
	 */
	public function registerDataType( DataType $dataType ) {
		$this->types[$dataType->getId()] = $dataType;
	}

	/**
	 * @since 0.4
	 *
	 * @param string $typeId
	 * @param callable $builder A builder that takes $typeId and returns a DataType object
	 *
	 * @throws \InvalidArgumentException
	 */
	public function registerBuilder( $typeId, $builder ) {
		if ( !is_callable( $builder ) ) {
			throw new InvalidArgumentException( "Builder must be callable." );
		}

		$this->typeBuilders[$typeId] = $builder;
	}

	/**
	 * Creates a DataType instance from some form of builder.
	 *
	 * @since 0.4
	 *
	 * @param string $typeId
	 * @param mixed  $builderSpec specifies how to build the data type.
	 *                            It supports several ways to specify a builder:
	 *        - if $builderSpec as a DataType object already, it is returned.
	 *        - if $builderSpec as callable, it will be called with $typeId as the only parameter.
	 *        - if $builderSpec as an associative array, newType( $typeId, $builderSpec )
	 *                            is called for backwards compatibility
	 *
	 * @throws \InvalidArgumentException
	 * @return DataType
	 */
	protected function buildType( $typeId, $builderSpec ) {
		if ( $builderSpec instanceof DataType ) {
			$type = $builderSpec;
		} elseif ( is_array( $builderSpec )
			&& count( $builderSpec  ) == 2
			&& $builderSpec[1] === '__construct' ) {

			$class = new \ReflectionClass( $builderSpec[0] );
			$type = $class->newInstance( $typeId );
		} elseif ( is_callable( $builderSpec ) ) {
			$type = call_user_func( $builderSpec, $typeId );
		} elseif ( is_array( $builderSpec ) ) {
			//B/C mode
			//TODO: start failing on this using wfWarn or wfDeprecated
			$type = $this->newType( $typeId, $builderSpec );
		} else {
			throw new InvalidArgumentException( "Bad builder spec, expected a callable." );
		}

		if ( !( $type instanceof DataType ) ) {
			throw new RuntimeException( "DataType builder did not return a DataType" );
		}

		return $type;
	}

	/**
	 * Returns a new instance of DataType constructed from the
	 * provided type data.
	 *
	 * @since 0.1
	 *
	 * @param string $typeId
	 * @param array $typeData
	 *
	 * @deprecated since 0.4, use a proper callable builder instead.
	 *
	 * @return DataType
	 * @throws InvalidArgumentException
	 */
	protected function newType( $typeId, array $typeData ) {
		if ( !array_key_exists( 'datavalue', $typeData ) || !is_string( $typeData['datavalue'] ) ) {
			throw new InvalidArgumentException( 'Invalid datavalue type provided to DataTypeFactory' );
		}

		// TODO: use string ids for components once they have their own factories

		// TODO: Get rid of this whole method, use callable builders instead.
		//       Make a utility class that generates a builder based on an a spec array.

		$parser = array_key_exists( 'parser', $typeData ) ? $typeData['parser'] : 'ValueParsers\NullParser';

		if ( is_string( $parser ) ) {
			$parser = new $parser();
		}

		$formatters = array();

		if ( array_key_exists( 'formatter', $typeData ) ) {
			$formatter = $typeData['formatter'];

			if ( is_string( $formatter ) ) {
				$formatter = new $formatter();
			}

			$formatters[] = $formatter;
		}

		if ( array_key_exists( 'validators', $typeData ) ) {
			$validators = is_array( $typeData['validators'] ) ? $typeData['validators'] : array( $typeData['validators'] );
		}
		else {
			$validators = array();
		}

		foreach ( $validators as &$validator ) {
			if ( is_string( $validator ) ) {
				$validator = new $validator();
			}
		}

		return new DataType(
			$typeId,
			$typeData['datavalue'],
			array( $parser ), // TODO
			$formatters,
			$validators
		);
	}

	/**
	 * Returns the type identifiers.
	 *
	 * @since 0.1
	 *
	 * @return string[] $typeId
	 */
	public function getTypeIds() {
		return array_unique( array_merge(
			array_keys( $this->types ),
			array_keys( $this->typeBuilders ) ) );
	}

	/**
	 * Returns the data type that has the specified type identifier.
	 * Types may be instantiated on the fly using a type builder spec.
	 *
	 * @since 0.1
	 *
	 * @param string $typeId
	 *
	 * @return DataType
	 * @throws OutOfBoundsException if the requested type is not known.
	 */
	public function getType( $typeId ) {
		if ( !array_key_exists( $typeId, $this->types )
			&& array_key_exists( $typeId, $this->typeBuilders ) ) {

			$builderSpec = $this->typeBuilders[$typeId];
			$this->types[$typeId] = $this->buildType( $typeId, $builderSpec );
		}

		if ( array_key_exists( $typeId, $this->types ) ) {
			return $this->types[$typeId];
		}

		throw new OutOfBoundsException( "Unknown data type '$typeId'" );
	}

	/**
	 * Returns all data types in an associative array with
	 * the keys being type identifiers pointing to their
	 * corresponding data type.
	 *
	 * @since 0.1
	 *
	 * @return DataType[]
	 */
	public function getTypes() {
		$types = array();

		foreach ( $this->getTypeIds() as $typeId ) {
			$types[] = $this->getType( $typeId );
		}

		return $types;
	}

}
