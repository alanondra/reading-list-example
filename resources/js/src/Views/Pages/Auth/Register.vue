<template>
	<div>
		<b-modal :title="trans('auth.attributes.accept_tos')" v-model="termsVisible" size="xl" scrollable @ok="acceptTerms()" @cancel="cancelTerms()" @hide="hideTerms()" @reset="resetState()">
			<template #default>
				<p v-for="(line, i) in trans('app.tos')">
					{{ line }}
				</p>
			</template>
			<template #modal-cancel>
				<i class="fas fa-times"></i>
				{{ trans('forms.cancel') }}
			</template>
			<template #modal-ok>
				<i class="fas fa-check"></i>
				{{ trans('forms.accept') }}
			</template>
		</b-modal>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('app.navbar.register') }}</h2>
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
						</b-col>

						<b-col md="6" sm="12">
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
								id="password_confirmation-group"
								:label="trans('auth.attributes.password_confirmation')"
								label-for="password"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text><i class="fas fa-redo"></i></b-input-group-text>
									</template>
									<b-form-input
										required
										id="password_confirmation"
										v-model="input.password_confirmation"
										type="password"
										maxlength="256"
										:state="state('password_confirmation')"
										@change="resetState('password_confirmation')"
										@input="resetState('password_confirmation')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('password_confirmation')">
										<p v-for="(error, i) in errors.password_confirmation">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-checkbox
								id="accept_tos-checkbox"
								v-model="input.accept_tos"
								name="accept_tos"
								:value="true"
								:unchecked-value="false"
								class="pt-4"
								:state="state('accept_tos')"
								@change="resetState('accept_tos')"
							>
							{{ trans('auth.accept_tos') }}
							</b-form-checkbox>
							<b-form-invalid-feedback :state="state('accept_tos')">
								<p v-for="(error, i) in errors.accept_tos">{{ error }}</p>
							</b-form-invalid-feedback>
							<b-link @click="showTerms">{{ trans('forms.view') }}</b-link>
						</b-col>
					</b-row>

					<b-row class="mt-4">
						<b-col cols="12">
							<b-button-toolbar>
								<b-button-group>
									<b-button type="submit" variant="primary">{{ trans('auth.register') }}</b-button>
									<b-button type="reset" variant="outline-secondary">{{ trans('forms.clear') }}</b-button>
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
	export default {
		data() {
			return {
				loaded: true,
				termsVisible: false,
				input: {
					email: null,
					login: null,
					password: null,
					password_confirmation: null,
					name: null,
					accept_tos: false,
				},
				errors: {
					email: [],
					login: [],
					password: [],
					password_confirmation: [],
					name: [],
					accept_tos: []
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

				axios.post(this.apiRoute('api.auth.register'), this.input)
					.then((response) => {
						let success = _.get(response.data, 'success');

						if (success) {
							this.$bvToast.toast(success, {
								title: 'Success',
								variant: 'success'
							});
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
					});
			},
			showTerms() {
				this.termsVisible = true;
			},
			hideTerms() {
				this.termsVisible = false;
			},
			acceptTerms() {
				this.input.accept_tos = true;
				this.hideTerms();
			},
			cancelTerms() {
				this.hideTerms();
			}
		}
	}
</script>
