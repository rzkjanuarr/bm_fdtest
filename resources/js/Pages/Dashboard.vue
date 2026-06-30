<template>
  <DashboardHeader
    title="Dashboard"
    subtitle="Selamat datang di Asietex"
  >
    <template #action1>
      <button type="button" class="btn-d btn-d--ghost" @click="goToUser">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        <span class="btn-d__label">User</span>
      </button>
    </template>
    <template #action2>
      <button type="button" class="btn-d btn-d--primary" @click="goToBook">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
        </svg>
        <span class="btn-d__label">Buku</span>
      </button>
    </template>
    <section class="grid-stats">
      <StatisticCard
        title="Total Pengguna"
        :value="totalUsers"
        icon="user"
      >
        <template #meta>
          <span class="badge badge--success">Semua aktif</span>
        </template>
      </StatisticCard>
      <StatisticCard
        title="Pengguna Baru"
        :value="newUsersThisMonth"
        icon="user-plus"
      >
        <template #meta>
          <span class="badge badge--muted">Bulan ini</span>
        </template>
      </StatisticCard>
      <StatisticCard
        title="Total Buku"
        :value="totalBooks"
        icon="user"
      >
        <template #meta>
          <span class="badge badge--success">Semua buku</span>
        </template>
      </StatisticCard>
      <StatisticCard
        title="Buku Baru"
        :value="newBooksThisMonth"
        icon="user-plus"
      >
        <template #meta>
          <span class="badge badge--muted">Bulan ini</span>
        </template>
      </StatisticCard>
    </section>

    <section class="charts-grid">
      <StatisticChart
        title="Pertumbuhan Pengguna"
        subtitle="Mingguan"
        preset="user-growth"
        :labels="userWeeklyLabels"
        :values="userWeeklyValues"
      >
        <template #icon>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 17 9 11 13 15 21 7" />
            <polyline points="14 7 21 7 21 14" />
          </svg>
        </template>
      </StatisticChart>
      <StatisticChart
        title="Pertumbuhan Buku"
        subtitle="Mingguan"
        preset="user-growth"
        :labels="bookWeeklyLabels"
        :values="bookWeeklyValues"
      >
        <template #icon>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 17 9 11 13 15 21 7" />
            <polyline points="14 7 21 7 21 14" />
          </svg>
        </template>
      </StatisticChart>
    </section>
  </DashboardHeader>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardHeader from '../Components/DashboardHeader.vue'
import StatisticCard from '../Components/StatisticCard.vue'
import StatisticChart from '../Components/StatisticChart.vue'
import { useUserTotalBadge } from '../composables/useUserTotalBadge.js'

function goToUser() {
  router.visit('/user')
}

function goToBook() {
  router.visit('/book')
}

const props = defineProps([
  'totalUsers', 
  'newUsersThisMonth', 
  'userWeeklyLabels', 
  'userWeeklyValues',
  'totalBooks',
  'newBooksThisMonth',
  'bookWeeklyLabels',
  'bookWeeklyValues',
  'totalBooksCount'
])
const { setTotalUsers, setTotalBooks } = useUserTotalBadge()

onMounted(() => {
  setTotalUsers(props.totalUsers)
  setTotalBooks(props.totalBooksCount)
})

watch(() => props.totalUsers, (newCount) => {
  setTotalUsers(newCount)
})

watch(() => props.totalBooksCount, (newCount) => {
  setTotalBooks(newCount)
})
</script>
