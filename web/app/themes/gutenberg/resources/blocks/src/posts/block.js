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
const { withSelect } = wp.data;
const { RichText } = wp.editor;
const { Spinner } = wp.components;

registerBlockType( 'as-blocks/posts', {
	title: __( 'DEMO: Latest posts' ), // Block title.
	icon: 'screenoptions', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

	edit: withSelect( select => {
		return {
			posts: select( 'core' ).getEntityRecords( 'postType', 'post' ),
		};
	} )( ( props ) => {
		const {
			posts,
			className,
			setAttributes,
			attributes,
		} = props;

		if ( ! posts ) {
			return <p className={ className } >
				<Spinner />
				{ __( 'Loading Posts', 'lang' ) }
			</p>;
		}

		if ( posts.length === 0 ) {
			return <p className={ className }>No posts</p>;
		}

		return (
			<div>
				<RichText tagName="h2" className={ className } onChange={ content => setAttributes( { content } ) } placeholder={ __( 'Your teaser title', 'lang' ) } value={ attributes.content } />

				{ posts.map( post =>
					<div key={ `latest-post-${ post.id }` }>
						<a href={ post.link } className={ className } >{ post.title.rendered }</a>
					</div>
				) }
			</div>
		);
	} ),
	save() {
		// rendered in PHP
		return null;
	},
} );
