<script setup>
defineOptions({ name: 'TeamPage' })
</script>

<template>
  <div class="about-page">
       <section class="about-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">{{ $t('about.title') }}</h1>
        <p class="page-subtitle">{{ $t('about.subtitle') }}</p>
      </div>
    </section>
    <section class="teams-section">
      <img src="@/assets/img/001.jpg" alt="Our Teams Organization Chart" class="teams-full-image" />
    </section></div>
</template>

<style scoped>
</style>
