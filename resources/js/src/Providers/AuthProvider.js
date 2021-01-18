import Vue from 'vue';
import { AuthService } from '@/src/Services';
import { Auth } from '@/src/Mixins';

const mixin = {
	data() {
		return {
			user: JSON.parse(this.$localStorage.get('user'))
		};
	},
	methods: {
		fetchSession() {
			return axios.get(this.apiRoute('api.session.index'))
				.then((response) => {
					let user = _.get(response, 'data.user') || null;

					if (user !== null) {
						this.$localStorage.set('user', JSON.stringify(user));
					} else {
						this.$localStorage.remove('user');
					}

					this.$root.$emit('session', response);
				});
		}
	}
};

export default {
	register() {
		Vue.mixin(Auth);
	},

	configure(vueConfig) {
		vueConfig.mixins.push(mixin);
	},

	boot(vue) {
		vue.$services.set(AuthService, AuthService(vue));
	}
}
