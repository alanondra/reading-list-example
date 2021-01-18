import Vue from 'vue';
import { Locale } from '@/src/Mixins';

const mixin = {
	data() {
		return {
			locale: document.getElementsByTagName('html')[0].getAttribute('lang'),
			i18n: null
		};
	},
	methods: {
		fetchLocales() {
			return axios.get(this.apiRoute('api.i18n'))
				.then((response) => {
					this.i18n = response.data;
				})
				.catch((error) => {
					console.log('failed to load translations:');
					console.log(error.response);

					this.i18n = [];
				});
		}
	}
};

export default {
	register: function() {
		Vue.mixin(Locale);
	},
	
	configure: function (vueConfig) {
		vueConfig.mixins.push(mixin);
	},
	
	boot: function (vue) {
		//
	}
}
