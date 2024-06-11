<template>
    <v-card>
        <v-card-title class="my-2">
            <h4>              <v-icon
                left
                dark
                icon="mdi-file-pdf"
                color="error"
              >
              </v-icon> <strong>Reporte de resultados</strong></h4>
        </v-card-title>
        <v-divider ></v-divider>
        <v-card-text v-if="!isShowEntityActive" class="my-4">
            <v-form ref="form"
                v-model="valid"
                @submit="onSubmit" 
                @submit.prevent="validate">
                <v-row>
                    <v-col cols="12" md="6">
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
                        @update:modelValue="loadGroups" 
                        :rules="[validators.requiredObject]"  
                    ></v-autocomplete>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-autocomplete
                                label="Grupos"
                                v-model="entityData.grupo"
                                variant="outlined"
                                density="compact"
                                :items="grupos"
                                item-title="GRUPO"
                                item-value="GRUPO_SOLO"
                                :menu-props="{ offsetY: true }"
                                hide-details="auto"                               
                                @update:modelValue="listarAlumnos"
                            ></v-autocomplete>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="4">
                        <v-autocomplete
                            label="DNI'S"
                            v-model="entityData.spriden_id"
                            variant="outlined"
                            density="compact"
                            :items="dnis"
                            item-title="dni"
                            item-value="dni"
                            :menu-props="{ offsetY: true }"
                            hide-details="auto"
                            :rules="[validators.requiredObject]"     
                        ></v-autocomplete>
                        <!-- 
                        <v-text-field
                            label="ID"
                            v-model="entityData.spriden_id"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>-->
                    </v-col>
                    
                    <v-col cols="12" md="4">
                        <v-autocomplete
                            label="Apellidos"
                            v-model="entityData.last_name"
                            variant="outlined"
                            density="compact"
                            :items="apellidos"
                            item-title="apellido"
                            item-value="apellido"
                            :menu-props="{ offsetY: true }"
                            hide-details="auto"
                            :rules="[validators.requiredObject]"     
                        ></v-autocomplete>
                        <!--
                        <v-text-field
                            label="Apellidos"
                            v-model="entityData.last_name"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>-->
                    </v-col>
                    
                    <v-col cols="12" md="4">
                        <v-autocomplete
                            label="Nombres"
                            v-model="entityData.first_name"
                            variant="outlined"
                            density="compact"
                            :items="nombres"
                            item-title="nombre"
                            item-value="nombre"
                            :menu-props="{ offsetY: true }"
                            hide-details="auto"
                            :rules="[validators.requiredObject]"     
                        ></v-autocomplete>
                        <!--
                        <v-text-field
                            label="Nombres"
                            v-model="entityData.first_name"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>-->
                    </v-col>
                </v-row>
                <v-divider class="mb-5 mt-5" ></v-divider>
                    <v-row>
                        <v-col cols="12" md="5">
                            NOMBRE DEL CURSO: <br>
                            <strong>{{entityData.curso}}</strong>
                        </v-col>
                        <v-divider vertical></v-divider>
                        <v-col cols="12" md="1">
                            COD CURSO: <br>
                            <strong>{{entityData.cod_curso}}</strong>
                        </v-col>
                        <v-divider vertical></v-divider>
                        <v-col cols="12" md="2">
                            HORARIOS: <br>
                            <strong>{{entityData.horario}}</strong>
                        </v-col>
                        <v-divider vertical></v-divider>
                        <v-col cols="12" md="3">
                            MODALIDAD: <br>
                            <strong>{{entityData.mod_sede}}</strong>
                        </v-col>    
                    </v-row>
                    <v-divider class="mb-5 mt-5" ></v-divider>
                <v-row>
                    <v-col
                        cols="12"
                        md="9"
                    >
                    </v-col>

                    <v-col
                        cols="12"
                        md="3"
                    >
                    <v-btn  block color="success" type="submit" >
                        <v-icon
                            left
                            dark
                            icon="mdi-magnify"
                        >
                        </v-icon>
                            Consultar
                    </v-btn>
                    
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="12">
                    <iframe id="frameResult" src="/evaluacion/preview-pdf" frameborder="1" style="width: 100%; height: 450vh;"></iframe>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script setup>
import useAppConfig from '@core/@app-config/useAppConfig';
import { requiredObject } from '@core/utils/validation.js';
import { ref } from 'vue';

  let { overlay } = useAppConfig()
  const $http = inject('http')
  const setOverlay = (value) => {
    overlay.value = value
  }
  let isShowEntityActive = ref(false)

  const valid = ref(false)
  const form = ref(null)

  const validate = () => {
   form.value.validate()
  }
  
  let  validators = { requiredObject}
  let entityData = ref({periodo: '202310', spriden_id:'TODOS', first_name:'TODOS', last_name:'TODOS'})
  let nrcs = ref([])
  let nrcs_lbls = ref([])
  let grupos = ref([])

  let dnis = ref([])
  let nombres = ref([])
  let apellidos = ref([])
  
  function initialize() {
      setOverlay(true)
      $http.post('/grupos/list-nrcs-docente')
        .then(response => {
            nrcs.value = response.data.data
            response.data.data.forEach((element) => 
                nrcs_lbls[element['NRC']] = element
            );
            setOverlay(false)
            //setOverlay(false)
        })
  }
  function loadGroups(){
    setOverlay(true)
    $http.post('/grupos/list-grupos', entityData.value)
        .then(response => {
            response.data.data.push({GRUPO_SOLO: '', GRUPO: 'TODOS'});
            entityData.value.horario = '' + nrcs_lbls[entityData.value.nrc]['INICIO_CLASE'] + ' - ' + nrcs_lbls[entityData.value.nrc]['FIN_CLASE']
            entityData.value.mod_sede = nrcs_lbls[entityData.value.nrc]['MODALIDAD'] + ((nrcs_lbls[entityData.value.nrc]['SEDE']) ? (' - ' + nrcs_lbls[entityData.value.nrc]['SEDE']) : '')
            entityData.value.curso = nrcs_lbls[entityData.value.nrc]['CURSO']
            entityData.value.cod_curso = nrcs_lbls[entityData.value.nrc]['COD_CURSO']
        
            
            entityData.value.grupo = ''
            grupos.value = response.data.data
            // cambia labels
            //console.log(this.entityData.nrc)
            listarAlumnos()
            setOverlay(false)
        })

    }
  function listarAlumnos(){
        //entityData.value.grupo = null
        entityData.value.dni = null
        setOverlay(true)
        $http.post('grupos/list-alumnos', entityData.value)
            .then(response => {
                //console.log(response.data.dnis)
                setOverlay(false)
                dnis.value = response.data.dnis
                apellidos.value = response.data.apellidos
                nombres.value = response.data.nombres
                tableKey++
            })
            .catch(error => {
                //isLoading.value = false
            })
    }
  function onSubmit(){
    //alert()
    if (valid.value) {
        setOverlay(true)
        $http.post('evaluacion/view-pdf', entityData.value)
            .then(response => {
                document.getElementById('frameResult').src = '/notas_alumnos/resultado_de_notas.pdf';
                this.overlay = false
                setOverlay(false)
                
            }).catch(err =>{
                setOverlay(false)
            });

    } 
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