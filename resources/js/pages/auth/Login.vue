<script setup lang="ts">
import { Head, setLayoutProps } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { route } from '@/lib/route';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { request } from '@/routes/password';

defineOptions({
    layout: AuthSplitLayout,
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Set layout props dynamically
setLayoutProps({
    title: 'Bem-vindo',
    description: 'Faça login com suas credenciais',
});
</script>

<template>
    <Head title="Entrar" />

    <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-700 dark:bg-green-950 dark:text-green-200">
        {{ status }}
    </div>

    <form @submit.prevent="form.post(route('login'))" class="flex flex-col gap-6">
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="seu.email@exemplo.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Senha</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs text-blue-600 hover:text-blue-700"
                        :tabindex="5"
                    >
                        Esqueceu a senha?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Sua senha"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-2 cursor-pointer">
                    <Checkbox id="remember" v-model:checked="form.remember" name="remember" :tabindex="3" />
                    <span class="text-sm">Manter conectado</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white"
                :tabindex="4"
                :disabled="form.processing"
                data-test="login-button"
            >
                <Spinner v-if="form.processing" />
                {{ form.processing ? 'Entrando...' : 'Entrar' }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground border-t pt-6">
            <span class="block mb-4">Não possui uma conta?</span>
            <TextLink :href="register()" class="font-semibold text-blue-600 hover:text-blue-700" :tabindex="5">
                Criar conta
            </TextLink>
        </div>
    </form>
</template>
