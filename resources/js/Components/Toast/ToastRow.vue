<template>
  <div
    :class="['toast', `toast--${entry.type}`, phase === 'visible' ? 'is-visible' : '', phase === 'leave' ? 'is-hiding' : '']"
    :role="entry.type === 'error' ? 'alert' : 'status'"
    :aria-live="entry.type === 'error' ? 'assertive' : 'polite'"
    :style="entry.duration > 0 ? { '--toast-duration': `${entry.duration}ms` } : {}"
    @transitionend="handleTransitionEnd"
  >
    <div class="toast__icon" v-html="icons[entry.type]" />
    <div class="toast__message">{{ entry.message }}</div>
    <button type="button" class="toast__close" aria-label="Tutup" @click="phase = 'leave'" v-html="closeIcon" />
    <span v-if="entry.duration > 0" class="toast__progress" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  entry: { type: Object, required: true },
  onRemove: { type: Function, required: true },
})

console.log('ToastRow entry:', props.entry)

const phase = ref('enter')
let timer

const icons = {
  success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="5 12.5 10 17.5 19 7.5"></polyline></svg>',
  error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>',
  info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="11" x2="12" y2="17"></line><circle cx="12" cy="7.5" r="0.8" fill="currentColor" stroke="none"></circle></svg>',
}

const closeIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>'

onMounted(() => {
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      phase.value = 'visible'
      if (props.entry.duration > 0) {
        timer = setTimeout(() => phase.value = 'leave', props.entry.duration)
      }
    })
  })
})

onUnmounted(() => {
  if (timer) clearTimeout(timer)
})

function handleTransitionEnd(e) {
  if (phase.value === 'leave') {
    props.onRemove(props.entry.id)
  }
}
</script>
