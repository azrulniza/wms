<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import router from '../../../router';

const { layoutConfig } = useLayout();
const email = ref('');
const password = ref('');
const password2 = ref('');

const emailError = ref(false);
const passwordError = ref(false);
const password2Error = ref(false);

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});

const passwordsMatch = computed(() => {
    return password.value === password2.value;
});
const BtnPasswordReset = () => {
    emailError.value = !email.value;
    passwordError.value = !password.value;
    password2Error.value = !password2.value;
    if (email.value && password.value && password2.value && passwordsMatch.value) {
        router.push({ name: 'login' });
    }
}
</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <span class="text-600 font-medium">Reset your password</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full" style="padding: 1rem" v-model="email" />
                        <small v-if="emailError" class="text-red-500">Email is required</small>

                        <label for="password1" class="block text-900 font-medium text-xl mb-2 mt-3">Password</label>
                        <Password id="password1" v-model="password" placeholder="Password" :toggleMask="true" class="w-full" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
                        <small v-if="passwordError" class="text-red-500">Password is required</small>

                        <label for="password2" class="block text-900 font-medium text-xl mb-2 mt-3">Confirm Password</label>
                        <Password id="password2" v-model="password2" placeholder="Re-Type Password" :toggleMask="true" class="w-full" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
                        <small v-if="password2Error" class="text-red-500">Confirm password is required</small>
                        <small v-if="!password2Error && !passwordsMatch" class="text-red-500">Passwords do not match!</small>
                        <small v-else-if="password && !password2Error && passwordsMatch" class="text-green-500">Passwords matched!</small>

                        <Button label="Continue" class="w-full p-3 mt-5 text-xl" @click="BtnPasswordReset"></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
</template>

<style scoped>
</style>
