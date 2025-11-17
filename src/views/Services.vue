<script setup>
defineOptions({
  name: 'ServicesPage',
})
import { ref, onMounted, onUnmounted } from 'vue'

// Wireframe Animation Variables
const canvasRefs = ref([])
let animationFrameIds = []
let particleArrays = []

// Wireframe Animation Functions
class Particle {
  constructor(canvas) {
    this.canvas = canvas
    this.x = Math.random() * canvas.width
    this.y = Math.random() * canvas.height
    this.vx = (Math.random() - 0.5) * 0.5
    this.vy = (Math.random() - 0.5) * 0.5
    this.radius = Math.random() * 5 + 1
    this.color = Math.random() > 0.5 ? '#397ab0' : '#78c054'
  }

  update() {
    this.x += this.vx
    this.y += this.vy
    if (this.x < 0 || this.x > this.canvas.width) this.vx *= -1
    if (this.y < 0 || this.y > this.canvas.height) this.vy *= -1
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

const initWireframe = (canvas, index) => {
  if (!canvas) return

  const ctx = canvas.getContext('2d')
  const particles = []
  const particleCount = 90
  const maxDistance = 180

  const resizeCanvas = () => {
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight

    if (particles.length === 0) {
      for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle(canvas))
      }
    }
  }

  resizeCanvas()
  window.addEventListener('resize', resizeCanvas)

  const animate = () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height)

    particles.forEach((particle) => {
      particle.update()
      particle.draw(ctx)
    })

    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x
        const dy = particles[i].y - particles[j].y
        const distance = Math.sqrt(dx * dx + dy * dy)

        if (distance < maxDistance) {
          const opacity = (1 - distance / maxDistance) * 0.3
          ctx.beginPath()
          ctx.strokeStyle = `rgba(57, 122, 176, ${opacity})`
          ctx.lineWidth = 1
          ctx.moveTo(particles[i].x, particles[i].y)
          ctx.lineTo(particles[j].x, particles[j].y)
          ctx.stroke()
        }
      }
    }

    animationFrameIds[index] = requestAnimationFrame(animate)
  }

  particleArrays[index] = particles
  animate()
}

onMounted(() => {
  canvasRefs.value.forEach((canvas, index) => {
    if (canvas) {
      initWireframe(canvas, index)
    }
  })
})

onUnmounted(() => {
  animationFrameIds.forEach(id => {
    if (id) cancelAnimationFrame(id)
  })
})

const services = ref([
  {
    id: 'software-development',
    image: new URL('@/assets/img/001.png', import.meta.url).href,
    title: 'Software Development',
    subtitle: 'Custom Solutions for Your Digital Needs',
    description:
      'Transform your business with tailored software solutions designed to meet your unique requirements.',
    details: [
      'Custom web and mobile application development',
      'Enterprise software solutions and system integration',
      'Cloud-based applications and SaaS platforms',
      'API development and third-party integrations',
      'Legacy system modernization and migration',
    ],
    highlight:
      'We developed a health welfare platform for the Terengganu State Government, enabling low-income (B40) families to access essential healthcare services across the state.',
    technologies: ['React', 'Vue.js', 'Node.js', 'Python', 'Java', 'Cloud Services'],
    layout: 'code-terminal', // Custom layout type
  },
  {
    id: 'interactive-analytics-dashboard',
    image: new URL('@/assets/img/Product 03.png', import.meta.url).href,
    title: 'Interactive Analytics Dashboard',
    subtitle: 'Data-Driven Decision Making',
    description:
      'Transform complex data into actionable insights with our comprehensive visualization platform.',
    details: [
      'Real-time data monitoring and visualization',
      'Customizable KPI tracking and reporting',
      'Predictive analytics and forecasting',
      'Multi-source data integration',
      'Role-based access control and security',
      'Mobile-responsive design for on-the-go access',
    ],
    highlight:
      'Our dashboards empower organizations to make informed decisions through intuitive visualizations, automated alerts, and advanced analytics capabilities.',
    technologies: ['Power BI', 'Tableau', 'D3.js', 'Python Analytics', 'SQL', 'Big Data'],
    layout: 'data-grid', // Custom layout type
  },
  {
    id: 'AI-surveillance-system',
    title: 'AI Surveillance System',
    subtitle: 'Intelligent Security Monitoring',
    description:
      'Advanced AI-powered surveillance that detects threats, analyzes patterns, and responds in real-time.',
    details: [
      'Facial recognition and person tracking',
      'Anomaly detection and threat identification',
      'License plate recognition (LPR)',
      'Crowd analysis and behavior monitoring',
      'Real-time alerts and notifications',
      'Video analytics and forensic search',
    ],
    highlight:
      'Our AI surveillance system enhances security operations with intelligent monitoring, reducing false alarms while ensuring critical events are never missed.',
    technologies: ['Computer Vision', 'Deep Learning', 'TensorFlow', 'OpenCV', 'Edge Computing'],
    image: new URL('@/assets/img/003.png', import.meta.url).href,
    layout: 'scanner-grid', // Custom layout type
  },
  {
    id: 'smart-technology-provider',
    title: 'Smart Technology Provider',
    subtitle: 'Innovation for Digital Transformation',
    description:
      'Comprehensive smart solutions that enhance efficiency, connectivity, and automation across your organization.',
    details: [
      'IoT solutions and sensor networks',
      'Smart building and facility management',
      'Automation and process optimization',
      'Digital twin technology',
      'Smart city infrastructure solutions',
      'Connected device management platforms',
    ],
    highlight:
      'We deliver cutting-edge smart technology solutions that connect devices, optimize operations, and drive digital innovation.',
    technologies: ['IoT', 'MQTT', 'LoRaWAN', 'Edge AI', 'Cloud Integration', '5G'],
    image: new URL('@/assets/img/004.png', import.meta.url).href,
    layout: 'network-nodes', // Custom layout type
  },
  {
    id: 'Training-Consultancy',
    image: new URL('@/assets/img/Product 04.png', import.meta.url).href,
    title: 'Training & Consultancy',
    subtitle: 'Empowering Digital Excellence',
    description:
      'Strategic guidance and comprehensive training programs to accelerate your digital transformation journey.',
    details: [
      'Digital transformation strategy consulting',
      'Technical skills development programs',
      'Leadership training in digital innovation',
      'Change management and adoption strategies',
      'Technology assessment and roadmap planning',
      'Custom workshops and certification programs',
    ],
    highlight:
      'Our consultancy services focus on developing both technology solutions and digital capabilities in people, ensuring sustainable and strategic growth.',
    technologies: ['Agile Methodologies', 'DevOps', 'Cloud Architecture', 'AI/ML', 'Cybersecurity'],
    layout: 'presentation', // Custom layout type
  },
  {
    id: 'nfc-business-card',
    image: new URL('@/assets/img/Product 02.png', import.meta.url).href,
    video: new URL('@/assets/img/NFC full (horizon).mp4', import.meta.url).href,
    title: 'NFC Business Card',
    subtitle: 'Modern Networking Solutions',
    description:
      'Digital business cards powered by NFC technology for seamless, contactless information sharing.',
    details: [
      'Tap-to-share contact information',
      'Customizable digital profiles',
      'Social media integration',
      'Analytics and engagement tracking',
      'Eco-friendly alternative to paper cards',
      'Real-time profile updates',
    ],
    highlight:
      'Transform networking with our NFC business cards that instantly share your contact details, portfolio, and social profiles with just a tap.',
    technologies: ['NFC', 'QR Codes', 'Mobile Apps', 'Cloud Storage', 'Web Technologies'],
  },
])
</script>

<template>
  <div class="services-page">
    <!-- Header -->
    <section class="services-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">Our Services</h1>
        <p class="page-subtitle">
          Empowering digital transformation through innovative solutions and expert guidance
        </p>
      </div>
    </section>

    <!-- Content - Full Width Magazine Layout -->
    <section class="services-content">
      <div class="services-magazine-container">
        <!-- Service Article -->
        <article
          v-for="(service, index) in services"
          :key="service.id"
          :id="service.id"
          :class="['service-article', index % 2 === 0 ? 'layout-left' : 'layout-right']"
        >
          <!-- Special Layout for Video Services -->
          <template v-if="service.video">
            <!-- Video Title Section -->
            <div class="video-title-section">
              <div class="content-wrapper">
                <span class="service-badge">{{ `0${index + 1}` }}</span>
                <h2 class="article-title">{{ service.title }}</h2>
                <p class="article-subtitle">{{ service.subtitle }}</p>
              </div>
            </div>

            <!-- Video + Content Side by Side -->
            <div class="video-split-section">
              <!-- Content on Left -->
              <div class="video-side-content">
                <div class="content-wrapper">
                  <p class="article-description">{{ service.description }}</p>

                  <div class="highlight-box">
                    <svg
                      class="highlight-icon"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"
                      />
                    </svg>
                    <p>{{ service.highlight }}</p>
                  </div>

                  <div class="features-section">
                    <h3 class="features-title">Key Features</h3>
                    <ul class="features-list">
                      <li v-for="(detail, idx) in service.details" :key="idx" class="feature-item">
                        <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                          />
                        </svg>
                        <span>{{ detail }}</span>
                      </li>
                    </ul>
                  </div>

                  <div class="technologies-section">
                    <h4 class="tech-label">Technologies:</h4>
                    <div class="tech-tags">
                      <span
                        v-for="(tech, idx) in service.technologies"
                        :key="idx"
                        class="tech-tag"
                      >
                        {{ tech }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Video on Right -->
              <div class="video-side-player">
                <video
                  :src="service.video"
                  class="side-video"
                  autoplay
                  loop
                  muted
                  playsinline
                ></video>
              </div>
            </div>

            <!-- Static Image Section -->
            <div class="static-image-section">
              <img :src="service.image" :alt="service.title" class="full-width-image" />
            </div>
          </template>

          <!-- Regular Layout for Image Services -->
          <template v-else>
            <!-- Title Section -->
            <div class="video-title-section">
              <div class="content-wrapper">
                <span class="service-badge">{{ `0${index + 1}` }}</span>
                <h2 class="article-title">{{ service.title }}</h2>
                <p class="article-subtitle">{{ service.subtitle }}</p>
              </div>
            </div>

            <!-- Custom Layout Container -->
            <div
              class="service-custom-layout"
              :class="`layout-${service.layout || 'default'}`"
            >
              <!-- Wireframe Canvas for Empty Area -->
              <canvas
                :ref="el => { if (el) canvasRefs[index] = el }"
                class="wireframe-canvas"
              ></canvas>

              <!-- Image Container (position varies by layout) -->
              <div class="layout-image-container" :class="`image-${service.layout || 'default'}`">
                <img :src="service.image" :alt="service.title" class="layout-image" />
                <div class="image-overlay" :class="`overlay-${service.layout || 'default'}`"></div>
              </div>

              <!-- Content Card (position varies by layout) -->
              <div
                class="layout-content-card"
                :class="`content-${service.layout || 'default'}`"
              >

                <!-- Description -->
                <p class="article-description">{{ service.description }}</p>

                <!-- Highlight Box -->
                <div class="highlight-box">
                  <svg
                    class="highlight-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                  </svg>
                  <p>{{ service.highlight }}</p>
                </div>

                <!-- Key Features -->
                <div class="features-section">
                  <h3 class="features-title">Key Features</h3>
                  <ul class="features-list">
                    <li v-for="(detail, idx) in service.details" :key="idx" class="feature-item">
                      <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"
                        />
                      </svg>
                      <span>{{ detail }}</span>
                    </li>
                  </ul>
                </div>

                <!-- Technologies -->
                <div class="technologies-section">
                  <h4 class="tech-label">Technologies:</h4>
                  <div class="tech-tags">
                    <span
                      v-for="(tech, idx) in service.technologies"
                      :key="idx"
                      class="tech-tag"
                    >
                      {{ tech }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </article>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
