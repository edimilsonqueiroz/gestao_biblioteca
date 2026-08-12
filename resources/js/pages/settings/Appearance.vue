<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';

defineOptions({
    layout: [AppLayout, SettingsLayout],
});
</script>

<template>
    <Head title="Configurações de Aparência" />

    <h1 class="sr-only">Configurações de Aparência</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Configurações de Aparência"
            description="Atualize as configurações de aparência da sua conta"
        />
        <AppearanceTabs />
    </div>
</template>
