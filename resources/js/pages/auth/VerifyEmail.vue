<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineOptions({
    layout: AuthSplitLayout,
});

defineProps<{
    status?: string;
}>();

// Set layout props dynamically
setLayoutProps({
    title: 'Verificar email',
    description: 'Por favor, verifique seu endereço de email clicando no link que acabamos de enviar para você.',
});
</script>

<template>
    <Head title="Verificar email" />

    <div v-if="status === 'verification-link-sent'" class="mb-4 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-700 dark:bg-green-950 dark:text-green-200">
        Um novo link de verificação foi enviado para o endereço de email que você forneceu durante o registro.
    </div>

    <Form
        v-bind="send.form()"
        class="flex flex-col gap-6"
        v-slot="{ processing }"
    >
        <Button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white"
            :disabled="processing"
        >
            <Spinner v-if="processing" />
            {{ processing ? 'Enviando...' : 'Reenviar email de verificação' }}
        </Button>
    </Form>

    <div class="border-t pt-6 text-center text-sm text-muted-foreground">
        <TextLink :href="logout()" as="button" class="font-semibold text-blue-600 hover:text-blue-700">
            Sair
        </TextLink>
    </div>
</template>
