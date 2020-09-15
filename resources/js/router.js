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
import category from './admin/pages/category'

const routes = [
    /*Admin Project routes*/
    {
      path: '/',
      component: home
    },
    {
        path: '/tags',
        component: tags
    },
    {
        path: '/category',
        component: category
    },

/*End Project Routes*/

    {
        path: '/my-new-vue-route',
        component: firstPage,
    },
    {
        path: '/new-route',
        component: newRoutePage
    },

    //Vue hooks
    {
        path: "/hooks",
        component: hooks
    },
    {
        path: '/methods',
        component: methods
    }
];

//To be used elsewhere in project
export default new Router({
    mode: 'history',
    routes
});
