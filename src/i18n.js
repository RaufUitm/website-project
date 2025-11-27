import { createI18n } from 'vue-i18n'
import en from './locales/en.json'
import ms from './locales/ms.json'

const messages = { en, ms }

const i18n = createI18n({
  legacy: false,
  globalInjection: true,
  locale: localStorage.getItem('locale') || 'en',
  fallbackLocale: 'en',
  messages,
})

export default i18n
