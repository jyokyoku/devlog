import OverlayColor from './overlay-color';
import OverlayOpacity from './overlay-opacity';

const {Fragment} = wp.element;

export default () => {
	return (
		<Fragment>
			<OverlayColor />
			<OverlayOpacity />
		</Fragment>
	)
}