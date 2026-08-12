<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: AuthSplitLayout,
});
</script>

<template>
    <Head title="Conta Criada" />

    <div class="flex flex-col gap-6">
        <div class="space-y-4">
            <div class="flex flex-col gap-2">
                <h2 class="text-2xl font-bold text-green-600">
                    Conta criada com sucesso!
                </h2>
                <p class="text-base text-muted-foreground">
                    Sua conta foi criada e agora está aguardando aprovação do administrador.
                </p>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 space-y-2">
                <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                    O que fazer agora?
                </p>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
                    <li>Você receberá um email quando sua conta for aprovada</li>
                    <li>Após a aprovação, você poderá fazer login com suas credenciais</li>
                    <li>Se tiver dúvidas, entre em contato com o administrador</li>
                </ul>
            </div>
        </div>

        <Link href="/login" class="w-full">
            <Button class="w-full bg-blue-600 hover:bg-blue-700 text-white">
                Voltar para o login
            </Button>
        </Link>
    </div>
</template>
