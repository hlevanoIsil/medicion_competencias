<template>
    <div>

      <v-data-table
        density="compact"
        v-if="!pagination"
        :headers="headers"
        :items="items"
        :loading="isLoading"
        :search="search"
        no-data-text="No hay datos para mostrar"
        loading-text="Cargando..."
        :page.sync="page"
        :items-per-page="itemsPerPage"
        :single-expand="singleexpand"
        :show-expand="showexpand"
      >
      <template #[`item.fecha`]="{item}">
         {{ formatDate(item.fecha || item.raw.fecha) }}
      </template>

      <template #[`item.femision`]="{item}">
         {{ formatDate(item.femision  || item.raw.femision ) }}
      </template>

      <template #[`item.fvencimiento`]="{item}">
         {{ formatDate(item.fvencimiento || item.raw.fvencimiento) }}
      </template>

      <template #[`item.begin_date`]="{item}">
         {{ formatDate(item.begin_date || item.raw.begin_date) }}
      </template>
      
      <template #[`item.end_date`]="{item}">
         {{ formatDate(item.end_date || item.raw.end_date) }}
      </template>

      </v-data-table>

      <!--<v-card-text class="pt-2">
      <v-row>
        <v-col
          lg="2"
          cols="3"
        >
          <v-text-field
            :value="itemsPerPage"
            label="Items por Página"
            type="number"
            min="-1"
            max="15"
            hide-details
            @input="itemsPerPage = parseInt($event, 10)"
            :readonly="pagination"
          ></v-text-field>
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
    export default {
        props: {
          headers: {
            type: Array,
            default: []
          },
          items: {
            type: Array,
            default: []
          },
          isLoading: {
            type: Boolean,
            default: false
          },
          canSearch: {
            type: Boolean,
            default: true
          },
          hideFooter: {
            type:Boolean,
            default: false
          },
          itemTotal: {
            type: Number,
            default: null
          },
          pagination: {
            type: Boolean,
            default: false
          },
          itemsPage: {
            type: Number,
            default: 25
          },
          singleexpand: {
            type: Boolean,
            default: false
          },
          showexpand: {
            type: Boolean,
            default: false
          }
        },
        data() {
            return {
                icons: {
                    mdiPencilOutline :null ,
                    mdiDelete:null,
                    mdiTextSearch:null,
                    mdiMagnify:null
                },
                search: "",
                page: 1,
                pageCount: 0,
                itemsPerPage: this.itemsPage
            }
        },
        watch: {
          page(value) {
            this.$emit('change-page', this.page)
          },
        },
        methods: {
          formatDate(created_at) {
            if (created_at === null) return

            return this.$moment(created_at).format('MM-DD-YYYY');
          },
        }
    }
</script>

<style scoped>

</style>