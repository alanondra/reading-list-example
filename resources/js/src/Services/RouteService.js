import VueRouter from 'vue-router';
import { default as ziggyRoute } from 'ziggy';

export default function(vue) {
	return {
		apiRoute(name, params, absolute) {
			return ziggyRoute(name, params, absolute, window.application.routes);
		},
		route(name, params) {
			try {
				return vue.$router.resolve({name, params}).route;
			} catch (e) {
				return null;
			}
		},
		redirect(name, params) {
			let route = this.route(name, params);

			if (route !== vue.$route) {
				vue.$router
					.push(this.route(name, params))
					.catch((e) => {
						console.log(e.message);
					});
			} else {
				vue.$root.$forceUpdate();
			}
		}
	}
}
