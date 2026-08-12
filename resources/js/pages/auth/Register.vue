<script setup lang="ts">
import { Form, Head, setLayoutProps } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

interface Props {
    passwordRules: string;
    hasAdmin: boolean;
}

const props = defineProps<Props>();
const selectedRole = ref(!props.hasAdmin ? 'admin' : 'aluno');

defineOptions({
    layout: AuthSplitLayout,
});

// Set layout props dynamically
setLayoutProps({
    title: 'Criar conta',
    description: 'Insira seus dados abaixo para criar sua conta',
});
</script>

<template>
    <Head title="Registrar" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid grid-cols-2 gap-6">
            <div class="grid gap-2 col-span-2">
                <Label for="name">Nome completo</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Seu nome completo"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="seu.email@exemplo.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="role">Perfil</Label>
                <select
                    id="role"
                    v-model="selectedRole"
                    name="role"
                    required
                    :tabindex="3"
                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus:border-blue-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <option v-if="!hasAdmin" value="admin">Administrador</option>
                    <option value="aluno">Aluno</option>
                    <option value="professor">Professor</option>
                </select>
                <InputError :message="errors.role" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Senha</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Sua senha"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirme a senha</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="5"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirme sua senha"
                    :passwordrules="passwordRules"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white col-span-2"
                :tabindex="6"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                {{ processing ? 'Criando conta...' : 'Criar conta' }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground border-t pt-6">
            <span class="block mb-4">Já possui uma conta?</span>
            <TextLink
                :href="login()"
                class="font-semibold text-blue-600 hover:text-blue-700"
                :tabindex="7"
            >
                Fazer login
            </TextLink>
        </div>
    </Form>
</template>
