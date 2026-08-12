<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { loans } = usePage().props

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
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold">Gerenciar Empréstimos</h1>
        <p class="text-gray-600 dark:text-gray-400">Gerencie todos os empréstimos de livros</p>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Empréstimos</CardTitle>
        <CardDescription>Lista de todos os empréstimos registrados</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Usuário</TableHead>
                <TableHead>Livro</TableHead>
                <TableHead>Emprestado em</TableHead>
                <TableHead>Devolução prevista</TableHead>
                <TableHead>Devolvido em</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Ações</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="loan in loans" :key="loan.id">
                <TableCell class="font-medium">{{ loan.user.name }}</TableCell>
                <TableCell>{{ loan.book.title }}</TableCell>
                <TableCell>{{ new Date(loan.loaned_at).toLocaleDateString('pt-BR') }}</TableCell>
                <TableCell>{{ new Date(loan.due_at).toLocaleDateString('pt-BR') }}</TableCell>
                <TableCell>
                  {{ loan.returned_at ? new Date(loan.returned_at).toLocaleDateString('pt-BR') : '-' }}
                </TableCell>
                <TableCell>
                  <span :class="['px-3 py-1 rounded text-sm', statusColors[loan.status]]">
                    {{ statusLabels[loan.status] }}
                  </span>
                </TableCell>
                <TableCell class="space-x-2">
                  <Link :href="route('librarian.loans.show', loan.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    Ver
                  </Link>
                  <template v-if="loan.status === 'active'">
                    <Link 
                      :href="route('librarian.loans.markReturned', loan.id)"
                      method="POST"
                      as="button"
                      class="text-green-600 hover:text-green-800 dark:text-green-400"
                    >
                      Marcar como devolvido
                    </Link>
                  </template>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </CardContent>
    </Card>
    </div>
  </div>
</template>
