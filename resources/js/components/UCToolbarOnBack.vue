<template>
  <v-toolbar flat class="mb-2"
    style="background: rgb(var(--v-theme-on-white))"
        >
        <v-btn
        variant="tonal"
        color="secondary"
        class="mb-2 mr-2"
        @click="clickRefresh"
        :loading="loading"
        > 
            <v-icon left icon="mdi-arrow-left">
            </v-icon>
            Regresar
        </v-btn>
      <v-divider></v-divider>
  </v-toolbar>
</template>

<script>
    
    export default {
        name: "UCToolbar",
        methods: {
            clickRefresh() {
                this.$emit('on-read');
            },
        }
    }
</script>