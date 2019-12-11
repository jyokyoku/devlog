import PostSelect from './post-select'
import {SortableElement} from "react-sortable-hoc";

const {apiFetch} = wp;
const {Component} = wp.element;

class RelatedPost extends Component {
	constructor(props) {
		super(props);

		this.state = {
			selectedPost: {},
			selectedLoaded: false,
		};

		this.handleUpdate = this.handleUpdate.bind(this);
		this.handleRemove = this.handleRemove.bind(this);
	}

	componentWillMount() {
		if (this.props.selectedPostId) {
			apiFetch({
				path: '/wp/v2/posts/' + this.props.selectedPostId,
				method: 'get',
			}).then(post => {
				if (post.status === 'publish') {
					this.setState({
						selectedPost: post,
						selectedLoaded: true,
					});
				} else {
					this.setState({
						selectedLoaded: true,
					});
				}
			}).catch(error => {
				this.setState({
					selectedLoaded: true,
				});
			});
		} else {
			this.setState({
				selectedLoaded: true,
			});
		}
	}

	handleUpdate(id) {
		this.props.updateHandler(this.props.editIndex, id);
	};

	handleRemove() {
		this.props.removeHandler(this.props.editIndex);
	};

	render() {
		let component;

		if (this.state.selectedLoaded) {
			if ('id' in this.state.selectedPost) {
				component = <PostSelect
					key="loaded"
					updateHandler={this.handleUpdate}
					removeHandler={this.handleRemove}
					defaultOptions={true}
					isLoading={false}
					defaultValue={{value: this.state.selectedPost.id, label: this.state.selectedPost.title.rendered}}
				/>;
			} else {
				component = <PostSelect
					key="loaded"
					updateHandler={this.handleUpdate}
					removeHandler={this.handleRemove}
					defaultOptions={true}
					isLoading={false}
				/>;
			}
		} else {
			component = <PostSelect
				key="loading"
				removeHandler={this.handleRemove}
				isLoading={true}
			/>;
		}

		return (
			<div className="dl-related-posts__item">
				{component}
			</div>
		);
	}
}

export default SortableElement(RelatedPost);