<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { reservations } from '@/routes/librarian'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AppLayout from '@/layouts/AppLayout.vue'
import { CheckCircle, Clock, Trash2, ArrowRight } from '@lucide/vue'
import { ref } from 'vue'
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

const { reservations: reservationsList } = usePage().props

const statusConfig = {
  pending: {
    label: 'Pendente',
    icon: Clock,
    color: 'text-yellow-600 dark:text-yellow-400',
    bgColor: 'bg-yellow-100 dark:bg-yellow-900/30',
  },
  approved: {
    label: 'Aprovada',
    icon: CheckCircle,
    color: 'text-green-600 dark:text-green-400',
    bgColor: 'bg-green-100 dark:bg-green-900/30',
  },
  converted_to_loan: {
    label: 'Convertida',
    icon: ArrowRight,
    color: 'text-blue-600 dark:text-blue-400',
    bgColor: 'bg-blue-100 dark:bg-blue-900/30',
  },
  cancelled: {
    label: 'Cancelada',
    icon: Trash2,
    color: 'text-red-600 dark:text-red-400',
    bgColor: 'bg-red-100 dark:bg-red-900/30',
  },
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const handleApprove = (reservation) => {
  router.post(reservations.approve(reservation.id).url)
}

const handleReject = (reservation) => {
  router.post(reservations.reject(reservation.id).url)
}

const handleConvert = (reservation) => {
  router.post(reservations.convertToLoan(reservation.id).url)
}
</script>

<template>
  <div class="px-6 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gerenciar Reservas</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Valide e converta reservas em empréstimos</p>
      </div>
    </div>

    <Card class="border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
      <CardHeader class="border-b border-gray-200 dark:border-gray-700">
        <div>
          <CardTitle class="text-gray-900 dark:text-white">Reservas</CardTitle>
          <CardDescription>{{ reservationsList.length }} reserva(s) no total</CardDescription>
        </div>
      </CardHeader>
      <CardContent class="p-0">
        <div v-if="reservationsList.length === 0" class="p-6 text-center text-gray-500">
          Nenhuma reserva cadastrada
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Usuário</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Livro</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Reservado para</th>
                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Status</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="reservation in reservationsList" :key="reservation.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                  {{ reservation.user.name }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ reservation.book.title }}
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ formatDate(reservation.reserved_for) }}
                </td>
                <td class="px-6 py-4">
                  <span :class="[statusConfig[reservation.status].bgColor, statusConfig[reservation.status].color, 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium']">
                    {{ statusConfig[reservation.status].label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <template v-if="reservation.status === 'pending'">
                      <Button
                        size="sm"
                        class="bg-green-600 hover:bg-green-700 text-white"
                        @click="handleApprove(reservation)"
                      >
                        Aprovar
                      </Button>
                      <Button
                        size="sm"
                        variant="outline"
                        class="text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50"
                        @click="handleReject(reservation)"
                      >
                        Rejeitar
                      </Button>
                    </template>
                    <template v-if="reservation.status === 'approved'">
                      <Button
                        size="sm"
                        class="bg-blue-600 hover:bg-blue-700 text-white gap-1"
                        @click="handleConvert(reservation)"
                      >
                        <ArrowRight class="w-4 h-4" />
                        Converter em Empréstimo
                      </Button>
                    </template>
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
