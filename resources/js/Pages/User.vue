<template>
  <DashboardHeader
    title="Kelola Pengguna"
    subtitle="Daftar pengguna yang terdaftar di sistem"
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
        <span class="btn-d__label">Tambah Pengguna</span>
      </button>
    </template>

    <section class="grid-stats">
      <StatisticCard
        title="Total Pengguna"
        :value="activeUsersCount"
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
    </section>

    <section class="charts-grid">
      <StatisticChart
        title="Pertumbuhan Pengguna"
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
        <tr v-for="row in usersFormatted" :key="row.id">
          <td>
            <div class="table__user">
              <span class="table__avatar">{{ row.initials }}</span>
              <div class="table__primary">
                <span class="table__primary-name">{{ row.name }}</span>
                <span class="table__primary-sub">{{ row.email }}</span>
              </div>
            </div>
          </td>
          <td>
            <span class="badge" :class="row.roleBadge">{{ row.role }}</span>
          </td>
          <td>
            <span class="badge" :class="row.statusBadge">{{ row.status ? 'Aktif' : 'Tidak Aktif' }}</span>
          </td>
          <td>
            <span class="badge" :class="row.emailVerifiedBadge">{{ row.emailVerified ? 'Terverifikasi' : 'Belum Terverifikasi' }}</span>
          </td>
          <td>{{ row.joinedAt }}</td>
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
        <button type="button" :disabled="!users.prev_page_url" @click="goToPage(users.prev_page_url)">&lsaquo;</button>
        <span class="pagination__info">{{ tableSummary }}</span>
        <button type="button" :disabled="!users.next_page_url" @click="goToPage(users.next_page_url)">&rsaquo;</button>
      </template>
    </DataTable>
  </DashboardHeader>

  <!-- Modals & Filter Drawer -->
  <FilterDrawer
    :open="filterOpen"
    title="Filter Pengguna"
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
        <input class="filter-input" type="search" v-model="filterDraft.search" placeholder="Cari nama/email pengguna..." />
      </div>
    </div>
    <div class="filter-group">
      <label class="filter-group__label">Status</label>
      <select class="filter-input" v-model="filterDraft.status">
        <option value="">Semua Status</option>
        <option value="1">Aktif</option>
        <option value="0">Tidak Aktif</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-group__label">Verifikasi Email</label>
      <select class="filter-input" v-model="filterDraft.emailVerified">
        <option value="">Semua</option>
        <option value="1">Terverifikasi</option>
        <option value="0">Belum Terverifikasi</option>
      </select>
    </div>
  </FilterDrawer>

  <ModalCreate
    :open="currentModal === 'create'"
    title="Tambah Pengguna"
    subtitle="Tambah pengguna baru ke sistem"
    submit-label="Tambah"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleCreate"
  >
    <template #body>
      <div class="form-field">
        <label class="form-field__label" for="ucName">Nama</label>
        <input id="ucName" name="name" class="filter-input" type="text" required placeholder="Nama lengkap" v-model="createFormName" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ucEmail">Email</label>
        <input id="ucEmail" name="email" class="filter-input" type="email" required placeholder="email@example.com" v-model="createFormEmail" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ucRole">Peran</label>
        <select id="ucRole" name="role" class="filter-input" required v-model="createFormRole">
          <option value="USER">USER</option>
          <option value="SUPER ADMIN">SUPER ADMIN</option>
        </select>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ucStatus">Status</label>
        <select id="ucStatus" name="status" class="filter-input" required v-model="createFormStatus">
          <option :value="1">Aktif</option>
          <option :value="0">Tidak Aktif</option>
        </select>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ucPassword">Password</label>
        <input id="ucPassword" name="password" class="filter-input" type="password" required minlength="6" placeholder="Password" v-model="createFormPassword" />
      </div>
    </template>
  </ModalCreate>

  <ModalEdit
    :open="currentModal === 'edit'"
    title="Edit Pengguna"
    subtitle="Perbarui detail pengguna"
    submit-label="Simpan"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleEdit"
  >
    <template #body>
      <div class="form-field">
        <label class="form-field__label" for="ueName">Nama</label>
        <input id="ueName" name="name" class="filter-input" type="text" required v-model="editFormName" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ueEmail">Email</label>
        <input id="ueEmail" name="email" class="filter-input" type="email" required v-model="editFormEmail" />
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ueRole">Peran</label>
        <select id="ueRole" name="role" class="filter-input" required v-model="editFormRole">
          <option value="USER">USER</option>
          <option value="SUPER ADMIN">SUPER ADMIN</option>
        </select>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="ueStatus">Status</label>
        <select id="ueStatus" name="status" class="filter-input" required v-model="editFormStatus">
          <option :value="1">Aktif</option>
          <option :value="0">Tidak Aktif</option>
        </select>
      </div>
      <div class="form-field">
        <label class="form-field__label" for="uePassword">Password Baru (Opsional)</label>
        <input id="uePassword" name="password" class="filter-input" type="password" minlength="6" placeholder="Password baru (opsional)" v-model="editFormPassword" />
      </div>
    </template>
  </ModalEdit>

  <ModalDelete
    :open="currentModal === 'delete'"
    title="Hapus Pengguna"
    subtitle="Pengguna akan dihapus dari sistem"
    message="Apakah Anda yakin ingin menghapus pengguna ini?"
    submit-label="Hapus"
    :disabled="mutationLoading"
    @close="closeModal"
    @submit="handleDelete"
  />

  <ModalView
    :open="currentModal === 'view'"
    title="Lihat Pengguna"
    subtitle="Detail pengguna"
    @close="closeModal"
  >
    <template #body>
      <dl class="detail-list">
        <div class="detail-row">
          <dt>Nama</dt>
          <dd>{{ selectedRow?.name }}</dd>
        </div>
        <div class="detail-row">
          <dt>Email</dt>
          <dd>{{ selectedRow?.email }}</dd>
        </div>
        <div class="detail-row">
          <dt>Verifikasi Email</dt>
          <dd>{{ selectedRow?.emailVerified ? 'Terverifikasi' : 'Belum Terverifikasi' }}</dd>
        </div>
        <div class="detail-row">
          <dt>Peran</dt>
          <dd>{{ selectedRow?.role }}</dd>
        </div>
        <div class="detail-row">
          <dt>Status</dt>
          <dd>{{ selectedRow?.status ? 'Aktif' : 'Tidak Aktif' }}</dd>
        </div>
        <div class="detail-row">
          <dt>Bergabung Pada</dt>
          <dd>{{ selectedRow?.joinedAt }}</dd>
        </div>
      </dl>
    </template>
    <template #footer-actions>
      <button type="button" class="btn-d btn-d--primary" @click="openModal('edit', selectedRow.id)">Edit</button>
    </template>
  </ModalView>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
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
import { useUserTotalBadge } from '../composables/useUserTotalBadge.js'

const props = defineProps(['users', 'weeklyLabels', 'weeklyValues', 'newUsersThisMonth', 'search', 'status', 'emailVerified', 'activeUsersCount', 'totalBooksCount'])
const toast = useToast()
const { setTotalUsers, setTotalBooks } = useUserTotalBadge()
const filterOpen = ref(false)
const currentModal = ref(null)
const selectedRow = ref(null)
const mutationLoading = ref(false)
const createFormName = ref('')
const createFormEmail = ref('')
const createFormPassword = ref('')
const createFormRole = ref('USER')
const createFormStatus = ref(1)
const editFormName = ref('')
const editFormEmail = ref('')
const editFormPassword = ref('')
const editFormRole = ref('USER')
const editFormStatus = ref(1)

const filterDraft = ref({
  search: props.search || '',
  status: props.status || '',
  emailVerified: props.emailVerified || '',
  date_from: '',
  date_to: '',
  sort_direction: 'desc'
})

watch(() => [props.search, props.status, props.emailVerified], ([newSearch, newStatus, newEmailVerified]) => {
  filterDraft.value.search = newSearch || ''
  filterDraft.value.status = newStatus || ''
  filterDraft.value.emailVerified = newEmailVerified || ''
}, { immediate: true })

const columns = [
  { key: 'user', label: 'Pengguna' },
  { key: 'role', label: 'Peran' },
  { key: 'status', label: 'Status' },
  { key: 'emailVerified', label: 'Verifikasi Email' },
  { key: 'joined', label: 'Bergabung' },
  { key: 'actions', label: 'Aksi' }
]

const usersFormatted = computed(() => {
  const users = props.users?.data || [];
  return users.map(user => ({
    ...user,
    initials: user.name?.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) ?? '',
    joinedAt: user.created_at ? new Date(user.created_at).toLocaleDateString('id-ID', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    }) : '',
    roleBadge: user.role === 'HRD' ? 'badge--primary' : 'badge--muted',
    statusBadge: user.status ? 'badge--success' : 'badge--danger',
    emailVerified: !!user.email_verified_at,
    emailVerifiedBadge: !!user.email_verified_at ? 'badge--success' : 'badge--warning'
  }))
})

const tableSummary = computed(() => {
  const from = props.users?.from ?? 0;
  const to = props.users?.to ?? 0;
  const total = props.users?.total ?? 0;
  return `Menampilkan ${from} - ${to} dari ${total} pengguna`;
})

onMounted(() => {
  setTotalUsers(props.activeUsersCount)
  setTotalBooks(props.totalBooksCount)
})

watch(() => props.activeUsersCount, (newCount) => {
  setTotalUsers(newCount)
})

watch(() => props.totalBooksCount, (newCount) => {
  setTotalBooks(newCount)
})

function openModal(type, rowId = null) {
  currentModal.value = type
  if (rowId) {
    const row = usersFormatted.value.find(u => u.id === rowId)
    selectedRow.value = row
    editFormName.value = row.name
    editFormEmail.value = row.email
    editFormPassword.value = ''
    editFormRole.value = row.role
    editFormStatus.value = row.status
  }
}

function closeModal() {
  currentModal.value = null
  selectedRow.value = null
}

function applyFilters() {
  filterOpen.value = false
  const query = {}
  if (filterDraft.value.search) query.search = filterDraft.value.search
  if (filterDraft.value.status !== '') query.status = filterDraft.value.status
  if (filterDraft.value.emailVerified !== '') query.email_verified = filterDraft.value.emailVerified
  
  router.get(route('user'), query, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Filter diterapkan')
    }
  })
}

function resetFilters() {
  filterDraft.value = {
    search: '',
    status: '',
    emailVerified: '',
    date_from: '',
    date_to: '',
    sort_direction: 'desc'
  }
  router.get(route('user'), {}, {
    preserveScroll: true,
    onSuccess: () => {
      toast.info('Filter direset')
    }
  })
}

function handleCreate() {
  mutationLoading.value = true
  router.post(route('user.store'), {
    name: createFormName.value,
    email: createFormEmail.value,
    password: createFormPassword.value,
    role: createFormRole.value,
    status: createFormStatus.value
  }, {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      createFormName.value = ''
      createFormEmail.value = ''
      createFormPassword.value = ''
      createFormRole.value = 'Employee'
      createFormStatus.value = 1
      toast.success('Pengguna berhasil ditambahkan')
    }
  })
}

function handleEdit() {
  mutationLoading.value = true
  router.put(route('user.update', { user: selectedRow.value.id }), {
    name: editFormName.value,
    email: editFormEmail.value,
    password: editFormPassword.value || undefined,
    role: editFormRole.value,
    status: editFormStatus.value
  }, {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      toast.success('Pengguna berhasil diperbarui')
    }
  })
}

function handleDelete() {
  mutationLoading.value = true
  router.delete(route('user.destroy', { user: selectedRow.value.id }), {
    onFinish: () => {
      mutationLoading.value = false
    },
    onSuccess: () => {
      closeModal()
      toast.success('Pengguna berhasil dihapus')
    }
  })
}

function goToPage(url) {
  if (url) {
    router.get(url);
  }
}
</script>
