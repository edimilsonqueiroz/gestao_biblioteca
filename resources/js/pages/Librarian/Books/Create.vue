<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { books } from '@/routes/librarian'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { Upload } from '@lucide/vue'

defineOptions({
  layout: AppLayout,
})

const coverPreview = ref(null)
const fileInput = ref(null)

const form = useForm({
  title: '',
  author: '',
  isbn: '',
  description: '',
  quantity: 1,
  category: '',
  year: new Date().getFullYear(),
  publisher: '',
  cover: null,
})

const handleCoverSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.cover = file
    const reader = new FileReader()
    reader.onload = (e) => {
      coverPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const submit = () => {
  form.post(books.store().url, {
    multipart: true,
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
        <h1 class="text-3xl font-bold">Adicionar Novo Livro</h1>
        <p class="text-gray-600 dark:text-gray-400">Cadastre um novo livro no acervo</p>
      </div>

      <Card>
      <CardHeader>
        <CardTitle>Informações do Livro</CardTitle>
        <CardDescription>Preencha os dados do livro</CardDescription>
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

          <div class="space-y-2">
            <Label for="cover">Foto da Capa</Label>
            <div class="flex gap-4">
              <div class="flex-1">
                <input
                  ref="fileInput"
                  id="cover"
                  type="file"
                  accept="image/*"
                  @change="handleCoverSelect"
                  class="hidden"
                />
                <button
                  type="button"
                  @click="fileInput.click()"
                  class="w-full px-4 py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-md text-center hover:border-blue-500 dark:hover:border-blue-500 transition-colors cursor-pointer flex items-center justify-center gap-2 text-gray-600 dark:text-gray-400"
                >
                  <Upload class="w-4 h-4" />
                  Clique para selecionar a imagem
                </button>
              </div>
              <div v-if="coverPreview" class="flex-shrink-0">
                <img
                  :src="coverPreview"
                  alt="Preview da capa"
                  class="h-32 w-auto object-cover rounded-md border border-gray-300 dark:border-gray-600"
                />
              </div>
            </div>
            <InputError v-if="form.errors.cover" :message="form.errors.cover" />
          </div>

          <div class="flex gap-4">
            <Button type="submit" :disabled="form.processing">
              Adicionar Livro
            </Button>
            <Link :href="books.index().url" as="button">
              <Button variant="outline">Cancelar</Button>
            </Link>
          </div>
        </form>
      </CardContent>
    </Card>
    </div>
  </div>
</template>
