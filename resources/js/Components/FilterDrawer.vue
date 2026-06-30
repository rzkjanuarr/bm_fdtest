<template>
  <div :class="['filter-drawer', open ? 'is-open' : '']">
    <div class="filter-drawer__header">
      <h2 class="filter-drawer__title" id="filterDrawerTitle">{{ title }}</h2>
      <button type="button" class="filter-drawer__close" @click="onClose" aria-label="Tutup">
        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
    </div>

    <div class="filter-drawer__body">
      <slot />
    </div>

    <div class="filter-drawer__footer">
      <button type="button" class="btn-d btn-d--ghost" @click="onReset">Reset</button>
      <button type="button" class="btn-d btn-d--primary" @click="onApply">Terapkan</button>
    </div>
  </div>
</template>

<script setup>
defineProps(['open', 'title'])
const emit = defineEmits(['close', 'apply', 'reset'])

function onClose() {
  emit('close')
}

function onApply() {
  emit('apply')
}

function onReset() {
  emit('reset')
}
</script>
