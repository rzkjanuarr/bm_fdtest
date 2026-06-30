<template>
  <Teleport to="body">
    <div class="toast-container" role="region" aria-label="Notifications">
      <ToastRow
        v-for="entry in entries"
        :key="entry.id"
        :entry="entry"
        :on-remove="remove"
      />
    </div>
  </Teleport>
  <slot />
</template>

<script setup>
import { ref, provide, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ToastRow from './ToastRow.vue'
import { TOAST_KEY } from '../../composables/useToast.js'

const page = usePage()
const entries = ref([])
let idCounter = 0

function show(options) {
  const id = `toast-${idCounter++}`
  const duration = options.duration || 4000
  entries.value.push({ id, type: options.type, message: options.message, duration })
}

function success(message, duration) {
  show({ type: 'success', message, duration })
}

function error(message, duration) {
  show({ type: 'error', message, duration })
}

function info(message, duration) {
  show({ type: 'info', message, duration })
}

function remove(id) {
  entries.value = entries.value.filter(e => e.id !== id)
}

// Watch for flash messages from Laravel
watch(() => page.props?.toast, (toast) => {
  if (toast && toast.message) {
    show({
      type: toast.type || 'info',
      message: toast.message,
      duration: toast.duration || 4000
    })
  }
}, { deep: true })

provide(TOAST_KEY, { success, error, info, show })
</script>
