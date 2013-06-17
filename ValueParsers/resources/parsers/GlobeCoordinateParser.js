/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author H. Snater < mediawiki@snater.com >
 */
( function( vp, dv, $, GlobeCoordinate ) {
	'use strict';

	var PARENT = vp.ApiBasedValueParser;

	/**
	 * Constructor for globe coordinate parsers.
	 *
	 * @constructor
	 * @extends vp.ApiBasedValueParser
	 * @since 0.1
	 */
	vp.GlobeCoordinateParser = dv.util.inherit( PARENT, {
		/**
		 * @see vp.ApiBasedValueParser.API_VALUE_PARSER_ID
		 */
		API_VALUE_PARSER_ID: 'globecoordinate',

		/**
		 * @see vp.ValueParser.parse
		 * @since 0.1
		 *
		 * TODO: Make this accept strings only.
		 *
		 * @param {globeCoordinate.GlobeCoordinate|string} rawValue
		 * @return $.Promise
		 */
		parse: function( rawValue ) {
			if( rawValue instanceof GlobeCoordinate ) {
				var globeCoordinateValue = new dv.GlobeCoordinateValue( rawValue ),
					deferred = $.Deferred().resolve( globeCoordinateValue );
				return deferred.promise();
			} else {
				return PARENT.prototype.parse.call( this, rawValue );
			}
		}
	} );

}( valueParsers, dataValues, jQuery, globeCoordinate.GlobeCoordinate ) );
