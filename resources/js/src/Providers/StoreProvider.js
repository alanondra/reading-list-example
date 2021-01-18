import Vue from 'vue';
import Vuex from 'vuex';
import VueLocalStorage from 'vue-localstorage';

import Store from '@/src/Store';

export default {
	register() {
		Vue.use(Vuex);

		Vue.use(VueLocalStorage, {
			bind: true
		});
	},

	configure(vueConfig) {
//		vueConfig.store = Store.create();
	},

	boot(vue) {
		//
	}
}
