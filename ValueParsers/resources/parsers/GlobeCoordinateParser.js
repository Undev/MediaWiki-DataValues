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
	 * Constructor for globe coordinate parsers.
	 *
	 * @constructor
	 * @extends vp.ValueParser
	 * @since 0.1
	 */
	vp.GlobeCoordinateParser = dv.util.inherit( PARENT, {
		/**
		 * @see vp.ValueParser.parse
		 * @since 0.1
		 *
		 * @param {globeCoordinate.GlobeCoordinate} globeCoordinate
		 * @return $.Promise
		 */
		parse: function( globeCoordinate ) {
			var dataValue = new dv.GlobeCoordinateValue( globeCoordinate ),
				deferred = $.Deferred().resolve( dataValue );

			return deferred.promise();
		}
	} );

}( valueParsers, dataValues, jQuery ) );
