<template>
  <div :class="['modal', open ? 'is-open' : '']" aria-hidden="true" role="dialog" aria-labelledby="modalDeleteTitle">
    <button type="button" class="modal__backdrop" aria-hidden @click="onClose" />
    <div class="modal__dialog" role="document">
      <div class="modal__header">
        <div class="modal__title-group">
          <h2 class="modal__title" id="modalDeleteTitle">
            <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6" />
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
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

      <div class="modal__body">
        <p class="modal__delete-message">{{ message }}</p>
        <div class="modal__footer">
          <button type="button" class="btn-d btn-d--ghost" @click="onClose">Batal</button>
          <button type="button" class="btn-d btn-d--danger" :disabled="disabled" @click="onSubmit">{{ submitLabel }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, watch } from 'vue'

const props = defineProps(['open', 'title', 'subtitle', 'message', 'submitLabel', 'disabled'])
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
