import './style.scss';
import './editor.scss';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType( 'as-blocks/cat', {
	title: __( 'DEMO: Cat', 'lang' ),
	icon: 'heart',
	category: 'common',

	edit: function( props ) {
		return (
			<div className={ props.className }>
				<p>
					This is a cat
				</p>
			</div>
		);
	},

	save: function( props ) {
		return (
			<div>
				<img src="https://media.giphy.com/media/3oKIPnAiaMCws8nOsE/giphy.gif" alt="a coding cat" />
			</div>
		);
	},
} );
