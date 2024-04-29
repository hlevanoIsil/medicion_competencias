import appConfigStoreModule from '@core/@app-config/appConfigStoreModule'
import Vuex from 'vuex'
import app from './app'

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    appConfig: appConfigStoreModule,
    app,
  },
})
