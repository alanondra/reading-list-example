import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import App from '@/src/Views/App';
import { Strings } from '@/src/Mixins';

const mixin = {
	computed: {
		loaded() {
			return (this.localesLoaded && this.routesLoaded && this.sessionLoaded);
		}
	}
};

export default {
	register: function () {
		Vue.use(BootstrapVue);

		Vue.prototype.$services = new WeakMap();

		Vue.mixin({
			methods: {
				$service(name) {
					if (this.$root.$services.has(name)) {
						return this.$root.$services.get(name);
					}
					return null;
				}
			}
		});

		Vue.mixin(Strings);
	},
	
	configure: function (vueConfig) {
		vueConfig.mixins.push(mixin);

		vueConfig.render = function(h) {
			return h(App);
		}

		vueConfig.beforeMount = function() {
			this.fetchRoutes()
				.then(() => {
					this.fetchLocales()
						.then(() => {
							this.fetchSession()
								.finally(() => {
									this.user = JSON.parse(this.$localStorage.get('user'));
									this.sessionLoaded = true;
									this.$forceUpdate();
								});
						});
				});
		};
	},
	
	boot: function (vue) {
		//
	},
}
