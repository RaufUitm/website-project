import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import i18n from './i18n.js'
import './assets/styles.css'
import './assets/about.css'
import './assets/contact.css'
import './assets/footer.css'
import './assets/home.css'
import './assets/service.css'
import './assets/news.css'
import './assets/newsdetail.css'
import './assets/mobile.css'
const app = createApp(App)
app.use(router)
app.use(i18n)
app.mount('#app')
