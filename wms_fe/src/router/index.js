import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';
import AppMenuItem from '../layout/AppMenuItem.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue')
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: () => import('@/views/pages/profile/Profile.vue')
                },
                {
                    path: '/pages/master',
                    name: 'masterdetail',
                    component: AppMenuItem
                },
                {
                    path: '/pages/master/employerlist',
                    name: 'employerlist',
                    component: () => import('@/views/pages/master/agency/EmployerList.vue')
                },
                {
                    path: '/pages/master/employerform',
                    name: 'employerform',
                    component: () => import('@/views/pages/master/agency/EmployerForm.vue')
                },
                {
                    path: '/pages/master/employeredit/:id',
                    name: 'employeredit',
                    component: () => import('@/views/pages/master/agency/EmployerEdit.vue')
                },
                {
                    path: '/pages/master/employeelist',
                    name: 'employeelist',
                    component: () => import('@/views/pages/master/employee/EmployeeList.vue')
                },
                {
                    path: '/pages/master/employeeform',
                    name: 'employeeform',
                    component: () => import('@/views/pages/master/employee/EmployeeForm.vue')
                },
                {
                    path: '/pages/master/employeeedit/:id',
                    name: 'employeeedit',
                    component: () => import('@/views/pages/master/employee/EmployeeEdit.vue')
                },
                {
                    path: '/pages/useraccess/useraccesslist',
                    name: 'useraccesslist',
                    component: () => import('@/views/pages/useraccess/UserAccessList.vue')
                },
                {
                    path: '/pages/useraccess/add',
                    name: 'useraccessform',
                    component: () => import('@/views/pages/useraccess/UserAccessForm.vue')
                },
                {
                    path: '/pages/useraccess/edit/:id',
                    name: 'useraccessedit',
                    component: () => import('@/views/pages/useraccess/UserAccessFormEdit.vue')
                },
                {
                    path: '/pages/setting/setup',
                    name: 'setuptab',
                    component: () => import('@/views/pages/setting/SetupTab.vue')
                },
                {
                    path: '/pages/setting/usersetup',
                    name: 'usersetuptab',
                    component: () => import('@/views/pages/setting/UserAccessTab.vue')
                }
            ]
        },
        {
            path: '/references',
            name: 'references',
            component: AppLayout,
            children: [
                {
                    path: '/uikit/formlayout',
                    name: 'formlayout',
                    component: () => import('@/views/uikit/FormLayout.vue')
                },
                {
                    path: '/uikit/input',
                    name: 'input',
                    component: () => import('@/views/uikit/Input.vue')
                },
                {
                    path: '/uikit/floatlabel',
                    name: 'floatlabel',
                    component: () => import('@/views/uikit/FloatLabel.vue')
                },
                {
                    path: '/uikit/invalidstate',
                    name: 'invalidstate',
                    component: () => import('@/views/uikit/InvalidState.vue')
                },
                {
                    path: '/uikit/button',
                    name: 'button',
                    component: () => import('@/views/uikit/Button.vue')
                },
                {
                    path: '/uikit/table',
                    name: 'table',
                    component: () => import('@/views/uikit/Table.vue')
                },
                {
                    path: '/uikit/schedule',
                    name: 'schedule',
                    component: () => import('@/views/uikit/Schedule.vue')
                },
                {
                    path: '/uikit/list',
                    name: 'list',
                    component: () => import('@/views/uikit/List.vue')
                },
                {
                    path: '/uikit/tree',
                    name: 'tree',
                    component: () => import('@/views/uikit/Tree.vue')
                },
                {
                    path: '/uikit/panel',
                    name: 'panel',
                    component: () => import('@/views/uikit/Panels.vue')
                },

                {
                    path: '/uikit/overlay',
                    name: 'overlay',
                    component: () => import('@/views/uikit/Overlay.vue')
                },
                {
                    path: '/uikit/media',
                    name: 'media',
                    component: () => import('@/views/uikit/Media.vue')
                },
                {
                    path: '/uikit/menu',
                    component: () => import('@/views/uikit/Menu.vue'),
                    children: [
                        {
                            path: '/uikit/menu',
                            component: () => import('@/views/uikit/menu/PersonalDemo.vue')
                        },
                        {
                            path: '/uikit/menu/seat',
                            component: () => import('@/views/uikit/menu/SeatDemo.vue')
                        },
                        {
                            path: '/uikit/menu/payment',
                            component: () => import('@/views/uikit/menu/PaymentDemo.vue')
                        },
                        {
                            path: '/uikit/menu/confirmation',
                            component: () => import('@/views/uikit/menu/ConfirmationDemo.vue')
                        }
                    ]
                },
                {
                    path: '/uikit/message',
                    name: 'message',
                    component: () => import('@/views/uikit/Messages.vue')
                },
                {
                    path: '/uikit/file',
                    name: 'file',
                    component: () => import('@/views/uikit/File.vue')
                },
                {
                    path: '/uikit/charts',
                    name: 'charts',
                    component: () => import('@/views/uikit/Chart.vue')
                },
                {
                    path: '/uikit/misc',
                    name: 'misc',
                    component: () => import('@/views/uikit/Misc.vue')
                },
                {
                    path: '/blocks',
                    name: 'blocks',
                    component: () => import('@/views/utilities/Blocks.vue')
                },
                {
                    path: '/utilities/icons',
                    name: 'icons',
                    component: () => import('@/views/utilities/Icons.vue')
                },
                {
                    path: '/pages/timeline',
                    name: 'timeline',
                    component: () => import('@/views/pages/Timeline.vue')
                },
                {
                    path: '/pages/empty',
                    name: 'empty',
                    component: () => import('@/views/pages/Empty.vue')
                },
                {
                    path: '/pages/crud',
                    name: 'crud',
                    component: () => import('@/views/pages/Crud.vue')
                },
                {
                    path: '/documentation',
                    name: 'documentation',
                    component: () => import('@/views/utilities/Documentation.vue')
                }
            ]
        },
        {
            path: '/landing',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },

        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/auth/forgotpassword',
            name: 'forgotpassword',
            component: () => import('@/views/pages/auth/ForgotPassword.vue')
        },
        {
            path: '/auth/passwordreset',
            name: 'passwordreset',
            component: () => import('@/views/pages/auth/PasswordReset.vue')
        },
        {
            path: '/auth/access',
            name: 'accessDenied',
            component: () => import('@/views/pages/auth/Access.vue')
        },
        {
            path: '/auth/error',
            name: 'error',
            component: () => import('@/views/pages/auth/Error.vue')
        }
    ]
});

export default router;
