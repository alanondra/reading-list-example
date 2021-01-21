require('./bootstrap');

import App from './src/App';

window.addEventListener('DOMContentLoaded', () => {
	window.application = App.create();
	window.application.$mount(`#app`);
});
