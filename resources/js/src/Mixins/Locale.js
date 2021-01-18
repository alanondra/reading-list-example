import _ from 'lodash';

export default {
	methods: {
		trans: function (key, replace) {
			let translation = null;
			let found = true;

			let i18n = this.$root.i18n;
			let locale = this.$root.locale;
		
			try {
				translation = key
					.split('.')
					.reduce(
						(t, i) => (t[i] || null),
						i18n[locale]
					);
		
				if (translation) {
					found = true;
				}
			} catch (e) {
				translation = key;
			}
		
			if (!found) {
				translation = ((i18n[locale][key]) ? i18n[locale][key] : key);
			}
		
			_.forIn(replace, (value, key) => {
				translation = translation.replace(':' + key, value);
			})
		
			return translation;
		}
	}
}
