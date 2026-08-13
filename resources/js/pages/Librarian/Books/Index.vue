<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { books } from '@/routes/librarian'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import AppLayout from '@/layouts/AppLayout.vue'
import DeleteBookConfirmation from '@/components/DeleteBookConfirmation.vue'
import { Edit2, Plus, Search, X } from '@lucide/vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'

defineOptions({
  layout: AppLayout,
})

const { books: initialBooks, search: initialSearch } = usePage().props
const booksList = ref([...initialBooks])
const searchQuery = ref(initialSearch || '')
const showImageModal = ref(false)
const selectedBook = ref(null)

const filteredBooks = computed(() => {
  if (!searchQuery.value) {
    return booksList.value
  }
  const query = searchQuery.value.toLowerCase()
  return booksList.value.filter(book =>
    book.title.toLowerCase().includes(query) ||
    book.author.toLowerCase().includes(query) ||
    book.isbn.toLowerCase().includes(query)
  )
})

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(books.index().url, { search: searchQuery.value })
  } else {
    router.get(books.index().url)
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  router.get(books.index().url)
}

const handleBookDeleted = (bookId) => {
  // Remove o livro deletado da lista local
  booksList.value = booksList.value.filter(b => b.id !== bookId)
}

const openImageModal = (book) => {
  selectedBook.value = book
  showImageModal.value = true
}

const closeImageModal = () => {
  showImageModal.value = false
  selectedBook.value = null
}
</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gerenciar Livros</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gerencie o acervo de livros da biblioteca</p>
      </div>
      <Link :href="books.create().url" as="button">
        <Button class="bg-blue-600 hover:bg-blue-700 text-white gap-2">
          <Plus class="w-4 h-4" />
          Novo Livro
        </Button>
      </Link>
    </div>

    <Card class="border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col gap-4">
          <div>
            <CardTitle class="text-gray-900 dark:text-white">Livros do Acervo</CardTitle>
            <CardDescription>{{ filteredBooks.length }} de {{ booksList.length }} livro(s)</CardDescription>
          </div>
          <div class="flex gap-2">
            <div class="flex-1 flex gap-2">
              <div class="relative flex-1">
                <Input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Buscar por título, autor ou ISBN..."
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
        <div v-if="filteredBooks.length === 0" class="p-6 text-center text-gray-500">
          {{ searchQuery ? 'Nenhum livro encontrado com essa busca' : 'Nenhum livro cadastrado' }}
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Capa</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Título</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Autor</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">ISBN</th>
                <th class="px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Disponíveis</th>
                <th class="px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Total</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="book in filteredBooks" :key="book.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                <td class="px-6 py-4">
                  <div v-if="book.cover" class="flex items-center justify-center cursor-pointer" @click="openImageModal(book)">
                    <img
                      :src="`/storage/${book.cover}`"
                      :alt="`Capa de ${book.title}`"
                      class="h-12 w-auto object-cover rounded border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-110 transition-all duration-200"
                    />
                  </div>
                  <div v-else class="flex items-center justify-center h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600">
                    <span class="text-gray-400 dark:text-gray-500 text-xs">Sem imagem</span>
                  </div>
                </td>
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
                    <Link :href="books.edit(book.id).url" as="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 dark:text-blue-400 transition-colors">
                      <Edit2 class="w-4 h-4" />
                      Editar
                    </Link>
                    <DeleteBookConfirmation 
                      :book="book"
                      :route="books.destroy(book.id).url"
                      @deleted="handleBookDeleted"
                    />
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>

    <!-- Image Preview Modal -->
    <Dialog :open="showImageModal" @update:open="closeImageModal">
      <DialogContent class="max-w-2xl">
        <DialogHeader>
          <DialogTitle v-if="selectedBook">{{ selectedBook.title }}</DialogTitle>
        </DialogHeader>
        <div v-if="selectedBook && selectedBook.cover" class="flex items-center justify-center py-6">
          <img
            :src="`/storage/${selectedBook.cover}`"
            :alt="`Capa de ${selectedBook.title}`"
            class="max-h-96 w-auto object-cover rounded-lg"
          />
        </div>
        <DialogFooter>
          <DialogClose as-child>
            <Button variant="outline" @click="closeImageModal">Fechar</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>
