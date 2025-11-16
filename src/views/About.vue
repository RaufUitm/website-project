<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineOptions({ name: 'AboutPage' })

// Wireframe Animation Variables
const canvasRef = ref(null)
let animationFrameId = null
let particles = []
const particleCount = 80
const maxDistance = 200

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
    image: new URL('@/assets/img/2.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/1.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/8.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/5.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/4.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/3.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/6.png', import.meta.url).href,
  },
  {
    image: new URL('@/assets/img/7.png', import.meta.url).href,
  },
])

// Scroll animation observer
const setupScrollAnimations = () => {
  const observerOptions = {
    threshold: 0.2,
    rootMargin: '0px 0px -100px 0px',
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible')
      }
    })
  }, observerOptions)

  // Observe vision/mission cards
  document.querySelectorAll('.vm-card').forEach((card) => {
    observer.observe(card)
  })

  // Observe director cards
  document.querySelectorAll('.director-card').forEach((card, index) => {
    card.style.transitionDelay = `${index * 0.1}s`
    observer.observe(card)
  })

  return observer
}

// Wireframe Animation Functions
class Particle {
  constructor(canvas) {
    this.canvas = canvas
    this.x = Math.random() * canvas.width
    this.y = Math.random() * canvas.height
    this.vx = (Math.random() - 0.5) * 0.5
    this.vy = (Math.random() - 0.5) * 0.5
    this.radius = Math.random() * 3 + 2
    // Random colors: blue (#397ab0) or green (#8fd15f)
    this.color = Math.random() > 0.5 ? '#397ab0' : '#8fd15f'
  }

  update() {
    this.x += this.vx
    this.y += this.vy

    // Bounce off edges
    if (this.x < 0 || this.x > this.canvas.width) this.vx *= -1
    if (this.y < 0 || this.y > this.canvas.height) this.vy *= -1

    // Keep within bounds
    this.x = Math.max(0, Math.min(this.canvas.width, this.x))
    this.y = Math.max(0, Math.min(this.canvas.height, this.y))
  }

  draw(ctx) {
    ctx.beginPath()
    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2)
    ctx.fillStyle = this.color
    ctx.fill()
  }
}

const initWireframe = () => {
  const canvas = canvasRef.value
  if (!canvas) return

  const ctx = canvas.getContext('2d')

  // Set canvas size
  const resizeCanvas = () => {
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight

    // Reinitialize particles on resize if needed
    if (particles.length === 0) {
      for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle(canvas))
      }
    }
  }

  resizeCanvas()
  window.addEventListener('resize', resizeCanvas)

  // Animation loop
  const animate = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height)

    // Update and draw particles
    particles.forEach((particle) => {
      particle.update()
      particle.draw(ctx)
    })

    // Draw connections
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x
        const dy = particles[i].y - particles[j].y
        const distance = Math.sqrt(dx * dx + dy * dy)

        if (distance < maxDistance) {
          const opacity = (1 - distance / maxDistance) * 0.5
          ctx.beginPath()
          ctx.strokeStyle = `rgba(57, 122, 176, ${opacity})`
          ctx.lineWidth = 1.2
          ctx.moveTo(particles[i].x, particles[i].y)
          ctx.lineTo(particles[j].x, particles[j].y)
          ctx.stroke()
        }
      }
    }

    animationFrameId = requestAnimationFrame(animate)
  }

  animate()
}

let observer = null

onMounted(() => {
  observer = setupScrollAnimations()
  initWireframe()
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
  }
})
</script>

<template>
  <div class="about-page">
    <!-- Hero heading -->
    <section class="hero-who-section" @mousemove="updateMousePosition">
      <!-- Animated Wireframe Canvas -->
      <canvas ref="canvasRef" class="wireframe-canvas"></canvas>

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
      <div class="board-header">
        <h2 class="board-title">BOARD OF <span class="board-highlight">DIRECTORS</span></h2>
      </div>
      <div class="board-pyramid-container">
        <!-- Row 1: Chairman (1 person) -->
        <div class="pyramid-row row-1">
          <div class="director-card">
            <img :src="directors[0].image" alt="Chairman" class="director-image" />
          </div>
        </div>

        <!-- Row 2: 2 Directors -->
        <div class="pyramid-row row-2">
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

    <!-- Our Teams Section -->
    <section class="teams-section">
      <img src="@/assets/img/001.jpg" alt="Our Teams Organization Chart" class="teams-full-image" />
    </section>
  </div>
</template>
