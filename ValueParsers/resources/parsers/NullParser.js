/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( mw, vp, dv, $, undefined ) {
	'use strict';

	var PARENT = vp.ValueParser,
		constructor = function() {};

	/**
	 * Constructor for null parsers.
	 *
	 * @constructor
	 * @extends vp.ValueParser
	 * @since 0.1
	 */
	vp.NullParser = dv.util.inherit( PARENT, constructor, {

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
			var deferred = $.Deferred();

			deferred.resolve( new dv.UnknownValue( rawValue ) );

			return deferred.promise();
		}

	} );

}( mediaWiki, valueParsers, dataValues, jQuery ) );
