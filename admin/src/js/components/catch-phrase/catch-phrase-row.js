import connectSimpleMeta from '../../utils/connect-simple-meta';

const {__} = wp.i18n;
const {TextControl} = wp.components;

export default ({number}) => {
	const Row = connectSimpleMeta(({value, setValue}) => {
		const handleChange = (value) => {
			setValue(value);
		};

		return (
			<TextControl
				label={__('Row ' + number, 'devlog')}
				value={value}
				onChange={handleChange}
			/>
		)
	}, 'catch_phrase_row_' + number);

	return <Row />
}