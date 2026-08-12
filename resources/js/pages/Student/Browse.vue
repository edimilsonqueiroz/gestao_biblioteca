<script setup>
import { ref } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { books, categories } = usePage().props
const search = ref('')
const selectedCategory = ref('')

const filteredBooks = ref(books)

const filterBooks = () => {
  let filtered = books
  
  if (search.value) {
    filtered = filtered.filter(book => 
      book.title.toLowerCase().includes(search.value.toLowerCase()) ||
      book.author.toLowerCase().includes(search.value.toLowerCase())
    )
  }
  
  if (selectedCategory.value) {
    filtered = filtered.filter(book => book.category === selectedCategory.value)
  }
  
  filteredBooks.value = filtered
}

const requestLoan = (bookId) => {
  const form = document.createElement('form')
  form.method = 'POST'
  form.action = route('student.request-loan')
  
  const input = document.createElement('input')
  input.type = 'hidden'
  input.name = 'book_id'
  input.value = bookId
  
  const token = document.querySelector('meta[name="csrf-token"]')?.content
  const tokenInput = document.createElement('input')
  tokenInput.type = 'hidden'
  tokenInput.name = '_token'
  tokenInput.value = token
  
  form.appendChild(input)
  form.appendChild(tokenInput)
  document.body.appendChild(form)
  form.submit()
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl font-bold">Buscar Livros</h1>
        <p class="text-gray-600 dark:text-gray-400">Busque e solicite livros emprestados</p>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Filtros de Busca</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="search">Buscar por título ou autor</Label>
            <Input
              id="search"
              v-model="search"
              type="text"
              placeholder="Digite o título ou autor..."
              @input="filterBooks"
            />
          </div>
          <div class="space-y-2">
            <Label for="category">Categoria</Label>
            <select
              id="category"
              v-model="selectedCategory"
              @change="filterBooks"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="">Todas as categorias</option>
              <option v-for="cat in categories" :key="cat" :value="cat">
                {{ cat }}
              </option>
            </select>
          </div>
        </div>
      </CardContent>
    </Card>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <Card v-for="book in filteredBooks" :key="book.id">
        <CardHeader>
          <CardTitle class="text-lg">{{ book.title }}</CardTitle>
          <CardDescription>{{ book.author }}</CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="text-sm space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-600 dark:text-gray-400">ISBN:</span>
              <span class="font-mono">{{ book.isbn }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600 dark:text-gray-400">Categoria:</span>
              <span>{{ book.category || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600 dark:text-gray-400">Disponíveis:</span>
              <span :class="book.quantity_available > 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold'">
                {{ book.quantity_available }}/{{ book.quantity }}
              </span>
            </div>
            <div v-if="book.year" class="flex justify-between">
              <span class="text-gray-600 dark:text-gray-400">Ano:</span>
              <span>{{ book.year }}</span>
            </div>
          </div>

          <Button 
            v-if="book.quantity_available > 0"
            @click="requestLoan(book.id)"
            class="w-full"
          >
            Solicitar Empréstimo
          </Button>
          <Button v-else disabled class="w-full">
            Não disponível
          </Button>

          <Link :href="route('librarian.books.show', book.id)" as="button" class="w-full">
            <Button variant="outline" class="w-full">Ver Detalhes</Button>
          </Link>
        </CardContent>
      </Card>
    </div>

    <div v-if="filteredBooks.length === 0" class="text-center py-12">
      <p class="text-gray-600 dark:text-gray-400 text-lg">Nenhum livro encontrado</p>
    </div>
  </div>
  </div>
</template>
