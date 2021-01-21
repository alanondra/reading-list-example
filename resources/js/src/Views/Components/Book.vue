<template>
	<div class="book">
		<div class="title">{{ title }}</div>
		<div class="isbn">ISBN #{{ isbn }}</div>
		<ul class="authors">
			<li v-for="author in authors" :key="author.id">
				{{ author.name }}
			</li>
		</ul>
	</div>
</template>

<script>
	export default {
		props: {
			title: String,
			isbn: String,
			authors: Array
		}
	}
</script>
