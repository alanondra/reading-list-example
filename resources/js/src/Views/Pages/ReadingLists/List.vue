<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-form @submit="update">
					<b-row>
						<b-col md="12" lg="8">
							<h2>{{ trans('app.navbar.reading_lists') }}</h2>
						</b-col>
						<b-col v-if="$user" md="12" lg="4">
							<b-button-toolbar class="my-2">
								<b-button-group size="sm">
									<b-button type="button" :title="trans('forms.create')" @click="create">
										<i class="fas fa-plus"></i>
										<span>{{ trans('forms.create') }}</span>
									</b-button>
								</b-button-group>
							</b-button-toolbar>
						</b-col>
					</b-row>
					<b-row class="my-2">
						<b-col cols="12" v-if="reading_lists.length === 0">
							{{ trans('readingLists.empty') }}
						</b-col>
						<b-col cols="12" v-else>
							<b-table striped :items="reading_lists" :fields="fields">
								<template #cell(actions)="data">
									<b-button-toolbar>
										<b-button-group size="sm">
											<b-button type="button" :to="{ name: 'reading_lists.show', params: { id: data.item.id } }">
												<i class="far fa-eye"></i>
												<span>{{ trans('forms.view') }}</span>
											</b-button>
										</b-button-group>
									</b-button-toolbar>
								</template>
							</b-table>
						</b-col>
					</b-row>
					<b-row v-if="pagination.pages > 1">
						<b-col cols="12">
							<b-pagination-nav use-router :link-gen="linkGen" :number-of-pages="pagination.pages">
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
							</b-pagination-nav>
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
				loaded: false,
				pagination: {
					pages: 1
				},
				fields: [
					{
						key: 'name',
						sortable: false,
						label: this.trans('readingLists.attributes.name')
					},
					{
						key: 'actions',
						label: this.trans('forms.actions')
					}
				],
				input: {
					page: 1,
					count: null
				},
				reading_lists: []
			};
		},
		mounted() {
			if (!this.$user) {
				this.redirect('login');
				return;
			}

			this.update();
		},
		methods: {
			create() {
				this.redirect('reading_lists.create');
			},
			update() {
				return axios.get(this.apiRoute('api.readingLists.index', this.input))
					.then((response) => {
						let data = response.data;
						let meta = _.get(data, 'meta');
						let reading_lists = _.get(data, 'data');

						if (this.input.count === null) {
							this.input.count = Number.parseInt(_.get(meta, 'per_page') || 0);
						}

						if (_.has(meta, 'last_page')) {
							this.pagination.pages = Number.parseInt(meta.last_page);
						}

						this.reading_lists = reading_lists;
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
			linkGen(pageNum) {
				return {
					name: 'reading_lists.list',
					query: {
						page: pageNum,
						count: this.input.count
					}
				}
			}
		}
	}
</script>
