<script setup>
defineOptions({
  name: 'ServicesPage',
})
import { ref } from 'vue'

const services = ref([
  {
    id: 'software-development',
    image: new URL('@/assets/img/001.png', import.meta.url).href,
    title: 'Software Development',

    description:
      'Transform your business with tailored software solutions designed to meet your unique requirements.',

    details: [
      'Custom System Development Building software according to organizational needs.',
      'Integration & Automation Connecting applications and automating processes.',
      'Maintenance & Security  Providing support, updates, and system protection.',
      'Data Analysis & Reporting  Managing and analyzing data for accurate decision-making',
    ],

    layout: 'code-terminal',
  },

  {
    id: 'AI-surveillance-system',
    title: 'AI Surveillance System',

    description:
      'Advanced AI-powered surveillance that detects threats, analyzes patterns, and responds in real-time.',

    details: [
      'Face Recognition – The system’s capability to accurately and efficiently identify individuals.',
      'Vehicle Recognition – Detects and classifies vehicle types such as trucks, including load capacity (tonnage), truck type, tire configuration, and other specific attributes.',
      'License Plate Recognition – Automatically captures, analyzes, and records vehicle registration numbers.',
      'Flood Detection – Monitors and detects water levels in real time for safety and emergency response purposes',
    ],
    image: new URL('@/assets/img/003.png', import.meta.url).href,
    layout: 'data-grid',
  },
  {
    id: 'interactive-analytics-dashboard',
    image: new URL('@/assets/img/Product 031.png', import.meta.url).href,
    title: 'Interactive Analytics Dashboard',

    description:
      'Transform complex data into actionable insights with our comprehensive visualization platform.',

    details: [
      'Data Visualization Provides charts, graphs, and indicators for quick understanding of data',
      'Interactive Analysis Allows users to filter, highlight, and explore data dynamically.',
      'Performance Insights Identify trends and patterns for smarter decision making.',
      'Reporting & Sharing Generates automated reports and shares insights with teams or stakeholders.',
      'User-Friendly Interface-Simple and intuitive design that anyone can use — no technical skills needed.',
    ],

    layout: 'code-terminal',
  },
  {
    id: 'smart-technology-provider',
    title: 'Smart Technology Provider',

    description:
      'Comprehensive smart solutions that enhance efficiency, connectivity, and automation across your organization.',

    details: [
      'LED DISPLAY - Indoor Large Display, borderless with Pitch size from P1.2mm - P4mm',
      'SMART CLASSROOM - Latest 21st century education technology including Smartboard, Virtual Classroom, wireless presentation system and others',
      'VIDEOWALL - Utilizing intuitive software and videowall controller combines several numbers of display LCD screens or LED Display from various inputs onto a larger screen.',
      'DIGITAL SIGNAGE - The cutting-edge communication system that allows you to design, create, schedule targeted messages on any display throughout remote offices.',
      'TOUCHSCREEN SOLUTIONS - Readymade or customizable kiosk that uses IR, Touch foil or PCAP touch technology. Interactive content development is also offered.',
    ],
    image: new URL('@/assets/img/Arvia1.png', import.meta.url).href,
    layout: 'data-grid',
  },
  {
    id: 'Training-Consultancy',
    image: new URL('@/assets/img/Product 04.png', import.meta.url).href,
    title: 'Training & Consultancy',

    description:
      'Strategic guidance and comprehensive training programs to accelerate your digital transformation journey.',

    details: [
      'Islamic-Based Digital Leadership – Covering the principles of khalifah, digital ethics, and the integration of worship values in modern leadership.',
      'Artificial Intelligence (AI) Course – Includes AI fundamentals, generative AI image creation, and practical applications of AI within organisations, administration, community engagement, official correspondence, and other operations.',
      'Digital Design Course – Utilising tools such as Canva and Photoshop to produce professional-quality visual designs.',
      'Digital Entrepreneurship Course – Focusing on creating marketing videos, promotional posters, and product images to enhance digital business presence.',
    ],

    layout: 'code-terminal',
  },
  {
    id: 'nfc-business-card',
    video: new URL('@/assets/img/NFC full (horizon).mp4', import.meta.url).href,
    title: 'NFC Business Card',

    description:
      'Digital business cards powered by NFC technology for seamless, contactless information sharing.',
    details: [
      'Easy to use – Simply touch or hold the device/card to work without complicated connections.',
      'Fast and efficient – Data transfer occurs in less than a second.',
      'Secure – The very short communication range (around 4 cm) reduces the risk of hacking.',
      'Versatile – Can be used for a variety of purposes such as cashless payments, door access, digital tickets, and information sharing.',
      'No external power required (for the card) – NFC cards work passively, receiving energy from the electromagnetic field of the reader.',
      'Easy integration – Can be easily connected to digital systems such as apps, websites, or databases.',
    ],
  },
])
</script>

<template>
  <div class="services-page">
    <!-- Header -->
    <section class="services-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">Our Service</h1>
        <p class="page-subtitle">
          Empowering digital transformation through innovative solutions and expert guidance
        </p>
      </div>
    </section>

    <!-- Content - Full Width Magazine Layout -->
    <section class="services-content">
      <div class="services-magazine-container">
        <!-- Service Article -->
        <article
          v-for="(service, index) in services"
          :key="service.id"
          :id="service.id"
          :class="['service-article', index % 2 === 0 ? 'layout-left' : 'layout-right']"
        >
          <!-- Special Layout for Video Services -->
          <template v-if="service.video">
            <!-- Video + Content Side by Side -->
            <div class="video-split-section">
              <!-- Content on Left -->
              <div class="video-side-content">
                <div class="content-wrapper">
                    <span class="service-badge">{{ `0${index + 1}` }}</span>
                    <h2 class="article-title">{{ service.title }}</h2>

                  <p class="article-description">{{ service.description }}</p>



                  <div class="features-section">
                    <h3 class="features-title">Details</h3>
                    <ul class="features-list">
                      <li v-for="(detail, idx) in service.details" :key="idx" class="feature-item">
                        <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                          />
                        </svg>
                        <span>{{ detail }}</span>
                      </li>
                    </ul>
                  </div>

                </div>
              </div>

              <!-- Video on Right -->
              <div class="video-side-player">
                <video
                  :src="service.video"
                  class="side-video"
                  autoplay
                  loop
                  muted
                  playsinline
                ></video>
              </div>
            </div>
          </template>

          <!-- Regular Layout for Image Services -->
          <template v-else>
            <!-- Custom Layout Container -->
            <div
              class="service-custom-layout"
              :class="`layout-${service.layout || 'default'}`"
            >
              <!-- Image Container (position varies by layout) -->
              <div class="layout-image-container" :class="`image-${service.layout || 'default'}`">
                <img :src="service.image" :alt="service.title" class="layout-image" />
                <div class="image-overlay" :class="`overlay-${service.layout || 'default'}`"></div>
              </div>

              <!-- Content Card (position varies by layout) -->
              <div
                class="layout-content-card"
                :class="`content-${service.layout || 'default'}`"
              >
                <span class="service-badge">{{ `0${index + 1}` }}</span>
                <h2 class="article-title">{{ service.title }}</h2>


                <!-- Description -->
                <p class="article-description">{{ service.description }}</p>



                <!-- Key Features -->
                <div class="features-section">
                  <h3 class="features-title">Details</h3>
                  <ul class="features-list">
                    <li v-for="(detail, idx) in service.details" :key="idx" class="feature-item">
                      <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path
                          fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"
                        />
                      </svg>
                      <span>{{ detail }}</span>
                    </li>
                  </ul>
                </div>

              </div>
            </div>
          </template>
        </article>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
