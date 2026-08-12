<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'
import { Edit2, Plus, Trash2 } from '@lucide/vue'

defineOptions({
  layout: AppLayout,
})

const { books } = usePage().props
</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gerenciar Livros</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie o acervo de livros da biblioteca</p>
      </div>
      <Link :href="route('librarian.books.create')" as="button">
        <Button class="bg-blue-600 hover:bg-blue-700 text-white gap-2">
          <Plus class="w-4 h-4" />
          Novo Livro
        </Button>
      </Link>
    </div>

    <Card class="border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <div>
          <CardTitle class="text-gray-900 dark:text-white">Livros do Acervo</CardTitle>
          <CardDescription>{{ books.length }} livro(s) cadastrado(s)</CardDescription>
        </div>
      </CardHeader>
      <CardContent class="p-0">
        <div v-if="books.length === 0" class="p-6 text-center text-gray-500">
          Nenhum livro cadastrado
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Título</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Autor</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">ISBN</th>
                <th class="px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Disponíveis</th>
                <th class="px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Total</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="book in books" :key="book.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  {{ book.title }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ book.author }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-mono text-xs">
                  {{ book.isbn }}
                </td>
                <td class="px-6 py-4 text-center">
                  <span :class="book.quantity_available > 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                    {{ book.quantity_available }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
                  {{ book.quantity }}
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <Link :href="route('librarian.books.edit', book.id)" as="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 dark:text-blue-400 transition-colors">
                      <Edit2 class="w-4 h-4" />
                      Editar
                    </Link>
                    <Link 
                      :href="route('librarian.books.destroy', book.id)" 
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
