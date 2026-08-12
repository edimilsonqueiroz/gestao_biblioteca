<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { loan } = usePage().props

const statusLabels = {
  active: 'Ativo',
  returned: 'Devolvido',
  overdue: 'Atrasado',
}

const statusColors = {
  active: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
  returned: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
  overdue: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6 max-w-3xl">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">Empréstimo #{{ loan.id }}</h1>
          <p class="text-gray-600 dark:text-gray-400">Detalhes do empréstimo</p>
        </div>
        <Link :href="route('librarian.loans.index')" as="button">
          <Button variant="outline">Voltar</Button>
        </Link>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Usuário</CardTitle>
          </CardHeader>
          <CardContent class="space-y-2">
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</label>
              <p class="text-lg">{{ loan.user.name }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
              <p class="text-lg">{{ loan.user.email }}</p>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Livro</CardTitle>
          </CardHeader>
          <CardContent class="space-y-2">
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Título</label>
              <p class="text-lg">{{ loan.book.title }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Autor</label>
              <p class="text-lg">{{ loan.book.author }}</p>
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Informações do Empréstimo</CardTitle>
        </CardHeader>
        <CardContent class="grid grid-cols-2 gap-6">
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Emprestado em</label>
          <p class="text-lg">{{ new Date(loan.loaned_at).toLocaleDateString('pt-BR') }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Devolução prevista</label>
          <p class="text-lg">{{ new Date(loan.due_at).toLocaleDateString('pt-BR') }}</p>
        </div>
        <div v-if="loan.returned_at">
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Devolvido em</label>
          <p class="text-lg">{{ new Date(loan.returned_at).toLocaleDateString('pt-BR') }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
          <span :class="['inline-block px-3 py-1 rounded text-sm mt-2', statusColors[loan.status]]">
            {{ statusLabels[loan.status] }}
          </span>
        </div>
      </CardContent>
    </Card>

    <div v-if="loan.status === 'active'" class="space-x-2">
      <Link 
        :href="route('librarian.loans.markReturned', loan.id)"
        method="POST"
        as="button"
      >
        <Button>Marcar como devolvido</Button>
      </Link>
    </div>
    </div>
  </div>
</template>
