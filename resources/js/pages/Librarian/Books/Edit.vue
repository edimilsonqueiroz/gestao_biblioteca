<script setup>
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout,
})

const { book } = usePage().props

const form = useForm({
  title: book.title,
  author: book.author,
  isbn: book.isbn,
  description: book.description,
  quantity: book.quantity,
  category: book.category,
  year: book.year,
  publisher: book.publisher,
})

const submit = () => {
  form.put(route('librarian.books.update', book.id), {
    onSuccess: () => {
      // Redirect handled by Laravel
    },
  })
}
</script>

<template>
  <div class="px-6 py-8">
    <div class="space-y-6 max-w-2xl">
      <div>
        <h1 class="text-3xl font-bold">Editar Livro</h1>
        <p class="text-gray-600 dark:text-gray-400">Altere as informações do livro</p>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Informações do Livro</CardTitle>
        <CardDescription>Atualize os dados do livro</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-2 gap-6">
            <div class="space-y-2">
              <Label for="title">Título *</Label>
              <Input
                id="title"
                v-model="form.title"
                type="text"
                placeholder="Título do livro"
                required
              />
              <InputError v-if="form.errors.title" :message="form.errors.title" />
            </div>

            <div class="space-y-2">
              <Label for="author">Autor *</Label>
              <Input
                id="author"
                v-model="form.author"
                type="text"
                placeholder="Nome do autor"
                required
              />
              <InputError v-if="form.errors.author" :message="form.errors.author" />
            </div>

            <div class="space-y-2">
              <Label for="isbn">ISBN *</Label>
              <Input
                id="isbn"
                v-model="form.isbn"
                type="text"
                placeholder="978-8-88888-888-8"
                required
              />
              <InputError v-if="form.errors.isbn" :message="form.errors.isbn" />
            </div>

            <div class="space-y-2">
              <Label for="quantity">Quantidade *</Label>
              <Input
                id="quantity"
                v-model.number="form.quantity"
                type="number"
                min="1"
                required
              />
              <InputError v-if="form.errors.quantity" :message="form.errors.quantity" />
            </div>

            <div class="space-y-2">
              <Label for="category">Categoria</Label>
              <Input
                id="category"
                v-model="form.category"
                type="text"
                placeholder="Ficção, Técnico, etc"
              />
            </div>

            <div class="space-y-2">
              <Label for="year">Ano de Publicação</Label>
              <Input
                id="year"
                v-model.number="form.year"
                type="number"
                min="1000"
              />
            </div>
          </div>

          <div class="space-y-2">
            <Label for="publisher">Editora</Label>
            <Input
              id="publisher"
              v-model="form.publisher"
              type="text"
              placeholder="Nome da editora"
            />
          </div>

          <div class="space-y-2">
            <Label for="description">Descrição</Label>
            <textarea
              id="description"
              v-model="form.description"
              placeholder="Descrição do livro..."
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <div class="flex gap-4">
            <Button type="submit" :disabled="form.processing">
              Salvar Alterações
            </Button>
            <Link :href="route('librarian.books.index')" as="button">
              <Button variant="outline">Cancelar</Button>
            </Link>
          </div>
        </form>
      </CardContent>
    </Card>
    </div>
  </div>
</template>
