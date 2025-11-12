<script setup>
defineOptions({
  name: 'FooterComponent',
})
import { ref } from 'vue'

const socialLinks = ref([
  {
    name: 'Facebook',
    url: 'https://www.facebook.com/TajdidCorporationTRG/',
    icon: 'facebook',
  },
  {
    name: 'Instagram',
    url: 'https://www.instagram.com/tajdidcorporation.official/',
    icon: 'instagram',
  },
  {
    name: 'TikTok',
    url: 'https://www.tiktok.com/@tajdid.academy',
    icon: 'tiktok',
  },
  {
    name: 'WhatsApp',
    url: 'https://wa.me/60196285800',
    icon: 'whatsapp',
  },
  {
    name: 'LinkedIn',
    url: 'https://my.linkedin.com/company/tajdid-corporation-sdn-bhd',
    icon: 'linkedin',
  },
])
</script>

<template>
  <footer class="footer">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
      <div class="footer-content">
        <!-- Logo and Social Media -->
        <div class="footer-brand">
          <img src="@/assets/img/logo.png" alt="TAJDID Logo" class="footer-logo" />
          <p class="company-name">TAJDID CORPORATION SDN BHD</p>
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
            <p>Terengganu Advanced Joint Digital Intelligent Development (TAJDID)</p>
            <p class="address-details">
              2nd Floor, TD1303, Jalan Sultan Zainal Abidin 20000 Kuala Terengganu Terengganu Darul
              Iman
            </p>
          </div>
        </div>

        <!-- Social Media & Contact -->
        <div class="footer-section">
          <h4 class="footer-heading">Social Media</h4>
          <div class="social-media-icons">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="social-icon-link"
              :aria-label="social.name"
            >
              <!-- Facebook -->
              <svg v-if="social.icon === 'facebook'" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2" />
                <path
                  d="M15.5 8.5h-2c-1 0-1.5.5-1.5 1.5v1.5h3.5l-.5 3.5H12V21h-3v-6.5H6.5V11H9V9.5c0-2.5 1.5-4 4-4h2.5v3z"
                  fill="currentColor"
                />
              </svg>
              <!-- Instagram -->
              <svg
                v-else-if="social.icon === 'instagram'"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
              </svg>
              <!-- TikTok -->
              <svg v-else-if="social.icon === 'tiktok'" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"
                />
              </svg>
              <!-- WhatsApp -->
              <svg v-else-if="social.icon === 'whatsapp'" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                />
              </svg>
              <!-- LinkedIn -->
              <svg v-else-if="social.icon === 'linkedin'" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                />
              </svg>
            </a>
          </div>

          <div class="contact-info">
            <a href="mailto:info@tajdid.com.my" class="contact-link">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 12h8M12 8v8" />
              </svg>
              info@tajdid.com.my
            </a>
            <a href="tel:+60196285800" class="contact-link">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                />
              </svg>
              +609 - 6285800
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
