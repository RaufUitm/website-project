# Company Website

A modern, responsive corporate website built with Vue 3, Vite, and Tailwind CSS, featuring interactive animations, dynamic layouts, and comprehensive service showcases.

## 🚀 Features

- **Modern Tech Stack**: Vue 3 Composition API, Vite, Tailwind CSS
- **Responsive Design**: Fully optimized for desktop, tablet, and mobile devices
- **Interactive Animations**: Wireframe particle animations, smooth transitions, and hover effects
- **Dynamic Service Layouts**: Custom 70/30 split layouts with floating content cards
- **Video Integration**: Autoplay video sections with overlay content
- **Shared Values Carousel**: Auto-scrolling infinite carousel with custom styling
- **SEO Optimized**: Meta tags, semantic HTML, and performance optimizations

## 📋 Pages

- **Home**: Hero section with animated wireframe background and company introduction
- **About**: Company overview, shared values carousel, vision & mission
- **Services**: 
  - Software Development
  - Interactive Analytics Dashboard
  - AI Surveillance System
  - Smart Technology Solutions
  - Training & Consultancy
  - NFC Business Cards
- **News**: Latest updates and announcements
- **Contact**: Contact form and company information

## 🛠️ Technology Stack

- **Frontend Framework**: Vue 3 (Composition API)
- **Build Tool**: Vite
- **Styling**: Tailwind CSS, Custom CSS
- **Routing**: Vue Router
- **Icons**: SVG icons
- **Animations**: CSS animations, Canvas API for particle effects

## 📦 Project Setup

### Install Dependencies
```sh
npm install
```

### Development Server
```sh
npm run dev
```
Access the site at `http://localhost:5173`

### Production Build
```sh
npm run build
```

### Preview Production Build
```sh
npm run preview
```

### Lint Code
```sh
npm run lint
```

## 🎨 Customization

### Colors
Primary colors are defined in the CSS files:
- Blue: `#397ab0`
- Green: `#78c054`

### Images
Replace images in `src/assets/img/` directory with your own:
- Service images: `001.png`, `002.png`, `003.png`, etc.
- Value icons: `taqwa.png`, `credibility.png`, `teamwork.png`, etc.

### Content
Update content in Vue component files:
- Service details: `src/views/Services.vue`
- Company values: `src/views/About.vue`
- Contact info: `src/views/Contact.vue`

## 📱 Responsive Breakpoints

- Mobile: `< 768px`
- Tablet: `768px - 1024px`
- Desktop: `> 1024px`

## 🌟 Key Features Breakdown

### Service Page Layouts
- **70/30 Split**: Images occupy 70% width, content cards float on 30%
- **Alternating Design**: Left-right zigzag pattern for visual interest
- **Wireframe Animation**: Particle effects in empty background areas
- **Custom Themes**: Each service has unique styling and color scheme

### About Page
- **Wireframe Hero**: Animated particle network background
- **Infinite Carousel**: Auto-scrolling shared values with custom cards
- **Vision & Mission**: Split-screen layout with imagery

## 🏗️ Project Structure

```
my-website/
├── public/              # Static assets
├── src/
│   ├── assets/         # Images, fonts, styles
│   │   ├── img/        # Image files
│   │   ├── fonts/      # Font files
│   │   ├── about.css   # About page styles
│   │   ├── contact.css # Contact page styles
│   │   ├── footer.css  # Footer styles
│   │   ├── home.css    # Home page styles
│   │   ├── mobile.css  # Mobile responsive styles
│   │   ├── news.css    # News page styles
│   │   └── service.css # Service page styles
│   ├── components/     # Reusable components
│   │   ├── Footer.vue
│   │   └── Navbar.vue
│   ├── router/         # Vue Router configuration
│   │   └── index.js
│   ├── views/          # Page components
│   │   ├── About.vue
│   │   ├── Contact.vue
│   │   ├── Home.vue
│   │   ├── News.vue
│   │   ├── NewsDetail.vue
│   │   └── Services.vue
│   ├── App.vue         # Root component
│   └── main.js         # Application entry point
├── index.html
├── package.json
├── vite.config.js
└── tailwind.config.js
```

## 🔧 Development Tips

### Adding New Services
1. Edit `src/views/Services.vue`
2. Add service object to the `services` array
3. Include: id, image, title, subtitle, description, details, highlight, technologies, layout type

### Customizing Animations
- Wireframe particle settings: Adjust `particleCount`, `maxDistance` in component script
- Animation speeds: Modify CSS animation durations in respective CSS files

### Color Schemes
Each service layout has a unique theme. Customize in `src/assets/service.css`:
- `.layout-code-terminal` - Blue theme
- `.layout-data-grid` - Cyan theme  
- `.layout-scanner-grid` - Purple theme
- `.layout-network-nodes` - Green theme
- `.layout-presentation` - Orange theme

## 📄 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 📄 License

Private project - All rights reserved

## 👥 Contact

For more information, visit the contact page on the website.
