import connectSimpleMeta from '../../utils/connect-simple-meta';

const {__} = wp.i18n;
const {RadioControl} = wp.components;

export default connectSimpleMeta(({value, setValue}) => {
	const handleChange = (value) => {
		setValue(value);
	};

	return (
		<RadioControl
			label={__('Emphasize row', 'devlog')}
			selected={parseInt(value, 10)}
			options={[
				{label: __('Row 1', 'devlog'), value: 1},
				{label: __('Row 2', 'devlog'), value: 2},
			]}
			onChange={handleChange}
		/>
	)
}, 'emphasize_row');