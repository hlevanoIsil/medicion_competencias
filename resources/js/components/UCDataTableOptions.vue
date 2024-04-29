
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
        <!--<v-col v-if="isTable"
              cols="12"
              md="2"
            >
              <v-btn block color="primary" @click="exportData" outlined> 
              <v-icon
                left
                dark
                icon="mdi-file-excel"
              >
              </v-icon>
              Exportar </v-btn>
        </v-col>-->
        <v-col
          cols="12"
          md="4"
          v-if="seeSearch"
        >
          <v-text-field
                label="Buscar en la tabla"
                v-model="search"
                outlined  
                type="text"                
                dense
                hide-details="auto"
            ></v-text-field>
        </v-col>
      </v-row>
    </v-card-text>
    <v-data-table
       class="pb-4"
       density="compact"
      :headers="headers"
      :items="items"
      :loading="isLoading"
      :items-per-page="itemsPerPage"
      :search="search"
      no-data-text="No hay datos para mostrar"
      :page.sync="page"
      :show-select="showSelect"
      @item-selected="select"
      @toggle-select-all="selectAll"
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
            <span>{{item.nombres || item.raw.nombres}}</span>
      </template>
      
      <template #[`item.created_at`]="{item}">
         {{ formatDate(item.created_at || item.raw.created_at) }}
      </template>

      <template #[`item.fecha`]="{item}">
         {{ formatDate(item.fecha || item.raw.fecha) }}
      </template>

      <template #[`item.updated_at`]="{item}">
         {{ formatDateTime(item.updated_at || item.raw.updated_at) }}
      </template>

      <template #[`item.estado`]="{item}">
        <v-chip
          small
          :color="enabledColor[item.estado || item.raw.estado]"
          :class="`${enabledColor[item.estado || item.raw.estado]}--text`"
          class="v-chip-light-bg"
        >
          {{ enabled[item.estado || item.raw.estado] }}
        </v-chip>
      </template>

      <template #[`item.operativo`]="{item}">
        <v-chip
          small
          :color="operativoColor[item.operativo || item.raw.operativo]"
          :class="`${operativoColor[item.operativo || item.raw.operativo] }--text`"
          class="v-chip-light-bg"
        >
          {{ operativo[item.operativo || item.raw.operativo ] }}
        </v-chip>
      </template>

      <template #[`item.begin_date`]="{item}">
         {{ formatDate(item.begin_date || item.raw.begin_date) }}
      </template>
      
      <template #[`item.end_date`]="{item}">
         {{ formatDate(item.end_date || item.raw.end_date) }}
      </template>

      <template #[`item.fecha_emision`]="{item}">
         {{ formatDate(item.fecha_emision || item.raw.fecha_emision) }}
      </template>
      
      <template #[`item.fecha_vencimiento`]="{item}">
         {{ formatDate(item.fecha_vencimiento || item.raw.fecha_vencimiento) }}
      </template>

    </v-data-table>
  </div>
</template>

<script>
  /*COMPONENTE GENERICO PARA MANTENEDORES SIN PAGINADO*/
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
    seeSearch: {
      type: Boolean,
      default: false
    },
    itemsPage: {
      type: Number,
      default: 25
    },
  },
  setup(props) {
   
    const enabledColor = {
      1: 'success',
      2: 'error',
    }
    const enabled = {
      2: 'Inactivo',
      1: 'Activo',
    }

    const job = {
      0: 'No Completado',
      1: 'Completado',
    }

    const jobColor = {
      0: 'error',
      1: 'success',
    }

    let search = ref("")

    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: props.itemsPage,
      enabledColor,
      enabled,
      job,
      jobColor,
      search
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
            var field = header.value || header.key
            var fieldname = header.text || header.title
            if(field!='options') {

              const value = field.split('.');

              if(value.length == 1) {
                row[fieldname] = element[field] == undefined ? '' :  element[field]
              } else if (value.length == 2) {
                row[fieldname] = element[value[0]] == undefined ? '' :  element[value[0]][value[1]]
              } else {
                row[fieldname] = ''
              }

              row[fieldname] = isNaN(row[fieldname]) ? replacef(row[fieldname]) : row[fieldname]
            } 

          })
          arrData.push(row)
      })
      Functions.downloadFileCsvEncode(arrData, 'Mantenimiento_Tabla.csv');
    },
  }
}
</script>