/**
 * @file
 * @ingroup ValueParsers
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( vp, $, dv, undefined ) {
	'use strict';

	/**
	 * ValueParsers API.
	 * @since 0.1
	 * @type Object
	 */
	vp.api = {};

	/**
	 * Helper for prototypical inheritance.
	 * @since 0.1
	 *
	 * @param {String} parser
	 * @param {Array} values
	 *
	 * @return $.Promise
	 */
	vp.api.parseValues = function( parser, values ) {
		var api = new mw.Api(),
			deferred = $.Deferred();

		api.get( {
			action: 'parsevalue',
			parser: parser,
			values: values.join( '|' )
		} ).done( function( apiResult ) {
			if ( apiResult.hasOwnProperty( 'results' ) ) {
				var dataValues = [];

				for ( var i in apiResult.results ) {
					var result = apiResult.results[i];

					if ( result.hasOwnProperty( 'value' ) && result.hasOwnProperty( 'type' ) ) {
						// TODO: get dv from factory using factory.getDv( result['type'], result['value'] )
						var dataValue;

						switch ( result['type'] ) {
							case 'boolean':
								dataValue = new dv.BoolValue( result['value'] );
								break;
							case 'number':
								dataValue = new dv.StringValue( 'foobar' );
								break;
							case 'unknown':
								dataValue = new dv.UnknownValue( result['value'] );
								break;
						}

						dataValues.push( dataValue );
					}
					else {
						deferred.reject( result.hasOwnProperty( 'error' ) ? result.error : 'Unknown error' );
					}
				}

				deferred.resolve( dataValues );
			}
			else {
				deferred.reject( 'The parse API returned an unexpected result' );
			}
		} ).fail( function( apiResult ) {
			deferred.reject( 'Communication with the parsing API failed' );
		} );

		return deferred.promise();
	};

}( valueParsers, jQuery, dataValues ) );
