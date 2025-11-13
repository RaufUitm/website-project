<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const API_BASE_URL = 'http://192.168.0.13/tajdid-api/api/news.php'

const newsItem = ref(null)
const loading = ref(true)

// Image viewer state
const showImageViewer = ref(false)
const currentImageIndex = ref(0)
const touchStartX = ref(0)
const touchEndX = ref(0)

// Parse images from JSON
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

// Get all images
const allImages = computed(() => {
  if (!newsItem.value) return []
  return parseImages(newsItem.value.image)
})

// Get main image (first one)
const mainImage = computed(() => {
  return allImages.value.length > 0 ? allImages.value[0] : null
})

// Split content into paragraphs
const contentParagraphs = computed(() => {
  if (!newsItem.value || !newsItem.value.content) return []
  return newsItem.value.content.split('\n').filter((p) => p.trim())
})

// Get additional images (excluding main image)
const additionalImages = computed(() => {
  return allImages.value.slice(1)
})

// Image viewer functions
const openImageViewer = (index) => {
  currentImageIndex.value = index
  showImageViewer.value = true
  document.body.style.overflow = 'hidden'
}

const closeImageViewer = () => {
  showImageViewer.value = false
  document.body.style.overflow = ''
}

const nextImage = () => {
  if (currentImageIndex.value < allImages.value.length - 1) {
    currentImageIndex.value++
  }
}

const prevImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--
  }
}

// Touch handlers for swipe
const handleTouchStart = (e) => {
  touchStartX.value = e.touches[0].clientX
}

const handleTouchMove = (e) => {
  touchEndX.value = e.touches[0].clientX
}

const handleTouchEnd = () => {
  const swipeThreshold = 50
  const diff = touchStartX.value - touchEndX.value

  if (Math.abs(diff) > swipeThreshold) {
    if (diff > 0) {
      // Swiped left - next image
      nextImage()
    } else {
      // Swiped right - previous image
      prevImage()
    }
  }

  touchStartX.value = 0
  touchEndX.value = 0
}

// Keyboard navigation
const handleKeyDown = (e) => {
  if (!showImageViewer.value) return

  if (e.key === 'ArrowLeft') {
    prevImage()
  } else if (e.key === 'ArrowRight') {
    nextImage()
  } else if (e.key === 'Escape') {
    closeImageViewer()
  }
}

// Load news detail
const loadNewsDetail = async () => {
  loading.value = true
  const newsId = route.params.id

  try {
    const response = await axios.get(`${API_BASE_URL}?id=${newsId}`)
    if (response.data) {
      newsItem.value = response.data
    } else {
      alert('News not found')
      router.push('/news')
    }
  } catch (error) {
    console.error('Error loading news:', error)
    alert('Failed to load news details')
    router.push('/news')
  } finally {
    loading.value = false
  }
}

// Format date
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

// Get category color
const getCategoryColor = (category) => {
  const colors = {
    general: '#6b7280',
    technology: '#3b82f6',
    announcement: '#10b981',
    event: '#f59e0b',
    achievement: '#8b5cf6',
  }
  return colors[category] || colors.general
}

// Back to news list
const goBack = () => {
  router.push('/news')
}

onMounted(() => {
  loadNewsDetail()
  window.addEventListener('keydown', handleKeyDown)
})
</script>

<template>
  <div class="news-detail-page">
    <!-- Loading State -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading news details...</p>
    </div>

    <!-- News Detail Content -->
    <div v-else-if="newsItem" class="detail-wrapper">
      <!-- Main Image Section - Full Width -->
      <figure class="hero-image-section">
        <div v-if="mainImage" class="hero-image-wrapper" @click="openImageViewer(0)">
          <img :src="mainImage" :alt="newsItem.title" class="hero-image" />
          <div class="image-overlay">
            <span class="click-to-view">Click to view full image</span>
          </div>
        </div>
        <div v-else class="no-image-placeholder">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"
            />
          </svg>
          <p>No image available</p>
        </div>
      </figure>

      <!-- Content Container -->
      <div class="content-container">
        <!-- Back Button -->
        <div class="back-section">
          <button @click="goBack" class="back-button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to News
          </button>
        </div>

        <!-- Article Content -->
        <article class="article-content">
          <!-- Header Section -->
          <header class="article-header">
            <div class="meta-badges">
              <span class="badge badge-date">{{ formatDate(newsItem.date) }}</span>
              <span
                class="badge badge-category"
                :style="{ backgroundColor: getCategoryColor(newsItem.category) }"
              >
                {{ newsItem.category }}
              </span>
              <span v-if="newsItem.is_featured" class="badge badge-featured"> ⭐ Featured </span>
            </div>

            <h1 class="article-title">{{ newsItem.title }}</h1>

            <p class="article-description">{{ newsItem.description }}</p>

            <div class="meta-info">
              <span v-if="newsItem.views" class="meta-views"> 👁️ {{ newsItem.views }} views </span>
            </div>
          </header>

          <!-- Content Body -->
          <div class="article-body">
            <div v-if="contentParagraphs.length > 0" class="article-text">
              <p
                v-for="(paragraph, index) in contentParagraphs"
                :key="index"
                class="text-paragraph"
              >
                {{ paragraph }}
              </p>
            </div>
            <div v-else class="no-content">
              <p>No additional content available for this news item.</p>
            </div>
          </div>

          <!-- Additional Images Gallery -->
          <div v-if="additionalImages.length > 0" class="gallery-section">
            <h3 class="gallery-heading">More Photos ({{ additionalImages.length }})</h3>

            <div class="gallery-grid">
              <figure
                v-for="(image, index) in additionalImages"
                :key="index"
                class="gallery-item"
                @click="openImageViewer(index + 1)"
              >
                <img :src="image" :alt="`Gallery image ${index + 1}`" />
                <div class="gallery-overlay">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" />
                  </svg>
                </div>
              </figure>
            </div>
          </div>

          <!-- Footer -->
          <footer class="article-footer">
            <button @click="goBack" class="footer-button">← Back to All News</button>
          </footer>
        </article>
      </div>
    </div>

    <!-- Full Screen Image Viewer -->
    <Transition name="fade">
      <div
        v-if="showImageViewer"
        class="image-viewer-overlay"
        @click.self="closeImageViewer"
        @touchstart="handleTouchStart"
        @touchmove="handleTouchMove"
        @touchend="handleTouchEnd"
      >
        <!-- Close Button -->
        <button class="viewer-close" @click="closeImageViewer">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6L6 18M6 6l12 12" />
          </svg>
        </button>

        <!-- Image Counter -->
        <div class="image-counter">{{ currentImageIndex + 1 }} / {{ allImages.length }}</div>

        <!-- Previous Button -->
        <button v-if="currentImageIndex > 0" class="viewer-nav viewer-prev" @click="prevImage">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>

        <!-- Current Image -->
        <div class="viewer-image-container">
          <img
            :src="allImages[currentImageIndex]"
            :alt="`Image ${currentImageIndex + 1}`"
            class="viewer-image"
          />
        </div>

        <!-- Next Button -->
        <button
          v-if="currentImageIndex < allImages.length - 1"
          class="viewer-nav viewer-next"
          @click="nextImage"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
            <path d="M9 18l6-6-6-6" />
          </svg>
        </button>

        <!-- Thumbnail Strip -->
        <div class="thumbnail-strip">
          <div
            v-for="(image, index) in allImages"
            :key="index"
            class="thumbnail-item"
            :class="{ active: index === currentImageIndex }"
            @click="currentImageIndex = index"
          >
            <img :src="image" :alt="`Thumbnail ${index + 1}`" />
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>
