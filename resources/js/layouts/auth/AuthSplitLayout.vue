<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpenIcon } from '@heroicons/vue/24/solid';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh grid-cols-1 lg:grid-cols-2 overflow-hidden"
    >
        <!-- Left Side: System Information -->
        <div
            class="relative hidden flex-col bg-linear-to-br from-blue-600 to-blue-800 p-8 text-white lg:flex dark:from-blue-900 dark:to-blue-950 overflow-hidden gap-8"
        >
            <!-- Background Decoration -->
            <div class="absolute inset-0 overflow-hidden opacity-10">
                <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full bg-white blur-3xl" />
                <div class="absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-white blur-3xl" />
            </div>

            <!-- Book Icon -->
            <div class="relative z-10 flex flex-1 items-center justify-center">
                <BookOpenIcon class="h-48 w-48 opacity-80 text-white" />
            </div>
        </div>

        <!-- Right Side: Form Content -->
        <div class="flex flex-col items-center justify-center px-8 sm:px-0 lg:px-8 overflow-hidden">
            <div
                class="mx-auto flex w-full sm:w-105 flex-col justify-center space-y-6"
            >
                <div class="flex flex-col space-y-2">
                    <h1 class="text-3xl font-bold tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-base text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
