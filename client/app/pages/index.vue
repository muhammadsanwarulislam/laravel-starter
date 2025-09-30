<template>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <!-- Background decoration -->
            <div
                class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-gray-800 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]">
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 lg:py-32">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <!-- Content -->
                    <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-12">
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-sm font-medium mb-6">
                            🚀 Trusted by 10,000+ businesses worldwide
                        </div>

                        <h1
                            class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
                            Supercharge Your
                            <span
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Business</span>
                            Operations
                        </h1>

                        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                            Streamline your workflow, analyze data in real-time, and make informed decisions with our
                            comprehensive admin dashboard.
                            Everything you need in one powerful platform.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            <button @click="$emit('openSignUp')"
                                class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                                <span class="relative z-10 flex items-center justify-center">
                                    Start Free Trial
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </button>

                            <button @click="$emit('openSignIn')"
                                class="group px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:border-indigo-300 dark:hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
                                <span class="flex items-center justify-center">
                                    Sign In
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <div class="flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span>No credit card required</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span>14-day free trial</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Preview -->
                    <div class="lg:w-1/2 relative">
                        <div class="relative max-w-lg mx-auto">
                            <!-- Floating elements -->
                            <div
                                class="absolute -top-4 -left-4 w-24 h-24 bg-purple-200 dark:bg-purple-900 rounded-full blur-xl opacity-50">
                            </div>
                            <div
                                class="absolute -bottom-4 -right-4 w-32 h-32 bg-indigo-200 dark:bg-indigo-900 rounded-full blur-xl opacity-50">
                            </div>

                            <!-- Main dashboard card -->
                            <div
                                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                                <!-- Dashboard header -->
                                <div
                                    class="bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex space-x-1">
                                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                        </div>
                                        <span class="text-white font-medium text-sm">Analytics Dashboard</span>
                                    </div>
                                    <div class="text-gray-400 text-xs">Live</div>
                                </div>

                                <!-- Dashboard content -->
                                <div class="p-6">
                                    <!-- Stats grid -->
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div
                                            class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-4">
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">24.5K</div>
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Total
                                                Users</div>
                                        </div>
                                        <div
                                            class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4">
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">$42.5K</div>
                                            <div class="text-xs text-green-600 dark:text-green-400 font-medium">Revenue
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart area -->
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <span
                                                class="text-sm font-medium text-gray-700 dark:text-gray-300">Performance</span>
                                            <span
                                                class="text-xs text-green-500 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">+12.5%</span>
                                        </div>
                                        <div
                                            class="h-20 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-lg opacity-80">
                                        </div>
                                    </div>

                                    <!-- Recent activity -->
                                    <div class="space-y-3">
                                        <div v-for="n in 3" :key="n" class="flex items-center space-x-3 animate-pulse">
                                            <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full"></div>
                                            <div class="flex-1">
                                                <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-3/4"></div>
                                                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="relative py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-sm font-medium mb-4">
                        ✨ Powerful Features
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Everything You Need to
                        <span
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Succeed</span>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Designed to help you work smarter, not harder. Our platform provides all the tools you need to
                        grow your business.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(feature, index) in features" :key="index"
                        class="group relative bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-200 dark:hover:border-indigo-500 transition-all duration-300 hover:shadow-xl">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <component :is="feature.icon" class="w-6 h-6 text-white" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ feature.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ feature.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="relative py-20 lg:py-28 bg-white/50 dark:bg-gray-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 text-sm font-medium mb-4">
                        💫 Loved by Thousands
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Trusted by
                        <span
                            class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Amazing
                            Teams</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(testimonial, index) in testimonials" :key="index"
                        class="group bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img :src="testimonial.avatar" :alt="testimonial.name"
                                class="w-12 h-12 rounded-full ring-4 ring-indigo-100 dark:ring-indigo-900/50">
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ testimonial.name }}</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ testimonial.position }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 italic">"{{ testimonial.quote }}"</p>
                        <div class="mt-4 flex text-yellow-400">
                            <span v-for="n in 5" :key="n">★</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative py-20 lg:py-28">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-12 lg:p-16 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                            Ready to Transform Your Business?
                        </h2>
                        <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto">
                            Join thousands of successful businesses already using our platform. Start your free trial
                            today.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button @click="$emit('openSignUp')"
                                class="px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 transform hover:-translate-y-1 shadow-lg">
                                Start Free Trial
                            </button>
                            <button @click="$emit('openSignIn')"
                                class="px-8 py-4 bg-transparent text-white border-2 border-white font-semibold rounded-xl hover:bg-white hover:text-indigo-600 transition-all duration-200">
                                Schedule Demo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
definePageMeta({ middleware: ["guest"], layout: "guest" });

defineEmits(['openSignIn', 'openSignUp']);

// Features data
const features = [
    {
        icon: 'ChartBarIcon',
        title: 'Advanced Analytics',
        description: 'Get real-time insights with customizable dashboards and detailed reports.'
    },
    {
        icon: 'ShieldCheckIcon',
        title: 'Enterprise Security',
        description: 'Bank-level security with encryption, 2FA, and compliance features.'
    },
    {
        icon: 'UsersIcon',
        title: 'Team Collaboration',
        description: 'Work seamlessly with role-based access and real-time updates.'
    },
    {
        icon: 'LightningBoltIcon',
        title: 'Lightning Fast',
        description: 'Optimized for speed with 99.9% uptime and instant data processing.'
    },
    {
        icon: 'CogIcon',
        title: 'Customizable',
        description: 'Tailor the platform to your specific business needs and workflows.'
    },
    {
        icon: 'GlobeIcon',
        title: 'Global Scale',
        description: 'Support for multiple languages, currencies, and time zones.'
    }
];

// Testimonials data
const testimonials = [
    {
        name: 'Sarah Johnson',
        position: 'CEO, TechStart Inc.',
        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=64&h=64&fit=crop&crop=face',
        quote: 'This platform transformed how we manage our business. The analytics are incredibly insightful!'
    },
    {
        name: 'Michael Chen',
        position: 'CTO, InnovateCo',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop&crop=face',
        quote: 'The security features give us peace of mind. Our data has never been safer or more accessible.'
    },
    {
        name: 'Emma Rodriguez',
        position: 'Marketing Director',
        avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=64&h=64&fit=crop&crop=face',
        quote: 'Our team collaboration has improved dramatically since we started using this platform.'
    }
];

// Icon components
const ChartBarIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`
};

const ShieldCheckIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`
};

const UsersIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>`
};

const LightningBoltIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`
};

const CogIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
};

const GlobeIcon = {
    template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9a9 9 0 01-9-9m9 9c0 5-4 9-9 9s-9-4-9-9m9-9a9 9 0 00-9 9"/></svg>`
};
</script>

<style scoped>
.bg-grid-slate-100 {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(241 245 249 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}

.bg-grid-gray-800 {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(31 41 55 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}

.dark .bg-grid-slate-100 {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(31 41 55 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}
</style>