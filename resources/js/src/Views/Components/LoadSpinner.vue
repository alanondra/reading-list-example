<template>
	<div>
		<b-overlay :show="!loaded" spinner-variant="info">
			<slot/>
		</b-overlay>
	</div>
</template>

<script>
	export default {
		props: {
			loaded: {
				type: Boolean,
				default: false
			}
		}
	}
</script>
