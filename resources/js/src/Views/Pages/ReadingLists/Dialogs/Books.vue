<template>
	<div>
		<b-modal :title="trans('readingLists.actions.books.search')" :visible="visible" size="xl" scrollable @show="open" @hide="close" @ok="accept()" @cancel="cancel()" >
			<template #default>
				<b-row>
					<b-col cols="7">
						<load-spinner :loaded="loaded">
							<b-table striped responsive small :items="provider" :current-page="pagination.page" :per-page="pagination.count" :fields="fields.results" no-provider-sorting no-provider-filtering>
								<template #cell(isbn)="data">
									{{ isbn(data.item.isbn) }}
								</template>

								<template #cell(actions)="data">
									<b-button-toolbar>
										<b-button-group size="sm">
											<b-button :disabled="isSelected(data.item.id)" type="button" :variant="state(data.item.id)" @click="add(data.item)" :title="trans('readingLists.actions.books.add')">
												<i class="fas fa-plus"></i>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-table>
							<b-pagination v-model="pagination.page" :per-page="pagination.count" :total-rows="pagination.total">
								<template #first-text>
									<i class="fas fa-angle-double-left"></i>
									<span>{{ trans('forms.first') }}</span>
								</template>
								<template #prev-text>
									<i class="fas fa-angle-left"></i>
									<span>{{ trans('forms.previous') }}</span>
								</template>
								<template #next-text>
									<span>{{ trans('forms.next') }}</span>
									<i class="fas fa-angle-right"></i>
								</template>
								<template #last-text>
									<span>{{ trans('forms.last') }}</span>
									<i class="fas fa-angle-double-right"></i>
								</template>
							</b-pagination>
						</load-spinner>
					</b-col>

					<b-col cols="5" v-if="data.selected.length === 0" class="text-center">
						{{ trans('readingLists.actions.books.empty') }}
					</b-col>

					<b-col cols="5" v-else>
						<b-table striped responsive small :items="data.selected" :fields="fields.selected">
							<template #cell(isbn)="data">
								{{ isbn(data.item.isbn) }}
							</template>

							<template #cell(actions)="data">
								<b-button-toolbar>
									<b-button-group size="sm">
										<b-button type="button" variant="outline-danger" @click="remove(data.item.id)" :title="trans('readingLists.actions.books.remove')">
											<i class="fas fa-times"></i>
										</b-button>
									</b-button-group>
								</b-button-toolbar>
							</template>
						</b-table>
					</b-col>
				</b-row>
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
	</div>
</template>

<script>
	import _ from 'lodash';

	export default {
		props: {
			visible: {
				type: Boolean,
				default: false
			},
			selected: {
				type: Array,
				default: []
			}
		},
		data() {
			return {
				loaded: false,

				pagination: {
					page: 1,
					count: null,
					pages: 1,
					total: 0
				},

				data: {
					results: [],
					selected: []
				},

				fields: {
					results: [
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
					],
					selected: [
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
				}
			};
		},
		methods: {
			open() {
				this.data.selected = _.cloneDeep(this.selected);
				this.pagination.page = 1;
			},
			close() {
				this.$emit('hide');
			},
			accept() {
				this.$emit('accept', this.data.selected);
			},
			cancel() {
				this.data.selected = this.selected;
			},
			isSelected(id) {
				let index = _.findIndex(this.data.selected, (book, i) => {
					return (book.id == id);
				});

				return (index >= 0);
			},
			state(id) {
				return (this.isSelected(id) ? 'default' : 'success')
			},
			add(book) {
				if (!this.isSelected(book.id)) {
					this.data.selected.push(book);
				}
			},
			remove(id) {
				this.data.selected = this.data.selected.filter((book, i) => {
					return (book.id != id);
				});
			},
			provider(ctx) {
				this.loaded = false;

				let query = {
					page: ctx.currentPage,
					count: ((ctx.perPage < 1) ? null : ctx.perPage)
				};

				return axios.get(this.apiRoute('api.books.index', query))
					.then((response) => {
						let data = response.data;
						let meta = _.get(data, 'meta');
						let books = _.get(data, 'data');

						this.pagination.count = Number.parseInt(_.get(meta, 'per_page') || 0);
						this.pagination.pages = meta.last_page;
						this.pagination.total = meta.total;

						return books;
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