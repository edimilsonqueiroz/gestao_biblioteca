<script setup>
import { usePage } from '@inertiajs/vue3'
import { Link, useForm, router } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import { Edit2, Plus, Trash2, Shield, BookOpen, Users, GraduationCap, Search, X } from '@lucide/vue'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

const { users: initialUsers, search: initialSearch } = usePage().props
const users = ref([...initialUsers])
const togglingId = ref(null)
const searchQuery = ref(initialSearch || '')

const roleConfig = {
  admin: {
    label: 'Administrador',
    icon: Shield,
    bg: 'bg-red-50 dark:bg-red-950/50',
    text: 'text-red-700 dark:text-red-200',
    border: 'border border-red-200 dark:border-red-800',
  },
  bibliotecaria: {
    label: 'Bibliotecária',
    icon: BookOpen,
    bg: 'bg-blue-50 dark:bg-blue-950/50',
    text: 'text-blue-700 dark:text-blue-200',
    border: 'border border-blue-200 dark:border-blue-800',
  },
  professor: {
    label: 'Professor',
    icon: Users,
    bg: 'bg-green-50 dark:bg-green-950/50',
    text: 'text-green-700 dark:text-green-200',
    border: 'border border-green-200 dark:border-green-800',
  },
  aluno: {
    label: 'Aluno',
    icon: GraduationCap,
    bg: 'bg-purple-50 dark:bg-purple-950/50',
    text: 'text-purple-700 dark:text-purple-200',
    border: 'border border-purple-200 dark:border-purple-800',
  },
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('admin.users.index'), { search: searchQuery.value })
  } else {
    router.get(route('admin.users.index'))
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  router.get(route('admin.users.index'))
}

const toggleUserActive = (user) => {
  togglingId.value = user.id
  const form = useForm({})
  form.post(`/admin/users/${user.id}/toggle-active`, {
    onSuccess: () => {
      // Atualiza o estado do usuário localmente
      const userIndex = users.value.findIndex(u => u.id === user.id)
      if (userIndex !== -1) {
        users.value[userIndex].active = !users.value[userIndex].active
      }
      togglingId.value = null
    },
    onError: () => {
      togglingId.value = null
    }
  })
}

</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gerenciar Usuários</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie todos os usuários do sistema</p>
      </div>
      <Link :href="route('admin.users.create')" as="button">
        <Button class="bg-blue-600 hover:bg-blue-700 text-white gap-2">
          <Plus class="w-4 h-4" />
          Novo Usuário
        </Button>
      </Link>
    </div>

    <Card class="border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col gap-4">
          <div>
            <CardTitle class="text-gray-900 dark:text-white">Usuários do Sistema</CardTitle>
            <CardDescription>{{ users.length }} usuário(s) cadastrado(s)</CardDescription>
          </div>
          <div class="flex gap-2">
            <div class="flex-1 flex gap-2">
              <div class="relative flex-1">
                <Input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Buscar por nome ou email..."
                  @keyup.enter="handleSearch"
                  class="pl-10"
                />
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
              </div>
              <Button
                @click="handleSearch"
                class="bg-blue-600 hover:bg-blue-700 text-white gap-2"
              >
                <Search class="w-4 h-4" />
                Buscar
              </Button>
              <Button
                v-if="searchQuery"
                @click="clearSearch"
                variant="outline"
                class="gap-2"
              >
                <X class="w-4 h-4" />
                Limpar
              </Button>
            </div>
          </div>
        </div>
      </CardHeader>
      <CardContent class="p-0">
        <div v-if="users.length === 0" class="p-6 text-center text-gray-500">
          Nenhum usuário cadastrado
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Nome</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Email</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Função</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="user in users" :key="user.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ user.email }}
                </td>
                <td class="px-6 py-4">
                  <div :class="[roleConfig[user.role].bg, roleConfig[user.role].border, roleConfig[user.role].text, 'px-3 py-1.5 rounded-full text-xs font-medium inline-flex items-center gap-2 w-fit']">
                    <component :is="roleConfig[user.role].icon" class="w-4 h-4" />
                    {{ roleConfig[user.role].label }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <button
                    @click="toggleUserActive(user)"
                    :disabled="togglingId === user.id || user.role === 'admin'"
                    class="relative inline-flex h-8 w-16 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                    :class="[
                      user.active 
                        ? 'bg-green-600 hover:bg-green-700' 
                        : 'bg-red-600 hover:bg-red-700',
                      togglingId === user.id || user.role === 'admin' ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer'
                    ]"
                    :title="user.role === 'admin' ? 'Administrador não pode ser desativado' : ''"
                  >
                    <span
                      class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform shadow-md"
                      :class="user.active ? 'translate-x-9' : 'translate-x-1'"
                    />
                    <span class="sr-only">{{ user.active ? 'Desativar' : 'Ativar' }}</span>
                  </button>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <Link :href="route('admin.users.edit', user.id)" as="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 dark:text-blue-400 transition-colors">
                      <Edit2 class="w-4 h-4" />
                      Editar
                    </Link>
                    <Link 
                      :href="route('admin.users.destroy', user.id)" 
                      method="DELETE"
                      as="button"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 dark:text-red-400 transition-colors"
                    >
                      <Trash2 class="w-4 h-4" />
                      Deletar
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>
  </div>
</template>
