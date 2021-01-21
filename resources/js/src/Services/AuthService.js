import axios from "axios";

const vue = window.application;

export default function(vue) {
	return {
		update(user) {
			vue.$root.user = user;

			if (user) {
				vue.$localStorage.set('user', JSON.stringify(user));
			} else {
				vue.$localStorage.remove('user');
			}
		},
		login(input) {
			return axios.post(vue.apiRoute('api.session.store'), input)
				.then((response) => {
					let data = response.data;
					let user = _.get(data, 'user') || null;

					if (!user) {
						console.log(data);
						throw new Error('Missing user data.');
					}

					this.update(user);

					vue.$emit('loginSuccess', data);
				})
				.catch((error) => {
					this.update(null);
					vue.$emit('loginFailure', error);
				});
		},
		logout() {
			return axios.delete(vue.apiRoute('api.session.destroy'))
				.then((response) => {
					vue.$emit('logoutSuccess', response.data);
				})
				.catch((error) => {
					vue.$emit('logoutFailure', error);
				})
				.finally(() => {
					this.update(null);
				});
		}
	};
}
