/**
 * Editor side of southtabor/next-meeting. Plain script with no build step:
 * it shows the server-rendered date so editors see what visitors see.
 */
( function ( wp ) {
	var el = wp.element.createElement;

	wp.blocks.registerBlockType( 'southtabor/next-meeting', {
		edit: function () {
			return el(
				'div',
				wp.blockEditor.useBlockProps(),
				el( wp.serverSideRender, { block: 'southtabor/next-meeting' } )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
