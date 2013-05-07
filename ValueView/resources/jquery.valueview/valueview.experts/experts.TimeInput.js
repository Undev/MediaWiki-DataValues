/**
 * @file
 * @ingroup ValueView
 * @licence GNU GPL v2+
 * @author Daniel Werner < daniel.werner@wikimedia.de >
 * @author H. Snater < mediawiki@snater.com >
 */
// TODO: Remove mediaWiki dependency
( function( dv, vp, $, vv, time, mw ) {
	'use strict';

	var Time = time.Time,
		timeSettings = time.settings;

	var PARENT = vv.Expert;

	/**
	 * Valueview expert handling input of time values.
	 *
	 * @since 0.1
	 *
	 * @constructor
	 * @extends jQuery.valueview.Expert
	 */
	vv.experts.TimeInput = vv.expert( 'timeinput', PARENT, {
		/**
		 * The the input element's node.
		 * @type {jQuery}
		 */
		$input: null,

		/**
		 * Caches a new value (or null for no value) set by _setRawValue() until draw() displaying
		 * the new value has been called. The use of this, basically, is a structural improvement
		 * which allows moving setting the displayed value to the draw() method which is supposed to
		 * handle all visual manners.
		 * @type {time.Time|null|false}
		 */
		_newValue: null,

		/**
		 * The preview section's node.
		 * @type {jQuery}
		 */
		$preview: null,

		/**
		 * The node of the previewed input value.
		 * @type {jQuery}
		 */
		$previewValue: null,

		/**
		 * @see jQuery.valueview.Expert._init
		 */
		_init: function() {
			var self = this;

			// TODO: Move preview out of the specific expert to a more generic place
			this.$preview = $( '<div/>' )
			.addClass( 'valueview-preview' )
			.append(
				$( '<div/>' )
				.addClass( 'valueview-preview-label' )
				.text( mw.msg( 'valueview-preview-label' ) )
			);

			this.$previewValue = $( '<div/>' )
			.addClass( 'valueview-preview-value' )
			.text( mw.msg( 'valueview-preview-novalue' ) )
			.appendTo( this.$preview );

			var precisionValues = [];
			$.each( timeSettings.precisiontexts, function( i, text ) {
				precisionValues.push( { value: i, label: text } );
			} );

			this.$precision = $( '<div/>' )
			.listrotator( { values: precisionValues.reverse(), deferInit: true } )
			.on( 'listrotatorauto', function( event ) {
				var value = new Time( self.$input.val() );
				$( this ).data( 'listrotator' ).rotate( value.precision() );
				self._setRawValue( value );
				self._updatePreview( value );
				self._viewNotifier.notify( 'change' );
			} )
			.on( 'listrotatorselected', function( event, precision ) {
				var value = new Time( self.$input.val(), precision );
				self._setRawValue( value );
				self._updatePreview( value );
				self._viewNotifier.notify( 'change' );
			} );

			this.$input = $( '<input/>', {
				type: 'text',
				'class': this.uiBaseClass + '-input valueview-input'
			} )
			.appendTo( this.$viewPort )
			.eachchange( function( event, oldValue ) {
				var value = self.$input.data( 'timeinput' ).value();
				if( oldValue === '' &&  value === null || self.$input.val() === '' ) {
					self._updatePreview( null );
				}
			} )
			.timeinput()
			// TODO: Move input extender out of here to a more generic place since it is not
			// TimeInput specific.
			.inputextender( {
				content: [ this.$preview ],
				extendedContent: [ this.$precision ],
				initCallback: function() {
					self.$precision.data( 'listrotator' ).initWidths();
				}
			} )
			.on( 'timeinputupdate', function( event, value ) {
				self._updatePreview( value );
				if( value && value.isValid() ) {
					self.$precision.data( 'listrotator' ).rotate( value.precision() );
				}
				self._viewNotifier.notify( 'change' );
			} );

		},

		/**
		 * Updates the input value's preview.
		 * @since 0.1
		 *
		 * @param {time.Time|null} value
		 */
		_updatePreview: function( value ) {
			// No need to update the preview when the input value is clear(ed) since the preview
			// will be hidden anyway.
			if( this.$input.val() === '' ) {
				return;
			}

			if( value === null ) {
				this.$previewValue
				.addClass( 'valueview-preview-novalue' )
				.text( mw.msg( 'valueview-preview-novalue' ) )
			} else {
				this.$previewValue
				.removeClass( 'valueview-preview-novalue' )
				.text( value.text() )
			}
		},

		/**
		 * @see Query.valueview.Expert.parser
		 */
		parser: function() {
			return new vp.TimeParser();
		},

		/**
		 * @see jQuery.valueview.Expert._getRawValue
		 *
		 * @return {time.Time|null}
		 */
		_getRawValue: function() {
			return ( this._newValue !== false )
				? this._newValue
				: this.$input.data( 'timeinput' ).value();
		},

		/**
		 * @see jQuery.valueview.Expert._setRawValue
		 *
		 * @param {time.Time|null} time
		 */
		_setRawValue: function( time ) {
			if( !( time instanceof Time ) || !time.isValid() ) {
				time = null;
			}
			this._newValue = time;
		},

		/**
		 * @see jQuery.valueview.Expert.rawValueCompare
		 */
		rawValueCompare: function( time1, time2 ) {
			if( time2 === undefined ) {
				time2 = this._getRawValue();
			}

			if( time1 === null && time2 === null ) {
				return true;
			}

			if( !( time1 instanceof Time ) || !( time2 instanceof Time ) ) {
				return false;
			}

			return time1.isValid() && time2.isValid()
				&& time1.precision() === time2.precision()
				&& time1.iso8601() === time2.iso8601();
		},

		/**
		 * @see jQuery.valueview.Expert.draw
		 */
		draw: function() {
			if( this._viewState.isDisabled() ) {
				this.$input.data( 'timeinput' ).disable();
			} else {
				this.$input.data( 'timeinput' ).enable();
			}

			if( this._newValue !== false ) {
				this.$input.data( 'timeinput' ).value( this._newValue );
				this._updatePreview( this._newValue );
				if( this._newValue !== null ) {
					this.$precision.data( 'listrotator' ).value( this._newValue.precision() );
				}
				this._newValue = false;
			}
		},

		/**
		 * @see jQuery.valueview.Expert.focus
		 */
		focus: function() {
			this.$input.focus();
		},

		/**
		 * @see jQuery.valueview.Expert.blur
		 */
		blur: function() {
			this.$input.blur();
		}

	} );

}( dataValues, valueParsers, jQuery, jQuery.valueview, time, mediaWiki ) );
