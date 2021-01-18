<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('app.navbar.profile') }}</h2>
						</b-col>
					</b-row>
					<b-row>
						<b-col md="6" sm="12">
							<b-form-group
								id="login-group"
								:label="trans('auth.attributes.login')"
								label-for="login"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="far fa-id-card"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										disabled
										id="login"
										v-model="input.login"
										type="text"
										maxlength="256"
										:state="state('login')"
										@change="resetState('login')"
										@input="resetState('login')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('login')">
										<p v-for="(error, i) in errors.login">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="email-group"
								:label="trans('auth.attributes.email')"
								label-for="email"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-at"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										disabled
										id="email"
										v-model="input.email"
										type="email"
										maxlength="256"
										:state="state('email')"
										@change="resetState('email')"
										@input="resetState('email')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('email')">
										<p v-for="(error, i) in errors.email">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="name-group"
								:label="trans('auth.attributes.name')"
								label-for="name"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-user"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										id="name"
										v-model="input.name"
										type="text"
										maxlength="256"
										:state="state('name')"
										@change="resetState('name')"
										@input="resetState('name')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('name')">
										<p v-for="(error, i) in errors.name">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="password-group"
								:label="trans('auth.attributes.password')"
								label-for="password"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-key"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										id="password"
										v-model="input.password"
										type="password"
										maxlength="256"
										:state="state('password')"
										@change="resetState('password')"
										@input="resetState('password')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('password')">
										<p v-for="(error, i) in errors.password">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="password_new-group"
								:label="trans('auth.attributes.password_new')"
								label-for="password_new"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-key"></i></b-input-group-text>
									</template>
									<b-form-input
										id="password_new"
										v-model="input.password_new"
										type="password"
										maxlength="256"
										:state="state('password_new')"
										@change="resetState('password_new')"
										@input="resetState('password_new')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('password_new')">
										<p v-for="(error, i) in errors.password_new">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="password_new_confirmation-group"
								:label="trans('auth.attributes.password_new_confirmation')"
								label-for="password"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-redo"></i></b-input-group-text>
									</template>
									<b-form-input
										id="password_new_confirmation"
										v-model="input.password_new_confirmation"
										type="password"
										maxlength="256"
										:state="state('password_new_confirmation')"
										@change="resetState('password_new_confirmation')"
										@input="resetState('password_new_confirmation')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('password_new_confirmation')">
										<p v-for="(error, i) in errors.password_new_confirmation">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>
						</b-col>
					</b-row>

					<b-row class="mt-4">
						<b-col cols="12">
							<b-button-toolbar>
								<b-button-group>
									<b-button type="submit" variant="primary">{{ trans('forms.update') }}</b-button>
									<b-button type="reset" variant="outline-secondary">{{ trans('forms.reset') }}</b-button>
								</b-button-group>
							</b-button-toolbar>
						</b-col>
					</b-row>
				</b-form>
			</b-container>
		</load-spinner>
	</div>
</template>

<script>
	import { AuthService } from '@/src/Services';

	export default {
		data() {
			return {
				loaded: true,
				input: {
					email: null,
					login: null,
					name: null,
					password: null,
					password_new: null,
					password_new_confirmation: null
				},
				errors: {
					email: [],
					login: [],
					name: [],
					password: [],
					password_new: [],
					password_new_confirmation: []
				}
			};
		},
		beforeMount() {
			this.input.email = this.$user.email;
			this.input.login = this.$user.login;
			this.input.name = this.$user.name;
		},
		methods: {
			state(field) {
				if (!_.has(this.errors, field)) {
					return false;
				}
				return ((this.errors[field].length === 0) ? null : false);
			},
			resetState(field) {
				if(_.has(this.errors, field)) {
					this.errors[field] = [];
				} else {
					_.forIn(this.errors, (v, k) => { this.errors[k] = []; });
				}
			},
			initialize() {
				this.input.email = this.$user.email;
				this.input.login = this.$user.login;
				this.input.name = this.$user.name;
			},
			onSubmit(e) {
				e.preventDefault();

				this.loaded = false;

				this.resetState();

				axios.post(this.apiRoute('api.profile.update'), this.input)
					.then((response) => {
						let success = _.get(response.data, 'success');

						if (success) {
							this.$bvToast.toast(success, {
								title: 'Success',
								variant: 'success'
							});
						}

						let user = _.get(response.data, 'user') || null;

						if (user) {
							this.$service(AuthService).update(user);
							this.initialize();
						}
					})
					.catch((error) => {
						let errors = _.get(error.response, 'data.errors') || [];

						errors.forEach((err) => {
							this.$bvToast.toast(err.message, {
								title: 'Error',
								variant: 'danger'
							});

							let fields = _.get(err, 'errors') || [];

							_.forIn(fields, (v, k) => { this.errors[k] = v; });
						});
					})
					.finally(() => {
						this.loaded = true;
						this.input.password = null;
						this.input.password_new = null;
						this.input.password_new_confirmation = null;
					});
			},
		}
	}
</script>
