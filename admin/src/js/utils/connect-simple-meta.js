const {compose} = wp.compose;
const {withSelect, withDispatch} = wp.data;

export default (component, key) => {
	return compose(
		withSelect((select) => {
			return {
				value: select('core/editor').getEditedPostAttribute('meta')[key]
			}
		}),
		withDispatch((dispatch, {value}) => {
			return {
				setValue: (newValue) => {
					dispatch('core/editor').editPost({meta: {[key]: newValue}})
				}
			}
		}),
	)(component);
}