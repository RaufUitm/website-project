<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

defineOptions({
  name: 'NewsPage',
})

const router = useRouter()

// API Base URLs
const API_BASE_URL = 'http://192.168.0.13/tajdid-api/api/news.php'
const UPLOAD_API_URL = 'http://192.168.0.13/tajdid-api/api/upload.php'

const news = ref([])
const loading = ref(true)
const showAdminPanel = ref(false)
const adminPassword = ref('')
const isAdmin = ref(false)
const showPasswordPrompt = ref(false)

// Image upload states
const uploadedImages = ref([])
const isDragging = ref(false)
const uploadProgress = ref(0)
const isUploading = ref(false)

// Form data for new/edit news
const newsForm = ref({
  id: null,
  title: '',
  content: '',
  image: '',
  date: '',
  category: 'general',
  is_featured: false,
})

const isEditing = ref(false)
const categories = ['general', 'technology', 'announcement', 'event', 'achievement']

// Parse images from JSON string
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

// Get primary image for display
const getPrimaryImage = (item) => {
  const images = parseImages(item.image)
  return images.length > 0 ? images[0] : null
}

// Get content excerpt (first 100 characters)
const getContentExcerpt = (content) => {
  if (!content) return ''
  const plainText = content.replace(/\n/g, ' ').trim()
  return plainText.length > 100 ? plainText.substring(0, 100) + '...' : plainText
}

// Open news detail page
const openNewsDetail = (newsId) => {
  router.push(`/news/${newsId}`)
}

// Load news from database
const loadNews = async () => {
  loading.value = true
  try {
    const response = await axios.get(API_BASE_URL)
    if (response.data && Array.isArray(response.data)) {
      news.value = response.data
    } else {
      news.value = []
    }
  } catch (error) {
    console.error('Error loading news:', error)
    alert('Failed to load news from database. Please check your connection.')
    news.value = []
  } finally {
    loading.value = false
  }
}

// Save news to database
const saveNews = async () => {
  try {
    const imageUrls = uploadedImages.value.map((img) => img.url)
    const newsData = {
      ...newsForm.value,
      image: JSON.stringify(imageUrls),
      description: getContentExcerpt(newsForm.value.content), // Auto-generate from content
    }

    if (isEditing.value) {
      await axios.put(API_BASE_URL, newsData)
      alert('News updated successfully!')
    } else {
      delete newsData.id
      await axios.post(API_BASE_URL, newsData)
      alert('News added successfully!')
    }

    await loadNews()
    openNewNewsForm()
  } catch (error) {
    console.error('Error saving news:', error)
    alert('Failed to save news. Please try again.')
  }
}

// Delete news from database
const deleteNews = async (id, event) => {
  event.stopPropagation()

  if (confirm('Are you sure you want to delete this news?')) {
    try {
      await axios.delete(`${API_BASE_URL}?id=${id}`)
      await loadNews()
      alert('News deleted successfully!')
    } catch (error) {
      console.error('Error deleting news:', error)
      alert('Failed to delete news. Please try again.')
    }
  }
}

// Handle file selection from input
const handleFileSelect = (event) => {
  const files = Array.from(event.target.files)
  uploadFiles(files)
}

// Handle drag and drop
const handleDrop = (event) => {
  event.preventDefault()
  isDragging.value = false

  const files = Array.from(event.dataTransfer.files)
  const imageFiles = files.filter((file) => file.type.startsWith('image/'))

  if (imageFiles.length > 0) {
    uploadFiles(imageFiles)
  } else {
    alert('Please drop only image files')
  }
}

const handleDragOver = (event) => {
  event.preventDefault()
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

// Upload files to server
const uploadFiles = async (files) => {
  if (files.length === 0) return

  isUploading.value = true
  uploadProgress.value = 0

  const formData = new FormData()
  files.forEach((file) => {
    formData.append('images[]', file)
  })

  try {
    const response = await axios.post(UPLOAD_API_URL, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      onUploadProgress: (progressEvent) => {
        uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
      },
    })

    if (response.data.success && response.data.files) {
      uploadedImages.value = [...uploadedImages.value, ...response.data.files]

      if (response.data.errors && response.data.errors.length > 0) {
        alert('Some files had errors:\n' + response.data.errors.join('\n'))
      }
    }
  } catch (error) {
    console.error('Upload error:', error)
    alert('Failed to upload images. Please try again.')
  } finally {
    isUploading.value = false
    uploadProgress.value = 0
  }
}

// Remove uploaded image
const removeImage = (index) => {
  if (confirm('Remove this image?')) {
    uploadedImages.value.splice(index, 1)
  }
}

// Set image as primary
const setPrimaryImage = (index) => {
  const images = uploadedImages.value
  const [primaryImage] = images.splice(index, 1)
  uploadedImages.value = [primaryImage, ...images]
}

// Admin authentication
const checkPassword = () => {
  if (adminPassword.value === '123') {
    isAdmin.value = true
    showPasswordPrompt.value = false
    showAdminPanel.value = true
    adminPassword.value = ''
  } else {
    alert('Incorrect password!')
    adminPassword.value = ''
  }
}

const toggleAdminPanel = () => {
  if (!isAdmin.value) {
    showPasswordPrompt.value = true
  } else {
    // If admin is currently viewing the panel, toggling it off should also log out
    if (showAdminPanel.value) {
      showAdminPanel.value = false
      isAdmin.value = false
    } else {
      showAdminPanel.value = true
    }
  }
}

const closeAdminPanel = () => {
  showAdminPanel.value = false
  isAdmin.value = false
  showPasswordPrompt.value = false
  adminPassword.value = ''
}

// Form operations
const openNewNewsForm = () => {
  isEditing.value = false
  uploadedImages.value = []
  newsForm.value = {
    id: null,
    title: '',
    content: '',
    image: '',
    date: new Date().toISOString().split('T')[0],
    category: 'general',
    is_featured: false,
  }
}

const editNews = (item, event) => {
  event.stopPropagation()

  isEditing.value = true
  newsForm.value = {
    ...item,
    is_featured: item.is_featured === 1 || item.is_featured === true,
  }

  const existingImages = parseImages(item.image)
  uploadedImages.value = existingImages.map((url, index) => ({
    url: url,
    filename: url.split('/').pop(),
    original_name: `Image ${index + 1}`,
  }))

  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveNewsItem = async () => {
  if (!newsForm.value.title || !newsForm.value.content) {
    alert('Please fill in title and content')
    return
  }

  await saveNews()
}

const cancelEdit = () => {
  openNewNewsForm()
}

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

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

onMounted(() => {
  loadNews()
})
</script>

<template>
  <div class="news-page">
    <!-- Admin Toggle Button -->
    <button @click="toggleAdminPanel" class="admin-toggle-btn" title="Admin Panel">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path
          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
        />
      </svg>
    </button>

    <!-- Password Prompt Modal -->
    <div v-if="showPasswordPrompt" class="modal-overlay" @click="showPasswordPrompt = false">
      <div class="modal-content" @click.stop>
        <h3>Admin Access</h3>
        <p>Enter admin password:</p>
        <input
          v-model="adminPassword"
          type="password"
          placeholder="Password"
          @keyup.enter="checkPassword"
          class="password-input"
        />
        <div class="modal-actions">
          <button @click="checkPassword" class="btn-confirm">Login</button>
          <button @click="showPasswordPrompt = false" class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Admin Panel -->
    <div v-if="isAdmin && showAdminPanel" class="admin-panel">
        <div class="admin-header">
        <h2>{{ isEditing ? 'Edit News' : 'Add New News' }}</h2>
        <button @click="closeAdminPanel" class="close-btn">×</button>
      </div>

      <form @submit.prevent="saveNewsItem" class="news-form">
        <div class="form-group">
          <label>Title *</label>
          <input v-model="newsForm.title" type="text" required class="form-input" />
        </div>

        <div class="form-group">
          <label>Full Content *</label>
          <textarea
            v-model="newsForm.content"
            rows="8"
            required
            class="form-input"
            placeholder="Write the full news content here..."
          ></textarea>
          <p class="form-hint">First 100 characters will be used as preview</p>
        </div>

        <!-- Image Upload Section -->
        <div class="form-group">
          <label>Images (Multiple)</label>

          <div
            class="drop-zone"
            :class="{ 'drop-zone-active': isDragging, 'drop-zone-uploading': isUploading }"
            @drop="handleDrop"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
          >
            <div v-if="!isUploading" class="drop-zone-content">
              <svg
                class="drop-zone-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                <polyline points="9 15 12 12 15 15" />
                <line x1="12" y1="12" x2="12" y2="21" />
              </svg>
              <p class="drop-zone-text">Drag & drop images here</p>
              <p class="drop-zone-subtext">or</p>
              <label class="drop-zone-btn">
                <input
                  type="file"
                  multiple
                  accept="image/*"
                  @change="handleFileSelect"
                  style="display: none"
                />
                Browse Files
              </label>
              <p class="drop-zone-hint">PNG, JPG, GIF, WebP up to 5MB</p>
            </div>

            <div v-else class="upload-progress">
              <div class="progress-bar-container">
                <div class="progress-bar" :style="{ width: uploadProgress + '%' }"></div>
              </div>
              <p class="progress-text">Uploading... {{ uploadProgress }}%</p>
            </div>
          </div>

          <div v-if="uploadedImages.length > 0" class="uploaded-images">
            <div v-for="(image, index) in uploadedImages" :key="index" class="image-preview">
              <img :src="image.url" :alt="image.original_name" />
              <div class="image-overlay">
                <button
                  type="button"
                  @click="setPrimaryImage(index)"
                  class="image-action-btn"
                  :class="{ 'primary-badge': index === 0 }"
                  :title="index === 0 ? 'Primary Image' : 'Set as Primary'"
                >
                  <svg v-if="index === 0" viewBox="0 0 24 24" fill="currentColor">
                    <path
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                  <svg
                    v-else
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                    />
                  </svg>
                </button>
                <button
                  type="button"
                  @click="removeImage(index)"
                  class="image-remove-btn"
                  title="Remove Image"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <p class="image-name">{{ image.original_name }}</p>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Category</label>
            <select v-model="newsForm.category" class="form-input">
              <option v-for="cat in categories" :key="cat" :value="cat">
                {{ cat.charAt(0).toUpperCase() + cat.slice(1) }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Date</label>
            <input v-model="newsForm.date" type="date" class="form-input" />
          </div>
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input v-model="newsForm.is_featured" type="checkbox" class="form-checkbox" />
            <span>Featured News (Show on homepage)</span>
          </label>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-save">
            {{ isEditing ? 'Update News' : 'Add News' }}
          </button>
          <button v-if="isEditing" type="button" @click="cancelEdit" class="btn-cancel">
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- Header -->
    <section class="news-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">Latest News & Updates</h1>
        <p class="page-subtitle">Showcasing our latest milestones and impactful activities</p>
      </div>
    </section>

    <!-- News Content -->
    <section class="news-content">
      <div class="max-w-7xl mx-auto px-4">
        <div v-if="loading" class="loading-state">
          <div class="spinner"></div>
          <p>Loading news...</p>
        </div>

        <div v-else-if="news.length === 0" class="empty-state">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path
              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
            />
          </svg>
          <h3>No News Available</h3>
          <p>Check back later for updates</p>
        </div>

        <div v-else class="news-grid">
          <article
            v-for="item in news"
            :key="item.id"
            :id="`news-${item.id}`"
            class="news-card"
            @click="openNewsDetail(item.id)"
          >
            <div v-if="item.is_featured" class="featured-badge">⭐ Featured</div>

            <div
              class="category-badge"
              :style="{ backgroundColor: getCategoryColor(item.category) }"
            >
              {{ item.category }}
            </div>

            <div class="news-image">
              <img v-if="getPrimaryImage(item)" :src="getPrimaryImage(item)" :alt="item.title" />
              <div v-else class="image-placeholder">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"
                  />
                </svg>
              </div>

              <div v-if="parseImages(item.image).length > 1" class="multi-image-badge">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"
                  />
                </svg>
                {{ parseImages(item.image).length }}
              </div>
            </div>

            <div class="news-body">
              <div class="news-meta">
                <span class="news-date">{{ formatDate(item.date) }}</span>
                <span v-if="item.views" class="news-views">{{ item.views }} views</span>
              </div>

              <h3 class="news-title">{{ item.title }}</h3>
              <p class="news-description">{{ getContentExcerpt(item.content) }}</p>

              <div v-if="isAdmin" class="admin-actions">
                <button @click="(e) => editNews(item, e)" class="btn-edit">Edit</button>
                <button @click="(e) => deleteNews(item.id, e)" class="btn-delete">Delete</button>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.form-hint {
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
  font-style: italic;
}

.news-card {
  cursor: pointer;
  transition: all 0.3s ease;
}

.news-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
}
</style>
