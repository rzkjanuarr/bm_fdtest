<template>
  <main style="padding: 24px; max-width: 980px; margin: 0 auto;">
    <h1 style="margin-bottom: 8px;">{{ title }}</h1>
    <p style="margin-top: 0; opacity: 0.7;">{{ subtitle }}</p>
    <slot />
  </main>
</template>

<script setup>
defineProps(['title', 'subtitle'])
</script>
