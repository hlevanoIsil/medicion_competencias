<template>
  <div class="mt-6">
    <v-row justify="center">
    <v-col cols="12" md="12" order="1" order-md="2" class="align-self-end mb-2 mt-md-0">
      <v-card class="ps-3 home" color="on-primary">
        <v-row>
          <v-col cols="8" sm="8">
            <v-card-text class="text-2xl pt-8 text-right text-no-wrap" >
              <span class="text-secondary text-no-wrap text-h6">Bienvenid@ !! </span>
              <!--<span class="text-no-wrap font-weight-bold mx-1">ISIL/LAB</span>-->
              <span class="text-h6">🥳</span>
            </v-card-text>
            <v-card-text class="text-2xl font-weight-bold py-10 text-right" >
              <span class="flex-nowrap text-primary text-h5 "> {{userData.first_name}} </span>
            </v-card-text>
          </v-col>
          <v-col cols="4" sm="4">
            <div>
              <VImg
                :src="triangleBg"
                class="triangle-bg flip-in-rtl"
              />
              <VImg
                width="150"
                :src="avatar"
                class="gamification-john-pose-2"
              ></VImg>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-col>
    </v-row>

  </div>
</template>
<script setup>

import useAppConfig from '@core/@app-config/useAppConfig'
import avatar from '@images/avatars/pose-f-39.png'
import triangleDark from '@images/misc/triangle-dark.png'
import triangleLight from '@images/misc/triangle-light.png'

const userData = JSON.parse(localStorage.getItem('userData'))
const userMenu = JSON.parse(localStorage.getItem('userMenu'))
var { overlay } = useAppConfig()
import { useTheme } from 'vuetify'

const { global } = useTheme()
const triangleBg = computed(() => global.name.value === 'light' ? triangleLight : triangleDark)

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