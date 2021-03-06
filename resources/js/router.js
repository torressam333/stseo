import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import firstPage from './components/pages/myFirstVuePage';
import newRoutePage from './components/pages/newRoutePage';
import hooks from './components/pages/basic/hooks';
import methods from './components/pages/basic/methods'

/*Admin Project Pages*/
import home from './components/pages/home';
import tags from './admin/pages/tags';
import category from './admin/pages/category';
import adminusers from './admin/pages/adminusers';
import login from './admin/pages/login';
import role from './admin/pages/role';
import assignRole from './admin/pages/assignRole';
import createBlog from './admin/pages/createBlog';
import editBlog from './admin/pages/editBlog';
import blogs from './admin/pages/blogs';
import notfound from './admin/pages/notfound'

const routes = [
    /*Admin Project routes*/
    {
      path: '/',
      component: home,
      name: 'home'
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/createBlog',
        component: createBlog,
        name: 'createBlog'
    },
    {
        path: '/editBlog/:id',
        component: editBlog,
        name: 'editBlog'
    },
    {
        path: '/blogs',
        component: blogs,
        name: 'blogs'
    },
    {
        path: '/adminusers',
        component: adminusers,
        name: 'adminusers'
    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '*',
        component: notfound,
        name: 'notfound'
    }
];

//To be used elsewhere in project
export default new Router({
    mode: 'history',
    routes
});
