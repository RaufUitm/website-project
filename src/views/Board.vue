<script setup>
import { ref } from 'vue'

defineOptions({ name: 'BoardPage' })

const directors = ref([
  { image: new URL('@/assets/img/2.png', import.meta.url).href },
  { image: new URL('@/assets/img/8.png', import.meta.url).href },
  { image: new URL('@/assets/img/1.png', import.meta.url).href },
  { image: new URL('@/assets/img/5.png', import.meta.url).href },
  { image: new URL('@/assets/img/6.png', import.meta.url).href },
  { image: new URL('@/assets/img/3.png', import.meta.url).href },
  { image: new URL('@/assets/img/4.png', import.meta.url).href },
  { image: new URL('@/assets/img/7.png', import.meta.url).href },
])
</script>

<template>
  <div class="about-page">
     <section class="about-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">Board of Directors</h1>
        <p class="page-subtitle">{{ $t('about.subtitle') }}</p>
      </div>
    </section>
    <section class="board-section">
    >
      <div class="board-pyramid-container">
        <!-- Row 1 -->
        <div class="pyramid-row row-1">
          <div class="director-card">
            <img :src="directors[0].image" alt="Chairman" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[1].image" alt="Director" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[2].image" alt="Director" class="director-image" />
          </div>
        </div>

        <!-- Row 3: 5 Directors -->
        <div class="pyramid-row row-3">
          <div class="director-card">
            <img :src="directors[3].image" alt="Director" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[4].image" alt="Director" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[5].image" alt="Director" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[6].image" alt="Director" class="director-image" />
          </div>
          <div class="director-card">
            <img :src="directors[7].image" alt="Director" class="director-image" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
