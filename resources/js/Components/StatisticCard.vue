<template>
  <article class="card">
    <div class="card__head">
      <div>
        <div class="card__title">{{ title }}</div>
        <div class="card__value">{{ value }}</div>
      </div>
      <span v-if="$slots.icon" class="card__icon"><slot name="icon" /></span>
    </div>
    <slot name="extra" />
    <div v-if="$slots.meta" class="card__meta"><slot name="meta" /></div>
  </article>
</template>

<script setup>
defineProps(['title', 'value'])
</script>
