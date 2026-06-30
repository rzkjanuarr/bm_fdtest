<template>
  <article class="chart-card">
    <div class="chart-card__header">
      <div class="chart-card__title-group">
        <h3 class="chart-card__title">
          <slot name="icon"></slot>
          {{ title }}
        </h3>
        <span class="chart-card__subtitle">{{ subtitle }}</span>
      </div>
      <slot name="badge"></slot>
    </div>
    <div class="chart-card__canvas">
      <Line v-if="isLine" :data="lineData" :options="commonOptions" />
      <Bar v-else-if="isBar" :data="barData" :options="commonOptions" />
      <Doughnut v-else-if="isDoughnut" :data="doughnutData" :options="commonOptions" />
      <Radar v-else-if="isRadar" :data="radarData" :options="commonOptions" />
      <PolarArea v-else-if="isPolarArea" :data="doughnutData" :options="commonOptions" />
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  RadarController,
  RadialLinearScale,
  Tooltip,
  Legend,
  Filler,
} from 'chart.js'
import { Line, Bar, Doughnut, Radar, PolarArea } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  RadarController,
  RadialLinearScale,
  Tooltip,
  Legend,
  Filler
)

const props = defineProps({
  title: String,
  subtitle: String,
  preset: { type: String, default: 'line' },
  labels: { type: Array, default: () => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] },
  values: { type: Array, default: () => [10, 14, 13, 18, 16, 19, 21] },
})

const isLine = computed(() => ['line', 'user-growth', 'user-signups', 'user-retention'].includes(props.preset))
const isBar = computed(() => ['bar', 'revenue-monthly', 'user-status'].includes(props.preset))
const isDoughnut = computed(() => ['doughnut', 'plan-doughnut'].includes(props.preset))
const isRadar = computed(() => props.preset === 'category-radar')
const isPolarArea = computed(() => props.preset === 'user-plan')

const commonOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: isDoughnut.value || isPolarArea.value } },
}))

const lineData = computed(() => {
  if (props.preset === 'user-retention') {
    return {
      labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6'],
      datasets: [
        { label: 'Retention', data: [82, 84, 86, 88, 87, 90], borderColor: '#fe0006', backgroundColor: 'rgba(254, 0, 6, 0.18)', fill: true },
        { label: 'Churn', data: [18, 16, 14, 12, 13, 10], borderColor: '#cc0005', backgroundColor: 'rgba(204, 0, 5, 0.08)', fill: true },
      ],
    }
  }
  return {
    labels: props.labels,
    datasets: [
      { label: props.title, data: props.values, borderColor: '#fe0006', backgroundColor: 'rgba(254, 0, 6, 0.16)', fill: true, tension: 0.35, pointRadius: 3 },
    ],
  }
})

const barData = computed(() => ({
  labels: props.labels,
  datasets: [{ label: props.title, data: props.values, backgroundColor: 'rgba(254, 0, 6, 0.75)', borderRadius: 8 }],
}))

const doughnutPalette = ['#fe0006', '#cc0005', '#aa0004', '#ff3338', '#ff1a20', '#dd0006', '#ff4d51', '#ff6669']
const doughnutData = computed(() => {
  const doughnutValues = props.values.length ? props.values : [55, 25, 20]
  const doughnutLabels = props.labels.length ? props.labels : ['Explore', 'Maniac', 'Enterprise']
  return {
    labels: doughnutLabels,
    datasets: [
      {
        label: props.title,
        data: doughnutValues,
        backgroundColor: doughnutValues.map((_, i) => doughnutPalette[i % doughnutPalette.length]),
        borderWidth: 0,
      },
    ],
  }
})

const radarData = computed(() => ({
  labels: ['Akhlak', 'Sejarah', 'Hadits', 'Quran', 'Motivasi'],
  datasets: [
    { label: props.title, data: [88, 75, 69, 81, 73], borderColor: '#fe0006', backgroundColor: 'rgba(254, 0, 6, 0.2)' },
  ],
}))
</script>
