<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';
import InputError from '@/components/InputError.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/password/confirm';

defineOptions({
    layout: AuthSplitLayout,
});

// Set layout props dynamically
setLayoutProps({
    title: 'Confirmar senha',
    description: 'Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.',
});
</script>

<template>
    <Head title="Confirmar senha" />

    <Form
        v-bind="store.form()"
        reset-on-success
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="flex flex-col gap-6">
            <div class="grid gap-2">
                <Label for="password">Senha</Label>
                <PasswordInput
                    id="password"
                    name="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="Sua senha"
                />

                <InputError :message="errors.password" />
            </div>

            <Button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white"
                :disabled="processing"
                data-test="confirm-password-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Confirmando...' : 'Confirmar senha' }}
            </Button>
        </div>
    </Form>
</template>
