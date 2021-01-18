require('./bootstrap');

import App from './src/app';

window.addEventListener('DOMContentLoaded', () => {
	window.application = App.create();
	window.application.$mount(`#app`);
});
