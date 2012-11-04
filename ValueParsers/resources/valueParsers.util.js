/**
 * @file
 * @ingroup ValueParsers
 * @licence GNU GPL v2+
 * @author Daniel Werner
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
( function( vp, $, dv, undefined ) {
	'use strict';

	/**
	 * Module for utilities of the ValueParsers extension.
	 * @since 0.1
	 * @type Object
	 */
	vp.util = {};

	/**
	 * Helper for prototypical inheritance.
	 * @since 0.1
	 *
	 * @param {Function} base Constructor which will be used for the prototype chain.
	 * @param {Function} [constructor] for overwriting base constructor. Can be omitted.
	 * @param {Object} [members] properties overwriting or extending those of the base.
	 * @return Function Constructor of the new, extended type.
	 */
	vp.util.inherit = function( base, constructor, members ) {
		return dv.util.inherit.apply( this, arguments );
	};

	/**
	 * Throw a kind of meaningful error whenever the function should be overwritten when inherited.
	 * @throws Error
	 *
	 * @since 0.1
	 *
	 * @example:
	 * SomethingAbstract.prototype = {
	 *     someFunc: function( a, b ) { doSomething() },
	 *     someAbstractFunc: wb.utilities.abstractFunction
	 * };
	 */
	vp.util.abstractMember = function() {
		throw new Error( 'Call to undefined abstract function' );
	};

}( valueParsers, jQuery, dataValues ) );
