import Vue from 'vue';
import VueRouter from 'vue-router';
import { RouteService } from '@/src/Services';
import { UpdateTitle } from '@/src/Middleware';
import { Urls } from '@/src/Mixins';
import routes from '@/routes';

const mixin = {
	data() {
		return {
			routes: null
		};
	},
	methods: {
		fetchRoutes() {
			return axios.get('/api/routes')
				.then((response) => {
					this.routes = response.data;
				})
				.catch((error) => {
					console.log('failed to load routing table');
					console.log(error.response);
				});
		}
	}
};

export default {
	register() {
		Vue.use(VueRouter);
		Vue.mixin(Urls);
	},
	
	configure(vueConfig) {
		const router = new VueRouter({
			mode: 'history',
			routes
		});
		
		router.beforeEach(UpdateTitle);

		vueConfig.router = router;

		vueConfig.mixins.push(mixin);
	},

	boot(vue) {
		vue.$services.set(RouteService, RouteService(vue));
	},
}
