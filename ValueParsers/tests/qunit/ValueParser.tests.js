/**
 * @file
 * @ingroup DataValues
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( vp, dv, $, QUnit, undefined ) {
	'use strict';

	vp.tests = {};

	/**
	 * Base constructor for ValueParser object tests.
	 *
	 * @constructor
	 * @abstract
	 * @since 0.1
	 */
	vp.tests.ValueParserTest = function() {};
	vp.tests.ValueParserTest.prototype = {

		/**
		 * Data provider that provides valid parse arguments.
		 *
		 * @since 0.1
		 *
		 * @return array
		 */
		getParseArguments: dv.util.abstractMember,

		/**
		 * Returns the ValueParser object to be tested (ie vp.IntParser).
		 *
		 * @since 0.1
		 *
		 * @return vp.ValueParser
		 */
		getObject: dv.util.abstractMember,

		/**
		 * Returns the dataValue object to be tested (ie dv.StringValue).
		 *
		 * @since 0.1
		 *
		 * @param {Array} constructorArguments
		 *
		 * @return vp.ValueParser
		 */
		getInstance: function( constructorArguments ) {
			constructorArguments = constructorArguments || [];

			var
				self = this,
				ValueParserInstance = function( constructorArguments ) {
					self.getObject().apply( this, constructorArguments );
				};

			ValueParserInstance.prototype = this.getObject().prototype;

			return new ValueParserInstance( constructorArguments );
		},

		/**
		 * Runs the tests.
		 *
		 * @since 0.1
		 *
		 * @param {String} moduleName
		 */
		runTests: function( moduleName ) {
			QUnit.module( moduleName, QUnit.newMwEnvironment() );

			var self = this;

			$.each( this, function( property, value ) {
				if ( property.substring( 0, 4 ) === 'test' && $.isFunction( self[property] ) ) {
					QUnit.asyncTest(
						property,
						function( assert ) {
							self[property].call( self, assert );
						}
					);
				}
			} );
		},

		/**
		 * Tests the parse method.
		 *
		 * @since 0.1
		 *
		 * @param {QUnit} assert
		 */
		testParse: function( assert ) {
			var parseArguments = this.getParseArguments(),
				parser = this.getInstance();

			for ( var i in parseArguments ) {

				( function( assert, parseArguments ) {
					parser.parse( parseArguments[0] )
						.done( function( dataValues ) {
							assert.ok( true, 'parsing succeeded' );

							for ( var j in dataValues ) {
								assert.ok( dataValues[j] instanceof dv.DataValue, 'result is instanceof DataValue' );

								if ( parseArguments.length > 1 ) {
									assert.ok( dataValues[j].equals( parseArguments[1] ), 'result is equal to the expected DataValue' );
								}
							}

							QUnit.start();
						} )
						.fail( function( errorMessage ) {
							assert.ok( false, 'parsing failed' );
							QUnit.start();
						} );
				} ( assert, parseArguments[i] ) );

			}

			assert.ok( true );
		}

	};

}( valueParsers, dataValues, jQuery, QUnit ) );
