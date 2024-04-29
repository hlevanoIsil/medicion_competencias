<template>
  <v-toolbar flat
  style="background: rgb(var(--v-theme-on-white))"
        >
        <v-btn
        variant="tonal"
        :color="showForm ? 'secondary' : 'primary'"
        class="mb-2 mr-2"
        @click="clickShowForm"
        :loading="loading"
        > 
            <v-icon left :icon="showForm ? 'mdi-arrow-left' : 'mdi-plus'">
            </v-icon>
            {{ showForm ? 'Regresar' : 'Agregar'}}
        </v-btn>
        <v-btn
        v-if="!showForm"
        color="secondary"
        class="mb-2"
        variant="outlined"
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