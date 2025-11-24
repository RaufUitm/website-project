import daisyui from 'daisyui'

/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '1rem',
        sm: '1rem',
        lg: '2rem',
        xl: '4rem',
      },
    },
    extend: {
      /* Explicit responsive breakpoints (matching Tailwind defaults)
         Adding them here ensures clarity and makes it easy to adjust later. */
      screens: {
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1536px',
      },
      fontFamily: {
        sf: ['"SF Pro Display"', 'sans-serif'],
      },
    },
  },
  plugins: [daisyui],
  daisyui: {
    themes: ['light'], // Use light theme by default
    base: true,
    styled: true,
    utils: true,
  },
}
