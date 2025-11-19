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
    subtitle: 'Custom Solutions for Your Digital Needs',
    description:
      'Transform your business with tailored software solutions designed to meet your unique requirements.',
    howItWorks: 'We follow agile methodology from requirement gathering to deployment, ensuring your vision becomes reality. Our development process includes consultation, design, development, testing, and continuous support.',
    benefits: 'Increased operational efficiency, reduced costs, scalability for future growth, and competitive advantage through digital innovation.',
    details: [
      'Full-cycle custom web and mobile application development with modern frameworks',
      'Enterprise software solutions with seamless system integration and automation',
      'Scalable cloud-based applications and SaaS platforms with high availability',
      'Robust API development and secure third-party integrations',
      'Legacy system modernization with minimal disruption to operations',
    ],
    highlight:
      'We developed a health welfare platform for the Terengganu State Government, enabling low-income (B40) families to access essential healthcare services across the state. The platform streamlined benefit distribution and improved accessibility for thousands of families.',
    technologies: ['React', 'Vue.js', 'Node.js', 'Python', 'Java', 'Cloud Services'],
    layout: 'code-terminal',
  },
  {
    id: 'interactive-analytics-dashboard',
    image: new URL('@/assets/img/Product 03.png', import.meta.url).href,
    title: 'Interactive Analytics Dashboard',
    subtitle: 'Data-Driven Decision Making',
    description:
      'Transform complex data into actionable insights with our comprehensive visualization platform.',
    howItWorks: 'Our system aggregates data from multiple sources, processes it in real-time, and delivers interactive dashboards accessible anywhere. The platform automatically monitors your key metrics, triggers alerts for important changes, and provides predictive insights.',
    benefits: '80% reduction in manual reporting time, 60% faster decision-making, improved ROI visibility, and early identification of business opportunities and risks.',
    details: [
      'Real-time data monitoring with live updates and customizable visualizations',
      'Customizable KPI tracking aligned with your business objectives',
      'Predictive analytics using machine learning for accurate forecasting',
      'Multi-source data integration from databases, APIs, and cloud platforms',
      'Role-based access control ensuring data security and compliance',
      'Mobile-responsive design for on-the-go access from any device',
    ],
    highlight:
      'Our dashboards empower organizations to make informed decisions through intuitive visualizations, automated alerts, and advanced analytics capabilities. Clients report 80% less time on reporting and 60% faster decision cycles, with one organization identifying RM500K in annual savings.',
    technologies: ['Power BI', 'Tableau', 'D3.js', 'Python Analytics', 'SQL', 'Big Data'],
    layout: 'data-grid',
  },
  {
    id: 'AI-surveillance-system',
    title: 'AI Surveillance System',
    subtitle: 'Intelligent Security Monitoring',
    description:
      'Advanced AI-powered surveillance that detects threats, analyzes patterns, and responds in real-time.',
    howItWorks: 'Using deep learning algorithms, our system continuously analyzes video feeds to identify suspicious activities, recognize faces and license plates, and detect anomalies. The AI learns normal patterns and immediately flags deviations, sending instant alerts to security personnel.',
    benefits: '90% reduction in false alarms, 24/7 automated monitoring, faster incident response, comprehensive forensic capabilities, and significant reduction in security staffing costs while improving coverage.',
    details: [
      'Advanced facial recognition with 99% accuracy and person tracking across cameras',
      'Intelligent anomaly detection identifying unusual behaviors and threat patterns',
      'High-precision license plate recognition (LPR) for vehicle tracking',
      'Crowd analysis monitoring density, flow patterns, and behavior',
      'Real-time alerts with video clips sent instantly to security teams',
      'Powerful video analytics with forensic search by person, vehicle, or event',
    ],
    highlight:
      'Our AI surveillance system enhances security operations with intelligent monitoring, reducing false alarms by 90% while ensuring critical events are never missed. Clients report 70% faster incident response and 50% reduction in security operational costs.',
    technologies: ['Computer Vision', 'Deep Learning', 'TensorFlow', 'OpenCV', 'Edge Computing'],
    image: new URL('@/assets/img/003.png', import.meta.url).href,
    layout: 'code-terminal',
  },
  {
    id: 'smart-technology-provider',
    title: 'Smart Technology Provider',
    subtitle: 'Innovation for Digital Transformation',
    description:
      'Comprehensive smart solutions that enhance efficiency, connectivity, and automation across your organization.',
    howItWorks: 'We create interconnected IoT ecosystems that collect real-time data from sensors and devices, process it at the edge or cloud, and trigger automated responses. Our solutions monitor environments, control systems remotely, predict maintenance needs, and optimize resource usage.',
    benefits: '30-50% reduction in operational costs, predictive maintenance preventing 85% of equipment failures, 40% energy savings, improved safety, and real-time visibility across all operations.',
    details: [
      'End-to-end IoT solutions with sensor networks and cloud connectivity',
      'Smart building systems managing HVAC, lighting, access, and energy',
      'Process automation optimizing workflows and reducing manual tasks',
      'Digital twin technology creating virtual replicas for simulation and testing',
      'Smart city infrastructure for traffic, waste, parking, and public services',
      'Centralized device management platforms monitoring thousands of endpoints',
    ],
    highlight:
      'We deliver cutting-edge smart technology solutions that connect devices, optimize operations, and drive digital innovation. Our systems have helped clients reduce operational costs by 30-50%, prevent 85% of equipment failures through predictive maintenance, and achieve 40% energy savings.',
    technologies: ['IoT', 'MQTT', 'LoRaWAN', 'Edge AI', 'Cloud Integration', '5G'],
    image: new URL('@/assets/img/0041.png', import.meta.url).href,
    layout: 'data-grid',
  },
  {
    id: 'Training-Consultancy',
    image: new URL('@/assets/img/Product 04.png', import.meta.url).href,
    title: 'Training & Consultancy',
    subtitle: 'Empowering Digital Excellence',
    description:
      'Strategic guidance and comprehensive training programs to accelerate your digital transformation journey.',
    howItWorks: 'We begin with thorough assessment of your current capabilities, identify gaps, and design customized programs that combine strategic consulting with hands-on training. Our approach ensures knowledge transfer, builds internal capabilities, and creates sustainable change.',
    benefits: '85% technology adoption rates, 50% faster time-to-value on new initiatives, reduced dependency on external consultants, improved employee productivity and satisfaction, and measurable ROI on digital investments.',
    details: [
      'Digital transformation strategy with comprehensive roadmap and implementation planning',
      'Technical skills development programs covering modern technologies and best practices',
      'Leadership training in digital innovation, agile methodologies, and change management',
      'Change management strategies ensuring smooth adoption and cultural transformation',
      'Technology assessment evaluating current state and future-ready recommendations',
      'Custom workshops and industry-recognized certification programs tailored to your needs',
    ],
    highlight:
      'Our consultancy services focus on developing both technology solutions and digital capabilities in people, ensuring sustainable and strategic growth. Clients achieve 85% adoption rates, 50% faster project delivery, and significant cost savings by building internal expertise.',
    technologies: ['Agile Methodologies', 'DevOps', 'Cloud Architecture', 'AI/ML', 'Cybersecurity'],
    layout: 'code-terminal',
  },
  {
    id: 'nfc-business-card',
    video: new URL('@/assets/img/NFC full (horizon).mp4', import.meta.url).href,
    title: 'NFC Business Card',
    subtitle: 'Modern Networking Solutions',
    description:
      'Digital business cards powered by NFC technology for seamless, contactless information sharing.',
    details: [
      'Instant tap-to-share contact information with universal smartphone compatibility',
      'Fully customizable digital profiles with rich media, links, and branding',
      'Seamless social media integration connecting to LinkedIn, Instagram, and more',
      'Comprehensive analytics tracking views, saves, and engagement patterns',
      'Eco-friendly alternative eliminating paper waste and printing costs',
      'Real-time profile updates propagating instantly to all existing cards',
    ],
    highlight:
      'Transform networking with our NFC business cards that instantly share your contact details, portfolio, and social profiles with just a tap. Users report 10x higher follow-up rates, zero printing costs, and the ability to update information instantly across all distributed cards.',
    technologies: ['NFC', 'QR Codes', 'Mobile Apps', 'Cloud Storage', 'Web Technologies'],
  },
])
</script>

<template>
  <div class="services-page">
    <!-- Header -->
    <section class="services-header">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="page-title">Our Services</h1>
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
                    <p class="article-subtitle">{{ service.subtitle }}</p>
                  <p class="article-description">{{ service.description }}</p>

                  <div class="highlight-box">
                    <svg
                      class="highlight-icon"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"
                      />
                    </svg>
                    <p>{{ service.highlight }}</p>
                  </div>

                  <div class="features-section">
                    <h3 class="features-title">Key Features</h3>
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
              <!-- Decorative placeholder (wireframe removed) -->
              <div class="wireframe-canvas"></div>

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
                <p class="article-subtitle">{{ service.subtitle }}</p>

                <!-- Description -->
                <p class="article-description">{{ service.description }}</p>

                <!-- Highlight Box -->
                <div class="highlight-box">
                  <svg
                    class="highlight-icon"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                  </svg>
                  <p>{{ service.highlight }}</p>
                </div>

                <!-- Key Features -->
                <div class="features-section">
                  <h3 class="features-title">Key Features</h3>
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
