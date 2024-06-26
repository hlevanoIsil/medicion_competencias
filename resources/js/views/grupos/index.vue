<template>
    <div v-if="!isShowEntityActive && !isShowEvaluacion">
        <v-row>
            <v-col cols="12" md="4">
                <v-card 
                color="#059fe1"  
                    class="mx-auto text-white"
                >
                    <v-card-text>
                        <h2 class="text-white text-center"> ¡Bienvenido(a) {{userData.first_name}} {{userData.last_name}}!</h2>
                        <hr class="mt-4 mb-4 pl-2 pr-2">
                        <div v-if="userData.role_id==2">
                            <v-icon
                                icon="mdi-user"
                            ></v-icon> Tu rol asignado es  
                            
                            <v-chip color="error" variant="flat" size="small" class="ml-2 rounded-sm" >
                                DOCENTE
                            </v-chip>

                            <p class="mt-2">En la plataforma, se te permite añadir y editar grupos, al igual que evaluar el desempeño de los estudiantes</p>
                            <p class="mt-2 text-white text-h6">El periodo vigente es {{periodo}}</p>

                            <p>
                               <strong>Soporte:</strong>
De presentarse algún inconveniente con la plataforma, por favor enviar un correo <a href="mailto:ticketsti@isil.pe" class="text-white"><strong>ticketsti@isil.pe</strong> </a> y poner en copia a su coordinador asignado. Describir el detalle del problema y de ser necesario enviar capturas de pantalla. Muchas gracias.

                            </p>
                        </div>
                        <div v-if="userData.role_id==3">
                            <v-icon
                                icon="mdi-user"
                            ></v-icon> Tu rol asignado es  
                            
                            <v-chip color="success" variant="flat" size="small" class="ml-2 rounded-sm" >
                                INVITADO
                            </v-chip>

                            <p class="mt-2">Dentro de la plataforma, tienes la posibilidad de asignar calificaciones y dejar comentarios a los estudiantes y a los grupos.</p>
                            <p class="mt-2 text-white text-h6">El periodo vigente es {{periodo}}</p>
                            <p>
                               <strong>Soporte:</strong>
De presentarse algún inconveniente con la plataforma, por favor enviar un correo <a href="mailto:ticketsti@isil.pe" class="text-white"><strong>ticketsti@isil.pe</strong> </a> y poner en copia a su coordinador asignado. Describir el detalle del problema y de ser necesario enviar capturas de pantalla. Muchas gracias.

                            </p>
                        </div>
                        <hr class="mt-4 mb-4 pl-2 pr-2">
                        <v-icon
                            icon="mdi-calendar"
                        ></v-icon>  Última actividad: {{fecha_hora}}
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
                                    item-title="LABEL"
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
                                <v-col cols="12" md="3" >
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
        @on-backward="createEntityOrClose"
        @ir-eval="irEval">
    </grupos-interfaz>

    <evaluacion v-if="isShowEvaluacion && !isShowEntityActive"
        :entityData="entityData"
        :nrcs="nrcs"
        :nrcs_lbls="nrcs_lbls2"
        :grupo_sel="grupo_sel"
        @on-backward="createEntityOrClose"
        >
    </evaluacion>
</template>
<script setup>
import useAppConfig from '@core/@app-config/useAppConfig';
import { ref } from 'vue';
import evaluacion from './grupos-components/evaluacion.vue';
import gruposInterfaz from './grupos-components/gruposInterfaz.vue';

  let { overlay } = useAppConfig()
  let $http = inject('http')
  const setOverlay = (value) => {
    overlay.value = value
  }
  const userData = JSON.parse(localStorage.getItem('userData')) || {}

  let isShowEntityActive = ref(false)
  let isShowEvaluacion = ref(false)
  
  let entityData = ref({})
  let nrcs = ref([])
  let nrcs_lbls = ref([])
  let nrcs_lbls2 = ref([])
  let grupo_sel = ref(null)
  let fecha_hora = ref(null)
  let periodo = ref(null)

  function initialize() {
    //
      setOverlay(true)
      $http.post('system/curtermcode')
      .then(per => {
        periodo = per.data
        entityData.periodo = per.data
      })

      if(userData.role_id == 2){
        $http.post('/grupos/list-nrcs-docente')
        .then(response => {
        //console.log(response.data.actividad)
            fecha_hora.value = response.data.actividad
            nrcs.value = response.data.data
            response.data.data.forEach((element) => 
                nrcs_lbls[element['NRC']] = element
            );
            nrcs_lbls2.value = nrcs_lbls
            setOverlay(false)
        })
      }else if(userData.role_id == 3){
        $http.post('/grupos/list-nrcs-jurado')
        .then(response => {
            nrcs.value = response.data.data
            response.data.data.forEach((element) => 
                nrcs_lbls[element['NRC']] = element
            );
            nrcs_lbls2.value = nrcs_lbls
            setOverlay(false)
        })
      }

  }

  function createEntityOrClose(){
    //blankEntityData()
    //isShowEntityActive.value = !isShowEntityActive.value
    //isShowEvaluacion.value = !isShowEvaluacion.value

    isShowEntityActive.value = false
    isShowEvaluacion.value = false
  }
  function changeNrc(){ 
    entityData.value.horario = '' + nrcs_lbls[entityData.value.nrc]['INICIO_CLASE'] + ' - ' + nrcs_lbls[entityData.value.nrc]['FIN_CLASE']
    entityData.value.mod_sede = nrcs_lbls[entityData.value.nrc]['MODALIDAD'] + ((nrcs_lbls[entityData.value.nrc]['SEDE']) ? (' - ' + nrcs_lbls[entityData.value.nrc]['SEDE']) : '')
    entityData.value.curso = nrcs_lbls[entityData.value.nrc]['CURSO']
    entityData.value.cod_curso = nrcs_lbls[entityData.value.nrc]['COD_CURSO']
    //console.log(nrcs)
    //console.log(nrcs_lbls2)
  }
  function irEval(item){
    isShowEvaluacion.value=true
    isShowEntityActive.value=false
    grupo_sel.value = item.columns.GRUPO_SOLO
  }
    onBeforeMount(() => {
        initialize() 
    })
</script>