<template>
	<div>
		<load-spinner :loaded="loaded">
			<b-container>
				<b-row>
					<b-col cols="12">
						<h1>{{ trans('app.name') }}</h1>
					</b-col>
				</b-row>
				<b-row>
					<b-col cols="12">
						<b-card-group>
							<b-card :title="trans('app.navbar.books')">
								Latest three books.
							</b-card>

							<b-card :title="trans('app.navbar.authors')">
								Latest three authors.
							</b-card>

							<b-card :title="trans('app.navbar.reading_lists')">
								Your next three reading list items.
							</b-card>
						</b-card-group>
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
				loaded: true
			};
		}
	}
</script>

LoadSpinner