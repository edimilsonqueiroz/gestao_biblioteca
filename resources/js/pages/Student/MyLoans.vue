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

const isOverdue = (loan) => {
  return new Date(loan.due_at) < new Date() && loan.status === 'active'
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">Meus Empréstimos</h1>
          <p class="text-gray-600 dark:text-gray-400">Acompanhe seus empréstimos de livros</p>
        </div>
        <Link :href="route('student.browse')" as="button">
          <Button>Buscar Livros</Button>
        </Link>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Livros Emprestados</CardTitle>
        <CardDescription>Lista de seus empréstimos</CardDescription>
      </CardHeader>
      <CardContent>
        <div v-if="loans.length === 0" class="text-center py-12">
          <p class="text-gray-600 dark:text-gray-400 text-lg">Você não tem nenhum empréstimo ativo</p>
          <Link :href="route('student.browse')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 mt-4 inline-block">
            Buscar livros agora
          </Link>
        </div>
        <div v-else class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Livro</TableHead>
                <TableHead>Autor</TableHead>
                <TableHead>Emprestado em</TableHead>
                <TableHead>Devolução prevista</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Ações</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="loan in loans" :key="loan.id">
                <TableCell class="font-medium">{{ loan.book.title }}</TableCell>
                <TableCell>{{ loan.book.author }}</TableCell>
                <TableCell>{{ new Date(loan.loaned_at).toLocaleDateString('pt-BR') }}</TableCell>
                <TableCell :class="isOverdue(loan) ? 'text-red-600 font-bold' : ''">
                  {{ new Date(loan.due_at).toLocaleDateString('pt-BR') }}
                  <span v-if="isOverdue(loan)" class="ml-2 text-sm">(Atrasado)</span>
                </TableCell>
                <TableCell>
                  <span :class="['px-3 py-1 rounded text-sm', statusColors[loan.status]]">
                    {{ statusLabels[loan.status] }}
                  </span>
                </TableCell>
                <TableCell>
                  <template v-if="loan.status === 'active'">
                    <Link 
                      :href="route('student.return-loan', loan.id)"
                      method="POST"
                      as="button"
                      class="text-green-600 hover:text-green-800 dark:text-green-400"
                    >
                      Devolver
                    </Link>
                  </template>
                  <template v-else>
                    <span class="text-gray-600 dark:text-gray-400">-</span>
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
