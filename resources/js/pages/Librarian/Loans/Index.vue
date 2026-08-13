<script setup>
import { usePage, Link, router } from '@inertiajs/vue3'
import { loans } from '@/routes/librarian'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import { Eye, CheckCircle, Clock, AlertCircle, Search, X, Plus } from '@lucide/vue'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

const { loans: initialLoans, search: initialSearch } = usePage().props
const loansList = ref([...initialLoans])
const searchQuery = ref(initialSearch || '')

const statusConfig = {
  active: {
    label: 'Ativo',
    icon: Clock,
    bg: 'bg-blue-50 dark:bg-blue-950/50',
    text: 'text-blue-700 dark:text-blue-200',
    border: 'border border-blue-200 dark:border-blue-800',
  },
  returned: {
    label: 'Devolvido',
    icon: CheckCircle,
    bg: 'bg-green-50 dark:bg-green-950/50',
    text: 'text-green-700 dark:text-green-200',
    border: 'border border-green-200 dark:border-green-800',
  },
  overdue: {
    label: 'Atrasado',
    icon: AlertCircle,
    bg: 'bg-red-50 dark:bg-red-950/50',
    text: 'text-red-700 dark:text-red-200',
    border: 'border border-red-200 dark:border-red-800',
  },
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(loans.index().url, { search: searchQuery.value })
  } else {
    router.get(loans.index().url)
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  router.get(loans.index().url)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
  })
}

const isOverdue = (dueAt, returnedAt, status) => {
  if (returnedAt || status === 'returned') return false
  return new Date(dueAt) < new Date()
}
</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gerenciar Empréstimos</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie todos os empréstimos de livros</p>
      </div>
      <Link :href="loans.create().url" as="button">
        <Button class="bg-blue-600 hover:bg-blue-700 text-white gap-2">
          <Plus class="w-4 h-4" />
          Novo Empréstimo
        </Button>
      </Link>
    </div>

    <Card class="border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col gap-4">
          <div>
            <CardTitle class="text-gray-900 dark:text-white">Empréstimos</CardTitle>
            <CardDescription>{{ loansList.length }} empréstimo(s) registrado(s)</CardDescription>
          </div>
          <div class="flex gap-2">
            <div class="flex-1 flex gap-2">
              <div class="relative flex-1">
                <Input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Buscar por usuário, livro..."
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
        <div v-if="loansList.length === 0" class="p-6 text-center text-gray-500">
          Nenhum empréstimo registrado
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Usuário</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Livro</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Emprestado em</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Devolução</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Devolvido em</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="loan in loansList" :key="loan.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  {{ loan.user.name }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ loan.book.title }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-xs">
                  {{ formatDate(loan.loaned_at) }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-xs" :class="isOverdue(loan.due_at, loan.returned_at, loan.status) ? 'text-red-600 dark:text-red-400 font-semibold' : ''">
                  {{ formatDate(loan.due_at) }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-xs">
                  {{ loan.returned_at ? formatDate(loan.returned_at) : '-' }}
                </td>
                <td class="px-6 py-4">
                  <div :class="[statusConfig[loan.status].bg, statusConfig[loan.status].border, statusConfig[loan.status].text, 'px-3 py-1.5 rounded-full text-xs font-medium inline-flex items-center gap-2 w-fit']">
                    <component :is="statusConfig[loan.status].icon" class="w-4 h-4" />
                    {{ statusConfig[loan.status].label }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <Link :href="loans.show(loan.id).url" as="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 dark:text-blue-400 transition-colors">
                      <Eye class="w-4 h-4" />
                      Ver
                    </Link>
                    <Link 
                      v-if="loan.status === 'active'"
                      :href="loans.markReturned(loan.id).url" 
                      method="POST"
                      as="button"
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-950/50 dark:text-green-400 transition-colors"
                    >
                      <CheckCircle class="w-4 h-4" />
                      Devolvido
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
