<script setup>

let vh = window.innerHeight * 0.01,
        vw = window.innerWidth * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    document.documentElement.style.setProperty('--vw', `${vw}px`);
    
</script>

<template>
  <VApp>
    <RouterView />
  </VApp>
</template>

<style type="text/css">

.swal2-container {
  z-index: 99999999999999;
}

</style>