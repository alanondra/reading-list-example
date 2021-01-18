import Vuex from 'vuex';

import { AuthStore } from './Modules';

export default {
	create() {
		return new Vuex.Store({
			modules: {
				AuthStore: new AuthStore()
			}
		});
	}
}
