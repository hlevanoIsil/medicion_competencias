<template>
  <div class="mt-6">
    <v-row justify="center">
    <v-col cols="12" md="12" order="1" order-md="2" class="align-self-end mb-2 mt-md-0">
      <v-card class="ps-3 home" color="on-primary">
        <v-row >
          <v-col cols="8" sm="8">
            <v-card-text class="text-2xl pt-8 text-right text-no-wrap" >
              <span class="text-secondary text-no-wrap text-h6">Bienvenid@ !! </span>
              <!--<span class="text-no-wrap font-weight-bold mx-1">ISIL/LAB</span>-->
              <span class="text-h6">🥳</span>
              
            </v-card-text>
            <v-card-text class="text-2xl  py-10 text-right" >
              <span class="flex-nowrap font-weight-bold text-primary text-h5 "> {{userData.first_name}} {{userData.last_name}} </span>

              <br><br>
              <span class="flex-nowrap text-h6 " style="text-align: justify;">Periodo vigente: {{periodo}}</span>
              <br><br>
              <p style="text-align: justify;">El modelo de Medición del Logro de Competencias fue diseñado e implementado desde el 2019. Tiene como finalidad de evaluar, analizar y validar el nivel del logro de las competencias específicas alcanzado por nuestros estudiantes en cada uno de nuestros programas de estudio y a lo largo de sus diferentes procesos formativos.</p>

<p style="text-align: justify;">Este modelo es aplicado utilizando el “proyecto” de la “evaluación final”, en los cursos que hemos denominado como “Cursos Integradores” los cuales tienen la característica de reunir las competencias propuestas por ISIL en cada una de las certificaciones anuales de nuestros programas de estudio. </p>

<p style="text-align: justify;">La información obtenida del proceso es sumamente relevante para la actualización nuestros programas de estudio, la mejora continua de nuestros procesos y como evidencia para los diferentes procesos de acreditación de ISIL. </p>

<p style="text-align: justify;">Desde el área de Calidad Educativa agradecemos su profesionalismo y buena disposición durante su participación en la ejecución de este modelo.</p>
<p style="text-align: justify;">Calidad Educativa <br>Sub-Dirección de Competencias y Acreditaciones</p>

            </v-card-text>
            
          </v-col>
          <v-col cols="4" sm="4"  >
            <VImg
                width="150"
                :src="avatar"
                class="gamification-john-pose-2"
              ></VImg>

          </v-col>
        </v-row>
      </v-card>
    </v-col>
    </v-row>

  </div>
</template>
<script setup>

import useAppConfig from '@core/@app-config/useAppConfig';
import avatar from '@images/avatars/pose-f-39.png';
import triangleDark from '@images/misc/triangle-dark.png';
import triangleLight from '@images/misc/triangle-light.png';
import { useTheme } from 'vuetify';
const $http = inject('http')

const userData = JSON.parse(localStorage.getItem('userData'))
const userMenu = JSON.parse(localStorage.getItem('userMenu'))
var { overlay } = useAppConfig()
let periodo = ref(null)

const { global } = useTheme()
const triangleBg = computed(() => global.name.value === 'light' ? triangleLight : triangleDark)
  
function initialize() {
  $http.post('system/curtermcode')
      .then(per => {
        periodo.value = per.data
         
      })
      .catch(error => {
          //isLoading.value = false
      })
  }
  onBeforeMount(() => { 
    initialize() 
  })
</script>
<style lang="scss" scoped>

.gamification-tree-4,
.gamification-john-pose-2,
.gamification-tree {
  position: absolute;
}
.gamification-tree {
  top: 10%;
}
.gamification-john-pose-2 {
  bottom: 0;

}
.gamification-tree-4 {
  bottom: 0;
  
}

.v-event {
  text-align: center;
}

@media (max-width: 600px) {
  .gamification-tree,
  .gamification-tree-4 {
    display: none;
  }

}

@media (max-width: 500px) {
  .gamification-john-pose-2 {
    max-width: 120px;
  }
}

@media (max-width: 400px) {
  .page-title {
    font-size: 1rem !important;
  }
  .page-subtitle {
    font-size: 1rem !important;
  }
  .space-name {
    max-width: 100%;
  }
  .space-subname {
    max-width: 60%;
  }
}


@media (max-width: 800px) {
  .home {
    min-height: 200px;
  }
}

.v-card .triangle-bg {
  position: absolute;
  inline-size: 8.375rem;
  inset-block-end: 0;
  inset-inline-end: 0;
}

</style>