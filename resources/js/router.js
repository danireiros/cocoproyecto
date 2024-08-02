import { createRouter, createWebHistory } from 'vue-router';
import myTasks from './components/UserTasks.vue';
import Home from './components/Home.vue';

import Login from './components/Login.vue';
import Register from './components/Register.vue';
import EmailVerify from './components/EmailVerify.vue';
import ForgotPassword from './components/ForgotPassword.vue';
import ResetPassword from './components/ResetPassword.vue';

import Projects from './components/Project/Index.vue';
import CreateProject from './components/Project/Create.vue';
import ShowProject from './components/Project/Show.vue';
import EditProject from './components/Project/Edit.vue';
import AddUsersToProject from './components/Project/AddUsersToProject.vue';
import EditProjectUserRole from './components/Project/EditUserProjectRole.vue';

import ProjectTasks from './components/Project/Task/Index.vue';
import CreateTask from './components/Project/Task/Create.vue';
import EditTask from './components/Project/Task/Edit.vue';
import AddUsersToTasks from './components/Project/Task/AddUsersToTasks.vue';
import SubmitTask from './components/Project/Task/Submit.vue';

import Users from './components/User/Index.vue';
import EditUserRole from './components/User/Edit.vue';

const routes = [
    { path: '/', component: Home, meta: { requiresAuth: true } },
    { path: '/mytasks', component: myTasks, meta: { requiresAuth: true } },
    { path: '/login', component: Login },
    { path: '/logout', component: myTasks, meta: { requiresAuth: true } },
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
    {
        path: '/projects/:projectId/users/:userId/edit-role',
        component: EditProjectUserRole,
        props: route => ({
          projectId: Number(route.params.projectId),
          userId: Number(route.params.userId)
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/projects/:projectId/tasks',
        component: ProjectTasks,
        props: route => ({
          projectId: Number(route.params.projectId),
          userId: Number(route.params.userId)
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/projects/:projectId/tasks/create',
        name: 'CreateTask',
        component: CreateTask,
        props: route => ({
            projectId: parseInt(route.params.projectId),
        })
    },
    {
        path: '/projects/:projectId/tasks/:taskId/edit',
        component: EditTask,
        props: route => ({
            projectId: Number(route.params.projectId),
            taskId: Number(route.params.taskId)
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/projects/:projectId/tasks/:taskId/assign-members',
        component: AddUsersToTasks,
        props: route => ({
            projectId: Number(route.params.projectId),
            taskId: Number(route.params.taskId)
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/projects/tasks/:taskId/submit',
        component: SubmitTask,
        props: route => ({
            taskId: Number(route.params.taskId),
            userId: Number(route.params.userId)
        }),
        meta: { requiresAuth: true }
    },
    {
        path: '/tasks/:taskId/submit',
        name: 'SubmitTask',
        component: SubmitTask,
        props: true,
        meta: { requiresAuth: true }
    },
    {
        path: '/users',
        name: 'Users',
        component: Users,
        props: true,
        meta: { requiresAuth: true }
    },
    {
        path: '/users/:userId/edit-role',
        component: EditUserRole,
        props: route => ({
          projectId: Number(route.params.projectId),
          userId: Number(route.params.userId)
        }),
        meta: { requiresAuth: true }
    },
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
