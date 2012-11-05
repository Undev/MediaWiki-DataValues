/**
 * @file
 * @ingroup ValueParsers
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( vp, $, undefined ) {
	'use strict';

	/**
	 * Base constructor for objects representing a value parser.
	 *
	 * @constructor
	 * @abstract
	 * @since 0.1
	 */
	vp.ValueParser = function() {};
	vp.ValueParser.prototype = {

		/**
		 * Parses a value.
		 *
		 * @since 0.1
		 *
		 * @param {mixed} $value
		 *
		 * @return $.Promise
		 */
		parse: vp.util.abstractMember

	};

}( valueParsers, jQuery ) );
