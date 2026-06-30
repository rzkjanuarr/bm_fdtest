<template>
  <DashboardHeader
    title="Kelola Buku"
    subtitle="Daftar buku yang terdaftar di sistem"
  >
    <template #action1>
      <button type="button" class="btn-d btn-d--ghost" @click="filterOpen = true">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
        </svg>
        <span class="btn-d__label">Filter</span>
      </button>
    </template>
    <template #action2>
      <button type="button" class="btn-d btn-d--primary" @click="openModal('create')">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
          <line x1="12" y1="5" x2="12" y2="19" />
          <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        <span class="btn-d__label">Tambah Buku</span>
      </button>
    </template>

    <section class="grid-stats">
      <StatisticCard
        title="Total Buku"
        :value="totalBooks"
        icon="book"
      >
        <template #meta>
          <span class="badge badge--success">Semua buku</span>
        </template>
      </StatisticCard>
      <StatisticCard
        title="Buku Baru"
        :value="newBooksThisMonth"
        icon="plus"
      >
        <template #meta>
          <span class="badge badge--muted">Bulan ini</span>
        </template>
      </StatisticCard>
    </section>

    <section class="charts-grid">
      <StatisticChart
        title="Pertumbuhan Buku"
        subtitle="Mingguan"
        preset="user-growth"
        :labels="weeklyLabels"
        :values="weeklyValues"
      >
        <template #icon>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 17 9 11 13 15 21 7" />
            <polyline points="14 7 21 7 21 14" />
          </svg>
        </template>
      </StatisticChart>
    </section>

    <DataTable :columns="columns" :summary="tableSummary">
      <template #rows>
        <tr v-for="row in booksFormatted" :key="row.id">
          <td>
            <div class="table__user">
              <span class="table__avatar">{{ row.initials }}</span>
              <div class="table__primary">
                <span class="table__primary-name">{{ row.title }}</span>
                <span class="table__primary-sub">{{ row.author }}</span>
              </div>
            </div>
          </td>
          <td>
            <span class="badge" :class="row.ratingBadge">{{ row.rating }}/5</span>
          </td>
          <td>{{ row.createdAt }}</td>
          <td>
            <div class="table__actions" style="justify-content: flex-end">
              <button type="button" class="icon-btn" @click="openModal('view', row.id)">
                <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
              <button type="button" class="icon-btn" @click="openModal('edit', row.id)">
                <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                  <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
              </button>
              <button type="button" class="icon-btn icon-btn--danger" @click="openModal('delete', row.id)">
                <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none" width="16" height="16" aria-hidden>
                  <polyline points="3 6 5 6 21 6" />
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                </svg>
              </button>
            </div>
          </td>
        </tr>
      </template>
      <template #pagination>
        <button type="button" :disabled="!books.prev_page_url" @click="goToPage(books.prev_page_url)">&lsaquo;</button>
        <span class="pagination__info">{{ tableSummary }}</span>
        <button type="button" :disabled="!books.next_page_url" @click="goToPage(books.next_page_url)">&rsaquo;</button>
      </template>
    </DataTable>
  </DashboardHeader>

  <!-- Modals & Filter Drawer -->
  <FilterDrawer
    :open="filterOpen"
    title="Filter Buku"
    @close="filterOpen = false"
    @apply="applyFilters"
    @reset="resetFilters"
  >
    <div class="filter-group">
      <label class="filter-group__label">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden>
          <circle cx="11" cy="11" r="7" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <span>Cari</span>
      </label>
      <div class="filter-search">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden>
          <circle cx="11" cy="11" r="7" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input class="filter-input" type="search" v-model="filterDraft.search" placeholder="Cari judul/penulis buku..." />
      </div>
    </div>
  </FilterDrawer>

  <ModalCreate
    :open="currentModal === 'create'"
    title="Tambah Buku"
    subtitle="Tambah buku baru ke sistem"
    submit-label="Tambah"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleCreate"
  >
    <template #body>
      <div class="form-field">
        <label class="form-field__label" for="bcTitle">Judul</label>
        <input id="bcTitle" name="title" class="filter-input" type="text" required placeholder="Judul buku" v-model="createFormTitle" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="bcAuthor">Penulis</label>
        <input id="bcAuthor" name="author" class="filter-input" type="text" required placeholder="Nama penulis" v-model="createFormAuthor" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="bcDescription">Deskripsi</label>
        <textarea id="bcDescription" name="description" class="filter-input" placeholder="Deskripsi buku" v-model="createFormDescription" rows="3"></textarea>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="bcThumbnail">Thumbnail (URL)</label>
        <input id="bcThumbnail" name="thumbnail" class="filter-input" type="url" placeholder="https://example.com/thumbnail.jpg" v-model="createFormThumbnail" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="bcRating">Rating</label>
        <select id="bcRating" name="rating" class="filter-input" required v-model.number="createFormRating">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
    </template>
  </ModalCreate>

  <ModalEdit
    :open="currentModal === 'edit'"
    title="Edit Buku"
    subtitle="Perbarui detail buku"
    submit-label="Simpan"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleEdit"
  >
    <template #body>
      <div class="form-field">
        <label class="form-field__label" for="beTitle">Judul</label>
        <input id="beTitle" name="title" class="filter-input" type="text" required v-model="editFormTitle" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="beAuthor">Penulis</label>
        <input id="beAuthor" name="author" class="filter-input" type="text" required v-model="editFormAuthor" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="beDescription">Deskripsi</label>
        <textarea id="beDescription" name="description" class="filter-input" v-model="editFormDescription" rows="3"></textarea>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="beThumbnail">Thumbnail (URL)</label>
        <input id="beThumbnail" name="thumbnail" class="filter-input" type="url" v-model="editFormThumbnail" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="beRating">Rating</label>
        <select id="beRating" name="rating" class="filter-input" required v-model.number="editFormRating">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
    </template>
  </ModalEdit>

  <ModalDelete
    :open="currentModal === 'delete'"
    title="Hapus Buku"
    subtitle="Buku akan dihapus dari sistem"
    message="Apakah Anda yakin ingin menghapus buku ini?"
    submit-label="Hapus"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleDelete"
  />

  <ModalView
    :open="currentModal === 'view'"
    title="Lihat Buku"
    subtitle="Detail buku"
    @close="closeModal"
  >
    <template #body>
      <dl class="detail-list">
        <div class="detail-row">
          <dt>Judul</dt>
          <dd>{{ selectedRow?.title }}</dd>
        </div>
        <div class="detail-row">
          <dt>Penulis</dt>
          <dd>{{ selectedRow?.author }}</dd>
        </div>
        <div class="detail-row">
          <dt>Deskripsi</dt>
          <dd>{{ selectedRow?.description || '-' }}</dd>
        </div>
        <div class="detail-row">
          <dt>Rating</dt>
          <dd>{{ selectedRow?.rating }}/5</dd>
        </div>
        <div class="detail-row">
          <dt>Dibuat Pada</dt>
          <dd>{{ selectedRow?.createdAt }}</dd>
        </div>
      </dl>
    </template>
    <template #footer-actions>
      <button type="button" class="btn-d btn-d--primary" @click="openModal('edit', selectedRow.id)">Edit</button>
    </template>
  </ModalView>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardHeader from '../Components/DashboardHeader.vue'
import StatisticCard from '../Components/StatisticCard.vue'
import StatisticChart from '../Components/StatisticChart.vue'
import DataTable from '../Components/DataTable.vue'
import FilterDrawer from '../Components/FilterDrawer.vue'
import ModalCreate from '../Components/ModalCreate.vue'
import ModalEdit from '../Components/ModalEdit.vue'
import ModalDelete from '../Components/ModalDelete.vue'
import ModalView from '../Components/ModalView.vue'
import { useToast } from '../composables/useToast.js'

const props = defineProps(['books', 'weeklyLabels', 'weeklyValues', 'newBooksThisMonth', 'totalBooks', 'search'])
const toast = useToast()
const filterOpen = ref(false)
const currentModal = ref(null)
const selectedRow = ref(null)
const mutationLoading = ref(false)
const createFormTitle = ref('')
const createFormAuthor = ref('')
const createFormDescription = ref('')
const createFormThumbnail = ref('')
const createFormRating = ref(3)
const editFormTitle = ref('')
const editFormAuthor = ref('')
const editFormDescription = ref('')
const editFormThumbnail = ref('')
const editFormRating = ref(3)

const filterDraft = ref({
  search: props.search || '',
})

watch(() => props.search, (newSearch) => {
  filterDraft.value.search = newSearch || ''
}, { immediate: true })

const columns = [
  { key: 'book', label: 'Buku' },
  { key: 'rating', label: 'Rating' },
  { key: 'created', label: 'Dibuat' },
  { key: 'actions', label: 'Aksi' }
]

const booksFormatted = computed(() => {
  const books = props.books?.data || [];
  return books.map(book => ({
    ...book,
    initials: book.title.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2),
    createdAt: new Date(book.created_at).toLocaleDateString('id-ID', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    }),
    ratingBadge: book.rating >= 4 ? 'badge--success' : book.rating >= 2 ? 'badge--warning' : 'badge--danger'
  }))
})

const tableSummary = computed(() => {
  const from = props.books?.from ?? 0;
  const to = props.books?.to ?? 0;
  const total = props.books?.total ?? 0;
  return `Menampilkan ${from} - ${to} dari ${total} buku`;
})

function openModal(type, rowId = null) {
  currentModal.value = type
  if (rowId) {
    const row = booksFormatted.value.find(b => b.id === rowId)
    selectedRow.value = row
    if (row) {
      editFormTitle.value = row.title
      editFormAuthor.value = row.author
      editFormDescription.value = row.description
      editFormThumbnail.value = row.thumbnail
      editFormRating.value = row.rating
    }
  }
}

function closeModal() {
  currentModal.value = null
  selectedRow.value = null
}

function applyFilters() {
  filterOpen.value = false
  router.get(route('book'), {
    search: filterDraft.value.search,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Filter diterapkan')
    }
  })
}

function resetFilters() {
  filterDraft.value = {
    search: '',
  }
  router.get(route('book'), {}, {
    preserveScroll: true,
    onSuccess: () => {
      toast.info('Filter direset')
    }
  })
}

function goToPage(url) {
  if (url) {
    router.get(url);
  }
}

function handleCreate() {
  mutationLoading.value = true
  router.post(route('book.store'), {
    title: createFormTitle.value,
    author: createFormAuthor.value,
    description: createFormDescription.value,
    thumbnail: createFormThumbnail.value,
    rating: createFormRating.value
  }, {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      createFormTitle.value = ''
      createFormAuthor.value = ''
      createFormDescription.value = ''
      createFormThumbnail.value = ''
      createFormRating.value = 3
      toast.success('Buku berhasil ditambahkan')
    }
  })
}

function handleEdit() {
  mutationLoading.value = true
  router.put(route('book.update', { book: selectedRow.value.id }), {
    title: editFormTitle.value,
    author: editFormAuthor.value,
    description: editFormDescription.value,
    thumbnail: editFormThumbnail.value,
    rating: editFormRating.value
  }, {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      toast.success('Buku berhasil diperbarui')
    }
  })
}

function handleDelete() {
  mutationLoading.value = true
  router.delete(route('book.destroy', { book: selectedRow.value.id }), {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      toast.success('Buku berhasil dihapus')
    }
  })
}
</script>
