
<template>
	<div>
		<input type="url"
			maxlength="160"
			:placeholder="placeholder"
			:value="currentUrl"
			@input="emitInput">
	</div>
</template>

<script>
export default {
	name: 'Webhooks',
	components: {},
	props: {
		value: {
			default: JSON.stringify({ url: '' }),
			type: String,
		},
	},
	data() {
		return {
			url: '',
			placeholder: 'Specify webhook URL',
		}
	},
	computed: {
		currentUrl() {
			if (!this.value) {
				return ''
			}
			return JSON.parse(this.value).url
		},
	},
	methods: {
		emitInput(value) {
			if (value !== null) {
				this.$emit('input', JSON.stringify({ url: value.target.value }))
			}
		},
	},
}
</script>
