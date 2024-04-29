<script setup>
import { useRouter } from 'vue-router'
const emit = defineEmits(['customChange'])

const router= useRouter()
const route = useRoute()

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

</script>

<template>
  <div id="link-menu">
    <v-list-item :value="item.path" 
        :class="item.class"
        :key="item.id"
        @click="router.push({ path: item.path })"
        :active="item.path == route.path"
       >
        <template v-slot:prepend>
          <v-icon v-if="item.class!='subsubitem'" :class="{'alternate-icon-small': !item.icon}"
                :icon="item.icon || 'mdi-checkbox-blank-circle-outline'"></v-icon>
        </template>

        <v-list-item-title v-text="item.title"></v-list-item-title>
    </v-list-item>
  </div>
</template>

<style lang="scss">

.alternate-icon-small {
    font-size: 14px;
    height: 14px;
    width: 14px;
}

.subsubitem {
    margin-left: -30px;
}


#link-menu {
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
</style>
