/**
 * BLOCK: my-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { RichText } = wp.editor;

registerBlockType( 'as-blocks/dynamic', {
	title: __( 'DEMO: dynamic' ),
	icon: 'screenoptions',
	category: 'common',
	attributes: {
		content: {
			type: 'string',
		},
	},
	edit: ( props ) => {
		const {
			className,
			setAttributes,
			attributes,
		} = props;

		return (
			<div>
				<RichText
					tagName="h2"
					className={ className }
					onChange={ content => setAttributes( { content } ) }
					placeholder={ __( 'Your dynamic title', 'lang' ) }
					value={ attributes.content } />
			</div>
		);
	},
	save( props ) {
		const { attributes, className } = props;
		return (
			<div>
				<h2 className={ className }>{ attributes.content }</h2>
			</div>
		);
	},
} );
