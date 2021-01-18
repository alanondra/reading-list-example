<template>
	<div>
		<b-overlay :show="!loaded" spinner-variant="info" class="vh-100">
			<b-container v-if="loaded" fluid class="h-100 px-0">
				<b-navbar toggleable="lg" type="dark" variant="info">
					<b-navbar-brand :to="{ name: 'home'}">{{ trans('app.name') }}</b-navbar-brand>

					<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

					<b-collapse id="nav-collapse" is-nav>
						<b-navbar-nav>
							<b-nav-item :to="{ name: 'books.list'}"><i class="fas fa-book"></i> {{ trans('app.navbar.books') }}</b-nav-item>
							<b-nav-item :to="{ name: 'authors.list'}"><i class="fas fa-user-edit"></i> {{ trans('app.navbar.authors') }}</b-nav-item>
							<b-nav-item href="/lists"><i class="fas fa-clipboard-list"></i> {{ trans('app.navbar.reading_lists') }}</b-nav-item>
						</b-navbar-nav>

						<b-navbar-nav class="ml-auto" v-if="!user">
							<b-nav-item :to="{ name: 'login'}"><i class="fas fa-sign-in-alt"></i> {{ trans('app.navbar.login') }}</b-nav-item>
							<b-nav-item :to="{ name: 'register'}"><i class="fas fa-user-plus"></i> {{ trans('app.navbar.register') }}</b-nav-item>
						</b-navbar-nav>

						<b-navbar-nav class="ml-auto" v-if="user">
							<b-nav-item-dropdown right>
								<template #button-content>
									<i class="fas fa-user"></i>
									<span>{{ trans('app.navbar.greet', {user: user.name}) }}</span>
								</template>
								<b-dropdown-item :to="{ name: 'profile'}"><i class="far fa-address-card"></i> {{ trans('app.navbar.profile') }}</b-dropdown-item>
								<b-dropdown-item @click="logout"><i class="fas fa-sign-out-alt"></i> {{ trans('app.navbar.logout') }}</b-dropdown-item>
							</b-nav-item-dropdown>
						</b-navbar-nav>
					</b-collapse>
				</b-navbar>

				<main>
					<router-view></router-view>
				</main>
			</b-container>
		</b-overlay>
	</div>
</template>

<script>
	import { AuthService } from '@/src/Services';

	export default {
		computed: {
			user() {
				return this.$user;
			},
			loaded() {
				return (
					this.$root.i18n !== null &&
					this.$root.routes !== null
				);
			}
		},
		created() {
			this.$root.$on('loginSuccess', this.onLogin);
			this.$root.$on('loginFailure', this.onError);

			this.$root.$on('logoutSuccess', this.onLogout);
			this.$root.$on('logoutFailure', this.onError);

			this.$root.$on('session', this.onSession);
			this.$root.$on('profileUpdated', this.onUserUpdate);
		},
		methods: {
			logout() {
				this.$root.$service(AuthService).logout();
			},
			onLogin(e) {
				this.toast(_.get(e, 'success'), 'Logged In', 'success');
				this.redirect('home');
			},
			onLogout(e) {
				this.toast(_.get(e, 'success'), 'Logged Out', 'success');

				this.redirect('home');
			},
			onError(e) {
				let errors = _.get(error.response, 'data.errors') || [];

				errors.forEach((err) => {
					this.toast(err.message, 'Error', 'danger');
				});
			},
			onSession(e) {
				this.toast(_.get(e.data, 'message'), 'message', 'Info');
				this.toast(_.get(e.data, 'success'), 'Success', 'success');
				this.toast(_.get(e.data, 'warning'), 'Warning', 'warning');
				this.toast(_.get(e.data, 'error'), 'Error', 'danger');
			},
			toast(message, key, title, variant) {
				if (message) {
					this.$bvToast.toast(message, {
						title: title,
						variant: (variant || 'default')
					});
				}
			}
		}
	}
</script>
