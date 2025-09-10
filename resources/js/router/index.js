import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../views/Dashboard.vue';
import ApplicationsIndex from '../views/applications/Index.vue';
import ApplicationsCreate from '../views/applications/Create.vue';
import ApplicationsEdit from '../views/applications/Edit.vue';
import ApplicationsShow from '../views/applications/Show.vue';
import ListenersIndex from '../views/listeners/Index.vue';
import ListenersCreate from '../views/listeners/Create.vue';
import ListenersEdit from '../views/listeners/Edit.vue';
import ListenersShow from '../views/listeners/Show.vue';
import RoutesIndex from '../views/routes/Index.vue';
import RoutesCreate from '../views/routes/Create.vue';
import RoutesEdit from '../views/routes/Edit.vue';
import RoutesShow from '../views/routes/Show.vue';

const routes = [
    {
        path: '/',
        redirect: '/unit/dashboard'
    },
    {
        path: '/unit/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/unit/applications',
        name: 'applications.index',
        component: ApplicationsIndex
    },
    {
        path: '/unit/applications/create',
        name: 'applications.create',
        component: ApplicationsCreate
    },
    {
        path: '/unit/applications/:name/edit',
        name: 'applications.edit',
        component: ApplicationsEdit,
        props: true
    },
    {
        path: '/unit/applications/:name',
        name: 'applications.show',
        component: ApplicationsShow,
        props: true
    },
    {
        path: '/unit/listeners',
        name: 'listeners.index',
        component: ListenersIndex
    },
    {
        path: '/unit/listeners/create',
        name: 'listeners.create',
        component: ListenersCreate
    },
    {
        path: '/unit/listeners/:address/edit',
        name: 'listeners.edit',
        component: ListenersEdit,
        props: true
    },
    {
        path: '/unit/listeners/:address',
        name: 'listeners.show',
        component: ListenersShow,
        props: true
    },
    {
        path: '/unit/routes',
        name: 'routes.index',
        component: RoutesIndex
    },
    {
        path: '/unit/routes/create',
        name: 'routes.create',
        component: RoutesCreate
    },
    {
        path: '/unit/routes/:name/edit',
        name: 'routes.edit',
        component: RoutesEdit,
        props: true
    },
    {
        path: '/unit/routes/:name',
        name: 'routes.show',
        component: RoutesShow,
        props: true
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;

