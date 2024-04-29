<template>
  <v-toolbar flat class="mb-2 px-0"
        style="background: rgb(var(--v-theme-on-white))">
        <v-btn v-if="showForm"
        variant="tonal"
        color="secondary"
        class="mb-2 mr-2"
        @click="clickShowForm"
        :loading="loading"
        > 
            <v-icon left icon="mdi-arrow-left">
            </v-icon>
            Regresar
        </v-btn>
        <v-btn
        v-if="!showForm"
        color="secondary"
        variant="tonal"
        class="mb-2"
        @click="clickRefresh"
        :loading="loading"
        >
            <v-icon left icon="mdi-reload">
            </v-icon>
            Actualizar
        </v-btn>
      <v-divider></v-divider>
  </v-toolbar>
</template>

<script>

    export default {
        name: "UCToolbar",
        props: {
            showForm: {
              type: Boolean,
              default: false,
            },
            loading: {
              type: Boolean,
              default: false,
            },
        },
        methods: {
            clickShowForm() {
                this.$emit('on-create');
            },
            clickRefresh() {
                this.$emit('on-read');
            },
        }
    }
</script>