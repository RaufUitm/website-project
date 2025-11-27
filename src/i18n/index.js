import { reactive, readonly } from 'vue'
import en from '../locales/en.json'
import ms from '../locales/ms.json'

const messages = {
  en,
  ms,
}

const state = reactive({
  locale: localStorage.getItem('locale') || 'en',
  messages,
})

export function t(key) {
  const segs = key.split('.')
  let val = state.messages[state.locale]
  for (const s of segs) {
    if (!val) return key
    val = val[s]
  }
  return val ?? key
}

export function setLocale(loc) {
  if (!state.messages[loc]) return
  state.locale = loc
  localStorage.setItem('locale', loc)
}

export function useI18n() {
  return {
    locale: readonly(state).locale,
    t,
    setLocale,
  }
}

export default {
  install: (app) => {
    app.config.globalProperties.$t = t
    app.config.globalProperties.$setLocale = setLocale
    app.provide('i18n', { t, setLocale, locale: state })
  },
}
