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
      <div class="max-w-7xl mx-auto px-4">
        <p class="header-subtitle">Feel free to reach out to us</p>
      </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
      <div class="max-w-7xl mx-auto px-4">
        <div class="contact-grid">
          <!-- Left Side - Contact Form -->
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

          <!-- Right Side - Contact Info & Map -->
          <div class="info-section">
            <!-- Contact Details -->
            <div class="contact-details">
              <div class="contact-item">
                <div class="contact-icon phone-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                      d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"
                    />
                  </svg>
                </div>
                <span class="contact-text">+609 - 628 5800</span>
              </div>

              <div class="contact-item">
                <div class="contact-icon email-icon">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                      d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
                    />
                  </svg>
                </div>
                <span class="contact-text">info@tajdid.com.my</span>
              </div>
            </div>

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
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Override DaisyUI input styles for white background */
:deep(.input),
:deep(.textarea) {
  background-color: #ffffff;
  color: #1f2937;
  border: 2px solid #e5e7eb;
}

:deep(.input:focus),
:deep(.textarea:focus) {
  background-color: #ffffff;
  border-color: #397ab0;
  outline: none;
}

:deep(.input::placeholder),
:deep(.textarea::placeholder) {
  color: #9ca3af;
}

/* Add error message styling */
.error-message {
  background: #fee2e2;
  color: #991b1b;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
  font-weight: 500;
}

.btn-primary {
  background: #397ab0;
  border-radius: 50px;
  padding: 1rem 3rem;
  font-weight: 600;
}

.btn-primary:hover {
  background: #2d6090;
  transform: translateY(-2px);
}
</style>
