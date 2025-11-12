import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/styles.css'
import './assets/about.css'
import './assets/contact.css'
import './assets/footer.css'
import './assets/home.css'
import './assets/service.css'
import './assets/news.css'
import './assets/newsdetail.css'
import './assets/mobile.css'

createApp(App).use(router).mount('#app')
