import CatchPhraseRow from './catch-phrase-row';
import Emphasize from './emphasize';

const {__} = wp.i18n;
const {Fragment} = wp.element;

export default () => {
	return (
		<Fragment>
			<CatchPhraseRow number="1" />
			<CatchPhraseRow number="2" />
			<Emphasize />
			{__('If blank, the category icon is used.', 'devlog')}
		</Fragment>
	)
};