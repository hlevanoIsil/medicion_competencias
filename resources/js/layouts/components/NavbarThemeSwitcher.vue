<script setup>
const themes = [
  {
    name: 'light',
    label: 'Dark',
    icon: 'mdi-weather-night',
  },
  {
    name: 'dark',
    label: 'Light',
    icon: 'mdi-weather-sunny',
  },
]
</script>

<template>
  <ThemeSwitcher :themes="themes" />
</template>
