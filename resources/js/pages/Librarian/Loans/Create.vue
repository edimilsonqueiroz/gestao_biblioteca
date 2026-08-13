<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { loans } from '@/routes/librarian'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import AppLayout from '@/layouts/AppLayout.vue'
import InputError from '@/components/InputError.vue'
import { ArrowLeft, User, Image as ImageIcon } from '@lucide/vue'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

const props = defineProps({
  users: Array,
  books: Array,
})

const selectedUserId = ref(null)
const selectedBookIds = ref([])

const form = useForm({
  user_id: '',
  book_ids: [],
  loaned_at: new Date().toISOString().split('T')[0],
  due_at: '',
})

const submit = () => {
  form.user_id = selectedUserId.value
  form.book_ids = selectedBookIds.value
  form.post(loans.store().url, {
    onFinish: () => form.reset(),
  })
}

const calculateDueDate = () => {
  if (form.loaned_at) {
    const date = new Date(form.loaned_at)
    date.setDate(date.getDate() + 14)
    form.due_at = date.toISOString().split('T')[0]
  }
}

const toggleBook = (bookId) => {
  const index = selectedBookIds.value.indexOf(bookId)
  if (index > -1) {
    selectedBookIds.value.splice(index, 1)
  } else {
    selectedBookIds.value.push(bookId)
  }
}
</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex items-center gap-4">
      <Link :href="loans.index().url" as="button">
        <Button variant="outline" size="icon">
          <ArrowLeft class="w-4 h-4" />
        </Button>
      </Link>
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Novo Empréstimo</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Registre um novo empréstimo de livro</p>
      </div>
    </div>

    <Card class="border-gray-200 dark:border-gray-700">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <CardTitle class="text-gray-900 dark:text-white">Selecione o Usuário</CardTitle>
        <CardDescription>Escolha quem está pegando os livros emprestados</CardDescription>
      </CardHeader>
      <CardContent class="pt-6">
        <div class="overflow-x-auto pb-4 -mx-6 px-6">
          <div class="flex gap-4 min-w-min">
            <button
              v-for="user in users"
              :key="user.id"
              @click="selectedUserId = user.id"
              :class="[
                'flex-shrink-0 w-56 p-4 rounded-lg border-2 transition-all cursor-pointer',
                selectedUserId === user.id
                  ? 'border-blue-600 bg-blue-50 dark:bg-blue-950/50'
                  : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
              ]"
            >
              <div class="flex items-start gap-3">
                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex-shrink-0">
                  <User class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="text-left flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 dark:text-white truncate">{{ user.name }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ user.email }}</p>
                  <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 capitalize">{{ user.role }}</p>
                </div>
              </div>
            </button>
          </div>
        </div>
        <InputError :message="form.errors.user_id" />
      </CardContent>
    </Card>

    <Card class="border-gray-200 dark:border-gray-700">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <CardTitle class="text-gray-900 dark:text-white">Selecione os Livros</CardTitle>
        <CardDescription>Escolha um ou mais livros para emprestar ({{ selectedBookIds.length }} selecionado(s))</CardDescription>
      </CardHeader>
      <CardContent class="pt-6">
        <div class="overflow-x-auto pb-4 -mx-6 px-6">
          <div class="flex gap-4 min-w-min">
            <button
              v-for="book in books"
              :key="book.id"
              @click="toggleBook(book.id)"
              :class="[
                'flex-shrink-0 w-56 rounded-lg border-2 overflow-hidden transition-all cursor-pointer',
                selectedBookIds.includes(book.id)
                  ? 'border-blue-600 ring-2 ring-blue-600'
                  : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'
              ]"
            >
              <div class="h-40 bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                <img
                  v-if="book.cover"
                  :src="`/storage/${book.cover}`"
                  :alt="book.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <ImageIcon class="w-8 h-8 text-gray-400 dark:text-gray-600" />
                </div>
              </div>
              <div class="p-2 bg-white dark:bg-gray-900">
                <p class="font-semibold text-gray-900 dark:text-white text-xs line-clamp-2">{{ book.title }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ book.author }}</p>
                <div class="mt-1 flex items-center justify-between">
                  <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ book.quantity_available }}</span>
                  <div
                    v-if="selectedBookIds.includes(book.id)"
                    class="w-4 h-4 rounded-full bg-blue-600 flex items-center justify-center"
                  >
                    <span class="text-white text-xs font-bold">✓</span>
                  </div>
                </div>
              </div>
            </button>
          </div>
        </div>
        <InputError :message="form.errors.book_ids" />
      </CardContent>
    </Card>

    <Card class="max-w-2xl border-gray-200 dark:border-gray-700">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <CardTitle class="text-gray-900 dark:text-white">Dados do Empréstimo</CardTitle>
        <CardDescription>Preencha as datas do empréstimo</CardDescription>
      </CardHeader>
      <CardContent class="pt-6">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Data de Empréstimo -->
          <div class="space-y-2">
            <Label for="loaned_at" class="text-gray-700 dark:text-gray-300">Data de Empréstimo</Label>
            <Input
              id="loaned_at"
              v-model="form.loaned_at"
              type="date"
              @update:model-value="calculateDueDate"
              class="dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            <InputError :message="form.errors.loaned_at" />
          </div>

          <!-- Data de Devolução -->
          <div class="space-y-2">
            <Label for="due_at" class="text-gray-700 dark:text-gray-300">Data de Devolução Prevista</Label>
            <Input
              id="due_at"
              v-model="form.due_at"
              type="date"
              class="dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            <InputError :message="form.errors.due_at" />
            <p class="text-sm text-gray-500 dark:text-gray-400">Preenchido automaticamente com 14 dias após a data de empréstimo</p>
          </div>

          <!-- Botões de Ação -->
          <div class="flex gap-2 pt-4">
            <Button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white"
              :disabled="form.processing || !selectedUserId || selectedBookIds.length === 0"
            >
              {{ form.processing ? 'Salvando...' : 'Registrar Empréstimo' }}
            </Button>
            <Link :href="loans.index().url" as="button">
              <Button type="button" variant="outline">
                Cancelar
              </Button>
            </Link>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
