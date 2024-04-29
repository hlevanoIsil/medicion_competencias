<script setup>
import { useTheme } from 'vuetify'
import { inject } from "vue";
import useAppConfig from '@core/@app-config/useAppConfig'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavMenu from '@layouts/components/VerticalNavMenu.vue'
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'

const route = useRoute()
var { overlay, menulist, navigation } = useAppConfig()
const http = inject('http')
let navMenuItems = ref([])
let open = ref([])

onBeforeMount(() => {
  overlay.value = true

  http.get('/system/menu')
    .then(response => {
      navMenuItems.value = response.data
      menulist.value =  response.data
      navigation.value =  [] 
      overlay.value = false
    })
})

</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <!---->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="mdi-menu" />
        </IconBtn>

        <VSpacer />

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
      <v-list style ="background: inherit" v-model:opened="navigation">
      <VerticalNavMenu
          v-for="item in menulist"
          :key="item.id"
          :item="item"
      />
      </v-list>

    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}

.layout-vertical-nav {
  .nav-link a {
    display: flex;
    align-items: center;
    cursor: pointer;
  }
}

.v-list-item-title{
  font-size: 0.9rem;
}

</style>
