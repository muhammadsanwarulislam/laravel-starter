export const useLandingTranslations = () => {
  const { translate } = useLocalization()
  
  const t = (key) => {

    const translations = {
      // Hero Section
      'hero.tagline': '🚀 Modern Full-Stack Starter Kit',
      'hero.title.part1': 'Build with',
      'hero.title.part2': 'Nuxt.js & Laravel',
      'hero.description': 'A production-ready starter kit combining the power of Nuxt.js frontend with Laravel API backend. Explore modern features, best practices, and clean architecture patterns in a cohesive project.',
      'hero.get_started': 'Get Started',
      'hero.view_demo': 'View Demo',
      'hero.free_open_source': 'Free & Open Source',
      'hero.production_ready': 'Production Ready',
      
      // Features Section
      'features.tagline': '✨ Key Features',
      'features.title.part1': 'Everything You Need to',
      'features.title.part2': 'Build Modern Apps',
      'features.description': 'Our starter kit includes all the essential features to jumpstart your development process with best practices and clean architecture.',
      
      // Feature Items
      'feature.auth.title': 'Secure Authentication',
      'feature.auth.description': 'Complete user authentication system with registration, login, and password management using Laravel Sanctum.',
      'feature.profile.title': 'Profile Management',
      'feature.profile.description': 'Comprehensive user profile management with CRUD operations and customizable fields.',
      'feature.i18n.title': 'Multi-language Support',
      'feature.i18n.description': 'Built-in internationalization (i18n) support to serve your app in multiple languages.',
      'feature.architecture.title': 'Clean Architecture',
      'feature.architecture.description': 'Repository-Service pattern implementation for maintainable and scalable code structure.',
      'feature.best_practices.title': 'Best Practices',
      'feature.best_practices.description': 'Implements modern Laravel API best practices including resources, events, and proper error handling.',
      'feature.seeding.title': 'Database Seeding',
      'feature.seeding.description': 'Pre-configured seeders for dynamic content to quickly populate your application with test data.',
      
      // Tech Stack Section
      'tech.tagline': '💻 Modern Tech Stack',
      'tech.title.part1': 'Built with',
      'tech.title.part2': 'Latest Technologies',
      'tech.description': 'Our starter kit leverages the latest versions of industry-leading frameworks to ensure your project is modern, scalable, and maintainable.',
      'tech.laravel.title': 'Laravel API',
      'tech.laravel.subtitle': 'Backend Framework',
      'tech.laravel.feature1': 'RESTful API development',
      'tech.laravel.feature2': 'Repository-Service pattern',
      'tech.laravel.feature3': 'Event-driven architecture',
      'tech.laravel.feature4': 'API resource transformation',
      'tech.nuxt.title': 'Nuxt.js',
      'tech.nuxt.subtitle': 'Frontend Framework',
      'tech.nuxt.feature1': 'Vue 3 Composition API',
      'tech.nuxt.feature2': 'Server-side rendering (SSR)',
      'tech.nuxt.feature3': 'Internationalization (i18n)',
      'tech.nuxt.feature4': 'Component-based architecture',
      
      // Navigation
      'nav.home': 'Home',
      'nav.features': 'Features',
      'nav.tech_stack': 'Tech Stack',
      'nav.documentation': 'Documentation',
      'nav.sign_in': 'Sign In',
      'nav.get_started': 'Get Started',
      
      // Language Selector
      'language.select': 'Select Language',
      'language.current': 'Current Language',
    }
    return translations[key]
  }
  
  return { t }
}