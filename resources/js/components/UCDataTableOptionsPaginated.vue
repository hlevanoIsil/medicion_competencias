
<template>
  <div>
    <v-card-text>
      <v-row>
        <v-col
          cols="12"
          md="3"
        >
        <span>Total de Registros: {{items.length}}</span>
          </v-col>
        <v-spacer></v-spacer>
        <v-col cols="12" md="3">
          <template>
            <slot name="btnaction">
            </slot>
          </template>
        </v-col>
        <v-col v-if="isTable"
              cols="12"
              md="2"
            >
              <v-btn block color="success" @click="exportData" outlined> 
              <v-icon
                left
                dark
                icon="mdi-file-esxcel"
              >
              </v-icon>
              Exportar </v-btn>
        </v-col>
        <v-col
          cols="12"
          md="4"
        >
          <template>
            <slot name="fieldSearch">
            </slot>
          </template>
        </v-col>
      </v-row>
    </v-card-text>

    <v-data-table-server
      class="pb-4"
      density="comfortable"
      :headers="headers"
      :items="items"
      :loading="isLoading"
      :page="page"
      :items-per-page="itemsPerPage"
      items-per-page-text="Items por Página"
      :search="search"
      no-data-text="No hay datos para mostrar"
      :items-length="itemTotal"
    >

      <template v-slot:loading>
        <div class="text-center py-4">
          <p>Obteniendo Data ...</p>
        </div>
      </template>

      <template #[`item.options`]="{ item }">
         

        <v-tooltip location="top" text="Ver">
          <template v-slot:activator="{ props }">
            <v-avatar v-bind="props"
                      size="28" 
                      color="info"
                      v-if="canSee">
                <v-icon size="20" @click="seeItem(item)" color="on-info"
                  icon="mdi-eye-outline">
                </v-icon>
            </v-avatar>
          </template>
        </v-tooltip>

        <v-tooltip location="top" text="Editar">
          <template v-slot:activator="{ props }">
            <v-avatar v-bind="props"
                      size="28"
                      color="success"
                      class="mx-1"
                      v-if="canEdit">
                <v-icon size="20" @click="editItem(item)"
                    icon="mdi-pencil-outline">
                </v-icon>
            </v-avatar>
          </template>
        </v-tooltip>

        <v-tooltip location="top" text="Borrar">
          <template v-slot:activator="{ props}">
            <v-avatar v-bind="props"
                      size="28" color="error"
                      v-if="canDelete">
                <v-icon size="20" @click="deleteItem(item)"
                    icon="mdi-delete-outline">
                </v-icon>
        </v-avatar>
          </template>
        </v-tooltip>
      </template>


      <template #[`item.nombres`]="{item}">
            <span>{{item.nombres || item.columns.nombres}}</span>
      </template>
      
      <template #[`item.created_at`]="{item}">
         {{ formatDate(item.created_at || item.columns.created_at) }}
      </template>

      <template #[`item.fecha`]="{item}">
         {{ formatDate(item.fecha || item.columns.fecha) }}
      </template>

      <template #[`item.updated_at`]="{item}">
         {{ formatDateTime(item.updated_at || item.columns.updated_at) }}
      </template>


    </v-data-table-server>
  </div>
</template>

<script>
  /*COMPONENTE GENERICO PARA MANTENEDORES "CON" PAGINADO*/
  /*TENER EN CUENTA QUE ESTE COMPONENTE ES USADO POR OTROS COMPONENTES*/
  /*LOS CAMBIOS DEBEN ASUMIR QUE OTRO COMPONENTES LO USARAN*/
import {Functions} from "@core/libs/lib.js"

export default {
  props: {
    items: {
      type: Array,
      default: []
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
    headers: {
      type: Array,
      required: true
    },
    canEdit: {
      type: Boolean,
      default: true
    },
    canDelete: {
      type: Boolean,
      default: true
    },
    canSee: {
      type: Boolean,
      default: false
    },
    sortBy: {
      type: String,
      default: "id"
    },
    isTable: {
      type: Boolean,
      default: true
    },
    showSelect: {
      type: Boolean,
      default: false
    },
    nameAction: {
      type: String,
      default: 'Guardar'
    },
    itemKey: {
      type: String,
      default: 'id'
    },
    search: {
      type: String,
      default: ''
    },
    itemsPage: {
      type: Number,
      default: 25
    },
  },
  setup(props) {
    const statusColor = {
      1: 'success',
      2: 'error',
    }
    const status = { //de reserva
      1: 'Reservado',
      2: 'Cancelado',
    }

    const enabledColor = {
      1: 'success',
      2: 'error',
    }
    const enabled = {
      2: 'Inactivo',
      1: 'Activo',
    }

    const operativoColor = {
      1: 'success',
      2: 'error',
    }
    const operativo = {
      2: 'No Operativo',
      1: 'Operativo',
    }

    const job = {
      0: 'No Completado',
      1: 'Completado',
    }

    const jobColor = {
      0: 'error',
      1: 'success',
    }

    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: props.itemsPage,
      status,
      statusColor,
      enabledColor,
      enabled,
      operativoColor,
      operativo,
      job,
      jobColor
    }
  },
  methods: {
    formatDate(created_at) {
       return this.$moment(created_at).format('MM-DD-YYYY');
    },
    formatDateTime(fecha) {
      if(fecha==null) return ''
      return this.$moment(fecha).format('MM-DD-YYYY HH:mm');
    },
    editItem(item) {
      this.$emit('edit', item)
    },
    deleteItem(item){
      this.$emit('delete', item)
    },
    seeItem(item){
      this.$emit('see', item)
    },
    /*changeItemPerPage(item){
      this.$emit('item-per-page', item)
    },*/
    select(value){
      //console.log(value)
      this.$emit('set-array-select', value)
    },
    selectAll(value){
      this.$emit('set-array-select', value)
    },
    exportData(){
      if(this.items.length==0) return

      const headers = this.headers
      var arrData = []

      const replacef = function (string) {
        return Functions.replaceChar(string)
      }

      this.items.map(function (element) {
          const row = new Object ()
          headers.map(function (header) {
            if(header.value!='options') {

              const value = header.value.split('.');
              if(value.length == 1) {
                row[header.text] = element[header.value] == undefined ? '' :  element[header.value]
              } else if (value.length == 2) {
                row[header.text] = element[value[0]] == undefined ? '' :  element[value[0]][value[1]]
              } else {
                row[header.text] = ''
              }

              row[header.text] = isNaN(row[header.text]) ? replacef(row[header.text]) : row[header.text]
            } 

          })
          arrData.push(row)
      })

      Functions.downloadFileCsvEncode(arrData, 'Mantenimiento_Tabla.csv');
    },
  }
}
</script>