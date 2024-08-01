import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';

import Login from './components/Login.vue';
import Register from './components/Register.vue';
import EmailVerify from './components/EmailVerify.vue';
import ForgotPassword from './components/ForgotPassword.vue';
import ResetPassword from './components/ResetPassword.vue';

import Projects from './components/Project/Projects.vue';
import CreateProject from './components/Project/CreateProject.vue';
import ShowProject from './components/Project/ShowProject.vue';
import EditProject from './components/Project/EditProject.vue';
import AddUsersToProject from './components/Project/AddUsersToProject.vue';
import EditUserRole from './components/Project/EditUserRole.vue';

const routes = [
    { path: '/', component: Home, meta: { requiresAuth: true } },
    { path: '/login', component: Login },
    { path: '/logout', component: Home, meta: { requiresAuth: true } },
    { path: '/register', component: Register },
    { path: '/email/verify/:id/:hash', component: EmailVerify, props: true },
    { path: '/password/forgot', component: ForgotPassword },
    { path: '/password/resetform/', component: ResetPassword, props: true },
    { path: '/password/reset/:token', component: ResetPassword, props: true },

    { path: '/projects', component: Projects, meta: { requiresAuth: true } },
    { path: '/projects/create', component: CreateProject, meta: { requiresAuth: true } },
    { path: '/projects/show/:id',
        component: ShowProject,
        props: route => ({ projectId: Number(route.params.id) }),
        meta: { requiresAuth: true }
    },
    { path: '/projects/edit/:id',
        component: EditProject,
        props: route => ({ projectId: Number(route.params.id) }),
        meta: { requiresAuth: true }
    },
    { path: '/projects/add-users/:id',
        component: AddUsersToProject,
        props: route => ({ projectId: Number(route.params.id) }),
        meta: { requiresAuth: true }
    },
    //{ path: '/projects/:projectId/users/:userId/edit-role', component: EditUserRole, meta: { requiresAuth: true } },
    {
        path: '/projects/:projectId/users/:userId/edit-role',
        component: EditUserRole,
        props: route => ({
          projectId: Number(route.params.projectId),
          userId: Number(route.params.userId)
        }),
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const token = localStorage.getItem('token');

  if (requiresAuth && !token) {
    next('/login');
  } else {
    next();
  }
});

export default router;
