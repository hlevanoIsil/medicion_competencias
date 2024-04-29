<script setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
import store from '@/store'

</script>

<template>
  <DefaultLayoutWithVerticalNav>
    <RouterView />
    <v-overlay
      :model-value="store.state.appConfig.loadcontentshowoverlay"
      class="align-center justify-center"
      style="z-index:999999"
      persistent
    >
      <v-progress-circular
        color="on-secondary"
        indeterminate
        size="50"
      ></v-progress-circular>
    </v-overlay>
  </DefaultLayoutWithVerticalNav>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
