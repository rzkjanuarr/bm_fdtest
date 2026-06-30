<template>
  <div>
    <div v-if="menuOpen" class="user-menu-backdrop is-open" aria-hidden @click="menuOpen = false" />
    <div :class="['dash', collapsed ? 'is-collapsed' : '']">
      <aside :class="['sidebar', mobileOpen ? 'is-open' : '']" id="sidebar">
        <Link href="/dashboard" class="sidebar__brand">
          <img :src="'/assets/logo.svg'" alt="" class="sidebar__brand-mark" width="36" height="36" />
          <span class="sidebar__brand-name">Asietex</span>
        </Link>

        <nav class="sidebar__nav" data-sidebar-nav>
          <template v-for="(group, gIdx) in navGroups" :key="`${group.index || 'g'}-${gIdx}`">
            <hr v-if="gIdx > 0" class="sidebar__separator" aria-hidden />
            <div v-if="group.label" class="sidebar__group-label">
              {{ group.label }}
            </div>
            <template v-for="(item, iIdx) in group.items" :key="`${item.index || 'i'}-${item.nav}-${iIdx}`">
              <Link
                :href="pathForSidebarNav(item.nav)"
                :class="['sidebar__item', page.url === pathForSidebarNav(item.nav) ? 'is-active' : '']"
                :data-nav="item.nav"
              >
                <span v-if="item.icon" style="display: contents" v-html="injectSidebarIconClass(item.icon)" />
                <span class="sidebar__item-label">{{ item.label }}</span>
                <span v-if="item.nav === 'user'" class="sidebar__count">{{ formatSidebarUserCount(totalUsers) }}</span>
                <span v-else-if="item.nav === 'book'" class="sidebar__count">{{ formatSidebarUserCount(totalBooks) }}</span>
                <span v-else-if="item.count" class="sidebar__count">{{ item.count }}</span>
              </Link>
            </template>
          </template>
        </nav>

        <div ref="footerRef" :class="['sidebar__footer', menuOpen ? 'is-open' : '']" data-user-menu>
          <button
            type="button"
            class="sidebar__user"
            @click.stop="menuOpen = !menuOpen"
          >
            <span class="sidebar__user-avatar">{{ sidebarInitials }}</span>
            <div class="sidebar__user-info">
              <span class="sidebar__user-name">{{ sidebarName }}</span>
              <span class="sidebar__user-email">{{ sidebarEmail }}</span>
            </div>
            <svg class="sidebar__user-caret" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden>
              <polyline points="9 6 15 12 9 18" />
            </svg>
          </button>
          <div class="sidebar__user-menu" role="menu">
            <button
              type="button"
              class="sidebar__user-menu-item sidebar__user-menu-item--danger"
              role="menuitem"
              :disabled="logoutLoading"
              :aria-busy="logoutLoading"
              @click.stop="handleLogout"
            >
              <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
              </svg>
              <span>Keluar</span>
            </button>
          </div>
        </div>
      </aside>

      <button type="button" :class="['sidebar-backdrop', mobileOpen ? 'is-open' : '']" @click="mobileOpen = false" aria-label="close sidebar" />

      <main class="main">
        <header :class="['topbar', topbarScrolled ? 'is-scrolled' : '']">
          <div class="topbar__left">
            <button
              class="topbar__burger"
              type="button"
              @click="handleToggleSidebar"
            >
              <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <line x1="9" y1="3" x2="9" y2="21" />
              </svg>
            </button>
            <div>
              <div class="topbar__title">{{ title }}</div>
              <div class="topbar__subtitle">{{ subtitle }}</div>
            </div>
          </div>

          <div class="topbar__right">
            <div class="topbar__actions">
              <slot name="action1"></slot>
              <slot name="action2"></slot>
            </div>
          </div>
        </header>

        <section class="page"><slot /></section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import SIDEBAR_NAV from '../config/sidebar-nav.json'
import { useUserTotalBadge } from '../composables/useUserTotalBadge.js'

const props = defineProps(['title', 'subtitle'])
const page = usePage()

const { totalUsers, totalBooks, formatSidebarUserCount } = useUserTotalBadge()

const sidebarName = computed(() => page.props.auth?.user?.name || 'User')
const sidebarEmail = computed(() => page.props.auth?.user?.email || 'user@example.com')
const sidebarInitials = computed(() => {
  const name = sidebarName.value
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})
const mobileOpen = ref(false)
const collapsed = ref(false)
const menuOpen = ref(false)
const topbarScrolled = ref(false)
const logoutLoading = ref(false)

const footerRef = ref(null)

const navGroups = computed(() => {
  return [...(SIDEBAR_NAV.groups || [])]
    .sort((a, b) => (a.index ?? 0) - (b.index ?? 0))
    .map(g => ({
      ...g,
      items: [...(g.items || [])].sort((a, b) => (a.index ?? 0) - (b.index ?? 0)),
    }))
})

function injectSidebarIconClass(svg) {
  if (!svg) return ''
  return svg.replace(/<svg\b(?![^>]*\bclass=)/, '<svg class="sidebar__item-icon"')
}

function pathForSidebarNav(nav) {
  const paths = {
    dashboard: '/dashboard',
    user: '/user',
    book: '/book',
  }
  return paths[nav] || '#'
}

const SCROLL_THRESHOLD = 8
let scrollListener, resizeListener, docClickListener

onMounted(() => {
  scrollListener = () => {
    topbarScrolled.value = window.scrollY > SCROLL_THRESHOLD
  }
  window.addEventListener('scroll', scrollListener, { passive: true })
  scrollListener()

  resizeListener = () => {
    if (window.innerWidth >= 1024) mobileOpen.value = false
  }
  window.addEventListener('resize', resizeListener)

  docClickListener = (e) => {
    if (footerRef.value && !footerRef.value.contains(e.target)) {
      // Add small delay to allow button clicks to process first
      setTimeout(() => {
        menuOpen.value = false
      }, 0)
    }
  }
  document.addEventListener('click', docClickListener)
})

onUnmounted(() => {
  window.removeEventListener('scroll', scrollListener)
  window.removeEventListener('resize', resizeListener)
  document.removeEventListener('click', docClickListener)
})

function handleToggleSidebar() {
  if (window.innerWidth < 1024) {
    mobileOpen.value = !mobileOpen.value
    return
  }
  collapsed.value = !collapsed.value
}

function handleLogout() {
  if (logoutLoading.value) return

  logoutLoading.value = true
  menuOpen.value = false

  router.post(route('logout'), {}, {
    onFinish: () => {
      logoutLoading.value = false
    },
    onError: () => {
      logoutLoading.value = false
    }
  })
}
</script>
