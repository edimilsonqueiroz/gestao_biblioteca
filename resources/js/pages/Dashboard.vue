<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Book, Clock, AlertCircle } from '@lucide/vue'

defineOptions({
    layout: AppLayout,
});

const { statistics, recentLoans } = usePage().props

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

const isOverdue = (dueAt: string, status: string) => {
  return status === 'active' && new Date(dueAt) < new Date()
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="px-6 py-8 space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Bem-vindo à Gestão da Biblioteca</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 md:grid-cols-3">
            <!-- Total Books -->
            <Card class="border-gray-200 dark:border-gray-700 shadow-sm">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Total de Livros</CardTitle>
                    <Book class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.totalBooks }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">livros no acervo</p>
                </CardContent>
            </Card>

            <!-- Active Loans -->
            <Card class="border-gray-200 dark:border-gray-700 shadow-sm">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Empréstimos Ativos</CardTitle>
                    <Clock class="h-4 w-4 text-green-600 dark:text-green-400" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.activeLoans }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">empréstimos em aberto</p>
                </CardContent>
            </Card>

            <!-- Overdue Loans -->
            <Card class="border-gray-200 dark:border-gray-700 shadow-sm">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Livros Vencidos</CardTitle>
                    <AlertCircle class="h-4 w-4 text-red-600 dark:text-red-400" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.overdueLoans }}</div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">prazos vencidos</p>
                </CardContent>
            </Card>
        </div>

        <!-- Recent Loans Table -->
        <Card class="border-gray-200 dark:border-gray-700 shadow-sm">
            <CardHeader class="border-b border-gray-200 dark:border-gray-700">
                <CardTitle>Empréstimos Recentes</CardTitle>
                <CardDescription>Últimos 10 empréstimos realizados</CardDescription>
            </CardHeader>
            <CardContent class="p-0">
                <div v-if="recentLoans.length === 0" class="p-6 text-center text-gray-500">
                    Nenhum empréstimo realizado
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-950/50 dark:to-blue-950/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Usuário</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Livro</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Emprestado em</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-200">Prazo até</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="loan in recentLoans" :key="loan.id" class="transition-all hover:bg-blue-50/50 dark:hover:bg-blue-950/30">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ loan.user.name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ loan.book.title }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(loan.loaned_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="[
                                        isOverdue(loan.due_at, loan.status)
                                            ? 'text-red-600 font-semibold'
                                            : 'text-gray-600 dark:text-gray-400'
                                    ]">
                                        {{ formatDate(loan.due_at) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span v-if="loan.status === 'active'" :class="[
                                        isOverdue(loan.due_at, loan.status)
                                            ? 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                            : 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                    ]">
                                        {{ isOverdue(loan.due_at, loan.status) ? 'Vencido' : 'Ativo' }}
                                    </span>
                                    <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Devolvido
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
