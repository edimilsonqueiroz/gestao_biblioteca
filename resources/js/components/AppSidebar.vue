<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Users, BookOpen, Share2, ClipboardList } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { books, loans, reservations } from '@/routes/librarian';

import type { NavItem } from '@/types';

const { auth } = usePage().props;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
        icon: LayoutGrid,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Usuários',
        href: route('admin.users.index'),
        icon: Users,
    },
];

const librarianNavItems: NavItem[] = [
    {
        title: 'Livros',
        href: books.index().url,
        icon: BookOpen,
    },
    {
        title: 'Empréstimos',
        href: loans.index().url,
        icon: Share2,
    },
    {
        title: 'Reservas',
        href: reservations.index().url,
        icon: ClipboardList,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" label="Geral" />
            <NavMain v-if="auth.user.role === 'admin'" :items="adminNavItems" label="Administração" />
            <NavMain v-if="auth.user.role === 'bibliotecaria'" :items="librarianNavItems" label="Biblioteca" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
