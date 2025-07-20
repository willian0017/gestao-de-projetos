<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { FolderOpenIcon } from '@heroicons/vue/24/solid';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-6 text-gray-900 dark:text-gray-100 flex flex-col items-center justify-center text-center"
                >
                    <FolderOpenIcon class="w-24 h-24 text-indigo-500 dark:text-indigo-400 mb-4" />
                    <h3 class="text-2xl font-bold mb-2">Bem-vindo(a) à sua Dashboard!</h3>
                    <p class="text-lg mb-6">Comece a gerenciar seus projetos agora mesmo.</p>
                    <Link
                        :href="route('projects.index')"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    >
                        Ver Meus Projetos
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>