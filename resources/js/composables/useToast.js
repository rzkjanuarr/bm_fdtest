import { inject } from 'vue'

const TOAST_KEY = Symbol('toast')

export function useToast() {
  return inject(TOAST_KEY, {
    success: () => {},
    error: () => {},
    info: () => {},
    show: () => {}
  })
}

export { TOAST_KEY }
