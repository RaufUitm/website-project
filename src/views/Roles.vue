<script setup>
import { onMounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'

defineOptions({ name: 'RolesPage' })

const route = useRoute()

const scrollToHash = async (hash) => {
  if (!hash) return
  const id = hash.replace('#', '')
  await nextTick()
  const el = document.getElementById(id)
  if (el) {
    // If it's a <details>, open it so content is visible
    const details = el.closest('details') || el
    if (details && details.tagName && details.tagName.toLowerCase() === 'details') {
      details.open = true
    }
    el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

onMounted(() => {
  if (route.hash) {
    scrollToHash(route.hash)
  }
})

watch(
  () => route.hash,
  (newHash) => {
    if (newHash) scrollToHash(newHash)
  },
)
</script>

<template>
  <div class="about-page">
    <section class="about-header">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="page-title">{{ $t('nav.roles') }}</h1>
        <p class="page-subtitle">{{ $t('about.subtitle') || 'Overview of roles and responsibilities' }}</p>
      </div>
    </section>

    <div class="roles-page max-w-7xl mx-auto px-6 py-12">

      <!-- Hero moved out of header into a separate section -->
      <section class="roles-hero-section py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
          <div class="hero-card lg:mx-24 bg-gradient-to-r from-blue-400 to-blue-700 text-white rounded-lg p-8 shadow-lg">
            <h2 class="text-xl font-semibold mb-4">TAJDID's Role</h2>
            <p class="mb-6">Digitalization of The State</p>
            <p class="opacity-90">Implement and monitor the digitalization of Terengganu by focusing on digitalization across sectors.</p>
          </div>

          <div class="header-divider mt-8" aria-hidden="true"></div>
        </div>
      </section>

      <section class="roles-cards-section py-8">
        <div class="max-w-7xl mx-auto px-4">
          <div class="roles-cards grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card p-6 rounded-lg">Economic Development and Digital Society
              <div class="mt-4 text-sm opacity-90">Creating a digital ecosystem based on 4IR technology to drive economic growth and digital society in the State of Terengganu</div>
            </div>

            <div class="card p-6 rounded-lg">Development & Monitoring of The Digital Master Plan/Framework Across Sectors
              <div class="mt-4 text-sm opacity-90">Responsible for developing and monitoring the progress of Digital Masterplans/Blueprints across Sectors.</div>
            </div>

            <div class="card p-6 rounded-lg">Secretariat
              <div class="mt-4 text-sm opacity-90">Functioning as the secretariat to the Terengganu State Digital Economy Blueprint and 4IR Policy Council</div>
            </div>
          </div>
        </div>
      </section>


          <div class="roles-sections space-y-8">
            <section id="ecosystem">
              <h4 class="text-lg font-semibold">Digitalization Ecosystem & Digital</h4>
              <p class="mt-4 pb-4 text-gray-700">Digital transformation is not solely about technology, it is a new strategy and way of thinking about how to use technology to reconstruct the business model in order for the organisation to remain relevant and competitive.

This smart digital state and smart people will improve people's lives and the state's economic growth in a more sustainable direction, as well as internationalise Terengganu on the global stage.

The digital transformation process has a significant impact on life and the economy if properly planned and managed. To achieve this, business models and operations must be reformed in accordance with the capabilities of digital technology, which may gradually close the gap between the real and virtual worlds through the unification of people, processes, and devices.

To realize this aspiration, several issues and challenges need to be addressed wisely. This is the foundation of the establishment of TAJDID, and it is in line with the Terengganu Smart Digital Master Plan.</p>
            </section>

            <section id="agenda">
              <h4 class="text-lg font-semibold">Terengganu's State Digitalization Agenda Framework</h4>
              <p class="mt-4 pb-4 text-gray-700">The state digitalization agenda was proposed in order to make Terengganu a practical and sustainable smart digital state model to drive the state's economic growth through the development of digital specialists, digital-based products/services, and local digital solutions. This agenda is demonstrated by the Terengganu State Digitalization Framework, which comprises of three main elements: People, Digital Platforms, and Digital Products.

Smart cities, smart villages, and other digital government components will use Digital Platforms based on new digital technology (emerging technology) to enable the People element to interact with each other through the use of Digital Products provided by government agencies, private parties, and communities.</p>
            </section>

            <section id="vision">
              <h4 class="text-lg font-semibold">Digital Terengganu Vision Framework 2030</h4>
              <p class="mt-4 pb-4 text-gray-700">The success of this plan's implementation depends on the dedication of all parties concerned, including government agencies, commercial corporations, educational institutions, industry, and the community. Several additional related strategic plans will be used to support the specifics and implementation of this master plan.

The confluence of the foregoing achievements and the implementation of these plans is projected to fulfil the objective of making the State of Terengganu Darul Iman a smart digital state upheld by smart people.

Smart people are people who have been empowered by digital mindsets and skills to establish a society with moral values based on Islam (Masyarakat Digital 99.0).</p>
            </section>

            <section id="thrust">
              <h4 class="text-lg font-semibold">Strategic Thrust</h4>
              <p class="mt-4 pb-4 text-gray-700">Five main strategic thrusts have been identified to assist TAJDID in carrying out its responsibilities as well as addressing the challenges arising in realising the vision and mission.</p>
            </section>
          </div>

    </div>
  </div>
</template>

<style scoped>
.about-page { background: white; }
.card { background: linear-gradient(180deg,#fff,#f8fafc); }

/* Roles header specific overrides to sit on the gradient */
.about-header .hero-card {
  max-width: 1100px;
  margin-left: auto;
  margin-right: auto;
}
.about-header .header-divider {
  width: 4px;
  height: 48px;
  background: rgba(255,255,255,0.55);
  margin: 2.5rem auto 0;
  border-radius: 2px;
}
.about-header .roles-cards .card {
  background: rgba(255,255,255,0.12);
  color: white;
  min-height: 160px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 1.5rem;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.about-header .roles-cards .card .mt-4 { opacity: 0.95; }

/* Styles for the separated cards section (white cards on page background) */
.roles-cards-section .card {
  background: linear-gradient(180deg,#fff,#f8fafc);
  color: #1f2937;
  min-height: 160px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 1.5rem;
  box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}
.roles-cards-section .card .mt-4 { opacity: 0.95; }
</style>
