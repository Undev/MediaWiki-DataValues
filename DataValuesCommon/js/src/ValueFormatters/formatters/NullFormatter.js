/**
 * @licence GNU GPL v2+
 * @author H. Snater < mediawiki@snater.com >
 */
( function( $, vf, dv ) {
	'use strict';

	var PARENT = vf.ValueFormatter;

	/**
	 * Null Formatter
	 * The Null Formatter formats any DataValue instance and may be used as a fallback for DataValue
	 * instances that cannot be identified (e.g. due to missing implementation). The formatted value
	 * will be the string casted result of the data value's toJSON() function.
	 * If the data value could not be identified, the data value passed on to the $.Promise returned
	 * by the format function will be an UnknownValue DataValue instance.
	 *
	 * @constructor
	 * @extends valueFormatters.ValueFormatter
	 * @since 0.1
	 */
	vf.NullFormatter = vf.util.inherit( PARENT, function() {}, {
		/**
		 * @see valueFormatters.ValueFormatter.format
		 */
		format: function( dataValue ) {
			var deferred = $.Deferred();

			if( dataValue === null ) {
				return deferred.resolve( null, null ).promise();
			}

			if( !( dataValue instanceof dv.DataValue ) ) {
				dataValue = new dv.UnknownValue( dataValue );
			}

			var formatted = dataValue.toJSON();

			if( formatted !== null ) {
				formatted = String( formatted );
			}

			return deferred.resolve( formatted, dataValue ).promise();
		}

	} );

}( jQuery, valueFormatters, dataValues ) );
