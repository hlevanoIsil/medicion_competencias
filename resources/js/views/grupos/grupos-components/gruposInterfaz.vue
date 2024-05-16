<template>
    <v-row>
        <v-col cols="12" md="12">
            <v-card >
                <v-card-text>
                    <v-row >
                        <v-col cols="12" md="9">
                            <h3 >DATOS DEL NRC</h3>
                        </v-col>
                        <v-col cols="12" md="3" class="text-right">
                            <v-btn block color="info" @click="regresar" variant="outlined"> 
                                <v-icon
                                    left
                                    dark
                                    icon="mdi-arrow-left-bold"
                                >
                                </v-icon>
                                Regresar al inicio</v-btn>

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
                            NRC: <br>
                            <strong>{{entityData.nrc}}</strong>
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

                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" md="7">
            <v-card >
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <h3 >GRUPOS</h3>
                        </v-col>
                        <v-col cols="12" md="6" class="text-right">
                   
                            <v-chip color="#d9f1fb" variant="flat" size="small" class="ml-2 rounded-sm text-primary" >
                                {{totalItems1}} grupos formados
                            </v-chip>
                        </v-col>
                    </v-row>
                    <v-divider class="mb-3 mt-5" ></v-divider>
                    <v-data-table-server
                        class="mb-4 mt-0"
                        :headers="headers"
                        :items="items"
                        density="compact"
                        no-data-text="No hay datos para mostrar"
                        loading-text="Cargando..."
                    >
                        <template #[`item.GRUPO_SOLO`]="{ item }">
                            <v-tooltip location="top" text="Editar" >
                                <template v-slot:activator="{ props }" >
                                <v-avatar size="28" color="success" v-bind="props" class="mr-1">
                                    <v-icon size="15"  
                                    @click="ventanaGrupo(2, item.value.GRUPO_SOLO)"
                                    icon="mdi-edit"></v-icon> 
                                </v-avatar>
                                </template>
                            </v-tooltip> 
                            
                            <v-tooltip location="top" text="Eliminar" >
                                <template v-slot:activator="{ props }">
                                <v-avatar size="28" color="error" v-bind="props" class="mr-1">
                                    <v-icon size="15"  
                                    @click="showMessageDelete2(item.value.GRUPO_SOLO)"
                                    icon="mdi-delete"></v-icon>
                                </v-avatar>
                                </template>
                            </v-tooltip> 
                            <v-tooltip location="top" text="Evaluar" >
                                <template v-slot:activator="{ props }">
                                <v-avatar size="28" color="warning" v-bind="props">
                                    <v-icon size="15"  
                                    @click="showDocForm(item)"
                                    icon="mdi-check-bold"></v-icon>
                                </v-avatar>
                                </template>
                            </v-tooltip>
                        </template>

                        <template #[`item.GRUPO`]="{item}">
                            <v-chip
                            small
                            density="compact"
                            :color="colorGrupos[item.value.GRUPO_SOLO]"
                            class="v-chip-light-bg"
                            >
                            {{ item.value.GRUPO }}
                            </v-chip>
                        </template>
                        <template #[`item.NOMBRES`]="{item}">
                            <ol >
                                <li v-for="(item, index) in splitJoin(item.value.NOMBRES)">
                                    <span class="text-primary text-xs">{{ item }}</span>
                                </li>
                            </ol>

                        </template>

                        <template v-slot:loading>
                        <div class="text-center py-4">
                            <p>Obteniendo Data ...</p>
                        </div>
                        </template>
                        <template v-slot:bottom></template>
                    </v-data-table-server>
                    <v-row >
                        <v-col cols="12" md="4">
                            <v-btn block color="primary" @click="ventanaGrupo(1)"> 
                                <v-icon
                                    left
                                    dark
                                    icon="mdi-plus"
                                >
                                </v-icon>
                                Agregar grupo</v-btn>

                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" md="5">
            <v-card >
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <h3 >ESTUDIANTES</h3>
                        </v-col>
                        <v-col cols="12" md="6" class="text-right">
                    
                            <v-chip color="#d9f1fb" variant="flat" size="small" class="ml-2 rounded-sm text-primary" >
                                {{totalItems2}} estudiantes
                            </v-chip>
                        </v-col>
                    </v-row>
                    <v-divider class="mb-3 mt-5" ></v-divider>
                    <v-row>
                        <v-col>
                            <v-autocomplete
                                    label="Ordenar estudiantes - seleccionar"
                                    outlined
                                    density="compact"
                                    :items="[]"
                                    :menu-props="{ offsetY: true }"
                                    hide-details="auto"
                                ></v-autocomplete>
                            <v-data-table-server
                                class="mb-4 mt-2"
                                :headers="headers2"
                                :items="items2"
                                density="compact"
                                no-data-text="No hay datos para mostrar"
                                loading-text="Cargando..."
                            >
                                <template #[`item.GRUPO`]="{item}">
                                    <v-chip
                                    small
                                    density="compact"
                                    :color="colorGrupos[item.value.GRUPO_SOLO]"
                                    class="v-chip-light-bg"
                                    >
                                    {{ item.value.GRUPO }}
                                    </v-chip>
                                </template>
                                <template #[`item.DNI`]="{item}">
                                    <slot name="cod">
                                        <strong>{{item.value.DNI}}</strong>
                                    </slot>
                                </template>
                            <template v-slot:bottom></template>
                            </v-data-table-server>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <div class="pa-4 text-center">
    <v-dialog
      v-model="dialog"
      max-width="600"
    >

      <v-card
        prepend-icon="mdi-account"
        v-bind:title="'Grupo' + grupoActual"
      >
        <v-card-text>
            <v-form ref="form"
                v-model="valid"
                @submit="agregarEstudiante" 
                @submit.prevent="validate">
            <v-row dense>
                <v-col
                cols="12"
                md="9"
                sm="6"
                >
                <v-autocomplete
                    label="Agregar Estudiante" 
                    v-model="entityData.pidm"
                    :items="items2"
                    item-title="NOMBRES"
                    item-value="PIDM"
                    auto-select-first
                    density="compact"
                    :rules="[validators.requiredObject]"
                ></v-autocomplete>
                </v-col>
                <v-col
                cols="12"
                md="3"
                sm="6">
                    <v-btn block color="primary" type="submit" @click="dialog = true"> 
                        <v-icon
                            left
                            dark
                            icon="mdi-plus"
                        >
                        </v-icon>
                        Agregar</v-btn>
                </v-col>
            </v-row>
          </v-form>
          <v-data-table-server
                class="mb-2 mt-2"
                :headers="headersPopup"
                :items="itemsPopup"
                density="compact"
                no-data-text="No hay datos para mostrar"
                loading-text="Cargando..."
            >
            
            <template #[`item.PIDM`]="{ item }">
                <v-tooltip location="top" text="Eliminar" >
                    <template v-slot:activator="{ props }">
                    <v-avatar size="28" color="error" v-bind="props" class="mr-1">
                        <v-icon size="15"  
                        @click="showMessageDelete(item.value.PIDM)"
                        icon="mdi-delete"></v-icon>
                    </v-avatar>
                    </template>
                </v-tooltip> 
            </template>
            <template v-slot:bottom></template>
            </v-data-table-server>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            text="Cerrar"
            variant="plain"
            @click="cerrarModal"
          ></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
    <UCDialogQuestion
        :visible="activeMsgDelete"
        :message="msgDelete"
        title="Eliminar"
        @cancel="closeMsgDelete"
        @ok="eliminarIndividual"
        ></UCDialogQuestion>
    <UCDialogQuestion
        :visible="activeMsgDelete2"
        :message="msgDelete2"
        title="Eliminar"
        @cancel="closeMsgDelete2"
        @ok="eliminarGrupo"
    ></UCDialogQuestion>
</template>
<script>
import UCDialogQuestion from '@/components/UCDialogQuestion.vue';
import useAppConfig from '@core/@app-config/useAppConfig';
import { required, requiredObject } from '@core/utils/validation.js';
import { ref } from 'vue';
export default {
    components: {
        UCDialogQuestion
    },
    data: () => ({
      dialog: false,
    }),
    props: {
        entityData: {
            type: Object,
            required: true,
        },
    },
    setup(){
        var { overlay } = useAppConfig()
        const valid = ref(false)
        const form = ref(null)
        const validate = () => {
            form.value.validate()
        }
        const colorGrupos = ref({
            1: 'info',
            2: 'success',
            3: 'error',
            4: 'warning',
            5: 'primary',
        })
        let activeMsgDelete = ref(false)
        let activeMsgDelete2 = ref(false)
        let headers = [
            { title: 'GRUPO', key: 'GRUPO', width:120, filterable: true , sortable: false},
            { title: 'INTEGRANTES', key: 'NOMBRES', filterable: true, sortable: false},
            { title: '', key: 'GRUPO_SOLO', width:150,  sortable: false },        
        ]
        let items = ref([])
        let totalItems1 = ref(0)

        let headers2 = [
            { title: 'GRUPO', key: 'GRUPO', filterable: true , width: 85, sortable: false},
            { title: 'NOMBRES', key: 'NOMBRES', filterable: true, sortable: false},
            { title: 'COD', key: 'DNI', filterable: true, sortable: false},
        ]
        let items2 = ref([])
        let totalItems2 = ref(0)

        let headersPopup = [
            { title: 'NOMBRES', key: 'NOMBRES', filterable: true , width: 320, sortable: false},
            { title: 'DNI', key: 'DNI', filterable: true, sortable: false},
            { title: '', key: 'PIDM', filterable: true, sortable: false},
        ]
        let itemsPopup = ref([])
        let grupoActual = ref()

        let pidmDrop = ref()
        let itemGrupoDrop = ref()
        
        return {
            overlay,
            valid,
            form,
            validate,
            validators: { required, requiredObject },
            headers,
            items,
            headers2,
            items2,
            totalItems1,
            totalItems2,
            headersPopup,
            grupoActual,
            itemsPopup,
            colorGrupos,
            activeMsgDelete,
            msgDelete: '',
            activeMsgDelete2,
            msgDelete2: '',
            pidmDrop,
            itemGrupoDrop
        }
    },
    beforeMount(){
        this.initialize() 
    },
    methods: {
        initialize() {
            //console.log(this.entityData.mod_sede)
            this.overlay = true
            this.listarGrupos()
            this.listarAlumnos()
        },
        listarGrupos(){

            this.$http.post('grupos/list-grupos', this.entityData)
                .then(response => {
                    //console.log(response.data.data)
                    
                    this.items = response.data.data
                    this.totalItems1 = Number(response.data.rows) 

                    tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        listarAlumnos(){
            this.entityData.grupo = null
            this.$http.post('grupos/list-alumnos', this.entityData)
                .then(response => {
                    this.items2 = response.data.data
                    this.totalItems2 = Number(response.data.rows) 
                    this.overlay = false

                    tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        listarAlumnosGrupo(){
            this.overlay = true
            this.entityData.grupo = this.grupoActual
            this.$http.post('grupos/list-alumnos', this.entityData)
                .then(response => {
                 
                    
                    this.itemsPopup = response.data.data
                    //this.totalItems2 = Number(response.data.rows) 
                    this.overlay = false

                    tableKey++
                })
                .catch(error => {
                    //isLoading.value = false
                })
        },
        splitJoin(theText){
            return theText.split(',');
        },
        ventanaGrupo(fModo, ngrupo = null){
            if(fModo == 1){//nuevo grupo
                this.dialog = true; 
                this.grupoActual = this.totalItems1 + 1
                this.listarAlumnosGrupo()
            }else{
                this.dialog = true; 
                this.grupoActual = ngrupo
                this.listarAlumnosGrupo()
            }
        },
        agregarEstudiante(){
            if (this.valid) {
                //alert(32323)
                this.overlay = true
                this.entityData.flag = 1
                this.entityData.grupo = this.grupoActual
                this.$http.post('grupos/agregar-alumno', this.entityData)
                    .then(response => {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        this.overlay = false
                        //this.$forceUpdate();
                        this.listarAlumnosGrupo()
                        this.entityData.pidm = null
                        this.loadAlert(response.data.mensaje, 'success', 'Éxito')
                        
                    }).catch(err =>{
                        this.overlay = false
                        if(err.response.status == 422) {                  
                            this.loadAlert('Elegir al menos un program schedule');
                            this.$forceUpdate();
                        } else {
                            this.loadAlert(err.response.data.message);
                        }

                    });
    
            } 
        },
        eliminarIndividual(){
            this.overlay = true
            this.entityData.pidmDrop = this.pidmDrop
            this.$http.post('/grupos/eliminar-alumno-grupo', this.entityData)
                .then(() => {
                    this.overlay = false
                    this.listarAlumnosGrupo()
                    this.entityData.pidm = null

                    //this.tableKey +=1
                    this.closeMsgDelete()
                    this.loadAlert('Alumno eliminado correctamente', 'success', 'Éxito')
                
                })
                .catch(error => {  
                    this.overlay = false
                    this.loadAlert(error.response.data.message)
                })
        },
        eliminarGrupo(){
            this.overlay = true
            this.entityData.itemGrupoDrop = this.itemGrupoDrop

            this.$http.post('/grupos/eliminar-grupo', this.entityData)
                .then(() => {
                    this.overlay = false
                    this.listarGrupos()
                    this.listarAlumnos()
                    this.listarAlumnosGrupo()
                    this.entityData.pidm = null

                    //this.tableKey +=1
                    this.closeMsgDelete2()
                    this.loadAlert('Grupo eliminado correctamente', 'success', 'Éxito')
                
                })
                .catch(error => {  
                    this.overlay = false
                    this.loadAlert(error.response.data.message)
                })
        },
        regresar(){
            this.$emit('on-backward')
        },
        showMessageDelete(item){
            this.pidmDrop = item
            this.msgDelete = "¿Eliminar Usuario Alumno del grupo?"
            this.activeMsgDelete= true
        },
        closeMsgDelete(){
            //this.blankEntityData()
            this.activeMsgDelete = false
        },
        showMessageDelete2(item){
            this.itemGrupoDrop = item
            this.msgDelete2 = "¿Eliminar el grupo del NRC?"
            this.activeMsgDelete2= true
        },
        closeMsgDelete2(){
            //this.blankEntityData()
            this.activeMsgDelete2 = false
        },
        cerrarModal(){
            this.overlay = true
            this.dialog = false
            this.listarGrupos()
            this.listarAlumnos()
        },
        loadAlert(text, type="error", title="Advertencia"){
        //this.$store.commit('appConfig/TOGGLE_SNACKBAR', {show: true, text: text, color: type})
            this.$swal.fire({
                title: title,
                text: text,
                icon: type,
                confirmButtonText: 'OK',
                }).then((result) => {
                if (result.isConfirmed) {
                    //document.querySelectorAll("#btnRegresar").forEach(el=>el.click())
                    //document.querySelectorAll("#btnConsultar").forEach(el=>el.click())
                    
                }
            })
        },
        
    }
}
</script>