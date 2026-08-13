<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Trash2 } from '@lucide/vue'

interface Props {
  user: {
    id: number
    name: string
  }
  route: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  deleted: [value: number]
}>()

const isOpen = ref(false)
const form = useForm({})

const handleDelete = () => {
  form.delete(props.route, {
    onSuccess: () => {
      isOpen.value = false
      emit('deleted', props.user.id)
    },
  })
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <button
        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 dark:text-red-400 transition-colors"
      >
        <Trash2 class="w-4 h-4" />
        Deletar
      </button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader class="space-y-3">
        <DialogTitle>Deletar usuário</DialogTitle>
        <DialogDescription>
          Tem certeza de que deseja deletar <strong>{{ user.name }}</strong>? Esta ação não pode ser desfeita.
        </DialogDescription>
      </DialogHeader>

      <DialogFooter class="gap-2">
        <DialogClose as-child>
          <Button variant="secondary">
            Cancelar
          </Button>
        </DialogClose>

        <Button
          variant="destructive"
          :disabled="form.processing"
          @click="handleDelete"
        >
          {{ form.processing ? 'Deletando...' : 'Deletar usuário' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
