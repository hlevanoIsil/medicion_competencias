<template>
    <v-snackbar
        color="success"
        rounded="pill"
        :timeout="3000"
        v-model="showSnackbar"
        location="right bottom"
        elevation="24"
        
    >
        <p class="text-center mb-0 pb-0">EVALUACIÓN REGISTRADA CORRECTAMENTE</p>
    </v-snackbar>
    <v-snackbar
        color="success"
        rounded="pill"
        :timeout="3000"
        v-model="showSnackbarCom1"
        location="right bottom"
        elevation="24"
    >
        <p class="text-center mb-0 pb-0">COMENTARIOS REGISTRADOS CORRECTAMENTE</p>
    </v-snackbar>
    
    <v-row >
        <v-col cols="12" md="10" class="text-right"></v-col>
        <v-col cols="12" md="2" class="text-right">
                            <v-btn block color="info" @click="regresar" variant="outlined" > 
                                <v-icon
                                    left
                                    dark
                                    icon="mdi-arrow-left-bold"
                                >
                                </v-icon>
                                Regresar al inicio</v-btn>

                        </v-col>
    </v-row>
    <v-row class="mt-0">
        <v-col cols="12" md="12">
            <v-card >
                <v-card-text>
                    <v-row >
                        <v-col cols="12" md="6">
                            <h3>RÚBRICA</h3>
                        </v-col>
                        <v-col cols="12" md="6" class="text-right" >
                            <v-btn title="Expandir / Contraer" color="warning" size="x-small"  > 
                                <v-icon size="20"
                                    left
                                    dark
                                    icon="mdi-eye"
                                    @click="showRubrica = !showRubrica"
                                >
                                </v-icon></v-btn>
                        </v-col>
                    </v-row>
                    <v-divider class="mt-3"></v-divider>
                    <v-row>
                        <v-col>
                            <v-expand-transition>
                                <div v-show="showRubrica">
                                    <v-table class="mt-0 pt-6">
                                        <template v-slot:default>
                                        <thead style="background-color: #f9fafc;">
                                            <tr>
                                                <th class="text-center font-weight-bold">
                                                    CRITERIOS
                                                </th>
                                                <th class="text-center font-weight-bold">
                                                    EXCELENTE
                                                </th>
                                                <th class="text-center font-weight-bold">
                                                    BUENO
                                                </th>
                                                <th class="text-center font-weight-bold">
                                                    EN PROCESO
                                                </th>
                                                <th class="text-center font-weight-bold">
                                                    DEFICIENTE
                                                </th>
                                                <th class="text-center font-weight-bold">
                                                    INSATISFACTORIO
                                                </th>
                                            </tr>
                                        </thead>
                                       
                                        <tbody>
                                            <tr
                                                v-for="item in items"
                                                :key="item.key"                                            
                                            >
                                                <td :class="[item.CRITERIO!='TOTAL' ? '' : 'tdBlue']" width="180" >
                                                     <strong :class="[item.CRITERIO!='TOTAL' ? '' : 'text-white']">{{ item.CRITERIO }}: </strong>
                                                     
                                                    <span v-if="item.CRITERIO!='TOTAL'" class="text-primary text-h6">
                                                        <br> {{ item.NOM_CRITERIO }}
                                                    </span>
                                                </td>
                                                <td :class="[item.CRITERIO!='TOTAL' ? 'pt-2' : 'tdBlue text-white']">
                                                    {{ item.DESC_EXC }}  
                                                    <p class="text-center mt-2"><strong>{{ item.PUN_EXC }}</strong></p>
                                                </td>
                                                <td :class="[item.CRITERIO!='TOTAL' ? 'pt-2' : 'tdBlue text-white']">
                                                    {{ item.DESC_BUE }}
                                                    <p class="text-center mt-2"><strong>{{ item.PUN_BUE }}</strong></p>
                                                </td>
                                                <td :class="[item.CRITERIO!='TOTAL' ? 'pt-2' : 'tdBlue text-white']">
                                                    {{ item.DESC_ENPR }}
                                                    <p class="text-center mt-2"><strong>{{ item.PUN_ENPR }}</strong></p>
                                                </td>
                                                <td :class="[item.CRITERIO!='TOTAL' ? 'pt-2' : 'tdBlue text-white']">
                                                    {{ item.DESC_DEFI }}
                                                    <p class="text-center mt-2"><strong>{{ item.PUN_DEFI }}</strong></p>
                                                </td>
                                                <td :class="[item.CRITERIO!='TOTAL' ? 'pt-2' : 'tdBlue text-white']">
                                                    {{ item.DESC_INSAT }}
                                                    <p class="text-center mt-2"><strong>{{ item.PUN_INSAT }}</strong></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                    </v-table>
                            </div>
                            </v-expand-transition>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-row >
        <v-col cols="12" md="12">
            <v-card >
                <v-card-text>
                    <v-row >
                        <v-col cols="12" md="1">
                            <h3>EVALUAR</h3>
                        </v-col>
                        <v-col cols="12" md="4" class="text-right" >
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
                                ></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="3">
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
                                    @update:modelValue="listarAlumnosGrupo" 
                                ></v-autocomplete>
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

                    <v-table class="mt-3 pt-6">
                        <template v-slot:default>
                        <thead style="background-color: #f9fafc;">
                            <tr>
                                <th class="text-left">
                                    NOMBRES
                                </th>
                                <th v-for="(itemC, indexC) in criterios">
                                    CRITERIO {{indexC}}
                                    <v-menu class="ml-3">
                                        <template v-slot:activator="{ props }">
                                            <v-btn color="none" icon="mdi-plus " size="30" v-bind="props"></v-btn>
                                        </template>
                                        <v-list class="d-inline-flex listaCal pr-3 pl-3">                                        
                                            <v-list-item
                                                v-for="(itemv, i) in itemC"
                                                :key="i"
                                                class="mr-1 ml-1 pr-1 pl-1"
                                            >
                                                <v-list-sub-header v-if="i === 'subheader'" > <strong>{{  itemv }}</strong> </v-list-sub-header>
                                                <v-list-item-title class="listaItem" v-if="i !== 'subheader'"  @click="calificarGrupal(indexC, itemv.COD);" v-bind:title="itemv.DESCR">{{ itemv.PUNT }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </th>
                                <th class="text-center">
                                    COMENTARIO
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="item in itemsNot"
                            :key="item.key"
                            >
                            <td class="text-primary"> <strong>{{ item.NOMBRES }}</strong></td>
                            <td class="text-center" v-for="(itemC, indexC) in criterios">
                                <v-menu v-if="item['notas'][indexC] == null" >
                                    <template v-slot:activator="{ props }">
                                        <v-btn color="secondary" icon="mdi-plus-circle-outline " size="30" v-bind="props"></v-btn>
                                    </template>
                                    <v-list class="d-inline-flex listaCal pr-3 pl-3">                                        
                                        <v-list-item
                                            v-for="(itemv, i) in itemC"
                                            :key="i"
                                            class="mr-1 ml-1 pr-1 pl-1"
                                        >
                                            <v-list-sub-header v-if="i === 'subheader'" > <strong>{{  itemv }}</strong> </v-list-sub-header>
                                            <v-list-item-title class="listaItem" v-if="i !== 'subheader'" @click="calificarIndividual(item.PIDM, indexC, itemv.COD); item['notas'][indexC] = itemv.PUNT"><span v-bind:title="itemv.DESCR" >{{ itemv.PUNT }}</span></v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>

                                <v-menu v-if="item['notas'][indexC] != null" >
                                    <template v-slot:activator="{ props }">
                                        <v-btn color="primary" icon="mdi-plus-circle-outline " size="30" v-bind="props"><strong>{{ item['notas'][indexC] }}</strong></v-btn>
                                    </template>
                                    <v-list class="d-inline-flex listaCal pr-3 pl-3">                                        
                                        <v-list-item
                                            v-for="(itemv, i) in itemC"
                                            :key="i"
                                            class="mr-1 ml-1 pr-1 pl-1"
                                        >
                                            <v-list-sub-header v-if="i === 'subheader'" > <strong>{{  itemv }}</strong> </v-list-sub-header>
                                            <v-list-item-title class="listaItem" v-if="i !== 'subheader'" @click="calificarIndividual(item.PIDM, indexC, itemv.COD); item['notas'][indexC] = itemv.PUNT"><span v-bind:title="itemv.DESCR" >{{ itemv.PUNT }}</span></v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-center">
                                    <v-textarea
                                        label="Comentarios"
                                        rows="2"
                                        variant="outlined"
                                        shaped
                                        class="mb-1 mt-1"
                                        title="Presione la tecla TAB para guardar el comentario"
                                        v-bind:model-value="item['comments']"
                                        @blur="updateCommentInd($event, item.PIDM)"
                                    ></v-textarea>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                        </template>
                    </v-table>

                    <v-divider></v-divider>
                    <v-row class="mt-2 mb-1">
                        <v-col cols="12" md="12" >
                            <v-btn size="small" color="warning"  @click="dialog = true"> 
                                        <v-icon
                                            left
                                            dark
                                            icon="mdi-comment"
                                        >
                                        </v-icon>&nbsp;Añadir comentario al grupo</v-btn>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row class="mt-2">
                        <v-col cols="12" md="12" class="text-right">
                            <!--
                            <v-btn color="primary" @click="isShowEntityActive=true" class="mr-2" > 
                                        <v-icon
                                            left
                                            dark
                                            icon="mdi-floppy-disc"
                                        >
                                        </v-icon>&nbsp;Guardar</v-btn>-->
                            <v-btn color="error" @click="isShowEvaluacion=false" > 
                                <v-icon
                                    left
                                    dark
                                    icon="mdi-arrow-left-top-bold"
                                >
                                </v-icon>&nbsp;Finalizar</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-dialog
      v-model="dialog"
      max-width="600"
    >
    <v-form ref="form"
                v-model="valid"
                @submit="comentarioGrupal" 
                @submit.prevent="validate">
      <v-card
        prepend-icon="mdi-account"
        v-bind:title="'Comentarios del grupo ' + entityData.grupo"
      >
        <v-card-text>
            
            <v-row dense>
                <v-col
                cols="12"
                md="12"
                >
                <v-textarea
                    label="Comentarios"
                    rows="6"
                    variant="outlined"
                    v-model="entityData.commentGrupal"
                    shaped
                    class="mb-1 mt-1"
                    :rules="[validators.required]"                    
                    v-bind:model-value="coment_grupal"
                ></v-textarea>
                </v-col>
                
            </v-row>
         
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            text="Cerrar"
            variant="plain"
            @click="cerrarModal"
          ></v-btn>
          <v-btn  color="primary" type="submit" > 
                        <v-icon
                            left
                            dark
                            icon="mdi-content-save"
                        >
                        </v-icon>
                        Guardar</v-btn>

        </v-card-actions>
      </v-card>
    </v-form>
    </v-dialog>
</template>
<script>
import UCDialogQuestion from '@/components/UCDialogQuestion.vue';
import useAppConfig from '@core/@app-config/useAppConfig';
import { required, requiredObject } from '@core/utils/validation.js';
export default {
    
    components: {
        UCDialogQuestion,
    },
    data: () => ({
      dialog: false,
    }),
    props: {
        entityData: {
            type: Object,
            required: true,
        },
        nrcs: {
            type: Array,
            required: true,
        },
        nrcs_lbls: {
            type: Array,
            required: true,
        }
    },
    setup(){
        var { overlay } = useAppConfig()
        let items = ref([])
        let grupos = ref([])
        let showRubrica = ref(false)
        let showSnackbar = ref(false)
        let showSnackbarCom1 = ref(false)
        const valid = ref(false)
        const form = ref(null)
        const validate = () => {
            form.value.validate()
        }
        
        let itemsCal= ref ([
            { type: 'subheader', title: 'Elige una calificación: ' },
            { title: '1' },
            { title: '2' },
            { title: '3' },
            { title: '4' },
        ])
        let headers = [
            { title: 'CRITERIOS', key: 'NOM_CRITERIO', width:120, filterable: true , sortable: false},
            { title: 'EXCELENTE', key: 'DESC_EXC', filterable: true, sortable: false},
            { title: 'BUENO', key: 'DESC_BUE', filterable: true, sortable: false},
            { title: 'EN PROCESO', key: 'DESC_ENPR', filterable: true, sortable: false},
            { title: 'DEFICIENTE', key: 'DESC_DEFI', filterable: true, sortable: false},
            { title: 'INSATISFACTORIO', key: 'DESC_INSAT', filterable: true, sortable: false}, 
        ]
        let itemsNot = ref([])
        let criterios = ref([])
        let coment_grupal = ref()
        let headersNot = [
            { title: 'NOMBRES', key: 'NOMBRES', width:120, filterable: true , width: 300, sortable: false},
            { title: 'CRITERIO 1', key: 'criterio_1', filterable: true, sortable: false},
            { title: 'CRITERIO 2', key: 'criterio_2', filterable: true, sortable: false},
            { title: 'CRITERIO 3', key: 'criterio_3', filterable: true, sortable: false},
            { title: 'CRITERIO 4', key: 'criterio_4', filterable: true, sortable: false},
            { title: 'CRITERIO 5', key: 'criterio_5', filterable: true, sortable: false}, 
            { title: 'COMENTARIO', key: 'comments', filterable: true, align:'center', sortable: false}, 
        ]
        return {
            overlay,
            items,
            headers,
            headersNot,
            itemsNot,
            showRubrica,
            showSnackbar,
            showSnackbarCom1,
            grupos,
            itemsCal,
            criterios,
            valid,
            form,
            validate,
            validators: { required, requiredObject },
            coment_grupal
        }
    },
    beforeMount(){
        //console.log(this.nrcs_lbls)

        this.initialize() 
    },
    methods:{
        initialize() {
            this.overlay = true
            //console.log(this.entityData.cod_curso)
            this.cargarRubricas()
            //this.listarAlumnosGrupo()
            this.loadGroups()
            //console.log(this.itemsCal)
        },
        rowClick(item, row) {
           // console.log('row clicked')
        },
        cargarRubricas(){
            this.$http.post('evaluacion/list-rubricas-x-curso', this.entityData)
                .then(response => {
                    //this.grupos = []
                    this.criterios = response.data.criterios
                    this.items = response.data.data
                    //console.log(response.data.data)
                    //this.items = response.data.data
                    //this.totalItems1 = Number(response.data.rows) 
                    this.overlay = false
                    tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        regresar(){
            this.$emit('on-backward')
        },
        loadGroups(){ 
            this.overlay = true
            this.$http.post('grupos/list-grupos', this.entityData)
                .then(response => {
                    //console.log(response.data.data) 
                    this.entityData.horario = '' + this.nrcs_lbls[this.entityData.nrc]['INICIO_CLASE'] + ' - a ' + this.nrcs_lbls[this.entityData.nrc]['FIN_CLASE']
                    this.entityData.mod_sede = this.nrcs_lbls[this.entityData.nrc]['MODALIDAD'] + ' - ' + this.nrcs_lbls[this.entityData.nrc]['SEDE']
                    this.entityData.curso = this.nrcs_lbls[this.entityData.nrc]['CURSO']
                    this.entityData.cod_curso = this.nrcs_lbls[this.entityData.nrc]['COD_CURSO']
                    this.cargarRubricas()
                    response.data.data.push({GRUPO_SOLO: '', GRUPO: 'Elegir grupo'});
                    this.entityData.grupo = ''
                    this.grupos = response.data.data
                    this.itemsNot = []

                    // cambia labels
                    //console.log(this.entityData.nrc)

                    this.overlay = false
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        listarAlumnosGrupo(){
            this.overlay = true 
            //this.entityData.grupo = 1
            this.$http.post('evaluacion/list-alumnos', this.entityData)
                .then(response => {        
                    //console.log(response.data.data)        
                    this.itemsNot = response.data.data 
                    this.coment_grupal = response.data.coment_grupal 
                    //console.log(this.itemsNot)
                    //this.totalItems2 = Number(response.data.rows) 
                    this.overlay = false

                    tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        calificarIndividual(pidm, numCalif, codNota){
            //alert(pidm)
            //alert(numCalif)
            //alert(nota)
            this.overlay = true
            this.entityData.pidm_alumno = pidm
            this.entityData.criterio = numCalif
            this.entityData.cod_nota = codNota
            this.$http.post('evaluacion/alumno-save-individual', this.entityData)
                .then(response => {  
                    this.showSnackbar = true    
                    this.overlay = false  
                    //console.log(response.data.data)        
                    //this.itemsNot = response.data.data 
                    //console.log(this.itemsNot)
                    //this.totalItems2 = Number(response.data.rows) 
                    //this.overlay = false

                    //tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        calificarGrupal(criterio, codNota){

            this.overlay = true
            this.entityData.criterio = criterio
            this.entityData.cod_nota = codNota
            this.$http.post('evaluacion/alumno-save-grupal', this.entityData)
                .then(response => {  
                    
                    this.listarAlumnosGrupo() 
                    this.showSnackbar = true   
                    //this.overlay = false  
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        updateCommentInd(e, pidm){
            this.overlay = true
            this.entityData.pidm_alumno = pidm
            this.entityData.comment = e.target.value
            
            this.$http.post('evaluacion/comentario-save-individual', this.entityData)
                .then(response => {  
                    this.showSnackbarCom1 = true    
                    this.overlay = false  
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        comentarioGrupal(){
            
            if (this.valid) {
                this.overlay = true  
                this.$http.post('evaluacion/comentario-save-grupal', this.entityData)
                    .then(response => {
                        this.showSnackbarCom1 = true    
                        this.overlay = false  
                        
                    }).catch(err =>{
                        this.overlay = false
                    });
    
            } 
        },
        cerrarModal(){
            this.dialog = false
        },

    }
}
</script>
<style lang="scss">
.bgAzul{
    background-color: #000FFF !important;
}
.bg-secondary{
    background-color: #d2d1d1 !important;
}
/*.v-data-table__td {
      background-color: #000FFF !important;
}*/
.listaCal{
    background-color: #003045 !important;
    color: #ffcc03 !important;
    border-radius: 5px;

}
.listaItem{
    background-color: #003045 !important;
    color: #FFFFFF !important;
    font-weight: bold;
    cursor: pointer;

}
.tdBlue{
    background-color: #44c7ff;
}
</style>