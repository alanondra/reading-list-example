<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-row>
					<b-col cols="12">
						<h2>{{ trans('readingLists.title') }}</h2>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<h3>{{ reading_list.name }}</h3>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12" class="my-4">
						<b-card :title="trans('readingLists.attributes.books')">
							<b-table v-if="reading_list.items.length > 0" striped :items="reading_list.items" :fields="fields.items">
								<template #cell(isbn)="data">
									{{ isbn(data.item.book.isbn) }}
								</template>

								<template #cell(name)="data">
									{{ data.item.book.name }}
								</template>

								<template #cell(finished_at)="data">
									{{ data.item.finished_at }}
								</template>

								<template #cell(actions)="data">
									<b-button-toolbar>
										<b-button-group v-if="data.item.finished_at === null" size="sm" class="mr-2">
											<b-button type="button" variant="danger" @click="removeBook">
												<i class="fas fa-times"></i>
												<span>{{ trans('forms.remove') }}</span>
											</b-button>
											<b-button type="button" variant="success" @click="completeBook">
												<i class="fas fa-check"></i>
												<span>{{ trans('forms.complete') }}</span>
											</b-button>
										</b-button-group>
										<b-button-group size="sm">
											<b-button type="button" :to="{ name: 'books.show', params: { isbn: data.item.book.isbn } }">
												<i class="far fa-eye"></i>
												<span>{{ trans('forms.view') }}</span>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-table>
							<div v-else>
								{{ trans('readingLists.empty') }}
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
				reading_list: {
					id: null,
					name: null,
					items: []
				},
				fields: {
					items: [
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
							key: 'finished_at',
							label: this.trans('readingLists.attributes.finished_at'),
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
					readingList: this.$route.params.id,
				};
			}
		},
		mounted() {
			this.fetch();
		},
		methods: {
			fetch() {
				return axios.get(this.apiRoute('api.readingLists.show', this.params))
					.then((response) => {
						let reading_list = _.get(response, 'data.data');
						let items = _.get(reading_list, 'items');

						_.forIn(reading_list, (value, key) => {
							if (_.has(this.reading_list, key)) {
								this.reading_list[key] = value;
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
			},
			removeBook(e) {
				window.alert('Not implemented');
			},
			completeBook(e) {
				window.alert('Not implemented');
			}
		}
	}
</script>
