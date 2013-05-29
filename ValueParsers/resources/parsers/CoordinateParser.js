/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author H. Snater < mediawiki@snater.com >
 */
( function( vp, dv, $ ) {
	'use strict';

	var PARENT = vp.ValueParser;

	/**
	 * Constructor for coordinate parsers.
	 *
	 * @constructor
	 * @extends vp.ValueParser
	 * @since 0.1
	 */
	vp.CoordinateParser = dv.util.inherit( PARENT, {
		/**
		 * @see vp.ValueParser.parse
		 * @since 0.1
		 *
		 * @param {coordinate.Coordinate} coordinate
		 * @return $.Promise
		 */
		parse: function( coordinate ) {
			var deferred = $.Deferred();

			if( coordinate.isValid() ) {
				var dataValue = new dv.CoordinateValue( coordinate );
				deferred.resolve( dataValue );
			} else {
				deferred.reject();
			}

			return deferred.promise();
		}
	} );

}( valueParsers, dataValues, jQuery ) );
