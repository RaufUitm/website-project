<script setup>
defineOptions({
  name: 'NavBar',
})

import { ref, onMounted, onUnmounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const mobileMenuOpen = ref(false)
const servicesDropdownOpen = ref(false)
const aboutDropdownOpen = ref(false)
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
    aboutDropdownOpen.value = false
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
  { id: 'AI-surveillance-system', name: 'AI Surveillance System' },
  { id: 'interactive-analytics-dashboard', name: 'Interactive Analytics Dashboard' },
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
            <router-link to="/home" class="nav-link">{{ $t('nav.home') }}</router-link>
            <div
              class="dropdown-container"
              @mouseenter="aboutDropdownOpen = true"
              @mouseleave="aboutDropdownOpen = false"
            >
              <router-link to="/about" class="nav-link">
                {{ $t('nav.about') }}
                <svg
                  class="icon-sm icon-rotate"
                  :class="{ rotated: aboutDropdownOpen }"
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

              <div v-show="aboutDropdownOpen" class="dropdown-bridge"></div>

              <div v-show="aboutDropdownOpen" class="dropdown-menu">
                <router-link to="/about" class="dropdown-item">{{ $t('nav.about_us') || 'About Us' }}</router-link>
                <router-link to="/about/board" class="dropdown-item">{{ $t('nav.board') || 'Board of Directors' }}</router-link>
                <router-link to="/about/team" class="dropdown-item">{{ $t('nav.team') || 'Our Teams' }}</router-link>
              </div>
            </div>
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

          <router-link to="/news" class="nav-link">{{ $t('nav.news') }}</router-link>
          <router-link to="/contact" class="nav-link">{{ $t('nav.contact') }}</router-link>
          <LanguageToggle />
        </div>

        <!-- Mobile Hamburger Menu Button -->
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="hamburger"
          :class="{ active: mobileMenuOpen }"
          aria-label="Toggle menu"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <!-- Mobile Menu Overlay -->
      <transition name="menu-fade">
        <div v-if="mobileMenuOpen" class="mobile-menu-overlay" @click="mobileMenuOpen = false">
          <div class="mobile-menu-panel" @click.stop>
            <!-- Menu Header -->
            <div class="mobile-menu-header">
              <img src="@/assets/img/logo-white.png" alt="TAJDID Logo" class="menu-logo" />
              <button @click="mobileMenuOpen = false" class="close-menu-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
              </button>
            </div>

            <!-- Menu Items -->
            <div class="mobile-menu-content">
              <router-link to="/home" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.home') }}</span>
              </router-link>

              <router-link to="/about" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.about') }}</span>
              </router-link>

              <router-link to="/about/board" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.board') || 'Board of Directors' }}</span>
              </router-link>

              <router-link to="/about/team" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.team') || 'Our Teams' }}</span>
              </router-link>

              <router-link to="/services" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.services') }}</span>
              </router-link>

              <router-link to="/news" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.news') }}</span>
              </router-link>

              <router-link to="/contact" class="mobile-nav-item">
                <span class="nav-item-text">{{ $t('nav.contact') }}</span>
              </router-link>
            </div>

            <!-- Menu Footer -->
            <div class="mobile-menu-footer">
              <p>© 2025 TAJDID Corporation</p>
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
.nav-transparent .hamburger span {
  color: white;
}

.nav-transparent .hamburger span {
  background: white;
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
.nav-scrolled .hamburger span {
  color: #4b5563;
}

.nav-scrolled .hamburger span {
  background: #4b5563;
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
