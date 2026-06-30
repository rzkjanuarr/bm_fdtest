<template>
  <slot />
</template>

<script setup>
import { ref, provide } from 'vue'
import { USER_TOTAL_KEY } from '../composables/useUserTotalBadge.js'

const totalUsers = ref(0)
const totalBooks = ref(0)

function setTotalUsers(count) {
  totalUsers.value = count
}

function setTotalBooks(count) {
  totalBooks.value = count
}

function formatSidebarUserCount(count) {
  if (count > 99) return '99+'
  return count.toString()
}

provide(USER_TOTAL_KEY, { totalUsers, totalBooks, setTotalUsers, setTotalBooks, formatSidebarUserCount })
</script>
