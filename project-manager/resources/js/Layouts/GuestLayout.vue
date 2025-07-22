<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[600px]"
            src="https://laravel.com/assets/img/welcome/background.svg"
            onerror="document.getElementById('background')?.classList.add('!hidden');"
        />
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="mt-6 w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg relative z-20"
        >
            <slot />
        </div>
    </div>
</template>
