<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: AuthSplitLayout,
});

defineProps<{
    status?: string;
}>();

// Set layout props dynamically
setLayoutProps({
    title: 'Recuperar senha',
    description: 'Insira seu email para receber um link de redefinição de senha',
});
</script>

<template>
    <Head title="Recuperar senha" />

    <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-700 dark:bg-green-950 dark:text-green-200">
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form v-bind="email.form()" v-slot="{ errors, processing }" class="flex flex-col gap-6">
            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="seu.email@exemplo.com"
                />
                <InputError :message="errors.email" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white"
                :disabled="processing"
                data-test="email-password-reset-link-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Enviando...' : 'Enviar link de redefinição' }}
            </Button>
        </Form>

        <div class="border-t pt-6 text-center text-sm text-muted-foreground">
            <span class="block mb-4">Lembrou da senha?</span>
            <TextLink :href="login()" class="font-semibold text-blue-600 hover:text-blue-700">
                Voltar ao login
            </TextLink>
        </div>
    </div>
</template>
