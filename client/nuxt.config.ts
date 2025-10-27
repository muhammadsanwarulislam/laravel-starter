import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['@/assets/css/main.css'],
  ssr: false,
  vite: {
    plugins: [
      tailwindcss()
    ]
  },
  modules: [],
  runtimeConfig: {
    public: {
      appMode: process.env.NUXT_PUBLIC_APP_MODE,
      apiURL: process.env.NUXT_PUBLIC_API_URL,
      frontendURL: process.env.FRONTEND_URL,
    }
  },
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Admin Panel',
      meta: [
        { name: 'description', content: 'Admin Panel' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
      ],
      script: [
        {
          innerHTML: `
            try {
              const theme = localStorage.getItem('themeMode');
              if (theme === 'dark') {
                document.documentElement.classList.add('dark');
              }
            } catch (e) {}
          `,
          tagPosition: 'head',
          type: 'text/javascript',
          id: 'inline-darkmode'
        }
      ],
    }
  }
})
