<script setup>
import store from '@/store';
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTheme } from 'vuetify';

const vuetifyTheme = useTheme()
const route = useRoute()
const router= useRouter()

/*const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? authV1MaskLight : authV1MaskDark
})*/

const isPasswordVisible = ref(false)
let id = ref('')
let password = ref('')
let loading = ref(false)

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

onMounted(() => {
  initialize()
})

</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      color="#ffffffb0"
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
        <p class="text-center mb-0 pb-0"> <strong>Ingresar como:</strong></p>
      </VCardText>

      <VCardText>
        <VForm>
          <VRow> 
            <VCol cols="12">
              <VBtn
                block
                type="submit"
                href="/app/redirect" 
                :loading="loading"
              ><v-icon icon="mdi-user"></v-icon> 
                DOCENTE ISIL
              </VBtn>
            </VCol>
          </VRow>
          <VRow> 
            <VCol cols="12">
              <VBtn
                block
                type="submit"
                href="/login-invitados" 
                :loading="loading"
              ><v-icon icon="mdi-user"></v-icon> 
                Invitado
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core-scss/pages/page-auth.scss";
</style>
