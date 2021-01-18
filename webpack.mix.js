const path = require('path');
const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		alias: {
			'@': path.resolve(__dirname, 'resources/js'),
			'vue$': path.resolve(__dirname, 'node_modules/vue/dist/vue.runtime.esm.js'),
			'ziggy': path.resolve(__dirname, 'vendor/tightenco/ziggy/dist')
		}
	}
});

let assetDir = 'assets/build';

mix.options({
	fileLoaderDirs: {
		images: `${assetDir}/img`,
		fonts: `${assetDir}/fonts`
	}
});

mix.js('resources/js/app.js', `public/${assetDir}/js`)
	.autoload({
		axios: ['axios', 'window.axios'],
		lodash: ['_', 'window._'],
		vue: ['Vue', 'window.vue']
	})
	.extract([
		'axios',
		'lodash',
		'vue'
	])
	.vue({
		extractStyles: true
	});

mix.sass('resources/sass/app.scss', `public/${assetDir}/css`);
