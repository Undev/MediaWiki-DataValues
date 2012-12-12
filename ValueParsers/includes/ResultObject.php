<?php

namespace ValueParsers;
use Exception;

/**
 * Implementation of the value parser result interface.
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
class ResultObject implements Result {

	/**
	 * Indicates if the parsing process was successful.
	 *
	 * @since 0.1
	 *
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * A DataValue instance representing the parsed value,
	 * or null if the parsing process failed.
	 *
	 * @since 0.1
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @since 0.1
	 *
	 * @var Error|null
	 */
	protected $error;

	/**
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return Result
	 */
	public static function newSuccess(  $value ) {
		return new static( true, $value );
	}

	/**
	 * @since 0.1
	 *
	 * @param Error $error
	 *
	 * @return Result
	 */
	public static function newError( Error $error ) {
		return new static( false, null, $error );
	}

	/**
	 * @since 0.1
	 *
	 * @param string $error
	 *
	 * @return Result
	 */
	public static function newErrorText( $error ) {
		return static::newError( ErrorObject::newError( $error ) );
	}

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param boolean $isValid
	 * @param mixed $value
	 * @param Error|null $error
	 */
	protected function __construct( $isValid, $value = null, Error $error = null ) {
		$this->isValid = $isValid;
		$this->value = $value;
		$this->error = $error;
	}

	/**
	 * @see ValueParserResult::getValue
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getValue() {
		if ( $this->isValid() ) {
			return $this->value;
		}
		else {
			throw new Exception( 'The parsing process failed, so the result value cannot be obtained' );
		}
	}

	/**
	 * @see ValueParserResult::isValid
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->isValid;
	}

	/**
	 * @see ValueParserResult::getError
	 *
	 * @since 0.1
	 *
	 * @return Error|null
	 */
	public function getError() {
		return $this->error;
	}

}
