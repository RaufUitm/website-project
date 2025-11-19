<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

defineOptions({
  name: 'HomePage',
})

// API Base URL
const API_BASE_URL = 'http://192.168.0.13/tajdid-api/api/news.php'

const features = ref([
  {
    title: 'Digitalization',
    description:
      'Supporting organizations in their journey toward a smarter, more sustainable digital world',
  },
  {
    title: 'Revolution',
    description:
      'Empowering the digital revolution through innovation and intelligent technology to boost productivity',
  },
  {
    title: 'Sustainability',
    description: 'Developing sustainable and resilient digital solutions for the future',
  },
])

const aboutFeatures = ref([
  {
    title: 'Smart Collaboration & Sharing',
    description: "e-room tools for the management of Terengganu's digital citizen agencies",
    image: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800',
  },
  {
    title: 'Big Data Advisory',
    description:
      'Responsible for coordination and technical advice on the delivery of Big Data initiatives',
    image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
  },
  {
    title: 'Consulting and Advisory Services',
    description:
      'Responsible for the development of strategic plan and action plan a digital society',
    image: 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800',
  },
  {
    title: 'Islamic Digital Leadership Development Training',
    description: 'Responsible for handling economic activity and society',
    image: new URL('@/assets/img/train.jpg', import.meta.url).href,
  },
])

const services = ref([
  {
    title: 'Software Development',
    description:
      'Transform your business with tailored software solutions designed to meet your unique requirements. From web and mobile applications to enterprise systems, we deliver high-quality software that drives efficiency and innovation.',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/002.png', import.meta.url).href,
  },
  {
    title: 'Interactive Analytics Dashboard',
    description:
      'A comprehensive data visualization platform that transforms complex datasets into intuitive dashboards with real-time monitoring and advanced analytics capabilities',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/Product 03.png', import.meta.url).href,
  },
  {
    title: 'AI Surveillance System',
    description:
      'Intelligent monitoring solution that uses AI to detect, analyze, and respond to real-time security events efficiently.',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/003.png', import.meta.url).href,
  },
  {
    title: 'Smart Technology Provider',
    description:
      'Delivers innovative smart solutions and digital systems to enhance efficiency, connectivity, and automation.',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/004.png', import.meta.url).href,
  },
  {
    title: 'Training & Consultancy',
    description:
      'Training & consultancy not only focuses on technological results but also on developing digital people in a smart, strategic and beneficial way.',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/Product 04.png', import.meta.url).href,
  },
  {
    title: 'NFC Business Card',
    description:
      'Digital business cards with NFC (Near Field Communication) technology that allows information to be sent with a touch to another phone',
    colorClass: 'service-blue',
    image: new URL('@/assets/img/Product 02.png', import.meta.url).href,
  },
])

const newsItems = ref([])
const featuredNews = ref(null)
const loading = ref(true)
const currentImageIndex = ref(0)
const autoplayInterval = ref(null)

const parseImages = (imageData) => {
  if (!imageData) return []
  if (typeof imageData === 'string') {
    try {
      return JSON.parse(imageData)
    } catch {
      return imageData ? [imageData] : []
    }
  }
  return Array.isArray(imageData) ? imageData : []
}

const getPrimaryImage = (item) => {
  const images = parseImages(item.image)
  return images.length > 0 ? images[0] : null
}

const fetchNews = async () => {
  loading.value = true
  try {
    const response = await axios.get(`${API_BASE_URL}?action=latest&limit=3`)
    if (response.data && response.data.length > 0) {
      featuredNews.value = response.data[0]
      newsItems.value = response.data.slice(1, 3)
      currentImageIndex.value = 0
      startAutoplay()
    }
  } catch (err) {
    console.error('Error fetching news:', err)
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const getContentExcerpt = (content) => {
  if (!content) return ''
  const plainText = content.replace(/\n/g, ' ').trim()
  return plainText.length > 150 ? plainText.substring(0, 150) + '...' : plainText
}

const getFeaturedImages = () => {
  if (!featuredNews.value) return []
  return parseImages(featuredNews.value.image)
}

const nextImage = () => {
  const images = getFeaturedImages()
  if (images.length > 1) {
    currentImageIndex.value = (currentImageIndex.value + 1) % images.length
  }
}

const prevImage = () => {
  const images = getFeaturedImages()
  if (images.length > 1) {
    currentImageIndex.value = (currentImageIndex.value - 1 + images.length) % images.length
  }
}

const goToImage = (index) => {
  currentImageIndex.value = index
}

const startAutoplay = () => {
  stopAutoplay()
  const images = getFeaturedImages()
  if (images.length > 1) {
    autoplayInterval.value = setInterval(() => {
      nextImage()
    }, 5000)
  }
}

const stopAutoplay = () => {
  if (autoplayInterval.value) {
    clearInterval(autoplayInterval.value)
    autoplayInterval.value = null
  }
}

onMounted(() => {
  fetchNews()
  return () => {
    stopAutoplay()
  }
})
</script>

<template>
  <div>
    <!-- Hero Section with Full Video Background -->
    <section class="hero-section">
      <video autoplay loop muted playsinline class="hero-video">
        <source src="@/assets/img/BACKGROUND WEBSITE TAJDID 2025.mp4" type="video/mp4" />
      </video>

      <div class="hero-overlay"></div>

      <div class="hero-content">
        <div class="max-w-8xl mx-auto px-6">
          <!-- Top Subtitle -->
          <p class="hero-top-subtitle text-left">
            TERENGGANU ADVANCED JOINT DIGITAL INTELLIGENT DEVELOPMENT
          </p>

          <!-- Main Title -->
          <h1 class="hero-main-title text-left">TAJDID CORPORATION<br />SDN BHD</h1>

          <!-- Feature Cards Below Title -->
          <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
            <div
              v-for="feature in features"
              :key="feature.title"
              class="hero-feature-card border-l-4 border-green-500 p-6 rounded-lg transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
            >
              <h3 class="text-xl font-bold text-white mb-2">{{ feature.title }}</h3>
              <p class="text-white/90 text-sm">{{ feature.description }}</p>
              <div class="border-right"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-20 custom-section-bg">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-green-600 mb-8">Latest News</h2>
        <h3 class="text-3xl font-bold mb-12">
          <span class="text-gray-800">Showcasing our latest </span>
          <span class="text-green-600">milestones </span>
          <span class="text-gray-800">and impactful activities</span>
        </h3>

        <div v-if="loading" class="text-center py-12">
          <div class="spinner"></div>
          <p class="mt-4 text-gray-600">Loading news...</p>
        </div>

        <div v-else class="news-grid-new">
          <div class="news-featured-new">
            <router-link
              v-if="featuredNews"
              :to="`/news/${featuredNews.id}`"
              class="news-card-overlay"
            >
              <div class="carousel-container">
                <div
                  v-for="(image, index) in getFeaturedImages()"
                  :key="index"
                  class="carousel-image"
                  :class="{ active: index === currentImageIndex }"
                >
                  <img :src="image" :alt="`${featuredNews.title} - Image ${index + 1}`" />
                </div>

                <div v-if="getFeaturedImages().length > 1" class="carousel-controls">
                  <button
                    @click.prevent="prevImage"
                    class="carousel-btn"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                  </button>
                  <button
                    @click.prevent="nextImage"
                    class="carousel-btn"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                  </button>
                </div>

                <div v-if="getFeaturedImages().length > 1" class="carousel-indicators">
                  <button
                    v-for="(image, index) in getFeaturedImages()"
                    :key="index"
                    @click.prevent="goToImage(index)"
                    class="carousel-indicator"
                    :class="{ active: index === currentImageIndex }"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                  ></button>
                </div>
              </div>

              <div class="news-card-content-overlay">
                <div class="news-meta-overlay">
                  <span class="news-date-badge-overlay"
                    >📅 {{ formatDate(featuredNews.date) }}</span
                  >
                </div>
                <h3 class="news-overlay-title">{{ featuredNews.title }}</h3>
                <p class="news-overlay-description">
                  {{ getContentExcerpt(featuredNews.content || featuredNews.description) }}
                </p>
              </div>
            </router-link>
          </div>

          <div class="news-sidebar-new">
            <router-link
              v-for="item in newsItems"
              :key="item.id"
              :to="`/news/${item.id}`"
              class="news-card-overlay news-card-small-overlay"
            >
              <div class="news-card-image">
                <img v-if="getPrimaryImage(item)" :src="getPrimaryImage(item)" :alt="item.title" />
                <div v-else class="image-placeholder-new">No Image</div>
              </div>
              <div class="news-card-content-overlay-small">
                <div class="news-meta-overlay-small">
                  <span class="news-date-badge-overlay-small">📅 {{ formatDate(item.date) }}</span>
                </div>
                <h4 class="news-overlay-title-small">{{ item.title }}</h4>
              </div>
            </router-link>
          </div>
        </div>
      </div>
      <div class="max-w-2xl mx-auto px-4">
        <button class="more-news-btn-new">
          <router-link to="/news">More News +</router-link>
        </button>
      </div>
    </section>

    <!-- About Section -->
    <section class="py-20 custom-section-bg">
      <div class="max-w-7xl mx-auto px-4">
        <img src="@/assets/img/logo.png" alt="TAJDID Logo" class="img-logo" />
        <div class="about-header text-center">
          <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
            Terengganu Advanced Joint Digital Intelligent Development (TAJDID)
          </h2>
          <p class="about-description">
            or known as <strong>TAJDID Corporation</strong> is a fully state-owned company under the
            Terengganu State Government, operating under Menteri Besar, Terengganu Incorporated.
            TAJDID functions as <strong>the entity to drive economic growth</strong> and
            <strong>the development of a digital society</strong> based on digital technology and
            the Fourth Industrial Revolution (4IR)
          </p>
        </div>

        <h2 class="mt-12 text-4xl md:text-5xl font-bold mb-16 text-center">
          <span class="text-gray-800">Our</span>
          <span class="text-green-600"> Fundamental Values</span>
        </h2>
        <div class="features-grid">
          <div v-for="(feature, index) in aboutFeatures" :key="index" class="feature-card">
            <div class="feature-image">
              <img v-if="feature.image" :src="feature.image" :alt="feature.title" />
              <div v-else class="feature-placeholder">Feature Image {{ index + 1 }}</div>
            </div>
            <div class="feature-content">
              <h3 class="feature-title">{{ feature.title }}</h3>
              <p class="feature-description">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Services Showcase Section -->
    <section class="services-section-wrapper">
      <div class="pt-20 pb-12 custom-section-bg">
        <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center">
          <span class="text-gray-800">Our</span>
          <span class="text-green-600"> Expertise</span>
          <span class="text-gray-800"> & Solutions</span>
        </h2>
      </div>

      <div class="services-showcase">
        <div
          v-for="(service, index) in services"
          :key="index"
          class="service-card"
          :class="[service.colorClass, index % 2 !== 0 ? 'reverse' : '']"
          :style="{ '--service-bg-image': `url('${service.image}')` }"
        >
          <div class="service-content">
            <h2 class="service-title">{{ service.title }}</h2>
            <p class="service-text">{{ service.description }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
