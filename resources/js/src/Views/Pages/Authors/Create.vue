<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('authors.create') }}</h2>
						</b-col>
					</b-row>
					<b-row>
						<b-col md="6" sm="12">
							<b-form-group
								id="name-group"
								:label="trans('authors.attributes.name')"
								label-for="name"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text>
											<i class="fas fa-user-tag"></i>
										</b-input-group-text>
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
								id="description-group"
								:label="trans('authors.attributes.description')"
								label-for="login"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text>
											<i class="far fa-id-card"></i>
										</b-input-group-text>
									</template>
									<b-form-textarea
										id="description"
										v-model="input.description"
										:state="state('description')"
										@change="resetState('description')"
										@input="resetState('description')"
									></b-form-textarea>
									<b-form-invalid-feedback :state="state('description')">
										<p v-for="(error, i) in errors.description">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>
						</b-col>
					</b-row>
					<b-row class="mt-4">
						<b-col cols="12">
							<b-button-toolbar>
								<b-button-group>
									<b-button type="submit" variant="primary">{{ trans('forms.create') }}</b-button>
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
				input: {
					name: null,
					description: null
				},
				errors: {
					name: [],
					description: []
				}
			};
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
			onSubmit(e) {
				e.preventDefault();

				this.loaded = false;

				this.resetState();

				axios.post(this.apiRoute('api.authors.store'), this.input)
					.then((response) => {
						let success = _.get(response.data, 'success');

						if (success) {
							this.$bvToast.toast(success, {
								title: 'Success',
								variant: 'success'
							});
						}

						this.redirect('books.list');
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
					.then(() => {
						this.loaded = true;
					});
			}
		}
	}
</script>
