import Vue from 'vue';
import LoadSpinner from '@/src/Views/Components/LoadSpinner';

export default {
	register: function () {
		Vue.component('load-spinner', LoadSpinner);
	},
	
	configure: function (vueConfig) {
		//
	},
	
	boot: function (vue) {
		//
	},
}
