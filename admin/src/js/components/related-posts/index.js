import RelatedPost from './related-post';
import {SortableContainer} from 'react-sortable-hoc';

const {__} = wp.i18n;
const {compose} = wp.compose;
const {withSelect, withDispatch} = wp.data;
const {Button} = wp.components;

const generateUniqueId = () => {
	const strong = 1000;

	return new Date().getTime().toString(16) + Math.floor(strong * Math.random()).toString(16)
};

const sortPosts = posts => {
	posts.sort((a, b) => {
		if (a.order < b.order) {
			return -1;
		}

		if (a.order > b.order) {
			return 1;
		}

		return 0;
	});

	let order = 0;

	return posts.map((post) => {
		post.order = order++;
		return post;
	});
};

const SortableRelatedPosts = SortableContainer(({relatedPosts, handleAdd, handleUpdate, handleRemove}) => {
	try {
		relatedPosts = JSON.parse(relatedPosts);
	} catch {
		relatedPosts = [];
	}

	return (
		<div className="dl-related-posts" id="dl-related-posts">
			<div className="dl-related-posts__list">
				{relatedPosts.map((post) => {
					return (
						<RelatedPost
							key={post.index}
							editIndex={post.index}
							index={post.order}
							selectedPostId={post.id}
							removeHandler={handleRemove}
							updateHandler={handleUpdate}
						/>
					);
				})}
			</div>
			<Button isPrimary onClick={handleAdd.bind(this, 0)} className="dl-related-posts__add-btn">{__('Add related post', 'devlog')}</Button>
		</div>
	)
});

export default compose(
	withSelect((select) => {
		return {
			relatedPosts: select('core/editor').getEditedPostAttribute('meta')['related_posts']
		}
	}),
	withDispatch((dispatch, {relatedPosts}) => {
		try {
			relatedPosts = JSON.parse(relatedPosts);
		} catch {
			relatedPosts = [];
		}

		return {
			handleAdd: (id) => {
				relatedPosts.push({
					index: generateUniqueId(),
					id: id,
					order: relatedPosts.length
				});

				dispatch('core/editor').editPost({meta: {related_posts: JSON.stringify(relatedPosts)}});
			},
			handleUpdate: (index, id) => {
				relatedPosts = relatedPosts.map((post) => {
					if (post.index === index) {
						post.id = id;
					}

					return post;
				});

				dispatch('core/editor').editPost({meta: {related_posts: JSON.stringify(sortPosts(relatedPosts))}});
			},
			handleRemove: (index) => {
				relatedPosts = relatedPosts.filter((post) => {
					return post.index !== index;
				});

				dispatch('core/editor').editPost({meta: {related_posts: JSON.stringify(sortPosts(relatedPosts))}});
			},
			handleSort: ({newIndex, oldIndex}) => {
				if (newIndex > oldIndex) {
					relatedPosts[oldIndex].order = relatedPosts[newIndex].order + 1;
				} else {
					relatedPosts[oldIndex].order = relatedPosts[newIndex].order - 1;
				}

				dispatch('core/editor').editPost({meta: {related_posts: JSON.stringify(sortPosts(relatedPosts))}});
			}
		}
	}),
)(({handleSort, ...props}) => {
	return <SortableRelatedPosts
		onSortEnd={handleSort}
		lockAxis="y"
		lockToContainerEdges
		useDragHandle
		helperContainer={() => {
			return document.getElementById('dl-related-posts');
		}}
		{...props}
	/>
})