<script setup>
//import avatar1 from '@images/logo.svg?raw'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useDisplay } from 'vuetify'
import theme from '@/plugins/vuetify/theme'
import useAppConfig from '@core/@app-config/useAppConfig'
import avatar1 from '@images/logos/Logo_Blanco.jpeg'
import { useTheme } from 'vuetify'

const vuetifyTheme = useTheme()

const navLayoutColor = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? theme.themes.light.navLayout : theme.themes.dark.navLayout
})

const props = defineProps({
  tag: {
    type: [
      String,
      null,
    ],
    required: false,
    default: 'aside',
  },
  isOverlayNavActive: {
    type: Boolean,
    required: true,
  },
  toggleIsOverlayNavActive: {
    type: Function,
    required: true,
  },
})

const { mdAndDown } = useDisplay()
const refNav = ref()
const route = useRoute()
const appName = theme.app.name

watch(() => route.path, () => {
  props.toggleIsOverlayNavActive(false)
})

const isVerticalNavScrolled = ref(false)
const updateIsVerticalNavScrolled = val => isVerticalNavScrolled.value = val

const handleNavScroll = evt => {
  isVerticalNavScrolled.value = evt.target.scrollTop > 0
}

let drawer = ref(true)
let rail = ref(true)
let open = ref([])
let drawerKey = ref(0)

watch(props, (newX) => {
    drawer = newX.isOverlayNavActive
    if(!mdAndDown.value) drawer = !mdAndDown.value
})

watch(mdAndDown, (newX) => {
    drawer = !newX
})

var { menulist, navigation } = useAppConfig()

function updateRail(argument) {
  if (argument) {
    navigation.value =  [] 
  } else {
    if (route.meta.levelOne != undefined) {
      navigation.value.push (route.meta.levelOne)
    }
    if (route.meta.levelTwo != undefined) {
      navigation.value.push (route.meta.levelTwo)
    }
  }
}

</script>

<template>
  <v-navigation-drawer
        :key="drawerKey"
        :theme="vuetifyTheme.global.name.value"
        :temporary="mdAndDown"
        expand-on-hover
        :rail="!mdAndDown"
        v-model="drawer"
        :color="navLayoutColor"
        ref="refNav"
        @update:rail="updateRail"
      >
        <v-list style ="background: inherit;"
        >
          <v-list-item     style ="padding-left: 5%"
          >
            <template v-slot:prepend>
                <v-img :width="50" :src="avatar1" style="border-radius: 5px;"></v-img>
              
             </template>
            <v-list-item-title><h1 class="pl-3 text-h5 font-weight-black text-primary text-uppercase">
            {{ appName }}
          </h1></v-list-item-title>
          </v-list-item>
        </v-list>

        <!-- <v-divider></v-divider>-->

        <slot name="before-nav-items">
          <div class="vertical-nav-items-shadow" />
        </slot>
        <slot
          name="nav-items"
          :update-is-vertical-nav-scrolled="updateIsVerticalNavScrolled"
        >
            <slot />
        </slot>
    
        <slot name="after-nav-items" />
    </v-navigation-drawer>
</template>

<style lang="scss">
@use "@configured-variables" as variables;
@use "@layouts/styles/mixins";

// 👉 Vertical Nav
.layout-vertical-nav {
  position: fixed;
  z-index: variables.$layout-vertical-nav-z-index;
  display: flex;
  flex-direction: column;
  block-size: 100%;
  inline-size: variables.$layout-vertical-nav-width;
  inset-block-start: 0;
  inset-inline-start: 0;
  transition: transform 0.25s ease-in-out, inline-size 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
  will-change: transform, inline-size;

  .nav-header {
    display: flex;
    align-items: center;

    .header-action {
      cursor: pointer;
    }
  }

  .app-title-wrapper {
    margin-inline-end: auto;
  }

  .nav-items {
    block-size: 100%;

    // ℹ️ We no loner needs this overflow styles as perfect scrollbar applies it
    // overflow-x: hidden;

    // // ℹ️ We used `overflow-y` instead of `overflow` to mitigate overflow x. Revert back if any issue found.
    // overflow-y: auto;
  }

  .nav-item-title {
    overflow: hidden;
    margin-inline-end: auto;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  // 👉 Collapsed
  .layout-vertical-nav-collapsed & {
    &:not(.hovered) {
      inline-size: variables.$layout-vertical-nav-collapsed-width;
    }
  }

  // 👉 Overlay nav
  &.overlay-nav {
    &:not(.visible) {
      transform: translateX(-#{variables.$layout-vertical-nav-width});

      @include mixins.rtl {
        transform: translateX(variables.$layout-vertical-nav-width);
      }
    }
  }
}

.v-navigation-drawer {
  ::-webkit-scrollbar {
    width: 5px;
  }
  
  /* Track */
  ::-webkit-scrollbar-track {
    box-shadow: transparent; 
    border-radius: 10px;
  }
   
  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #aaaaaa57; 
    border-radius: 6px;
  }
  
  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #999; 
  }
  
  ::-webkit-scrollbar {
    display: none;
  }
}

.v-navigation-drawer--is-hovering {
  ::-webkit-scrollbar {
      display: block;
  }
}
</style>
