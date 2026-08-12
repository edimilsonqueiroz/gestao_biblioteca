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

const { books } = usePage().props
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">Gerenciar Livros</h1>
          <p class="text-gray-600 dark:text-gray-400">Gerencie o acervo de livros da biblioteca</p>
        </div>
        <Link :href="route('librarian.books.create')" as="button">
          <Button>Novo Livro</Button>
        </Link>
      </div>

    <Card>
      <CardHeader>
        <CardTitle>Livros do Acervo</CardTitle>
        <CardDescription>Lista de todos os livros cadastrados</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Título</TableHead>
                <TableHead>Autor</TableHead>
                <TableHead>ISBN</TableHead>
                <TableHead>Disponíveis</TableHead>
                <TableHead>Total</TableHead>
                <TableHead>Ações</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="book in books" :key="book.id">
                <TableCell class="font-medium">{{ book.title }}</TableCell>
                <TableCell>{{ book.author }}</TableCell>
                <TableCell>{{ book.isbn }}</TableCell>
                <TableCell>
                  <span :class="book.quantity_available > 0 ? 'text-green-600' : 'text-red-600'">
                    {{ book.quantity_available }}
                  </span>
                </TableCell>
                <TableCell>{{ book.quantity }}</TableCell>
                <TableCell class="space-x-2">
                  <Link :href="route('librarian.books.edit', book.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                    Editar
                  </Link>
                  <Link 
                    :href="route('librarian.books.destroy', book.id)" 
                    method="DELETE"
                    as="button"
                    class="text-red-600 hover:text-red-800 dark:text-red-400"
                  >
                    Deletar
                  </Link>
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
