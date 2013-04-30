/**
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author Daniel Werner < danweetz@web.de >
 */
( function( vp, dv, $, Time ) {
	'use strict';

	var PARENT = vp.ValueParser;

	/**
	 * Constructor for time parsers.
	 *
	 * @constructor
	 * @extends vp.ValueParser
	 * @since 0.1
	 */
	vp.TimeParser = dv.util.inherit( PARENT, {
		/**
		 * @see vp.ValueParser.parse
		 * @since 0.1
		 *
		 * @param {time.Time} time
		 * @return $.Promise
		 */
		parse: function( time ) {
			var deferred = $.Deferred();

			if( time.isValid() ) {
				// Valid time:
				var dataValue = new dv.TimeValue( time );
				deferred.resolve( dataValue );
			} else {
				// Invalid time:
				deferred.reject();
			}

			return deferred.promise();
		}
	} );

}( valueParsers, dataValues, jQuery, time.Time ) );
