<template>
  <v-dialog
    v-model="visib"
    persistent
    width="500"
    class="text-center"
  >
    <v-card class="text-center">
      <v-card-title class="font-weight-bold text-center">
        {{title}}
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text class="subtitle-1 font-weight-bold my-4">{{message}}</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="info"
          variant="elevated"
          @click="cancel"
        >
          CERRAR
        </v-btn>
        <v-btn
          color="success"
          @click="ok"
          variant="outlined"
        >
          OK
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>

export default {
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    message: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    let visib = ref(false)

    return {
      visib
    }
  },
  watch: {
    visible(value){
      this.visib = value
    }
  },
  methods: {
    ok(){
      this.$emit('ok')
    },
    cancel(){
      this.$emit('cancel')
    }
  }
}
</script>