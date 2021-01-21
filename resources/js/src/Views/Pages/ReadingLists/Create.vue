<template>
	<div>
		<load-spinner :loaded="loaded">
			<book-search :visible="modals.books" :selected="form.books" @accept="acceptBooks" @hide="hideBooks"></book-search>
			<b-container>
				<b-form @submit="onSubmit">
					<b-row>
						<b-col cols="12">
							<h2>{{ trans('readingLists.create') }}</h2>
						</b-col>
					</b-row>
					<b-row>
						<b-col md="6" sm="12">
							<b-form-group
								id="name-group"
								:label="trans('readingLists.attributes.name')"
								label-for="name"
							>
								<b-input-group>
									<template #prepend>
										<b-input-group-text>
											<i class="fas fa-bookmark"></i>
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
						</b-col>

						<b-col md="6" sm="12" class="mt-2">
							<b-card :title="trans('readingLists.attributes.books')">
								<b-card-body v-if="form.books.length > 0">
									<b-table striped :items="form.books" :fields="fields.books">
										<template #cell(isbn)="data">
											{{ isbn(data.item.isbn) }}
										</template>

										<template #cell(actions)="data">
											<b-button-toolbar>
												<b-button-group size="sm">
													<b-button type="button" variant="outline-danger" @click="removeBook(data.item.id)">
														<i class="fas fa-user-times"></i>
														<span>{{ trans('forms.remove') }}</span>
													</b-button>
												</b-button-group>
											</b-button-toolbar>
										</template>
									</b-table>
								</b-card-body>

								<b-card-body v-else>
									{{ trans('readingLists.actions.books.empty') }}
								</b-card-body>

								<template #footer>
									<b-button-toolbar>
										<b-button-group size="md">
											<b-button type="button" variant="danger" @click="clearBooks">
												<i class="fas fa-users-slash"></i>
												<span>{{ trans('forms.clear') }}</span>
											</b-button>
										</b-button-group>
										<b-button-group class="ml-auto">
											<b-button type="button" variant="info" @click="showBooks">
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
	import { default as BookSearch }  from './Dialogs/Books';

	export default {
		components: {
			BookSearch
		},
		data() {
			return {
				loaded: true,
				form: {
					name: null,
					books: []
				},
				errors: {
					name: [],
					books: []
				},
				fields: {
					books: [
						{
							key: 'isbn',
							label: this.trans('books.attributes.isbn'),
							sortable: false
						},
						{
							key: 'name',
							label: this.trans('books.attributes.name'),
							sortable: false
						},
						{
							key: 'actions',
							label: this.trans('forms.actions'),
							sortable: false
						}
					]
				},
				modals: {
					books: false
				}
			};
		},
		computed: {
			input() {
				let input = _.cloneDeep(this.form);

				input.books = input.books.map(book => book.id);

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

				axios.post(this.apiRoute('api.readingLists.store'), this.input)
					.then((response) => {
						let success = _.get(response.data, 'success');

						if (success) {
							this.$bvToast.toast(success, {
								title: 'Success',
								variant: 'success'
							});
						}

						let readingListId = _.get(response.data, 'data.id');

						this.redirect('reading_lists.show', { id });
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
			removeBook(id) {
				this.form.books = this.form.books.filter((book, i) => {
					return (book.id != id);
				});
			},
			clearBooks() {
				let confirmed = window.confirm(this.trans('readingLists.actions.books.clear_confirm'));

				if (confirmed) {
					this.books = [];
				}
			},
			showBooks() {
				this.modals.books = true;
			},
			hideBooks() {
				this.modals.books = false;
			},
			acceptBooks(books) {
				this.form.books = books;
			}
		}
	}
</script>
