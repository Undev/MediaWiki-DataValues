<?php

/**
 * Entry point for the ValueValidator extension.
 * For usage as MediaWiki extension, use the ValueValidator.mw.php entry point.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueValidator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * Files belonging to the ValueValidator extension.
 *
 * @defgroup ValueValidator ValueValidator
 */

/**
 * Tests part of the ValueValidator extension.
 *
 * @defgroup ValueValidatorTests ValueValidatorTests
 * @ingroup ValueValidator
 */

if ( !defined( 'DATAVALUES' ) ) {
	die( 'Not an entry point.' );
}

define( 'ValueValidator_VERSION', '0.1' );
