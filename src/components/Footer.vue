<script setup>
defineOptions({
  name: 'FooterComponent',
})
import { ref } from 'vue'

const socialLinks = ref([
  {
    name: 'Instagram',
    url: 'https://www.instagram.com/tajdidcorporation.official/',
    icon: 'instagram',
  },
  {
    name: 'Facebook',
    url: 'https://www.facebook.com/TajdidCorporationTRG/',
    icon: 'facebook',
  },
  {
    name: 'Twitter/X',
    url: 'https://x.com/TAJDIDB',
    icon: 'twitter',
  },
])
</script>

<template>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-content">
        <!-- Logo and Social Media -->
        <div class="footer-brand">
          <img src="@/assets/img/logo-white.png" alt="TAJDID Logo" class="footer-logo" />
          <p class="company-name">TAJDID CORPORATION SDN BHD</p>

          <div class="social-media">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="social-link"
              :aria-label="social.name"
            >
              <!-- Instagram Icon -->
              <svg
                v-if="social.icon === 'instagram'"
                class="social-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
              </svg>

              <!-- Facebook Icon -->
              <svg
                v-else-if="social.icon === 'facebook'"
                class="social-icon"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2" />
                <path
                  d="M15.5 8.5h-2c-1 0-1.5.5-1.5 1.5v1.5h3.5l-.5 3.5H12V21h-3v-6.5H6.5V11H9V9.5c0-2.5 1.5-4 4-4h2.5v3z"
                  fill="currentColor"
                />
              </svg>

              <!-- Twitter/X Icon -->
              <svg
                v-else-if="social.icon === 'twitter'"
                class="social-icon"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                />
              </svg>
            </a>
          </div>
        </div>

        <!-- Explore Links -->
        <div class="footer-section">
          <h4 class="footer-heading">Explore</h4>
          <ul class="footer-links">
            <li><router-link to="/home" class="footer-link">Home</router-link></li>
            <li><router-link to="/services" class="footer-link">Services</router-link></li>
            <li><router-link to="/" class="footer-link">News</router-link></li>
            <li><router-link to="/contact" class="footer-link">Contact Us</router-link></li>
          </ul>
        </div>

        <!-- Address -->
        <div class="footer-section">
          <h4 class="footer-heading">Address</h4>
          <div class="footer-address">
            <p>
              Terengganu Advance Joint Digital Intelligent Development (TAJDID) Corporation Sdn Bhd
            </p>
            <p class="address-details">
              Tingkat 2, TD 1303, Jalan Sultan Zainal Abidin, 20000 Kuala Terengganu, Terengganu
              Darul Iman
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
