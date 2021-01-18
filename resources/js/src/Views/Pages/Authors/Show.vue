<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-row>
					<b-col cols="12">
						<h2>{{ trans('authors.title') }}</h2>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<h3>{{ author.name }}</h3>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12" v-html="author.description" class="my-2"/>
				</b-row>
				<b-row>
					<b-col cols="12" class="my-4">
						<b-card :title="trans('authors.attributes.books')">
							<b-table v-if="author.books.length > 0" striped :items="author.books" :fields="fields.books">
								<template #cell(actions)="data">
									<b-button-toolbar>
										<b-button-group size="sm">
											<b-button type="button" :to="{ name: 'books.show', params: { isbn: data.item.isbn } }">
												<i class="far fa-eye"></i>
												<span>{{ trans('forms.view') }}</span>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-table>
							<div v-else>
								{{ trans('books.empty') }}
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
				author: {
					id: null,
					name: null,
					description: null,
					books: []
				},
				fields: {
					books: [
						{
							key: 'isbn',
							label: this.trans('books.attributes.isbn'),
							sortable: true
						},
						{
							key: 'name',
							label: this.trans('books.attributes.name'),
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
					author: this.$route.params.id,
				};
			}
		},
		mounted() {
			this.fetch();
		},
		methods: {
			fetch() {
				return axios.get(this.apiRoute('api.authors.show', this.params))
					.then((response) => {
						let author = _.get(response, 'data.data');
						let books = _.get(author, 'books');

						_.forIn(author, (value, key) => {
							if (_.has(this.author, key)) {
								this.author[key] = value;
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
