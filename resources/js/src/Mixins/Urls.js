import { RouteService } from '@/src/Services';

export default {
	methods: {
		apiRoute(name, params, absolute) {
			return this.$service(RouteService).apiRoute(name, params, absolute);
		},
		route(name, params) {
			return this.$service(RouteService).route(name, params);
		},
		redirect(name, params) {
			return this.$service(RouteService).redirect(name, params);
		},
		back() {
			return this.$router.go(-1);
		}
	}
}
