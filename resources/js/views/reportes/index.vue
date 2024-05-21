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
                                    :rules="[validators.requiredObject]"  
                                ></v-autocomplete>
                        </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            label="ID"
                            v-model="entityData.spriden_id"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                        <v-text-field
                            label="Apellidos"
                            v-model="entityData.last_name"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>
                    </v-col>
                    
                    <v-col cols="12" md="4">
                        <v-text-field
                            label="Nombres"
                            v-model="entityData.first_name"
                            variant="outlined"
                            density="compact"
                            hide-details="auto"
                        ></v-text-field>
                    </v-col>
                </v-row>
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
  let entityData = ref({periodo: '202320'})
  let nrcs = ref([])
  let nrcs_lbls = ref([])
  let grupos = ref([])
  
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
            
            entityData.value.horario = '' + nrcs_lbls[entityData.value.nrc]['INICIO_CLASE'] + ' - a ' + nrcs_lbls[entityData.value.nrc]['FIN_CLASE']
            entityData.value.mod_sede = nrcs_lbls[entityData.value.nrc]['MODALIDAD'] + ' - ' + nrcs_lbls[entityData.value.nrc]['SEDE']
            entityData.value.curso = nrcs_lbls[entityData.value.nrc]['CURSO']
            entityData.value.cod_curso = nrcs_lbls[entityData.value.nrc]['COD_CURSO']
        
            response.data.data.push({GRUPO_SOLO: '', GRUPO: 'Elegir grupo'});
            entityData.value.grupo = ''
            grupos.value = response.data.data
            // cambia labels
            //console.log(this.entityData.nrc)
            
            setOverlay(false)
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