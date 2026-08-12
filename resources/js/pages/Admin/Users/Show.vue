<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { user } = usePage().props

const roleLabels = {
  admin: 'Administrador',
  bibliotecaria: 'Bibliotecária',
  professor: 'Professor',
  aluno: 'Aluno',
}

const statusConfig = {
  true: {
    label: 'Ativo',
    color: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    buttonText: 'Inativar',
    buttonVariant: 'destructive',
  },
  false: {
    label: 'Inativo',
    color: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    buttonText: 'Ativar',
    buttonVariant: 'default',
  },
}

const toggleForm = useForm({})

const toggleActive = () => {
  toggleForm.post(`/admin/users/${user.id}/toggle-active`, {
    onSuccess: () => {
      // Força recarregar a página para atualizar os dados
      window.location.reload()
    }
  })
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6 max-w-2xl">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">{{ user.name }}</h1>
          <p class="text-gray-600 dark:text-gray-400">Visualizar detalhes do usuário</p>
        </div>
        <div class="space-x-2">
          <Link :href="route('admin.users.edit', user.id)" as="button" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
            Editar
          </Link>
          <Link :href="route('admin.users.index')" as="button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            Voltar
          </Link>
        </div>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Informações do Usuário</CardTitle>
      </CardHeader>
      <CardContent class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</label>
          <p class="text-lg">{{ user.name }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
          <p class="text-lg">{{ user.email }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Papel</label>
          <span class="inline-block px-3 py-1 mt-2 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded">
            {{ roleLabels[user.role] }}
          </span>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
          <span :class="['inline-block px-3 py-1 mt-2 rounded', statusConfig[user.active].color]">
            {{ statusConfig[user.active].label }}
          </span>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</label>
          <p class="text-lg">{{ new Date(user.created_at).toLocaleDateString('pt-BR') }}</p>
        </div>
      </CardContent>
    </Card>

    <div class="flex gap-2 mt-6">
      <button @click="toggleActive" class="px-4 py-2 rounded font-medium transition-colors" :class="user.active ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-blue-600 text-white hover:bg-blue-700'">
        {{ user.active ? 'Inativar' : 'Ativar' }}
      </button>
    </div>
    </div>
  </div>
</template>
