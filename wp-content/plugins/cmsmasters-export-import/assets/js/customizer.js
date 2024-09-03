/* Export/Import */
const cmsmastersExportImport = function() {
	const obj = {
		init: function() {
			obj.bindEvents();
		},
		bindEvents: function() {
			jQuery( 'body' ).on( 'click', '.cmsmasters-ei-button', function() {
				obj.$button = jQuery( this );
				obj.templateName = obj.$button.closest( '#customize-control-cmsmasters-ei-setting' ).find( '#cmsmasters-ei-export-template-name' ).val();

				if ( 'export-options' === obj.$button.data( 'eiType' ) ) {
					obj.exportOptions();
				} else if ( 'export-kits' === obj.$button.data( 'eiType' ) ) {
					obj.exportKits();
				} else if ( 'export-theme-options' === obj.$button.data( 'eiType' ) ) {
					obj.exportThemeOptions();
				} else if ( 'export-givewp-form-meta' === obj.$button.data( 'eiType' ) ) {
					obj.exportGiveWPFormMeta();
				} else if ( 'import' === obj.$button.data( 'eiType' ) ) {
					obj.import();
				}
			} );
		},
		exportOptions: function() {
			const hrefStr = ei_params.customizer_url + '?cmsmasters-ei-export-options=' + ei_params.export_nonce + '&template=' + obj.templateName;

			window.location.href = hrefStr;
		},
		exportKits: function() {
			window.location.href = ei_params.customizer_url + '?cmsmasters-ei-export-kits=' + ei_params.export_nonce;
		},
		exportThemeOptions: function() {
			window.location.href = ei_params.customizer_url + '?cmsmasters-ei-export-theme-options=' + ei_params.export_nonce;
		},
		exportGiveWPFormMeta: function() {
			window.location.href = ei_params.customizer_url + '?cmsmasters-ei-export-givewp-form-meta=' + ei_params.export_nonce;
		},
		import: function() {
			const file = jQuery( 'input[name=cmsmasters-ei-import-file]' );

			if ( '' === file.val() ) {
				alert( ei_params.empty_import );

				return;
			}

			const form = jQuery( '<form class="cmsmasters-ei-form" method="POST" enctype="multipart/form-data"></form>' ),
				controls = jQuery( '.cmsmasters-ei-import-controls' ),
				message = jQuery( '.cmsmasters-ei-uploading' );

			jQuery( window ).off( 'beforeunload' );
			jQuery( 'body' ).append( form );
			form.append( controls );
			message.show();
			form.submit();
		}
	};

	obj.init();
};

cmsmastersExportImport();
