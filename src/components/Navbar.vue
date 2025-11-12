<script setup>
defineOptions({
  name: 'NavBar',
})
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const mobileMenuOpen = ref(false)
const servicesDropdownOpen = ref(false)
const isScrolled = ref(false)
const router = useRouter()
const route = useRoute()

const handleScroll = () => {
  isScrolled.value = window.scrollY > 100
}

// Close mobile menu when route changes
watch(
  () => route.path,
  () => {
    mobileMenuOpen.value = false
    servicesDropdownOpen.value = false
  },
)

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  handleScroll()

  // Prevent body scroll when menu is open
  watch(mobileMenuOpen, (isOpen) => {
    if (isOpen) {
      document.body.style.overflow = 'hidden'
    } else {
      document.body.style.overflow = ''
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.body.style.overflow = ''
})

const services = [
  { id: 'software-development', name: 'Software Development' },
  { id: 'interactive-analytics-dashboard', name: 'Interactive Analytics Dashboard' },
  { id: 'AI-surveillance-system', name: 'AI Surveillance System' },
  { id: 'smart-technology-provider', name: 'Smart Technology Provider' },
  { id: 'Training-Consultancy', name: 'Training & Consultancy' },
  { id: 'nfc-business-card', name: 'NFC Business Card' },
]

const navigateToService = (serviceId) => {
  if (router.currentRoute.value.path !== '/services') {
    router.push('/services').then(() => {
      setTimeout(() => {
        scrollToService(serviceId)
      }, 100)
    })
  } else {
    scrollToService(serviceId)
  }
  servicesDropdownOpen.value = false
  mobileMenuOpen.value = false
}

const scrollToService = (serviceId) => {
  const element = document.getElementById(serviceId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}
</script>

<template>
  <nav :class="{ 'nav-scrolled': isScrolled, 'nav-transparent': !isScrolled }">
    <div class="max-w-7xl mx-auto px-6">
      <div class="nav-container">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/home" class="logo-link">
            <img v-if="isScrolled" src="@/assets/img/logo.png" alt="TAJDID Logo" class="logo-img" />
            <img v-else src="@/assets/img/logo-white.png" alt="TAJDID Logo" class="logo-img" />
          </router-link>
        </div>

        <!-- Desktop Menu (unchanged) -->
        <div class="desktop-menu">
          <router-link to="/home" class="nav-link">Home</router-link>
          <router-link to="/about" class="nav-link">About Us</router-link>

          <div
            class="dropdown-container"
            @mouseenter="servicesDropdownOpen = true"
            @mouseleave="servicesDropdownOpen = false"
          >
            <router-link to="/services" class="nav-link">
              Services
              <svg
                class="icon-sm icon-rotate"
                :class="{ rotated: servicesDropdownOpen }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </router-link>

            <div v-show="servicesDropdownOpen" class="dropdown-bridge"></div>

            <div v-show="servicesDropdownOpen" class="dropdown-menu">
              <button
                v-for="service in services"
                :key="service.id"
                @click="navigateToService(service.id)"
                class="dropdown-item"
              >
                <span>{{ service.icon }}</span>
                <span class="dropdown-item-text">{{ service.name }}</span>
              </button>
            </div>
          </div>

          <router-link to="/news" class="nav-link">News</router-link>
          <router-link to="/contact" class="nav-link">Contact</router-link>
        </div>

        <!-- Enhanced Mobile Menu Button -->
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="mobile-menu-btn"
          :class="{ 'menu-open': mobileMenuOpen }"
          aria-label="Toggle menu"
        >
          <div class="hamburger-box">
            <div class="hamburger-inner"></div>
          </div>
        </button>
      </div>

      <!-- Enhanced Mobile Menu Overlay -->
      <transition name="menu-fade">
        <div v-if="mobileMenuOpen" class="mobile-menu-overlay" @click="mobileMenuOpen = false">
          <div class="mobile-menu-panel" @click.stop>
            <!-- Menu Header -->
            <div class="mobile-menu-header">
              <img src="@/assets/img/logo-white.png" alt="TAJDID Logo" class="menu-logo" />
            </div>

            <!-- Menu Items -->
            <div class="mobile-menu-content">
              <router-link
                to="/home"
                class="mobile-nav-item"
                :class="{ active: $route.path === '/home' }"
              >
                <div class="nav-item-icon">🏠</div>
                <span class="nav-item-text">Home</span>
                <svg class="nav-item-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </router-link>

              <router-link
                to="/about"
                class="mobile-nav-item"
                :class="{ active: $route.path === '/about' }"
              >
                <div class="nav-item-icon">ℹ️</div>
                <span class="nav-item-text">About Us</span>
                <svg class="nav-item-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </router-link>

              <!-- Services Accordion -->
              <div class="mobile-nav-accordion">
                <button
                  @click="servicesDropdownOpen = !servicesDropdownOpen"
                  class="mobile-nav-item accordion-trigger"
                  :class="{ active: $route.path === '/services', expanded: servicesDropdownOpen }"
                >
                  <div class="nav-item-icon">⚙️</div>
                  <span class="nav-item-text">Services</span>
                  <svg
                    class="nav-item-chevron"
                    :class="{ rotated: servicesDropdownOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    />
                  </svg>
                </button>

                <!-- Services Submenu -->
                <transition name="accordion">
                  <div v-show="servicesDropdownOpen" class="mobile-submenu">
                    <button
                      v-for="service in services"
                      :key="service.id"
                      @click="navigateToService(service.id)"
                      class="mobile-submenu-item"
                    >
                      <span class="submenu-icon">{{ service.icon }}</span>
                      <span class="submenu-text">{{ service.name }}</span>
                    </button>
                  </div>
                </transition>
              </div>

              <router-link
                to="/news"
                class="mobile-nav-item"
                :class="{ active: $route.path === '/news' }"
              >
                <div class="nav-item-icon">📰</div>
                <span class="nav-item-text">News</span>
                <svg class="nav-item-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </router-link>

              <router-link
                to="/contact"
                class="mobile-nav-item"
                :class="{ active: $route.path === '/contact' }"
              >
                <div class="nav-item-icon">📧</div>
                <span class="nav-item-text">Contact</span>
                <svg class="nav-item-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </router-link>
            </div>

            <!-- Menu Footer -->
            <div class="mobile-menu-footer">
              <p class="footer-text">© 2025 TAJDID</p>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </nav>
</template>

<style scoped>
/* Desktop Nav Styles - Keep in component */
nav {
  transition:
    background-color 0.3s ease,
    box-shadow 0.3s ease;
}

.nav-transparent {
  background-color: transparent;
  box-shadow: none;
}

.nav-transparent .nav-link,
.nav-transparent .mobile-menu-btn {
  color: white;
}

.nav-transparent .nav-link:hover {
  color: #d1d5db;
}

.nav-transparent .nav-link.router-link-active {
  color: white;
}

.nav-transparent .nav-link::after {
  background-color: white;
}

.nav-scrolled {
  background-color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.nav-scrolled .nav-link,
.nav-scrolled .mobile-menu-btn {
  color: #4b5563;
}

.nav-scrolled .nav-link:hover {
  color: #10b981;
}

.nav-scrolled .nav-link.router-link-active {
  color: #10b981;
}

.nav-scrolled .nav-link::after {
  background-color: #10b981;
}

.logo-img {
  transition: opacity 0.3s ease;
}

.dropdown-bridge {
  position: absolute;
  left: 0;
  width: 16rem;
  top: 100%;
  height: 1rem;
}

.dropdown-menu {
  top: calc(100% + 0.2rem);
}
</style>
