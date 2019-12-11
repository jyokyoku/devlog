import connectSimpleMeta from '../../utils/connect-simple-meta';

const {__} = wp.i18n;
const {RangeControl} = wp.components;

export default connectSimpleMeta(({value, setValue}) => {
	value = value || 80;

	const handleChange = (value) => {
		setValue(value);
	};

	return (
		<RangeControl
			label={__('Overlay opacity', 'devlog')}
			value={value}
			onChange={handleChange}
			min={0}
			max={100}
		/>
	)
}, 'overlay_opacity');