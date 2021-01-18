<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-row>
					<b-col cols="12">
						<h2>{{ trans('books.title') }}</h2>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<h3>{{ book.name }}</h3>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<dl>
							<dt>
								{{ trans('books.attributes.isbn') }}
							</dt>
							<dd>
								{{ isbn(book.isbn) }}
							</dd>
						</dl>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<div v-html="book.description"/>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12" class="my-4">
						<b-card :title="trans('books.attributes.authors')">
							<b-table v-if="book.authors.length > 0" striped :items="book.authors" :fields="fields.authors">
								<template #cell(actions)="data">
									<b-button-toolbar>
										<b-button-group size="sm">
											<b-button type="button" :to="{ name: 'authors.show', params: { id: data.item.id } }">
												<i class="far fa-eye"></i>
												<span>{{ trans('forms.view') }}</span>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-table>
							<div v-else>
								{{ trans('authors.empty') }}
							</div>
						</b-card>
					</b-col>
				</b-row>
			</b-container>
		</load-spinner>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				loaded: false,
				book: {
					id: null,
					isbn: null,
					name: null,
					description: null,
					authors: []
				},
				fields: {
					authors: [
						{
							key: 'name',
							label: this.trans('authors.attributes.name'),
							sortable: true
						},
						{
							key: 'actions',
							label: this.trans('forms.actions')
						}
					]
				}
			};
		},
		computed: {
			params() {
				return {
					book: this.$route.params.isbn,
				};
			}
		},
		mounted() {
			this.fetch();
		},
		methods: {
			fetch() {
				return axios.get(this.apiRoute('api.books.show', this.params))
					.then((response) => {
						let book = _.get(response, 'data.data');
						let authors = _.get(book, 'authors');

						_.forIn(book, (value, key) => {
							if (_.has(this.book, key)) {
								this.book[key] = value;
							}
						});
					})
					.catch((error) => {
						let errors = _.get(error.response, 'data.errors') || [];

						errors.forEach((err) => {
							this.$bvToast.toast(err.message, {
								title: 'Error',
								variant: 'danger'
							});
						});
					})
					.finally(() => {
						this.loaded = true;
					});
			}
		}
	}
</script>
