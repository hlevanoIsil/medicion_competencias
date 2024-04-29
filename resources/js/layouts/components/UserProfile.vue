<!-- <script setup>

import store from '@/store'
import useAppConfig from '@core/@app-config/useAppConfig'

const userData = JSON.parse(localStorage.getItem('userData')) || {}
var { overlay } = useAppConfig()

function logout() {

      overlay.value = true
      vue.$store.dispatch('app/AUTH_LOGOUT').then(() => {
        //overlay.value = false
        window.location.href = "/app/logout";
      })
    }

</script>-->

<script>
import useAppConfig from '@core/@app-config/useAppConfig'
import avatar1 from '@images/avatars/avatar-1.png'

export default {
  setup() {
    const userData = JSON.parse(localStorage.getItem('userData')) || {}
    var { overlay } = useAppConfig()

    return {
      userData,
      overlay,
      avatar1
    }
  },
  methods: {
    logout: function () {
      this.overlay = true
      this.$store.dispatch('app/AUTH_LOGOUT').then(() => {
        window.location.href = "/app/logout";
      })
    }
  },
}

</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    color="success"
    bordered
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList >
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>
            <template v-slot:title="{ title }">
              <div style="line-height: 1rem;">{{ userData.last_name || '...' }} </div>
              <div >{{ userData.first_name || '...'}} </div>
            </template>
            <VListItemSubtitle class="text-uppercase mt-2">{{ userData.role || '...' }}</VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem to="/login">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="mdi-logout"
                size="22"
              />
            </template>

            <VListItemTitle @click="logout">CERRAR SESIÓN</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
