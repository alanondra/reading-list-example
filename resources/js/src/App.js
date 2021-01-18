import Vue from 'vue';

import {
	AppProvider,
	AuthProvider,
	ComponentProvider,
	LocaleProvider,
	RoutingProvider,
	StoreProvider
} from './Providers';

const providers = [
	AppProvider,
	AuthProvider,
	ComponentProvider,
	LocaleProvider,
	RoutingProvider,
	StoreProvider
];

export default {
	create() {
		_.forIn(providers, (provider, i) => {
			if (_.has(provider, 'register')) {
				provider.register();
			}
		});

		let vueConfig = {
			mixins: [],
			computed: {},
			methods: {}
		};

		_.forIn(providers, (provider, i) => {
			if (_.has(provider, 'configure')) {
				provider.configure(vueConfig);
			}
		});

		let app = new Vue(vueConfig);

		_.forIn(providers, (provider, i) => {
			if (_.has(provider, 'boot')) {
				provider.boot(app);
			}
		});

		return app;
	}
}
