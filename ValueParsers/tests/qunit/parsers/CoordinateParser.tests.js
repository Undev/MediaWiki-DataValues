/**
 * @since 0.1
 * @file
 * @ingroup ValueParsers
 *
 * @licence GNU GPL v2+
 *
 * @author H. Snater < mediawiki@snater.com >
 */
( function( vp, dv, $, QUnit, Coordinate ) {
	'use strict';

	var PARENT = vp.tests.ValueParserTest;

	/**
	 * Constructor for creating a test object holding tests for the CoordinateParser.
	 *
	 * @constructor
	 * @extends dv.tests.ValueParserTest
	 * @since 0.1
	 */
	vp.tests.CoordinateParserTest = vp.util.inherit( PARENT, {

		/**
		 * @see vp.tests.ValueParserTest.getObject
		 */
		getObject: function() {
			return vp.CoordinateParser;
		},

		/**
		 * @see vp.tests.ValueParserTest.getParseArguments
		 */
		getParseArguments: function() {
			return [
				[
					new Coordinate( '1.5 1.25' ),
					new dv.CoordinateValue( new Coordinate( '1.5 1.25' ) )
				],
				[
					new Coordinate( '-50 -20' ),
					new dv.CoordinateValue( new Coordinate( '-50 -20' ) )
				]
			];
		}

	} );

	var test = new vp.tests.CoordinateParserTest();

	test.runTests( 'valueParsers.CoordinateParser' );

}( valueParsers, dataValues, jQuery, QUnit, coordinate.Coordinate ) );
