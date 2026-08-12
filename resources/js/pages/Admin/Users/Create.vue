<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'bibliotecaria',
  grade: '',
  class: '',
})

const submit = () => {
  form.post(route('admin.users.store'), {
    onSuccess: () => {
      // Redirect handled by Laravel
    },
  })
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6 max-w-2xl">
      <div>
        <h1 class="text-3xl font-bold">Criar Novo Usuário</h1>
        <p class="text-gray-600 dark:text-gray-400">Adicione um novo usuário ao sistema</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Informações do Usuário</CardTitle>
          <CardDescription>Preencha os dados do novo usuário</CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
          <div class="space-y-2">
            <Label for="name">Nome Completo</Label>
            <Input
              id="name"
              v-model="form.name"
              type="text"
              placeholder="João Silva"
              required
            />
            <InputError v-if="form.errors.name" :message="form.errors.name" />
          </div>

          <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="joao@example.com"
              required
            />
            <InputError v-if="form.errors.email" :message="form.errors.email" />
          </div>

          <div class="space-y-2">
            <Label for="password">Senha</Label>
            <Input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="Sua senha"
              required
            />
            <InputError v-if="form.errors.password" :message="form.errors.password" />
          </div>

          <div class="space-y-2">
            <Label for="password_confirmation">Confirmar Senha</Label>
            <Input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              placeholder="Confirme sua senha"
              required
            />
          </div>

          <div class="space-y-2">
            <Label for="role">Papel</Label>
            <select
              id="role"
              v-model="form.role"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              required
            >
              <option value="bibliotecaria">Bibliotecária</option>
              <option value="professor">Professor</option>
              <option value="aluno">Aluno</option>
            </select>
            <InputError v-if="form.errors.role" :message="form.errors.role" />
          </div>

          <!-- Campos condicionais para Aluno -->
          <div v-if="form.role === 'aluno'" class="space-y-2">
            <Label for="grade">Série</Label>
            <select
              id="grade"
              v-model="form.grade"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              required
            >
              <option value="">Selecione a série</option>
              <option value="1">1ª série</option>
              <option value="2">2ª série</option>
              <option value="3">3ª série</option>
            </select>
            <InputError v-if="form.errors.grade" :message="form.errors.grade" />
          </div>

          <div v-if="form.role === 'aluno'" class="space-y-2">
            <Label for="class">Turma</Label>
            <Input
              id="class"
              v-model="form.class"
              type="text"
              placeholder="Ex: A, B, C..."
            />
            <InputError v-if="form.errors.class" :message="form.errors.class" />
          </div>

          <div class="flex gap-4">
            <Button type="submit" :disabled="form.processing">
              Criar Usuário
            </Button>
            <Link :href="route('admin.users.index')" as="button">
              <Button variant="outline">Cancelar</Button>
            </Link>
          </div>
        </form>
      </CardContent>
    </Card>
    </div>
  </div>
</template>
