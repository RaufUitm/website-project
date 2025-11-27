<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const current = computed(() => locale.value)

const toggle = () => {
  const next = locale.value === 'en' ? 'ms' : 'en'
  locale.value = next
  localStorage.setItem('locale', next)
}
</script>

<template>
  <button @click="toggle" class="lang-toggle" aria-label="Toggle language">
    <span v-if="current === 'en'">EN</span>
    <span v-else>BM</span>
  </button>
</template>

<style scoped>
.lang-toggle {
  border: 1px solid rgba(0, 0, 0, 0);
  background: transparent;
  border-radius: 9999px;
  font-weight: 700;
  cursor: pointer;
}
</style>
