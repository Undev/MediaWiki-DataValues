/**
 * @file
 * @ingroup DataValues
 *
 * @licence GNU GPL v2+
 *
 * @author Daniel Werner
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( mw, vp, dv, $, undefined ) {
	'use strict';

	var PARENT = vp.ValueParser,
		constructor = function() {};

	/**
	 * Constructor for string to integer parsers.
	 *
	 * @constructor
	 * @extends vp.ValueParser
	 * @since 0.1
	 */
	vp.IntParser = dv.util.inherit( PARENT, constructor, {

		/**
		 * @see vp.ValueParser.parse
		 *
		 * @since 0.1
		 *
		 * @param {String} rawValue
		 *
		 * @return $.Promise
		 */
		parse: function( rawValue ) {
			return vp.api.parseValues( 'int', [ rawValue ] );
		}

	} );

}( mediaWiki, valueParsers, dataValues, jQuery ) );
