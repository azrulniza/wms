<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed } from 'vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';
import Message from 'primevue/message';

const API_URL = import.meta.env.VITE_BASE_URL;

const { layoutConfig } = useLayout();
const toast = useToast();
const router = useRouter();

const loading = ref(false);
const userEmail = ref('');
const userEmailError = ref(false);
const userEmailFormat = ref(false);
const failed = ref(false);

const validate = () => {
    // Reset error states
    userEmailError.value = false;
    userEmailFormat.value = false;

    // Validate email
    if (!userEmail.value.trim()) {
        userEmailError.value = true; // Email is required
    } else if (!isValidEmail(userEmail.value)) {
        userEmailFormat.value = true; // Invalid email format
    }

    // Return overall validation result
    return !userEmailError.value && !userEmailFormat.value;
};

const isValidEmail = (email) => {
    // Regular expression for basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

const BtnForgotPasswordAction = async () => {
    if (!validate()) {
        return;
    }
    loading.value = true; // Set loading state to true
    try {
        const payload = {
            user_email: userEmail.value
        };
        const response = await axios.post(`${API_URL}/forgot-password`, payload);

        if (response.data.success) {
            toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
            localStorage.setItem('passwordResetRequested', 'true');
            router.push({ name: 'forgotpasswordsuccess' });
        } else {
            failed.value = response.data.error;
            console.log(response.data);
            //toast.add({ severity: 'error', summary: 'Error', detail: response.data.error, life: 3000 });
        }
    } catch (error) {
        failed.value = true;
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to send password reset email', life: 3000 });
        console.error('Error forgot password:', error);
    } finally {
        loading.value = false; // Set loading state to false
    }
};

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});
</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center col-12">
            <img :src="logoUrl" alt="WMS logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Lupa Kata Laluan ke? Kesian</div>
                        <p class="message text-600 mx-auto font-medium">Masukkanlah email tu, takkanlah email pun lupa. Kalau lupa juga memang jenis makan semut banyak ni</p>
                    </div>
                    <div>
                        <Message v-if="failed" severity="error" icon="pi pi-fw pi-exclamation-circle text-2xl"> {{ failed || 'Failed to send password reset email' }}</Message>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <small v-if="userEmailError" class="p-error">Email is required!</small>
                        <small v-if="userEmailFormat" class="p-error">Invalid email format!</small>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full mb-3" :class="{ 'p-invalid': userEmailError || userEmailFormat }" inputClass="w-full" style="padding: 1rem" v-model="userEmail" />
                        <Button label="Continue" @click="BtnForgotPasswordAction" class="w-full p-3 text-xl"></Button>
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

.message {
    width: 60%;
}
</style>
