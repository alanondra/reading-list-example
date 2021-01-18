<template>
	<div>
		<load-spinner :loaded="loaded">
			<author-search :visible="modals.authors" :selected="form.authors" @accept="acceptAuthors" @hide="hideAuthors"></author-search>
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('books.create') }}</h2>
						</b-col>
					</b-row>
					<b-row>
						<b-col md="6" sm="12">
							<b-form-group
								id="isbn-group"
								:label="trans('books.attributes.isbn')"
								label-for="isbn"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text>
											<i class="fas fa-bookmark"></i>
										</b-input-group-text>
									</template>
									<b-form-input
										required
										id="isbn"
										v-model="form.isbn"
										type="text"
										maxlength="13"
										:state="state('isbn')"
										@change="resetState('isbn')"
										@input="resetState('isbn')"
									></b-form-input>
									<b-form-invalid-feedback :state="state('isbn')">
										<p v-for="(error, i) in errors.isbn">{{ error }}</p>
									</b-form-invalid-feedback>
								</b-input-group>
							</b-form-group>

							<b-form-group
								id="name-group"
								:label="trans('books.attributes.name')"
								label-for="name"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text>
											<i class="fas fa-book"></i>
										</b-input-group-text>
									</template>
									<b-form-input
										required
										id="name"
										v-model="form.name"
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
											<i class="fas fa-book-open"></i>
										</b-input-group-text>
									</template>
									<b-form-textarea
										id="description"
										v-model="form.description"
										maxlength="65535"
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
						<b-col md="6" sm="12" class="mt-2">
							<b-card :title="trans('books.attributes.authors')">
								<b-card-body v-if="form.authors.length > 0">
									<b-table striped :items="form.authors" :fields="fields.authors">
										<template #cell(actions)="data">
											<b-button-toolbar>
												<b-button-group size="sm">
													<b-button type="button" variant="outline-danger" @click="removeAuthor(data.item.id)">
														<i class="fas fa-user-times"></i>
														<span>{{ trans('forms.remove') }}</span>
													</b-button>
												</b-button-group>
											</b-button-toolbar>
										</template>
									</b-table>
								</b-card-body>

								<b-card-body v-else>
									{{ trans('books.actions.authors.empty') }}
								</b-card-body>

								<template #footer>
									<b-button-toolbar>
										<b-button-group size="md">
											<b-button type="button" variant="danger" @click="clearAuthors">
												<i class="fas fa-users-slash"></i>
												<span>{{ trans('forms.clear') }}</span>
											</b-button>
										</b-button-group>
										<b-button-group class="ml-auto">
											<b-button type="button" variant="info" @click="showAuthors">
												<i class="fas fa-users"></i>
												<span>{{ trans('forms.search') }}</span>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-card>
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
	import { default as AuthorSearch } from './Dialogs/Authors';

	export default {
		components: {
			AuthorSearch
		},
		data() {
			return {
				loaded: true,
				form: {
					isbn: null,
					name: null,
					description: null,
					authors: [],
				},
				errors: {
					isbn: [],
					name: [],
					description: [],
					authors: []
				},
				fields: {
					authors: [
						{
							key: 'name',
							sortable: false
						},
						{
							key: 'actions',
							sortable: false
						}
					]
				},
				modals: {
					authors: false
				}
			};
		},
		computed: {
			input() {
				let input = _.cloneDeep(this.form);

				input.authors = input.authors.map(author => author.id);

				return input;
			}
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

				axios.post(this.apiRoute('api.books.store'), this.input)
					.then((response) => {
						let success = _.get(response.data, 'success');

						if (success) {
							this.$bvToast.toast(success, {
								title: 'Success',
								variant: 'success'
							});
						}

						this.redirect('authors.list');
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
			},
			removeAuthor(id) {
				this.form.authors = this.form.authors.filter((author, i) => {
					return (author.id != id);
				});
			},
			clearAuthors() {
				let confirmed = window.confirm(this.trans('authors.actions.clear_confirm'));

				if (confirmed) {
					this.authors = [];
				}
			},
			showAuthors() {
				this.modals.authors = true;
			},
			hideAuthors() {
				this.modals.authors = false;
			},
			acceptAuthors(authors) {
				this.form.authors = authors;
			}
		}
	}
</script>
