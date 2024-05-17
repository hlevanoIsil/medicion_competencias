<template>
    <div v-if="!isShowEntityActive && !isShowEvaluacion">
        <v-row>
            <v-col cols="12" md="4">
                <v-card 
                color="#059fe1"  
                    class="mx-auto text-white"
                >
                    <v-card-text>
                        <h2 class="text-white text-center"> ¡Bienvenido(a) Juan Perez!</h2>
                        <hr class="mt-4 mb-4 pl-2 pr-2">
                        <v-icon
                            icon="mdi-user"
                        ></v-icon> Tu rol asignado es  
                        
                        <v-chip color="error" variant="flat" size="small" class="ml-2 rounded-sm" >
                            DOCENTE
                        </v-chip>

                        <p class="mt-2">En la plataforma, se te permite añadir y editar grupos, al igual que evaluar el desempeño de los estudiantes</p>
                        <hr class="mt-4 mb-4 pl-2 pr-2">
                        <v-icon
                            icon="mdi-calendar"
                        ></v-icon>  Última actividad: 15, Abril 2024
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="8">
                <v-card title="Completa los datos para iniciar"  >
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-autocomplete
                                    label="NRC - Curso a evaluar"
                                    v-model="entityData.nrc"
                                    variant="outlined"
                                    density="compact"
                                    :items="nrcs"
                                    item-title="NRC_LBL"
                                    item-value="NRC"
                                    :menu-props="{ offsetY: true }"
                                    hide-details="auto"
                                    @update:modelValue="changeNrc" 
                                ></v-autocomplete>
                            </v-col>
                        </v-row>
                        <div v-if="entityData.nrc != null">
                            <v-row>
                                <v-col cols="12" md="12">
                                    CURSO: <strong>{{ entityData.curso }}</strong>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-card ><v-card-text>
                                        <p class="text-center">Código <br>
                                            <strong>{{entityData.nrc}}</strong>
                                        </p>
                                    </v-card-text></v-card>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-card ><v-card-text>
                                        <p class="text-center">Horario<br>
                                            <strong>{{entityData.horario}}</strong></p>
                                    </v-card-text></v-card>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-card ><v-card-text>
                                        <p class="text-center">Modalidad<br>
                                            <strong>{{entityData.mod_sede}}</strong></p>
                                    </v-card-text></v-card>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="3">
                                    <v-btn block color="info" @click="isShowEntityActive=true" > 
                                        <v-icon
                                            left
                                            dark
                                            icon="mdi-people"
                                        >
                                        </v-icon>
                                        Grupos</v-btn>
                                        
                                </v-col>
                                <v-col cols="12" md="3">

                                        <v-btn block color="success" @click="isShowEvaluacion=true; isShowEntityActive=false" > 
                                        <v-icon
                                            left
                                            dark
                                            icon="mdi-edit"
                                        >
                                        </v-icon>
                                        Evaluar</v-btn>
                                </v-col>
                            </v-row>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <grupos-interfaz v-if="isShowEntityActive && !isShowEvaluacion"
        :entityData="entityData"
        @on-backward="createEntityOrClose">
    </grupos-interfaz>

    <evaluacion v-if="isShowEvaluacion && !isShowEntityActive"
        :entityData="entityData"
        :nrcs="nrcs"
        :nrcs_lbls="nrcs_lbls2"
        @on-backward="createEntityOrClose">
    </evaluacion>
</template>
<script setup>
import useAppConfig from '@core/@app-config/useAppConfig';
import { ref } from 'vue';
import evaluacion from './grupos-components/evaluacion.vue';
import gruposInterfaz from './grupos-components/gruposInterfaz.vue';

  let { overlay } = useAppConfig()
  const $http = inject('http')
  const setOverlay = (value) => {
    overlay.value = value
  }
  let isShowEntityActive = ref(false)
  let isShowEvaluacion = ref(false)
  
  let entityData = ref({periodo: '202320'})
  let nrcs = ref([])
  let nrcs_lbls = ref([])
  let nrcs_lbls2 = ref([])
  function initialize() {
      setOverlay(true)
      $http.post('/grupos/list-nrcs-docente')
        .then(response => {
            nrcs.value = response.data.data
            response.data.data.forEach((element) => 
                nrcs_lbls[element['NRC']] = element
            );
            nrcs_lbls2.value = nrcs_lbls
            setOverlay(false)
        })
  }

  function createEntityOrClose(){
    //blankEntityData()
    //isShowEntityActive.value = !isShowEntityActive.value
    //isShowEvaluacion.value = !isShowEvaluacion.value

    isShowEntityActive.value = false
    isShowEvaluacion.value = false
  }
  function changeNrc(){ 
    entityData.value.horario = '' + nrcs_lbls[entityData.value.nrc]['INICIO_CLASE'] + ' - a ' + nrcs_lbls[entityData.value.nrc]['FIN_CLASE']
    entityData.value.mod_sede = nrcs_lbls[entityData.value.nrc]['MODALIDAD'] + ' - ' + nrcs_lbls[entityData.value.nrc]['SEDE']
    entityData.value.curso = nrcs_lbls[entityData.value.nrc]['CURSO']
    entityData.value.cod_curso = nrcs_lbls[entityData.value.nrc]['COD_CURSO']
    //console.log(nrcs)
    //console.log(nrcs_lbls2)
  }
    onBeforeMount(() => {
        initialize() 
    })
</script>