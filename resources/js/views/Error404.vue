<template>
  <div id="misc" class="mt-4">
    <!--<img
      class="misc-mask"
      height="226"
      :src="require(`@/assets/images/misc/misc-mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default"
    />

    <v-img class="misc-tree" :src="tree"></v-img>-->

    <div class="page-title text-center px-4">
      <h2 class="text-2xl font-weight-semibold d-flex align-center justify-center">
        <span class="me-2">Módulo no Encontrado</span>
        <v-icon color="warning" icon="mdi-alert">
        </v-icon>
      </h2>
      <p class="text-sm">No pudimos encontrar el módulo que estas buscando</p>

      <div class="misc-character d-flex justify-center">
        <v-img max-width="700" :src="error"></v-img>
      </div>

      <v-btn color="primary" to="/" class="mt-6"> Volver al Inicio </v-btn>
    </div>
  </div>
</template>

<script setup>

import tree from '@images/misc/tree-4.png'
import error from '@images/3d-characters/error.png'

</script>
