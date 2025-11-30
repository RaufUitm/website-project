// router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import News from '../views/News.vue'
import NewsDetail from '../views/NewsDetail.vue'
import About from '../views/About.vue'
import Board from '../views/Board.vue'
import Team from '../views/Team.vue'
import Services from '../views/Services.vue'
import Contact from '../views/Contact.vue'

const routes = [
  {
    path: '/',
    redirect: '/home',
  },
  {
    path: '/home',
    name: 'Home',
    component: Home,
  },
  {
    path: '/about',
    name: 'About',
    component: About,
  },
  {
    path: '/about/board',
    name: 'Board',
    component: Board,
  },
  {
    path: '/about/team',
    name: 'Team',
    component: Team,
  },
  {
    path: '/services',
    name: 'Services',
    component: Services,
  },
  {
    path: '/news',
    name: 'News',
    component: News,
  },
  {
    path: '/news/:id',
    name: 'NewsDetail',
    component: NewsDetail,
    props: true,
  },
  {
    path: '/contact',
    name: 'Contact',
    component: Contact,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  },
})

export default router
