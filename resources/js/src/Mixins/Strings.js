import _ from 'lodash';

export default {
	methods: {
		isbn: function (value) {
			if (typeof value != 'string') {
				return null;
			}

			if (value.length === 13) {
				let matches = /^([\dX]{3})([\dX]{1})([\dX]{2})([\dX]{6})([\dX]{1})$/.exec(value);

				if (!matches) {
					console.log(`Invalid match for 13-digit ISBN: ${value}`);
					return value;
				}
	
				let p = _.values(_.pick(matches, _.range(matches.index + 1, matches.length)));
			
				return p.join('-');
			}

			if (value.length === 10) {
				let matches = /^([\dX]{1})([\dX]{2})([\dX]{6})([\dX]{1})$/.exec(value);

				if (!matches) {
					console.log(`Invalid match for 10-digit ISBN: ${value}`);
					return value;
				}
	
				let p = _.values(_.pick(matches, _.range(matches.index + 1, matches.length)));
			
				return p.join('-');
			}

			return;
		}
	}
}
