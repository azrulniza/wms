<script setup>
import { useLayout } from '@/layout/composables/layout';
import { useToast } from 'primevue/usetoast';
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import Message from 'primevue/message';

const API_URL = import.meta.env.VITE_BASE_URL;
const { layoutConfig } = useLayout();
const route = useRoute();
const router = useRouter();
const toast = useToast();

const token = ref('');
const email = ref('');
const newPassword = ref('');
const newPassword1 = ref('');

const newPasswordError = ref(false);
const newPassword1Error = ref(false);
const validateError = ref(false);
const passwordFormatError = ref(false);
const passwordMatchError = ref(false);
const resetPasswordError = ref(false);

const isValidPassword = (password) => {
    // Regular expression for password validation
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
};

const BtnSaveSetting = async () => {
    newPasswordError.value = !newPassword.value;
    newPassword1Error.value = !newPassword1.value;

    if (!newPasswordError.value && !newPassword1Error.value && passwordMatch.value) {
        if (isValidPassword(newPassword.value)) {
            const payload = { token: token.value, email: email.value, password: newPassword.value };
            const result = await axios.post(`${API_URL}/reset-password`, payload);
            if (result.data.success === true) {
                localStorage.setItem('passwordReseted', 'true');
                router.push({ name: 'resetpasswordsuccess' });
            } else {
                resetPasswordError.value = true;
            }
        } else {
            passwordFormatError.value = true;
        }
    } else {
        passwordMatchError.value = true;
    }
};

const passwordMatch = computed(() => {
    return newPassword.value === newPassword1.value;
});

const BtnCancel = () => {
    router.push({ name: 'login' });
};

const checkToken = async () => {
    const payload = {
        token: token.value,
        email: email.value
    };

    try {
        const response = await axios.post(`${API_URL}/reset-password-validate`, payload);
        if (!response.data.valid) {
            //toast.add({ severity: 'error', summary: 'Error', detail: 'Invalid token or email', life: 3000 });
            //router.push({ name: 'login' });
            validateError.value = true;
        }
    } catch (error) {
        //toast.add({ severity: 'error', summary: 'Error', detail: 'Error validating token', life: 3000 });
        // router.push({ name: 'login' });
        validateError.value = true;
    }
};
onMounted(() => {
    token.value = route.query.token;
    email.value = route.query.email;
    if (token.value && email.value) {
        checkToken();
    } else {
        //toast.add({ severity: 'error', summary: 'Error', detail: 'Invalid token or email', life: 3000 });
        //router.push({ name: 'login' });
        validateError.value = true;
    }
});
const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});
</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8 flex flex-column align-items-center" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">Reset Password</div>
                    </div>
                    <Message v-if="validateError" severity="error" icon="pi pi-fw pi-exclamation-circle text-2xl">Your token has expired or invalid</Message>
                    <Message v-if="passwordMatchError" severity="error" icon="pi pi-fw pi-exclamation-circle text-2xl"> Your password unmatch</Message>
                    <Message v-if="resetPasswordError" severity="error" icon="pi pi-fw pi-exclamation-circle text-2xl"> Failed to reset the password</Message>
                    <Message v-if="passwordFormatError" severity="error" icon="pi pi-fw pi-exclamation-circle text-2xl">
                        Password must contain at least one lowercase letter, one uppercase letter, one number, one special character, and be at least 8 characters long!</Message
                    >
                    <div class="field col-10 md:col-8 justify-center mx-auto max-w-lg">
                        <div class="field col-12 md:col-12">
                            <label for="newPassword">New Password</label>
                            <Password v-model="newPassword" :class="{ 'p-invalid': newPasswordError }" toggleMask>
                                <template #header>
                                    <h6>Pick a password</h6>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <p class="mt-2">Suggestions</p>
                                    <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>At least one special character</li>
                                        <li>Minimum 8 characters</li>
                                    </ul>
                                </template>
                            </Password>
                            <small v-if="newPasswordError" class="p-error">New password is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="newPassword1">Confirm Password</label>
                            <Password v-model="newPassword1" :class="{ 'p-invalid': newPassword1Error }" toggleMask>
                                <template #header>
                                    <h6>Pick a password</h6>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <p class="mt-2">Suggestions</p>
                                    <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>At least one special character</li>
                                        <li>Minimum 8 characters</li>
                                    </ul>
                                </template>
                            </Password>
                            <small v-if="newPassword1Error" class="p-error">Confirm password is required</small>
                            <small v-if="!newPassword1Error && !passwordMatch" class="p-error">Passwords do not match</small>
                            <small v-else-if="newPassword && !newPassword1Error && passwordMatch" class="text-green-500">Passwords matched!</small>
                        </div>
                        <div class="col-12 flex justify-content-center py-3 gap-2 button-group">
                            <Button label="Save" class="col-3 md:col-3 py-2" @click="BtnSaveSetting" />
                            <Button severity="secondary" label="Cancel" class="col-3 md:col-3 py-2" @click="BtnCancel" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.button-group {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

@media (max-width: 600px) {
    .button-group {
        flex-direction: column;
        align-items: center;
    }

    .button-group .p-button {
        width: 100%;
    }
}
</style>
