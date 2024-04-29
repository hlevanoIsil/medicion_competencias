<template>
  <div id="misc" class="mt-4">
    <!--<img
      class="misc-mask"
      height="226"
      :src="require(`@/assets/images/misc/misc-mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default"
    />

    <v-img class="misc-tree" :src="require('@/assets/images/misc/tree-2.png').default"></v-img>-->

    <div class="page-title text-center px-5">
      <h2 class="text-2xl font-weight-semibold">Acceso No Válido 🔐</h2>
      <p class="text-sm">Comunicate con tu Administrador</p>

      <div class="misc-character d-flex justify-center">
        <v-img max-width="700" :src="error"></v-img>
      </div>

      <v-btn color="primary" to="/" class="mt-6"> Volver al Login </v-btn>
    </div>
  </div>
</template>


<script setup>

import error from '@images/3d-characters/launching-soon.png'

</script>