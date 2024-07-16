<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const API_URL = import.meta.env.VITE_BASE_URL;
const router = useRouter();
const toast = useToast();

const userEmail = ref('');
const userPassword = ref('');
const userRole = ref();

const selectedUserRole = ref([]);

const userEmailError = ref(false);
const userPasswordError = ref(false);
const userRoleError = ref(false);
const userEmailFormat = ref(false);
const userPasswordFormat = ref(false);

const validate = () => {
    // Reset error states
    userEmailError.value = false;
    userPasswordError.value = false;
    userRoleError.value = false;
    userPasswordFormat.value = false;

    // Validate email
    if (!userEmail.value.trim()) {
        userEmailError.value = true; // Email is required
    } else if (!isValidEmail(userEmail.value)) {
        userEmailFormat.value = true; // Invalid email format
    }

    // Validate password
    if (!userPassword.value) {
        userPasswordError.value = true; // Password is required
    } else if (!isValidPassword(userPassword.value)) {
        userPasswordFormat.value = true; // Invalid password format
    }

    // Validate user role
    userRoleError.value = !userRole.value;

    // Return overall validation result
    return !userEmailError.value && !userPasswordError.value && !userRoleError.value && !userPasswordFormat.value && !userEmailFormat.value;
};

const isValidEmail = (email) => {
    // Regular expression for basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

const isValidPassword = (password) => {
    // Regular expression for password validation
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
};

const getUserRoles = async () => {
    try {
        const response = await axios.get(`${API_URL}/user-roles`);
        // Map the API response to match the format expected by the Dropdown component
        selectedUserRole.value = response.data.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.role
            }));
    } catch (error) {
        console.error('Error fetching seniority details:', error);
    }
};

const BtnUserAdd = async () => {
    // Validate form inputs
    if (!validate()) {
        return;
    }

    try {
        const response = await axios.post(`${API_URL}/register`, {
            user_email: userEmail.value,
            user_password: userPassword.value,
            user_role: userRole.value.id,
            user_password_confirmation: userPassword.value
        });

        //Handle API response as needed
        if (response.data.id > 0) {
            //reset form fields after successful submission
            userEmail.value = '';
            userPassword.value = '';
            userRole.value = '';
            toast.add({ severity: 'success', summary: 'Success', detail: 'User Access added', life: 3000 });

            router.push({ name: 'useraccesslist' });
        } else {
            console.log(response.data.message);
            toast.add({ severity: 'error', summary: 'Error', detail: response.data.error, life: 4000 });
        }

        // Handle API response as needed
        console.log('User Access added:', response);
    } catch (error) {
        console.error('Failed to add user:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to add user', life: 4000 });
        // Optionally, show a toast or error message here
    }
};

const BtnCancel = () => {
    router.push({ name: 'useraccesslist' });
};

onMounted(() => {
    getUserRoles();
});
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Add User</h5>
                    <div class="p-fluid formgrid grid flex justify-content-center">
                        <div class="field col-12 md:col-8">
                            <div class="field col-12">
                                <label for="user_email">Email</label>
                                <InputText v-model="userEmail" id="DfUserEmail" type="text" placeholder="Enter Email" :class="({ 'p-invalid': userEmailError }, { 'p-invalid': userEmailFormat })" />
                                <small v-if="userEmailError" class="p-error">Email is required!</small>
                                <small v-if="userEmailFormat" class="p-error">Invalid email format!</small>
                            </div>
                            <div class="field col-12">
                                <label for="user_password">Password</label>
                                <Password v-model="userPassword" id="DfUserPassword" placeholder="Password" toggleMask :class="({ 'p-invalid': userPasswordError }, { 'p-invalid': userPasswordFormat })">
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
                                <small v-if="userPasswordError" class="p-error">Password is required!</small>
                                <small v-if="userPasswordFormat" class="p-error">Password must contain at least one lowercase letter, one uppercase letter, one number, one special character, and be at least 8 characters long!</small>
                            </div>
                            <div class="field col-12">
                                <label for="user_role">User Role</label>
                                <Dropdown id="DfUserRole" v-model="userRole" :options="selectedUserRole" optionLabel="name" placeholder="Select User Role" :class="{ 'p-invalid': userRoleError }" />
                                <small v-if="userRoleError" class="p-error">User Role is required!</small>
                            </div>
                            <div class="col-12 flex justify-content-center py-3 gap-2 button-group">
                                <Button label="Save" class="col-3 md:col-3 py-2" @click="BtnUserAdd" />
                                <Button severity="secondary" label="Cancel" class="col-3 md:col-3 py-2" @click="BtnCancel" />
                            </div>
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

@media (max-width: 500px) {
    .button-group {
        flex-direction: column;
        align-items: center;
    }

    .button-group .p-button {
        width: 100%;
    }
}
</style>
