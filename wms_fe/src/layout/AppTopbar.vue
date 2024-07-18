<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import router from '../router';
import { useAuthStore } from '@/store/auth';

const authStore = useAuthStore();
const user = computed(() => authStore.getUser);
//const isAuthenticated = computed(() => authStore.isAuthenticated);

const name = ref(user.value.employee_name || '');
const initials = computed(() => getInitials(name.value));
const { layoutConfig, onMenuToggle } = useLayout();

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);

const BtnSignOut = () => {
    authStore.clearAuthData(); // Clear auth data
    router.push({ name: 'login' });
};

const menu = ref();

const items = ref([
    {
        label: 'Manage Profile',
        icon: 'pi pi-user',
        command: () => {
            router.push({ name: 'profile' });
        }
    },
    {
        label: 'Sign Out',
        icon: 'pi pi-sign-out',
        command: () => {
            BtnSignOut();
        }
    }
]);

const toggle = (event) => {
    menu.value.toggle(event);
};

onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});

const topbarMenuClasses = computed(() => {
    return {
        'layout-topbar-menu-mobile-active': topbarMenuActive.value
    };
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                topbarMenuActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};
const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};
const isOutsideClicked = (event) => {
    if (!topbarMenuActive.value) return;

    const sidebarEl = document.querySelector('.layout-topbar-menu');
    const topbarEl = document.querySelector('.layout-topbar-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
};

const getInitials = (fullName) => {
    const words = fullName.split(' ');
    let initials = '';
    for (let i = 0; i < words.length && initials.length < 2; i++) {
        initials += words[i].charAt(0).toUpperCase();
    }
    if (initials.length < 2 && words.length > 1) {
        initials += words[1].charAt(0).toUpperCase();
    } else if (initials.length < 2 && words.length === 1) {
        initials += words[0].charAt(1).toUpperCase();
    }
    return initials;
};
</script>

<template>
    <div class="layout-topbar">
        <router-link to="/" class="layout-topbar-logo">
            <img :src="logoUrl" alt="logo" />
        </router-link>

        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()" v-tooltip.right="'menu bar'">
            <i class="pi pi-bars"></i>
        </button>

        <button class="p-link layout-topbar-menu-button" @click="toggle" v-tooltip.left="'Option'">
            <Avatar :label="initials" shape="circle" />
        </button>

        <div class="layout-topbar-menu" :class="topbarMenuClasses">
            <span class="flex align-items-center mr-2" id="TxtUserName"> {{ name }}</span>
            <button @click="toggle" class="p-link" v-tooltip.left="'profile'">
                <Avatar :label="initials" shape="circle" />
            </button>
            <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
        </div>
    </div>
</template>

<style lang="scss" scoped></style>
