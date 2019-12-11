import $ from 'jquery';
import UserInterface from './lib/_user-interface';

window.context = {
	ui: null,
};

$(() => {
	window.context.ui = new UserInterface();
});
