<template>
  <v-toolbar flat class="mb-4 px-0"
        style="background: rgb(var(--v-theme-on-white))">      
        <v-btn
        variant="outlined"
        color="secondary"
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
        setup() {
            return {
            }
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