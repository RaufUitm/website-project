<script setup>
defineOptions({
  name: 'ServicesPage',
})
import { ref } from 'vue'

const services = ref([
  {
    id: 'software-development',
    image: new URL('@/assets/img/002.png', import.meta.url).href,
    title: 'Software Development',
    description:
      'A health welfare platform service created by the Terengganu State Government to help low income (B40) gain access to basic healthcare services in the state',
  },
  {
    id: 'interactive-analytics-dashboard',
    image: new URL('@/assets/img/Product 03.png', import.meta.url).href,
    title: 'Interactive Analytics Dashboard',
    description:
      'A comprehensive data visualization and analytics platform that transforms complex datasets into intuitive, interactive dashboards. Our solution empowers organizations to make data-driven decisions through real-time monitoring, customizable reports, and advanced analytics capabilities. Features include dynamic charts, KPI tracking, predictive analytics, and seamless integration with multiple data sources. The dashboard provides role-based access control, automated alerts, and mobile-responsive design for on-the-go insights.',
  },
  {
    id: 'AI-surveillance-system',
    title: 'AI Surveillance System',
    description:
      'Intelligent monitoring solution that uses AI to detect, analyze, and respond to real-time security events efficiently.',
    image: new URL('@/assets/img/003.png', import.meta.url).href,
  },
  {
    id: 'smart-technology-provider',
    title: 'Smart Technology Provider',
    description:
      'Delivers innovative smart solutions and digital systems to enhance efficiency, connectivity, and automation.',

    image: new URL('@/assets/img/004.png', import.meta.url).href,
  },
  {
    id: 'Training-Consultancy',
    image: new URL('@/assets/img/Product 04.png', import.meta.url).href,
    title: 'Training & Consultancy',
    description:
      'Training & consultancy not only focuses on technological results but also on developing digital people in a smart, strategic and beneficial way.',
  },
  {
    id: 'nfc-business-card',
    image: new URL('@/assets/img/Product 02.png', import.meta.url).href,
    title: 'NFC Business Card',
    description:
      'Digital business cards with NFC (Near Field Communication) technology that allows information to be sent with a touch to another phone',
  },
])
</script>

<template>
  <div class="services-page">
    <div class="max-w-7xl mx-auto px-4">
      <h1 class="text-5xl font-bold text-white text-center mb-8">Our Services</h1>
      <p class="text-xl text-white text-center mb-16 max-w-2xl mx-auto">
        We offer a comprehensive range of services to help your business thrive online
      </p>

      <!-- Single continuous page layout -->
      <div class="services-container">
        <section
          v-for="service in services"
          :key="service.id"
          :id="service.id"
          class="service-section"
        >
          <div class="service-image-wrapper">
            <img :src="service.image" :alt="service.title" class="service-full-image" />
          </div>
          <div class="service-text-content">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ service.title }}</h2>
            <p class="text-lg text-gray-600 leading-relaxed">{{ service.description }}</p>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
