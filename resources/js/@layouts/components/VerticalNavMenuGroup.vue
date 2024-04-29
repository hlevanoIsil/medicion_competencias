<script setup>
//import { useRouter } from 'vue-router'
import VerticalNavMenu from '@layouts/components/VerticalNavMenu.vue'

const emit = defineEmits(['customChange'])

const route = useRoute()

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

</script>

<template><!--:color="item.isActive==0 ? '' : 'primary'"-->
    <div id="link-menugroup">
    <v-list-group :value="item.path"
          >
          <template v-slot:activator="{ props }">
            <v-list-item
              v-bind="props"
              :title="item.title"
            >
              <template v-slot:prepend>
                <v-icon :class="{'alternate-icon-small': !item.icon}"
                :icon="item.icon || 'mdi-checkbox-blank-circle-outline'" ></v-icon>
              </template>
            </v-list-item>
          </template>
          <VerticalNavMenu
          v-for="subitem in item.children"
          :key="subitem.id"
          :item="subitem"
          />
  </v-list-group>
</div>
</template>

<style lang="scss">

.alternate-icon-small {
    font-size: 14px;
    height: 14px;
    width: 14px;
}

.v-list-group__items .v-list-item {
    padding-inline-start: calc(-35px + var(--indent-padding)) !important;
}


#link-menugroup {
  .v-list-item--active {
    border-radius: 0px 32px 32px 0px;
  }
  
  .v-list-item {
    border-radius: 0px 32px 32px 0px;
  }
  
  .v-list-item-title{
    font-size: 0.9rem;
  }

}
/*
.v-list-group__items .v-list-item {

}*/

</style>
