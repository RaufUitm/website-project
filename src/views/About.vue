<script setup>
import { ref } from 'vue'

defineOptions({ name: 'AboutPage' })

const values = ref([
  {
    title: 'Taqwa',
    icon: '@/assets/icons/taqwa.svg',
    description:
      'Conscious integrity and responsibility; doing what is right with sincerity and ethics.',
    color: '#1d4ed8',
  },
  {
    title: 'Credibility',
    icon: '@/assets/icons/credibility.svg',
    description: 'Reliable and trustworthy in our promises, delivery, and transparency.',
    color: '#15803d',
  },
  {
    title: 'Teamwork',
    icon: '@/assets/icons/teamwork.svg',
    description: 'We collaborate, share ownership, and move in one direction to achieve results.',
    color: '#1d4ed8',
  },
  {
    title: 'Professional',
    icon: '@/assets/icons/professional.svg',
    description: 'Competent, disciplined and accountable; we uphold standards in every action.',
    color: '#15803d',
  },
  {
    title: 'Customer Centric',
    icon: '@/assets/icons/customer-centric.svg',
    description: 'We prioritise user needs, respond quickly, and improve from feedback.',
    color: '#1d4ed8',
  },
  {
    title: 'Exemplary Leadership',
    icon: '@/assets/icons/leadership.svg',
    description: 'We lead by example, empower others, and act with vision and integrity.',
    color: '#15803d',
  },
])

const duplicatedValues = ref([...values.value, ...values.value])

// Track mouse position for interactive glow
const updateMousePosition = (e) => {
  const heroSection = e.currentTarget
  const rect = heroSection.getBoundingClientRect()
  const x = ((e.clientX - rect.left) / rect.width) * 100
  const y = ((e.clientY - rect.top) / rect.height) * 100
  heroSection.style.setProperty('--mouse-x', `${x}%`)
  heroSection.style.setProperty('--mouse-y', `${y}%`)
}
</script>

<template>
  <div class="about-page">
    <!-- Hero heading -->
    <section class="hero-who-section" @mousemove="updateMousePosition">
      <div class="gradient-overlay"></div>
      <div class="wave-overlay"></div>
      <div class="interactive-glow"></div>

      <div class="max-w-8xl mx-auto px-6">
        <h1 class="hero-title">"Together We Make A Difference"</h1>
      </div>

      <!-- Shared Values Title -->
      <div class="shared-values-header">
        <h2 class="shared-values-title">Our <span class="outline-text">Shared Values</span></h2>
      </div>

      <!-- Full-width moving shared values -->
      <div class="w-screen overflow-hidden">
        <div class="values-track flex gap-10">
          <div
            v-for="(value, i) in duplicatedValues"
            :key="`val-${i}`"
            class="value-card bg-white rounded-2xl shadow-lg flex flex-col items-center text-center px-10 py-12"
          >
            <div
              class="w-28 h-28 rounded-full flex items-center justify-center mb-6 shadow-md"
              :style="{ backgroundColor: '#eef2f7', border: `4px solid ${value.color}` }"
            >
              <img
                v-if="value.icon"
                :src="value.icon"
                :alt="`${value.title} icon`"
                class="w-16 h-16 object-contain"
                loading="lazy"
              />
              <span v-else class="text-3xl font-bold" :style="{ color: value.color }">
                {{ value.title.charAt(0) }}
              </span>
            </div>
            <h3
              class="text-xl font-semibold uppercase tracking-wide"
              :style="{ color: value.color }"
            >
              {{ value.title }}
            </h3>
            <p class="mt-4 text-sm text-gray-600 leading-relaxed max-w-xs">
              {{ value.description }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Vision & Mission Zigzag -->
    <section class="vision-mission-section">
      <!-- Vision -->
      <div class="vm-split vision-split">
        <div class="vm-image-full">
          <img
            src="@/assets/img/aerial-view-shanghai-overpass-night.jpg"
            alt="Vision illustration"
            class="vm-bg-image"
          />
          <div class="vm-overlay"></div>
        </div>
        <div class="vm-content-box vm-content-right">
          <h2 class="vm-heading">OUR <span class="vm-highlight-green">VISION</span></h2>
          <p class="vm-description">
            The pioneer and leader in the development of Terengganu's digital economy and digital
            society.
          </p>
        </div>
      </div>

      <!-- Mission -->
      <div class="vm-split mission-split">
        <div class="vm-image-full">
          <img
            src="@/assets/img/light-trails-buildings.jpg"
            alt="Mission illustration"
            class="vm-bg-image"
          />
          <div class="vm-overlay"></div>
        </div>
        <div class="vm-content-box vm-content-left">
          <h2 class="vm-heading">OUR <span class="vm-highlight-green">MISSION</span></h2>
          <p class="vm-description">
            Driving the growth of Terengganu's digital economy and digital society through smart
            digital transformation.
          </p>
        </div>
      </div>
    </section>

    <!-- Board of Directors Section -->
    <section class="board-section">
      <img src="@/assets/img/002.jpg" alt="Board of Directors" class="board-full-image" />
    </section>

    <!-- Our Teams Section -->
    <section class="teams-section">
      <img src="@/assets/img/001.jpg" alt="Our Teams Organization Chart" class="teams-full-image" />
    </section>
  </div>
</template>
