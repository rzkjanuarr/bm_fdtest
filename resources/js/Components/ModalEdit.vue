<template>
  <div :class="['modal', open ? 'is-open' : '']" aria-hidden="true" role="dialog" aria-labelledby="modalEditTitle">
    <button type="button" class="modal__backdrop" aria-hidden @click="onClose" />
    <div class="modal__dialog" role="document">
      <div class="modal__header">
        <div class="modal__title-group">
          <h2 class="modal__title" id="modalEditTitle">
            <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
              <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            {{ title }}
          </h2>
          <span class="modal__subtitle">{{ subtitle }}</span>
        </div>
        <button type="button" class="modal__close" @click="onClose" aria-label="Tutup">
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </div>

      <form @submit.prevent="onSubmit" class="modal__body">
        <div class="form-grid">
          <slot name="body" />
        </div>

        <div class="modal__footer">
          <button type="button" class="btn-d btn-d--ghost" @click="onClose">Batal</button>
          <button type="submit" class="btn-d btn-d--primary" :disabled="disabled">{{ submitLabel }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, watch } from 'vue'

const props = defineProps(['open', 'title', 'subtitle', 'submitLabel', 'disabled'])
const emit = defineEmits(['close', 'submit'])

function onClose() {
  emit('close')
}

function onSubmit() {
  emit('submit')
}

function handleKeydown(e) {
  if (e.key === 'Escape' && props.open) {
    onClose()
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
})

watch(() => props.open, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>
