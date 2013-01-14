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
	 *
	 * @param {Object} options
	 */
	vp.ValueParser = function( options ) {
		this._options = options || {};
	};

	vp.ValueParser.prototype = {

		/**
		 * Parser options.
		 * Option name: option value.
		 *
		 * @since 0.1
		 */
		_options: {},

		/**
		 * Parses a value. Will return a jQuery.Promise which will be resolved if the parsing is
		 * successful or rejected if it fails. There can be various reasons for the parsing to fail,
		 * e.g. the parser is using the API and the API can't be reached.
		 *
		 * @since 0.1
		 *
		 * @param {*} rawValue
		 *
		 * @return $.Promise In the resolved callbacks the first parameter will be the parsed
		 *         DataValue object.
		 */
		parse: vp.util.abstractMember

	};

}( valueParsers, jQuery ) );
