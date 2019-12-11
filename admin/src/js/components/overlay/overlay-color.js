import connectSimpleMeta from '../../utils/connect-simple-meta';

const {__} = wp.i18n;
const {ColorPalette} = wp.components;
const {Fragment} = wp.element;

export default connectSimpleMeta(({value, setValue}) => {
	const colors = typeof window.post_theme_colors === 'object' ? window.post_theme_colors : {};

	const handleChange = (value) => {
		setValue(value);
	};

	return (
		<Fragment>
			<ColorPalette
				colors={colors}
				value={value}
				onChange={handleChange}
			/>
		</Fragment>
	)
}, 'overlay_color');