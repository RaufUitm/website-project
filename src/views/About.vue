<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

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

// Board of Directors data
const directors = ref([
  {
    image: '/src/assets/img/2.png',
  },
  {
    image: '/src/assets/img/1.png',
  },
  {
    image: '/src/assets/img/8.png',
  },
  {
    image: '/src/assets/img/3.png',
  },
  {
    image: '/src/assets/img/4.png',
  },
  {
    image: '/src/assets/img/5.png',
  },
  {
    image: '/src/assets/img/6.png',
  },
  {
    image: '/src/assets/img/7.png',
  },
])

// Duplicate directors for infinite loop effect
const duplicatedDirectors = ref([...directors.value, ...directors.value, ...directors.value])

// Carousel drag functionality with momentum
const carouselRef = ref(null)
const isDragging = ref(false)
const startX = ref(0)
const scrollLeft = ref(0)
const velocity = ref(0)
const lastX = ref(0)
const lastTime = ref(0)

const handleMouseDown = (e) => {
  if (!carouselRef.value) return
  isDragging.value = true
  startX.value = e.pageX - carouselRef.value.offsetLeft
  scrollLeft.value = carouselRef.value.scrollLeft
  lastX.value = e.pageX
  lastTime.value = Date.now()
  velocity.value = 0
  carouselRef.value.style.cursor = 'grabbing'
  carouselRef.value.style.scrollBehavior = 'auto'
}

const handleMouseMove = (e) => {
  if (!isDragging.value || !carouselRef.value) return
  e.preventDefault()

  const x = e.pageX - carouselRef.value.offsetLeft
  const walk = (x - startX.value) * 1.5
  carouselRef.value.scrollLeft = scrollLeft.value - walk

  // Calculate velocity for momentum
  const currentTime = Date.now()
  const timeDiff = currentTime - lastTime.value
  if (timeDiff > 0) {
    velocity.value = (e.pageX - lastX.value) / timeDiff
  }
  lastX.value = e.pageX
  lastTime.value = currentTime
}

const applyMomentum = () => {
  if (!carouselRef.value || Math.abs(velocity.value) < 0.05) {
    velocity.value = 0
    return
  }

  carouselRef.value.scrollLeft -= velocity.value * 30
  velocity.value *= 0.92 // Friction - slower decay for longer momentum

  requestAnimationFrame(applyMomentum)
}

const handleMouseUp = () => {
  isDragging.value = false
  if (carouselRef.value) {
    carouselRef.value.style.cursor = 'grab'
    if (Math.abs(velocity.value) > 0.05) {
      applyMomentum()
    }
  }
}

const handleMouseLeave = () => {
  if (isDragging.value) {
    isDragging.value = false
    if (carouselRef.value) {
      carouselRef.value.style.cursor = 'grab'
      if (Math.abs(velocity.value) > 0.05) {
        applyMomentum()
      }
    }
  }
}

// Touch events for mobile with momentum
const handleTouchStart = (e) => {
  if (!carouselRef.value) return
  isDragging.value = true
  startX.value = e.touches[0].pageX - carouselRef.value.offsetLeft
  scrollLeft.value = carouselRef.value.scrollLeft
  lastX.value = e.touches[0].pageX
  lastTime.value = Date.now()
  velocity.value = 0
  carouselRef.value.style.scrollBehavior = 'auto'
}

const handleTouchMove = (e) => {
  if (!isDragging.value || !carouselRef.value) return

  const x = e.touches[0].pageX - carouselRef.value.offsetLeft
  const walk = (x - startX.value) * 1.5
  carouselRef.value.scrollLeft = scrollLeft.value - walk

  // Calculate velocity for momentum
  const currentTime = Date.now()
  const timeDiff = currentTime - lastTime.value
  if (timeDiff > 0) {
    velocity.value = (e.touches[0].pageX - lastX.value) / timeDiff
  }
  lastX.value = e.touches[0].pageX
  lastTime.value = currentTime
}

const handleTouchEnd = () => {
  isDragging.value = false
  if (carouselRef.value && Math.abs(velocity.value) > 0.05) {
    applyMomentum()
  }
}

// Infinite scroll logic
const checkInfiniteScroll = () => {
  if (!carouselRef.value) return

  const carousel = carouselRef.value
  const currentScroll = carousel.scrollLeft

  // Calculate one set width (8 directors)
  const oneSetWidth = carousel.scrollWidth / 3

  // If scrolled past the second set, jump back to first set
  if (currentScroll >= oneSetWidth * 2) {
    carousel.scrollLeft = oneSetWidth
  }
  // If scrolled before the first set, jump to second set
  else if (currentScroll <= 0) {
    carousel.scrollLeft = oneSetWidth
  }
}

onMounted(() => {
  if (carouselRef.value) {
    carouselRef.value.addEventListener('mousedown', handleMouseDown)
    carouselRef.value.addEventListener('mousemove', handleMouseMove)
    carouselRef.value.addEventListener('mouseup', handleMouseUp)
    carouselRef.value.addEventListener('mouseleave', handleMouseLeave)
    carouselRef.value.addEventListener('touchstart', handleTouchStart)
    carouselRef.value.addEventListener('touchmove', handleTouchMove)
    carouselRef.value.addEventListener('touchend', handleTouchEnd)
    carouselRef.value.addEventListener('scroll', checkInfiniteScroll)

    // Set initial scroll position to middle set
    setTimeout(() => {
      if (carouselRef.value) {
        const oneSetWidth = carouselRef.value.scrollWidth / 3
        carouselRef.value.scrollLeft = oneSetWidth
      }
    }, 100)
  }
})

onBeforeUnmount(() => {
  if (carouselRef.value) {
    carouselRef.value.removeEventListener('mousedown', handleMouseDown)
    carouselRef.value.removeEventListener('mousemove', handleMouseMove)
    carouselRef.value.removeEventListener('mouseup', handleMouseUp)
    carouselRef.value.removeEventListener('mouseleave', handleMouseLeave)
    carouselRef.value.removeEventListener('touchstart', handleTouchStart)
    carouselRef.value.removeEventListener('touchmove', handleTouchMove)
    carouselRef.value.removeEventListener('touchend', handleTouchEnd)
    carouselRef.value.removeEventListener('scroll', checkInfiniteScroll)
  }
})
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
    <section class="py-20 custom-section-bg">
      <div class="board-header">
        <h2 class="board-title">BOARD OF <span class="board-highlight">DIRECTORS</span></h2>
      </div>
      <div ref="carouselRef" class="board-carousel">
        <div class="board-track">
          <div v-for="(director, index) in duplicatedDirectors" :key="index" class="director-card">
            <img :src="director.image" :alt="`Director ${index}`" class="director-image" />
          </div>
        </div>
      </div>
    </section>

    <!-- Our Teams Section -->
    <section class="teams-section">
      <img src="@/assets/img/001.jpg" alt="Our Teams Organization Chart" class="teams-full-image" />
    </section>
  </div>
</template>
