<template>
    <div class="min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-500">
        <section class="mb-12">
            <h2 class="text-2xl font-extrabold mb-8 text-indigo-700 dark:text-indigo-400 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Today's Metrics
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    class="p-6 rounded-2xl shadow-xl bg-linear-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-900 border border-blue-200 dark:border-blue-700 text-blue-900 dark:text-blue-200 card-hover-effect">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider opacity-80">Articles Published
                            </h3>
                            <p class="text-4xl font-extrabold mt-2">42</p>
                        </div>
                        <div class="p-3 rounded-xl bg-blue-200 dark:bg-blue-700 text-blue-600 dark:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs mt-4 flex items-center">
                        <span class="text-green-600 dark:text-green-400 flex items-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 transform rotate-180"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            12% increase
                        </span>
                        <span class="ml-2 text-gray-500 dark:text-gray-400">since yesterday</span>
                    </p>
                </div>

                <div
                    class="p-6 rounded-2xl shadow-xl bg-linear-to-br from-green-50 to-green-100 dark:from-gray-800 dark:to-gray-900 border border-green-200 dark:border-green-700 text-green-900 dark:text-green-200 card-hover-effect">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider opacity-80">Active Subscribers
                            </h3>
                            <p class="text-4xl font-extrabold mt-2">12,583</p>
                        </div>
                        <div class="p-3 rounded-xl bg-green-200 dark:bg-green-700 text-green-600 dark:text-green-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs mt-4 flex items-center">
                        <span class="text-green-600 dark:text-green-400 flex items-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 transform rotate-180"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            5.2% increase
                        </span>
                        <span class="ml-2 text-gray-500 dark:text-gray-400">this month</span>
                    </p>
                </div>

                <div
                    class="p-6 rounded-2xl shadow-xl bg-linear-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-900 border border-purple-200 dark:border-purple-700 text-purple-900 dark:text-purple-200 card-hover-effect">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider opacity-80">Page Views</h3>
                            <p class="text-4xl font-extrabold mt-2">245,871</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-purple-200 dark:bg-purple-700 text-purple-600 dark:text-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs mt-4 flex items-center">
                        <span class="text-green-600 dark:text-green-400 flex items-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 transform rotate-180"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            22% increase
                        </span>
                        <span class="ml-2 text-gray-500 dark:text-gray-400">since yesterday</span>
                    </p>
                </div>

                <div
                    class="p-6 rounded-2xl shadow-xl bg-linear-to-br from-red-50 to-red-100 dark:from-gray-800 dark:to-gray-900 border border-red-200 dark:border-red-700 text-red-900 dark:text-red-200 card-hover-effect">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-wider opacity-80">Pending Articles</h3>
                            <p class="text-4xl font-extrabold mt-2">8</p>
                        </div>
                        <div class="p-3 rounded-xl bg-red-200 dark:bg-red-700 text-red-600 dark:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs mt-4 flex items-center">
                        <span class="text-red-600 dark:text-red-400 flex items-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 transform rotate-180"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            3 need immediate review
                        </span>
                    </p>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
definePageMeta({
    middleware: 'auth'
});
import { useLocale } from '~/composables/useLocale';

const { locale, t } = useLocale();
</script>

<style scoped>
.card-hover-effect {
    position: relative;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, box-shadow;
}

.card-hover-effect:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 6px 6px rgba(0, 0, 0, 0.1);
}
</style>