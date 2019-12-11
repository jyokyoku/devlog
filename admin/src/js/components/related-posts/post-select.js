import AsyncSelect from 'react-select/async';
import {SortableHandle} from "react-sortable-hoc";

const {Fragment} = wp.element;
const {apiFetch} = wp;

const handleLoadOptions = (value, callback) => {
	apiFetch({
		path: '/wp/v2/posts/?search=' + value,
		method: 'get',
	}).then(posts => {
		let selections = [];

		posts.forEach((post) => {
			selections.push({label: post.title.rendered, value: post.id});
		});

		callback(selections);
	}).catch(error => {
		callback([]);
	})
};

const DragHandle = SortableHandle(() => {
	return (
		<div className="dl-related-posts__item-drag-handle">
			<span />
			<span />
			<span />
		</div>
	)
});

export default ({isLoading, defaultValue, updateHandler, removeHandler, ...props}) => {
	const handleUpdate = (selected) => {
		updateHandler(selected ? selected.value : null);
	};

	const handleRemove = () => {
		removeHandler();
	};

	return (
		<Fragment>
			<AsyncSelect
				className="dl-related-posts__item-selector"
				loadOptions={handleLoadOptions}
				cacheOptions
				isClearable
				isLoading={isLoading}
				defaultValue={defaultValue}
				onChange={handleUpdate}
				{...props}
			/>
			<div className="dl-related-posts__item-action">
				<DragHandle />
				<a onClick={handleRemove} className="dl-related-posts__item-remove" />
			</div>
		</Fragment>
	)
};