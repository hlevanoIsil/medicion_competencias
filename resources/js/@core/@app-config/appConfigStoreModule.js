
export default {
  namespaced: true,
  state: {
    loadcontentshowoverlay: false,
    openNavigation: [],
    snackbar: {show: false, 
            timeout: '4000', 
            color: 'error', 
            text: "Advertencia"
    },
  },
  mutations: {
    TOGGLE_CONTENT_OVERLAY(state, value) {
      state.loadcontentshowoverlay = value !== undefined ? value : !state.loadcontentshowoverlay
    },
    TOGGLE_OPEN_NAGIVATION(state, value) {
      state.openNavigation = value
    },
    TOGGLE_SNACKBAR(state, value) {
      state.snackbar.show = value.show !== undefined ? value.show : !state.snackbar.show
      state.snackbar.text = value.text !== undefined ? value.text : state.snackbar.text
      state.snackbar.color = value.color !== undefined ? value.color : state.snackbar.color
    },
  },
}
