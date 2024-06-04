<template>
  <div class="auth-wrapper2 d-flex align-center justify-center pa-4">
    <VCard
      
      class="auth-card pa-4 pt-7"
      mmin-width="448"
    >
      <VCardItem class="justify-center">
        <!--<template #prepend>
          <div class="d-flex">
            <div v-html="logo" />
          </div>
        </template>-->

        <VCardTitle class="font-weight-semibold text-2xl text-uppercase">
          Bienvenido a 👋🏻
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 font-weight-bold mb-1 text-center text-black">
          ISIL
        </h5>
        <p class="text-center mb-0 pb-0"> <strong>Acceso de invitados:</strong></p>
      </VCardText>

      <VCardText>
        <VRow> 
          <VCol cols="12" class="text-right">
            <v-icon icon="mdi-hand-pointing-left "></v-icon>
            <a href="/login">Volver al inicio</a>
          </VCol>
        </VRow>
        <v-form ref="form"
         v-model="valid"
          @submit="onSubmit" 
          @submit.prevent="validate">
          <VRow> 
            <VCol cols="12">
              <v-text-field
                label="Dni"
                v-model="entityData.id"
                hint="Ingrese su dni"
                variant="outlined"
                density="compact"
                hide-details="auto"
                :rules="[validators.required]"
              ></v-text-field>
            </VCol>
          </VRow>
          <VRow> 
            <VCol cols="12">
              <v-text-field
                v-model="entityData.password"
                hint="Ingrese su contraseña"
                label="Contraseña"
                persistent-hint
                type="password" 
                :rules="[validators.required]"
              ></v-text-field>
            </VCol>
          </VRow>
          <VRow v-if="msj1"> 
            <VCol cols="12" >
             <p class="text-error text-center mt-0 mb-0" >Credenciales incorrectas</p>
            </VCol>
          </VRow>
          <VRow > 
            <VCol cols="12">
              <v-btn block type="submit" :loading="isLoading" >
                  <v-icon
                    left
                    dark
                    id="btnConsultar"
                    icon="mdi-login"
                  >
                  </v-icon>
                  INGRESAR
              </v-btn>
              <!-- 
              <VBtn
                block
                type="submit"
              ><v-icon icon="mdi-login" class="mr-2"></v-icon> 
                INGRESAR
              </VBtn>-->
            </VCol>
          </VRow>
        </v-form>
      </VCardText>
    </VCard>
  </div>
</template>
<script setup>
import store from '@/store';
import useAppConfig from '@core/@app-config/useAppConfig';
import { required } from '@core/utils/validation.js';
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTheme } from 'vuetify';
const vuetifyTheme = useTheme()

const valid = ref(false)
const form = ref(null)
const validate = () => {
  form.value.validate()
}  
const validators = { required }
const route = useRoute()
const router= useRouter()

import { ref } from 'vue';

let { overlay } = useAppConfig()
const $http = inject('http')
const setOverlay = (value) => {
  overlay.value = value
}

let entityData = ref({})

/*const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? authV1MaskLight : authV1MaskDark
})*/

const isPasswordVisible = ref(false)
let id = ref('')
let password = ref('')
let loading = ref(false)
let msj1 = ref(false)

function initialize() {

    if (route.params.id == undefined) return
    if (route.params.token == undefined)  return   
    loading = true
    id = route.params.id;
    password = route.params.token;

    store.dispatch('app/AUTH_REQUEST', {id: id, password: password})
      .then(() => {
        router.replace({ path: '/home' })
        //console.log(response)
    })

}

function onSubmit() {
  setOverlay(true)
  $http.post('auth/loginExterno', entityData.value)
  .then(response => {
    console.log(response.data.token)
    if(response.data.token !== null){
      location.href ="/login/" + response.data.dni + "/" + response.data.token;
    }else{
      msj1.value = true
    }
    //alert("aa")         
    setOverlay(false)             
  })
  .catch(error => {
    console.log(error)
    setOverlay(false)   
  })
}
onMounted(() => {
  initialize()
})

</script>
<style lang="scss">
@use "@core-scss/pages/page-auth.scss";
</style>
