<?php

/**
 * Class registration file for the ValueValidator library.
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
 * @ingroup ValueValidators
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return array(
	'ValueValidators\ValueValidatorError' => 'includes/ValueValidatorError.php',
	'ValueValidators\ValueValidatorErrorObject' => 'includes/ValueValidatorErrorObject.php',
	'ValueValidators\ValueValidatorResult' => 'includes/ValueValidatorResult.php',
	'ValueValidators\ValueValidatorResultObject' => 'includes/ValueValidatorResultObject.php',

	'ValueValidators\DimensionValidator' => 'valuevalidator/DimensionValidator.php',
	'ValueValidators\ListValidator' => 'valuevalidator/ListValidator.php',
	'ValueValidators\NullValidator' => 'valuevalidator/NullValidator.php',
	'ValueValidators\RangeValidator' => 'valuevalidator/RangeValidator.php',
	'ValueValidators\StringValidator' => 'valuevalidator/StringValidator.php',
	'ValueValidators\TitleValidator' => 'valuevalidator/TitleValidator.php',
	'ValueValidators\ValueValidator' => 'valuevalidator/ValueValidator.php',
	'ValueValidators\ValueValidatorObject' => 'valuevalidator/ValueValidatorObject.php',
);