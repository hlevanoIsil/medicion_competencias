import * as types from '../mutation-types'
import actions from './actions'

export default {
  namespaced: true,
  state: {
    shallContentShowOverlay: false,
    token: localStorage.getItem('userToken') || '',
    status: localStorage.getItem('userStatus') || false,
    user: localStorage.getItem('userData') == 'undefined' ?  {} : JSON.parse(localStorage.getItem('userData')),
    menu: localStorage.getItem('userMenu') == 'undefined'  || localStorage.getItem('userMenu')  == null ? {} : JSON.parse(localStorage.getItem('userMenu')),
  },
  getters: {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status,
    user: state => state.user,
    menu: state => state.menu
  },
  mutations: {
    TOGGLE_CONTENT_OVERLAY(state, value) {
      state.shallContentShowOverlay = value !== undefined ? value : !state.shallContentShowOverlay
    },
    [types.AUTH_REQUEST](state) {
        state.status = 'loading'
    },
    [types.AUTH_SUCCESS]: (state, token) => {
        state.status = 'success'
        localStorage.setItem('userStatus', state.status)
        state.token = token
    },
    [types.AUTH_ERROR]: (state) => {
        state.status = 'error'
    },
    [types.USER]: (state, user) => {
        state.user = user
    },
    [types.MENU]: (state, menu) => {
        state.menu = menu
    },
    [types.AUTH_LOGOUT](state) {
        state.status = 'logout';
        state.token = null;
    }
  },
  actions: actions,
}
