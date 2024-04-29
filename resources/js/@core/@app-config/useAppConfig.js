import store from '@/store'
import { computed }  from 'vue'

export default function useAppConfig() {

  var menulist = computed({
    get: () => store.state.app.menu,
    set: value => {
      localStorage.setItem('userMenu', JSON.stringify(value));
      store.commit('app/MENU', value)
    },
  })

  var overlay = computed({
    get: () => store.state.appConfig.loadcontentshowoverlay,
    set: value => {
      store.commit('appConfig/TOGGLE_CONTENT_OVERLAY', value)
    },
  })

  var navigation = computed({
    get: () => store.state.appConfig.openNavigation,
    set: value => {
      store.commit('appConfig/TOGGLE_OPEN_NAGIVATION', value)
    },
  })



  return {
    menulist,
    overlay,
    navigation
  }
}
