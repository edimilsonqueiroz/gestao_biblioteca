<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { reservations } = usePage().props

const statusLabels = {
  pending: 'Pendente',
  confirmed: 'Confirmada',
  cancelled: 'Cancelada',
}

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
  confirmed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
  cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold">Gerenciar Reservas</h1>
        <p class="text-gray-600 dark:text-gray-400">Gerencie todas as reservas de livros</p>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Reservas</CardTitle>
        <CardDescription>Lista de todas as reservas registradas</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Usuário</TableHead>
                <TableHead>Livro</TableHead>
                <TableHead>Data da reserva</TableHead>
                <TableHead>Status</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="reservation in reservations.data" :key="reservation.id">
                <TableCell class="font-medium">{{ reservation.user.name }}</TableCell>
                <TableCell>{{ reservation.book.title }}</TableCell>
                <TableCell>{{ new Date(reservation.reserved_at).toLocaleDateString('pt-BR') }}</TableCell>
                <TableCell>
                  <span :class="['px-3 py-1 rounded text-sm', statusColors[reservation.status]]">
                    {{ statusLabels[reservation.status] }}
                  </span>
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
