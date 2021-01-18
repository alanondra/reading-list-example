<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('app.navbar.login') }}</h2>
						</b-col>
					</b-row>
					<b-row>
						<b-col md="6" sm="12">
							<b-form-group
								id="login-group"
								:label="trans('auth.login')"
								label-for="login"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-user"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										id="login"
										v-model="input.login"
										type="text"
										maxlength="256"
										:state="state('login')"
										@change="resetState('login')"
										@input="resetState('login')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('login')">
										<p v-for="(error, i) in errors.login" v-text="error"></p>
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
										<p v-for="(error, i) in errors.password" v-text="error"></p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-button-toolbar>
								<b-button-group>
									<b-button type="submit" variant="primary">{{ trans('auth.login') }}</b-button>
									<b-button type="button" variant="outline-secondary" href="/forgot">{{ trans('auth.forgot') }}</b-button>
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
					login: null,
					password: null,
				},
				errors: {
					login: [],
					password: []
				}
			};
		},
		methods: {
			state(input) {
				return ((this.errors[input].length === 0) ? null : false);
			},
			resetState(field) {
				if(_.has(this.errors, field)) {
					this.errors[field] = [];
				} else {
					_.forIn(this.errors, (v, k) => { this.errors[k] = []; });
				}
			},
			onSubmit(e) {
				e.preventDefault();

				this.loaded = false;

				this.resetState();

				this.$service(AuthService)
					.login(this.input)
					.catch((error) => {
						let errors = _.get(error.response, 'data.errors') || [];

						errors.forEach((err) => {
							let fields = _.get(err, 'errors') || [];

							_.forIn(fields, (v, k) => { this.errors[k] = v; });
						});
					})
					.finally(() => {
						this.loaded = true;
					});
			}
		}
	}
</script>
