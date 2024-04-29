
<template>
  <div>
    <v-card-text class="mt-6">
      <v-row>
        <v-col
          cols="12"
          md="2"
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
                icon="mdi-file-excel"
              >
              </v-icon>
              Exportar </v-btn>
        </v-col>
        <v-col
          cols="12"
          md="4"
          v-if="seeSearch"
        >
          <!--<template>
            <slot name="fieldSearch">
            </slot>
          </template>-->
          <v-text-field
                  slot="fieldSearch"
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Buscar en la tabla"
                  hide-details
                  dense
                  outlined
                  small
          ></v-text-field>
        
        </v-col>
      </v-row>
    </v-card-text>
    <v-data-table
      :headers="headers"
      :items="items"
       density="compact"
      :loading="isLoading"
      :items-per-page="itemsPerPage"
      :search="search"
      no-data-text="No hay datos para mostrar"
      loading-text="Cargando..."
      :page.sync="page"
      hide-default-footer
      @page-count="pageCount = $event"
      :show-select="showSelect"
      @item-selected="select"
      @toggle-select-all="selectAll"
      :item-key="itemKey"
    >
      <template #[`item.options`]="{ item }">
         
        <v-tooltip top color="info">
          <template v-slot:activator="{ on, attrs }">
            <v-avatar v-bind="attrs"
                      v-on="on" size="28"
                      v-if="canSee">
                <v-icon size="20" @click="seeItem(item)" color="info" icon="mdi-eye-outline">
                </v-icon>
            </v-avatar>
          </template>
          <span>Ver</span>
        </v-tooltip>

        <v-tooltip top color="success">
          <template v-slot:activator="{ on, attrs }">
            <v-avatar v-bind="attrs"
                      v-on="on"
                      color="success"
                      size="28"
                      v-if="canEdit">
                <v-icon size="20" @click="editItem(item)" icon="mdi-pencil-outline">
                </v-icon>
            </v-avatar>
          </template>
          <span>Editar</span>
        </v-tooltip>

        <v-tooltip top color="error">
          <template v-slot:activator="{ on, attrs }">
            <v-avatar v-bind="attrs"
                      v-on="on"
                      size="28"
                      v-if="canDelete">
                <v-icon size="20" @click="deleteItem(item)" color="error" icon="mdi-delete-outline">
                </v-icon>
        </v-avatar>
          </template>
          <span>Borrar</span>
        </v-tooltip>
      </template>


      <template #[`item.nombres`]="{item}">
            <span>{{item.nombres}}</span>
      </template>
      
      <template #[`item.created_at`]="{item}">
         {{ formatDate(item.created_at) }}
      </template>

      <template #[`item.updated_at`]="{item}">
         {{ formatDateTime(item.updated_at) }}
      </template>

      <template #[`item.enabled`]="{item}">
        <v-chip
          small
          :color="enabledColor[item.enabled]"
          :class="`${enabledColor[item.enabled]}--text`"
          class="v-chip-light-bg"
        >
          {{ enabled[item.enabled] }}
        </v-chip>
      </template>

      <template #[`item.availability`]="{item}">
        <v-chip
          small
          :color="availabilityColor[item.availability]"
          :class="`${availabilityColor[item.availability]}--text`"
          class="v-chip-light-bg"
        >
          {{ availability[item.availability] }}
        </v-chip>
      </template>


    </v-data-table>
    <!--<v-card-text class="pt-2">
      <v-row>
        <v-col
          lg="2"
          cols="3"
        >
          <v-select
            v-model="itemsPerPage"
            :items="[5, 25, 50, 100]"
            label="Items por Página"
            hide-details
          ></v-select>
        </v-col>

        <v-col
          lg="10"
          cols="9"
          class="d-flex justify-end"
        >
          <v-pagination
            v-model="page"
            total-visible="6"
            :length="pageCount"
          ></v-pagination>
        </v-col>
      </v-row>
    </v-card-text>-->
  </div>
</template>

<script>
import * as moment from 'moment'
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
      default: true
    }
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

    const availabilityColor = {
      'SI': 'success',
      'NO': 'error',
    }
    const availability = {
      'SI': 'SI',
      'NO': 'NO',
    }

    const job = {
      0: 'No Completado',
      1: 'Completado',
    }

    const jobColor = {
      0: 'error',
      1: 'success',
    }

    let search = ref('')

    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 25,
      status,
      statusColor,
      enabledColor,
      enabled,
      availabilityColor,
      availability,
      search
    }
  },
  methods: {
    formatDate(created_at) {
        return moment(created_at).format('DD/MM/YYYY');
    },
    formatDateTime(fecha) {
      if(fecha==null) return ''
      return moment(fecha).format('DD/MM/YYYY HH:mm');
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
    changeItemPerPage(item){
      this.$emit('item-per-page', item)
    },
    cancelReserveItem(item){
      this.$emit('cancel', item)
    },
    correoReserveItem(item){
      this.$emit('send', item)
    },
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

              if(header.value == 'estado') {
                row[header.text] = element[header.value]==1 ? 'Activo' : 'Inactivo'
              }
              if(header.value == 'availability') {
                row[header.text] = element[header.value]==1 ? 'availability' : 'No availability'
              }
              if(header.value == 'lab_virtual') {
                row[header.text] = element[header.value]==1 ? 'Habilitado' : 'No Habilitado'
              }
            } 

          })
          arrData.push(row)
      })

      Functions.downloadFileCsvEncode(arrData, 'Mantenimiento_Tabla.csv');
    }
  }
}
</script>