import { inject } from 'vue'

const USER_TOTAL_KEY = Symbol('user-total')

export function useUserTotalBadge() {
  return inject(USER_TOTAL_KEY, {
    totalUsers: { value: 0 },
    totalBooks: { value: 0 },
    setTotalUsers: () => {},
    setTotalBooks: () => {},
    formatSidebarUserCount: (c) => c
  })
}

export { USER_TOTAL_KEY }
