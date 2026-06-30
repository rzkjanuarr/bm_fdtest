<template>
  <section class="table-wrap">
    <div class="table-scroll">
      <table class="table">
        <thead>
          <tr>
            <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
          </tr>
        </thead>
        <tbody><slot name="rows" /></tbody>
      </table>
    </div>
    <div class="table-footer">
      <span>{{ summary }}</span>
      <div class="pagination"><slot name="pagination" /></div>
    </div>
  </section>
</template>

<script setup>
defineProps(['columns', 'summary'])
</script>
