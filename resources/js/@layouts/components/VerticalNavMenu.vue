<script setup>
import VerticalNavMenuGroup from '@/@layouts/components/VerticalNavMenuGroup.vue'
import VerticalNavLink from '@/@layouts/components/VerticalNavLink.vue'
const emit = defineEmits(['customChangeNav'])

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

</script>

<template><!-- :open="open" -->
    <VerticalNavMenuGroup
      v-if="item.isItem==0"
      :item="item"
    />
    <VerticalNavLink
      v-if="item.isItem==1"
      :item="item"
    />
</template>