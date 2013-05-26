<?php
/**
 * Definition of "DataValues" resourceloader modules.
 * When included this returns an array with all the modules introduced by "DataValues" extension.
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
 * @ingroup DataValues
 *
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 *
 * @codeCoverageIgnoreStart
 */
return call_user_func( function() {

	$moduleTemplate = array(
		'localBasePath' => __DIR__ . '/resources',
		'remoteExtPath' =>  'DataValues/DataValues/resources',
	);

	$mwVvResources = array(
		'mw.ext.dataValues' => $moduleTemplate + array(
			'scripts' => array(
				'mw.ext.dataValues.js',
			),
			'dependencies' => array(
				// load all values. TODO: this is bad but the system is not as advanced as ValueView yet.
				'dataValues.values'
			),
			'messages' => array(
				'jan', 'january',
				'feb', 'february',
				'mar', 'march',
				'apr', 'april',
				'may', 'may_long',
				'jun', 'june',
				'jul', 'july',
				'aug', 'august',
				'sep', 'september',
				'oct', 'october',
				'nov', 'november',
				'dec', 'december',
			)
		),

		// Dependencies required by "DataValues" library:

		// coordinate.js
		'coordinate.js' => $moduleTemplate + array(
			'scripts' => array(
				'coordinate.js/src/coordinate.js',
				'coordinate.js/src/coordinate.parser.js',
				'coordinate.js/src/coordinate.Coordinate.js',
			),
		),

		// time.js
		'time.js' => $moduleTemplate + array(
			'scripts' => array(
				'time.js/src/time.js',
				'time.js/src/time.Time.js',
				'time.js/src/time.Time.validate.js',
				'time.js/src/time.Time.parse.js',
			),
			'dependencies' => array(
				'jquery',
			),
		),

		'time.js.validTimeDefinitions' => $moduleTemplate + array(
			'scripts' => array(
				'time.js/tests/time.validTimeDefinitions.js', // example times for testing purposes
			),
			'dependencies' => array(
				'time.js',
			),
		),

		// qunit-parameterize from https://github.com/AStepaniuk/qunit-parameterize
		'qunit.parameterize' => $moduleTemplate + array(
			'scripts' => array(
				'qunit.parameterize/qunit.parameterize.js',
			),
			'dependencies' => array(
				'jquery.qunit',
			),
		),
	);

	// return "DataValue" module's native resources plus those required by the MW extension:
	return $mwVvResources + include( __DIR__ . '/DataValues.resources.php' );
} );
// @codeCoverageIgnoreEnd
