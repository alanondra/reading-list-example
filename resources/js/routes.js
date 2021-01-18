import NotFound from '@/src/Views/Pages/NotFound';
import Home from '@/src/Views/Pages/Home';

import Auth from '@/src/Views/Pages/Auth';
import Profile from '@/src/Views/Pages/Profile';
import Authors from '@/src/Views/Pages/Authors';
import Books from '@/src/Views/Pages/Books';

const routes = [
	{ path: '/', name: 'home', component: Home, meta: { title: 'Home' } },
	{ path: '/login', name: 'login', component: Auth.Login, meta: { title: 'Log In' } },
	{ path: '/register', name: 'register', component: Auth.Register, meta: { title: 'Register' } },
	{ path: '/verify/:id/:hash', name: 'verify', component: Auth.Verify, meta: { title: 'Verify' } },
	{ path: '/profile', name: 'profile', component: Profile, meta: { title: 'Profile' } },
	{ path: '/authors', name: 'authors.list', component: Authors.List, meta: { title: 'Authors' } },
	{ path: '/authors/create', name: 'authors.create', component: Authors.Create, meta: { title: 'Create Author' } },
	{ path: '/authors/:id', name: 'authors.show', component: Authors.Show, meta: { title: 'Author' } },
	{ path: '/books', name: 'books.list', component: Books.List, meta: { title: 'Books' } },
	{ path: '/books/create', name: 'books.create', component: Books.Create, meta: { title: 'Create Book' } },
	{ path: '/books/:isbn', name: 'books.show', component: Books.Show, meta: { title: 'Book' } },
//	{ path: '/lists', name: 'lists.list', component: Lists.List, meta: { title: 'Reading Lists' } },
//	{ path: '/lists/:id', name: 'lists.show', component: Lists.Show, meta: { title: 'Reading List' } },
//	{ path: '/lists/create', name: 'lists.create', component: Lists.Create, meta: { title: 'Create Reading List' } },
	{ path: '*', name: 'default', component: NotFound, meta: { title: 'Page Not Found' } }
];

export default routes;
