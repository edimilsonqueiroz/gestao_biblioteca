/**
 * Wrapper para simular a função route() do Laravel
 * Usa Wayfinder para gerar as URLs
 */

import * as adminUsers from '@/routes/admin/users'
import * as librarianBooks from '@/routes/librarian/books'
import * as librarianLoans from '@/routes/librarian/loans'
import * as studentRoutes from '@/routes/student'

type RouteParams = string | number | (string | number)[]

export function route(name: string, params?: RouteParams | Record<string, any>): string {
  const routes: Record<string, (p?: any) => { url: string }> = {
    'admin.users.index': () => adminUsers.index(),
    'admin.users.create': () => adminUsers.create(),
    'admin.users.store': () => adminUsers.store(),
    'admin.users.show': (id) => adminUsers.show({ id }),
    'admin.users.edit': (id) => adminUsers.edit({ id }),
    'admin.users.update': (id) => adminUsers.update({ id }),
    'admin.users.destroy': (id) => adminUsers.destroy({ id }),

    'librarian.books.index': () => librarianBooks.index(),
    'librarian.books.create': () => librarianBooks.create(),
    'librarian.books.store': () => librarianBooks.store(),
    'librarian.books.show': (id) => librarianBooks.show({ id }),
    'librarian.books.edit': (id) => librarianBooks.edit({ id }),
    'librarian.books.update': (id) => librarianBooks.update({ id }),
    'librarian.books.destroy': (id) => librarianBooks.destroy({ id }),

    'librarian.loans.index': () => librarianLoans.index(),
    'librarian.loans.show': (id) => librarianLoans.show({ id }),
    'librarian.loans.markReturned': (id) => librarianLoans.markReturned({ id }),

    'student.browse': () => studentRoutes.browse(),
    'student.request-loan': () => studentRoutes.requestLoan(),
    'student.return-loan': (id) => studentRoutes.returnLoan({ loan: id }),
  }

  const routeFn = routes[name]
  if (!routeFn) {
    console.warn(`Route ${name} not found in Wayfinder`)
    return '#'
  }

  return routeFn(params).url
}
