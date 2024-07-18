<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed, onMounted } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import router from '../../../router';
import { useAuthStore } from '@/store/auth';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;

const { layoutConfig } = useLayout();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const checked = ref(false);
const loading = ref(false);

const emailError = ref(false);
const passwordError = ref(false);
const loginError = ref(''); // Add a state for login error message

// Check if there's saved email and remember me status in localStorage
onMounted(() => {
    const savedEmail = localStorage.getItem('email');
    const savedChecked = localStorage.getItem('rememberMe') === 'true';

    if (savedEmail && savedChecked) {
        email.value = savedEmail;
        checked.value = true;
    }
});

const BtnUserLogin = async () => {
    emailError.value = !email.value;
    passwordError.value = !password.value;

    if (email.value && password.value) {
        // router.push({ name: 'dashboard' });
        loading.value = true; // Set loading state to true
        try {
            try {
                const payload = {
                    user_email: email.value,
                    user_password: password.value
                };

                const response = await axios.post(`${API_URL}/login`, payload);
                authStore.setAuthData(response.data);

                if (checked.value) {
                    localStorage.setItem('email', email.value);
                    localStorage.setItem('rememberMe', true);
                } else {
                    localStorage.removeItem('email');
                    localStorage.setItem('rememberMe', false);
                }

                router.push({ name: 'dashboard' });
            } catch (error) {
                console.error('Error login:', error);
                loginError.value = 'Invalid email or password. Please try again.'; // Set the error message
            }
        } catch (error) {
            console.error('Error saving employee profile:', error);
        } finally {
            loading.value = false; // Set loading state to false
        }
    }

    return false;
};

const BtnForgotPassword = () => {
    router.push({ name: 'forgotpassword' });
};

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});
</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <!-- <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" /> -->
                        <div class="text-900 text-3xl font-medium mb-3">Welcome to Workforce Management System!</div>
                        <span class="text-600 font-medium">Sign in to continue</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <small v-if="emailError" class="p-error">Email is required!</small>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full mb-3" :class="{ 'p-invalid': emailError }" inputClass="w-full" style="padding: 1rem" v-model="email" />

                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                        <small v-if="passwordError" class="p-error">Password is required!</small>
                        <Password id="password1" :feedback="false" v-model="password" placeholder="Password" :toggleMask="true" class="w-full mb-3" :class="{ 'p-invalid': emailError }" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>

                        <div v-if="loginError" class="p-error text-center mb-3">{{ loginError }}</div>
                        <!-- Display the login error message -->
                        <div class="flex align-items-center justify-content-between mb-5 gap-5">
                            <div class="flex align-items-center">
                                <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Remember me</label>
                            </div>
                            <Button class="font-medium ml-2 text-right pr-0 cursor-pointer" label="Forgot password?" @click="BtnForgotPassword" style="color: var(--primary-color)" link />
                        </div>
                        <!-- <Button label="Sign In" class="w-full p-3 text-xl" @click="router.push({ name: 'dashboard' })" :disabled="loading"></Button> -->
                        <Button label="Sign In" class="w-full p-3 text-xl" @click="BtnUserLogin"></Button>
                        <div v-if="loading">
                            <ProgressBar mode="indeterminate" style="height: 6px; margin-top: 10px" severity="info"> </ProgressBar>
                            <div class="loading-text">Please wait...</div>
                            <!-- Loading message -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
  }

  .pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
  }
</style>
