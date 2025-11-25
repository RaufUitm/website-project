<script setup>
import { ref, onMounted, watch, nextTick, computed } from 'vue'
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

// Filter state for listing
const selectedCategory = ref('all')
const dateFrom = ref('')
const dateTo = ref('')


const clearFilters = () => {
  selectedCategory.value = 'all'
  dateFrom.value = ''
  dateTo.value = ''
}

// Calendar popup state for picking filter date
const calendarVisible = ref(false)
const calendarMonth = ref(new Date()) // shows current month

const toggleCalendar = () => {
  calendarVisible.value = !calendarVisible.value
}



const prevMonth = () => {
  const d = new Date(calendarMonth.value)
  d.setMonth(d.getMonth() - 1)
  calendarMonth.value = d
}

const nextMonth = () => {
  const d = new Date(calendarMonth.value)
  d.setMonth(d.getMonth() + 1)
  calendarMonth.value = d
}

const monthLabel = computed(() => {
  const d = calendarMonth.value
  return d.toLocaleString('default', { month: 'long', year: 'numeric' })
})

const pad = (n) => (n < 10 ? '0' + n : '' + n)

const toISODate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`

const isoToTime = (iso) => {
  if (!iso) return NaN
  const t = new Date(iso)
  return isNaN(t.getTime()) ? NaN : t.getTime()
}

const isInRange = (iso) => {
  if (!dateFrom.value) return false
  const fromT = isoToTime(dateFrom.value)
  const toT = dateTo.value ? isoToTime(dateTo.value) : fromT
  const cur = isoToTime(iso)
  if (isNaN(fromT) || isNaN(cur) || isNaN(toT)) return false
  const min = Math.min(fromT, toT)
  const max = Math.max(fromT, toT)
  return cur >= min && cur <= max
}

const isStart = (iso) => dateFrom.value && iso === dateFrom.value
const isEnd = (iso) => dateTo.value && iso === dateTo.value

const calendarDays = computed(() => {
  const monthStart = new Date(calendarMonth.value.getFullYear(), calendarMonth.value.getMonth(), 1)
  const startDay = monthStart.getDay() // 0-6 Sun-Sat
  const daysInMonth = new Date(calendarMonth.value.getFullYear(), calendarMonth.value.getMonth() + 1, 0).getDate()

  const cells = []
  // previous month's tail
  for (let i = 0; i < startDay; i++) {
    const d = new Date(monthStart)
    d.setDate(d.getDate() - (startDay - i))
    cells.push({ key: `p-${i}`, day: d.getDate(), inMonth: false, iso: toISODate(d) })
  }

  // current month days
  for (let day = 1; day <= daysInMonth; day++) {
    const d = new Date(calendarMonth.value.getFullYear(), calendarMonth.value.getMonth(), day)
    cells.push({ key: `c-${day}`, day, inMonth: true, iso: toISODate(d) })
  }

  // fill remaining to complete weeks (42 cells max)
  while (cells.length % 7 !== 0) {
    const d = new Date(calendarMonth.value.getFullYear(), calendarMonth.value.getMonth(), daysInMonth + (cells.length % 7))
    cells.push({ key: `n-${cells.length}`, day: d.getDate(), inMonth: false, iso: toISODate(d) })
  }

  return cells
})

const selectCalendarDate = (iso) => {

  if (!dateFrom.value) {
    dateFrom.value = iso
    dateTo.value = ''
    return
  }

  if (dateFrom.value && !dateTo.value) {
    // set end; if iso is before start, swap
    if (isoToTime(iso) < isoToTime(dateFrom.value)) {
      dateTo.value = dateFrom.value
      dateFrom.value = iso
    } else {
      dateTo.value = iso
    }
    // close calendar after range selected
    calendarVisible.value = false
    return
  }

  // both set -> start over
  dateFrom.value = iso
  dateTo.value = ''
}

const filteredNews = computed(() => {
  return news.value.filter((item) => {
    // Category filter
    if (selectedCategory.value !== 'all' && item.category !== selectedCategory.value) return false

    // Date filtering (item.date may be string like YYYY-MM-DD)
    if ((dateFrom.value && dateFrom.value !== '') || (dateTo.value && dateTo.value !== '')) {
      const itemDate = item.date ? new Date(item.date) : null
      if (!itemDate) return false

      if (dateFrom.value && dateFrom.value !== '') {
        const from = new Date(dateFrom.value)
        from.setHours(0, 0, 0, 0)
        if (itemDate < from) return false
      }

      if (dateTo.value && dateTo.value !== '') {
        const to = new Date(dateTo.value)
        to.setHours(23, 59, 59, 999)
        if (itemDate > to) return false
      }
    }

    return true
  })
})

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
  if (adminPassword.value === 'tajdid2025') {
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

// When the admin panel opens, scroll it into view below the sticky header
watch(showAdminPanel, async (val) => {
  await nextTick()
  const el = document.querySelector('.admin-panel')
  if (!el) return

  const header = document.querySelector('nav')
  const headerHeight = header ? header.offsetHeight : 64

  // Ensure panel uses normal document flow (no fixed positioning)
  el.style.position = ''
  el.style.top = ''
  el.style.left = ''
  el.style.transform = ''
  el.style.zIndex = ''
  el.style.margin = ''
  el.style.width = ''

  if (val) {
    // Scroll the page so the admin panel sits just under the header,
    // effectively showing it as the last part of the page content.
    const rect = el.getBoundingClientRect()
    const absoluteTop = window.scrollY + rect.top
    window.scrollTo({ top: Math.max(0, absoluteTop - headerHeight - 12), behavior: 'smooth' })
  }
})

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

  const editNews = async (item, event) => {
    if (event && typeof event.stopPropagation === 'function') event.stopPropagation()

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

    // Open the admin panel and let the watcher scroll it into view below the header
    showAdminPanel.value = true

    // Wait for DOM to update and focus the title input for convenience
    await nextTick()
    // Explicitly scroll to the admin panel (in case watcher timing differs)
    const panel = document.querySelector('.admin-panel')
    const header = document.querySelector('nav')
    const headerHeight = header ? header.offsetHeight : 64
    if (panel) {
      const rect = panel.getBoundingClientRect()
      const absoluteTop = window.scrollY + rect.top
      window.scrollTo({ top: Math.max(0, absoluteTop - headerHeight - 12), behavior: 'smooth' })
    }

    const titleInput = document.querySelector('.admin-panel .form-input')
    if (titleInput) titleInput.focus()
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

const setTodayDate = () => {
  newsForm.value.date = new Date().toISOString().split('T')[0]
}

// date input ref and picker helper
const dateInput = ref(null)
const openDatePicker = () => {
  const el = dateInput.value
  if (!el) return
  // showPicker is supported in some browsers (Chrome/Chromium)
  if (typeof el.showPicker === 'function') {
    el.showPicker()
  } else {
    // fallback: focus to trigger native picker where possible
    el.focus()
    // attempt click; ignore if not allowed
    try { el.click() } catch { /* ignore */ }
  }
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

    <!-- Admin Panel moved below news content -->

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

        <div v-else>
          <!-- Filters -->
          <div class="news-filters" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;margin-bottom:1rem;">
            <div style="display:flex;gap:0.5rem;align-items:center;">
              <label style="font-weight:600">Category:</label>
              <select v-model="selectedCategory" class="form-input" style="min-width:140px;padding:0.35rem;border-radius:6px;">
                <option value="all">All</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat.charAt(0).toUpperCase() + cat.slice(1) }}</option>
              </select>
            </div>

            <div style="display:flex;gap:0.5rem;align-items:center;position:relative;">
              <label style="font-weight:600">Date:</label>
              <div style="display:flex;gap:0.5rem;align-items:center;">
                <button type="button" @click="toggleCalendar" class="btn-calendar" style="padding:0.35rem 0.6rem;border-radius:6px;border:1px solid #d1d5db;background:#fff;">{{ dateFrom && dateTo ? (dateFrom + (dateFrom === dateTo ? '' : ' → ' + dateTo)) : 'Any date' }}</button>
              </div>

              <div v-show="calendarVisible" class="inline-calendar" @click.stop>
                <div class="cal-header">
                  <button type="button" @click="prevMonth">‹</button>
                  <div class="cal-title">{{ monthLabel }}</div>
                  <button type="button" @click="nextMonth">›</button>
                </div>
                <div class="cal-grid">
                  <div class="cal-weekday" v-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="d">{{ d }}</div>
                  <button
                    v-for="cell in calendarDays"
                    :key="cell.key"
                    class="cal-cell"
                    :class="[
                      { muted: !cell.inMonth },
                      { start: isStart(cell.iso), end: isEnd(cell.iso), 'in-range': isInRange(cell.iso) }
                    ]"
                    @click="selectCalendarDate(cell.iso)"
                  >
                    {{ cell.day }}
                  </button>
                </div>
              </div>
            </div>

            <div style="margin-left:auto;display:flex;gap:0.5rem;align-items:center;">
              <button type="button" @click="clearFilters" class="btn-cancel">Clear</button>
              <div style="font-size:0.9rem;color:#374151">Showing {{ filteredNews.length }} of {{ news.length }}</div>
            </div>
          </div>

          <div class="news-grid">
          <article
            v-for="item in filteredNews"
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
                <button @click.stop="editNews(item)" class="btn-edit">Edit</button>
                <button @click.stop="(e) => deleteNews(item.id, e)" class="btn-delete">Delete</button>
              </div>
            </div>
          </article>
        </div>
      </div>
      </div>
    </section>

    <!-- Admin Panel (placed after news content so it appears at the end of the page) -->
    <div v-if="isAdmin && showAdminPanel" class="admin-panel-wrapper">
      <div class="admin-panel">
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
              <div style="display:flex;gap:0.5rem;align-items:center;">
                <input ref="dateInput" v-model="newsForm.date" type="date" class="form-input" placeholder="YYYY-MM-DD" />
                <button type="button" @click="openDatePicker" class="btn-calendar" title="Open calendar">📅</button>
                <button type="button" @click="setTodayDate" class="btn-today" title="Set to today">Today</button>
              </div>
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
    </div>

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

/* Center the admin panel wrapper so the panel is horizontally centered on the page */
.admin-panel-wrapper {
  display: flex;
  justify-content: center;
  width: 100%;
}

/* Ensure the admin panel keeps a responsive max width */
.admin-panel {
  width: min(880px, calc(100% - 32px));
  margin: 2rem auto;
}
</style>

<style scoped>
.inline-calendar {
  position: absolute;
  top: 40px;
  left: 0;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  box-shadow: 0 6px 24px rgba(15,23,42,0.12);
  padding: 8px;
  z-index: 60;
  width: 280px;
}
.cal-header {
  display:flex;align-items:center;justify-content:space-between;padding:4px 8px;margin-bottom:6px;
}
.cal-header button { background:transparent;border:none;font-size:18px;cursor:pointer }
.cal-title { font-weight:700 }
.cal-grid { display:grid;grid-template-columns:repeat(7,1fr);gap:4px }
.cal-weekday { font-size:11px;color:#6b7280;text-align:center }
.cal-cell { background:transparent;border:none;padding:8px;border-radius:6px;cursor:pointer;text-align:center }
.cal-cell.muted { color:#9ca3af }
.cal-cell.start { background:#1f2937;color:#fff;font-weight:700 }
.cal-cell.end { background:#1f2937;color:#fff;font-weight:700 }
.cal-cell.in-range { background:#e6eef8 }
</style>
