<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { book } = usePage().props
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6 max-w-2xl">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold">{{ book.title }}</h1>
          <p class="text-gray-600 dark:text-gray-400">Detalhes do livro</p>
        </div>
        <div class="space-x-2">
          <Link :href="route('librarian.books.edit', book.id)" as="button">
            <Button>Editar</Button>
          </Link>
          <Link :href="route('librarian.books.index')" as="button">
            <Button variant="outline">Voltar</Button>
          </Link>
        </div>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Detalhes do Livro</CardTitle>
      </CardHeader>
      <CardContent class="grid grid-cols-2 gap-6">
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</label>
          <p class="text-lg font-mono">{{ book.isbn }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</label>
          <p class="text-lg">{{ book.category || 'Não especificado' }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Quantidade Total</label>
          <p class="text-lg">{{ book.quantity }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Disponíveis</label>
          <p :class="book.quantity_available > 0 ? 'text-lg text-green-600 font-bold' : 'text-lg text-red-600 font-bold'">
            {{ book.quantity_available }}
          </p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Ano de Publicação</label>
          <p class="text-lg">{{ book.year || 'Não especificado' }}</p>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Editora</label>
          <p class="text-lg">{{ book.publisher || 'Não especificado' }}</p>
        </div>
      </CardContent>
    </Card>

    <Card v-if="book.description">
      <CardHeader>
        <CardTitle>Descrição</CardTitle>
      </CardHeader>
      <CardContent>
        <p class="text-gray-700 dark:text-gray-300">{{ book.description }}</p>
      </CardContent>
    </Card>
    </div>
  </div>
</template>
