<?php

/**
 * Class registration file for the ValueValidator library.
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
	'ValueValidators\Error' => 'includes/Error.php',
	'ValueValidators\ErrorObject' => 'includes/Error.php',
	'ValueValidators\Result' => 'includes/Result.php',
	'ValueValidators\ResultObject' => 'includes/Result.php',
	'ValueValidators\ValueValidator' => 'includes/ValueValidator.php',
	'ValueValidators\ValueValidatorFactory' => 'includes/ValueValidatorFactory.php',
	'ValueValidators\ValueValidatorObject' => 'includes/ValueValidatorObject.php',

	'ValueValidators\DimensionValidator' => 'includes/validators/DimensionValidator.php',
	'ValueValidators\ListValidator' => 'includes/validators/ListValidator.php',
	'ValueValidators\NullValidator' => 'includes/validators/NullValidator.php',
	'ValueValidators\RangeValidator' => 'includes/validators/RangeValidator.php',
	'ValueValidators\StringValidator' => 'includes/validators/StringValidator.php',
	'ValueValidators\TitleValidator' => 'includes/validators/TitleValidator.php',
);
