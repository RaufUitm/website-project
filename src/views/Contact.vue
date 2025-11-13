<script setup>
defineOptions({
  name: 'ContactPage',
})
import { ref } from 'vue'
import emailjs from '@emailjs/browser'

const form = ref({
  name: '',
  email: '',
  message: '',
})

const submitted = ref(false)
const isLoading = ref(false)
const error = ref('')

// Replace these with your actual EmailJS credentials
const EMAILJS_SERVICE_ID = 'service_dcqye0m'
const EMAILJS_TEMPLATE_ID = 'template_rqogadq'
const EMAILJS_PUBLIC_KEY = 'J4c7AkUmqNFB39G20'

const submitForm = async () => {
  isLoading.value = true
  error.value = ''

  try {
    // Send email using EmailJS
    const result = await emailjs.send(
      EMAILJS_SERVICE_ID,
      EMAILJS_TEMPLATE_ID,
      {
        from_name: form.value.name,
        from_email: form.value.email,
        message: form.value.message,
        to_name: 'Tajdid Team', // Optional: recipient name
      },
      EMAILJS_PUBLIC_KEY,
    )

    console.log('Email sent successfully:', result)
    submitted.value = true

    // Reset form after 3 seconds
    setTimeout(() => {
      form.value = { name: '', email: '', message: '' }
      submitted.value = false
    }, 3000)
  } catch (err) {
    console.error('Failed to send email:', err)
    error.value = 'Failed to send message. Please try again.'

    // Clear error after 5 seconds
    setTimeout(() => {
      error.value = ''
    }, 5000)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="contact-page">
    <!-- Header Section -->
    <section class="contact-header">
      <div class="max-w-8xl mx-auto px-4">
        <p class="header-subtitle">Contact Us</p>
      </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
      <div class="max-w-7xl mx-auto px-4">
        <div class="contact-grid">
          <!-- Left Side - Company Info -->
          <div class="info-section">
            <h2 class="section-title">Office Information</h2>

            <div class="company-info">
              <p class="company-address">
                Terengganu Advanced Joint Digital Intelligent Development (TAJDID)<br />
                2nd Floor, TD1303, Jalan Sultan Zainal Abidin<br />
                20000 Kuala Terengganu<br />
                Terengganu Darul Iman
              </p>
            </div>

            <div class="contact-info-group">
              <div class="info-row">
                <div class="info-column">
                  <h4 class="info-heading">Phone</h4>
                  <p class="info-text">+609 - 628 5800</p>
                </div>
                <div class="info-column">
                  <h4 class="info-heading">Email</h4>
                  <p class="info-text">info@tajdid.com.my</p>
                </div>
              </div>

              <div class="operation-hours">
                <h4 class="info-heading">Business Hours</h4>
                <ul class="hours-list">
                  <li>Sunday - Thusday: 9:00 AM - 5:30 PM</li>
                  <li>Friday - Saturday: Closed</li>
                </ul>
              </div>
            </div>

            <div class="social-media-section">
              <a
                href="https://www.facebook.com/TajdidCorporationTRG/"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
                aria-label="Facebook"
              >
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <circle
                    cx="12"
                    cy="12"
                    r="10"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  />
                  <path
                    d="M15.5 8.5h-2c-1 0-1.5.5-1.5 1.5v1.5h3.5l-.5 3.5H12V21h-3v-6.5H6.5V11H9V9.5c0-2.5 1.5-4 4-4h2.5v3z"
                    fill="currentColor"
                  />
                </svg>
              </a>
              <a
                href="https://www.instagram.com/tajdidcorporation.official/"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
                aria-label="Instagram"
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                  <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                  <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                </svg>
              </a>
              <a
                href="https://www.tiktok.com/@tajdid.academy"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
                aria-label="TikTok"
              >
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"
                  />
                </svg>
              </a>
              <a
                href="https://wa.me/60196285800"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
                aria-label="WhatsApp"
              >
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                  />
                </svg>
              </a>
              <a
                href="https://my.linkedin.com/company/tajdid-corporation-sdn-bhd"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
                aria-label="LinkedIn"
              >
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
                  />
                </svg>
              </a>
            </div>
          </div>

          <!-- Right Side - Contact Form -->
          <div class="form-section">
            <h2 class="form-title">Send a Message</h2>

            <form @submit.prevent="submitForm" class="contact-form">
              <div class="form-control">
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="input input-bordered w-full"
                  placeholder="Your Name"
                  :disabled="isLoading"
                />
              </div>

              <div class="form-control">
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="input input-bordered w-full"
                  placeholder="Email ID"
                  :disabled="isLoading"
                />
              </div>

              <div class="form-control">
                <textarea
                  v-model="form.message"
                  required
                  rows="6"
                  class="textarea textarea-bordered w-full"
                  placeholder="How can we help you?"
                  :disabled="isLoading"
                ></textarea>
              </div>

              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isLoading ? 'Sending...' : 'Submit' }}
              </button>

              <div v-if="submitted" class="success-message">
                Thank you! We'll get back to you soon.
              </div>

              <div v-if="error" class="error-message">
                {{ error }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
      <div class="embed-map-responsive">
        <div class="embed-map-container">
          <iframe
            class="embed-map-frame"
            frameborder="0"
            scrolling="no"
            marginheight="0"
            marginwidth="0"
            src="https://maps.google.com/maps?width=589&height=385&hl=en&q=TD1303&t=&z=16&ie=UTF8&iwloc=B&output=embed"
          ></iframe>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Override DaisyUI input styles */
:deep(.input),
:deep(.textarea) {
  background-color: #ffffff;
  color: #1a1a1a;
  border: 2px solid #1a1a1a;
  border-radius: 8px;
  padding: 1.125rem 1.375rem;
  font-size: 1.1875rem;
  font-weight: 500;
  box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
}

:deep(.input:focus),
:deep(.textarea:focus) {
  background-color: #ffffff;
  outline: 3px solid #1a1a1a;
  outline-offset: 0;
  border-color: #1a1a1a;
}

:deep(.input::placeholder),
:deep(.textarea::placeholder) {
  color: #6b7280;
  font-weight: 400;
}

:deep(.btn-primary) {
  background: #1a1a1a;
  border: none;
  border-radius: 50px;
  padding: 1.25rem 2.5rem;
  font-weight: 800;
  font-size: 1.25rem;
  letter-spacing: 1px;
  color: #ffffff;
  width: 100%;
  transition: all 0.3s ease;
  box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
  text-transform: uppercase;
}

:deep(.btn-primary:hover) {
  background: #2a2a2a;
  transform: translateY(-3px);
  box-shadow: 3px 5px 15px rgba(0, 0, 0, 0.4);
}

:deep(.btn-primary:disabled) {
  background: #6b7280;
  color: #d1d5db;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}
</style>
