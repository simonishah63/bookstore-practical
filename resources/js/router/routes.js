const Login = () => import('../components/Login.vue')
const Register = () => import('../components/Register.vue')
const DahboardLayout = () => import('../components/layouts/Default.vue')
const Dashboard = () => import('../components/Dashboard.vue')
const Books = () => import('../components/Books/List.vue');
const AddBook = () => import('../components/Books/Add.vue');
const EditBook = () => import('../components/Books/Edit.vue');

export default [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "listBook",
                path: '/books',
                component: Books,
                meta: {
                    title: `Books`
                }
            },
            {
                name: "addBook",
                path: '/books/add',
                component: AddBook,
                meta: {
                    title: `Add Book`
                }
            },
            {
                name: "editBook",
                path: '/books/edit/:id',
                component: EditBook,
                meta: {
                    title: `Edit Book`
                }
            }
        ]
    }
]