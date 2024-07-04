<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { agencyCreate } from '@/service/Administration'; // Import agencyCreate function

const router = useRouter();

const userName = ref('');
const userPassword = ref('');
const userRole = ref();

const selectedUserRole = ref([{ name: 'Admin', code: 'Admin' }]);

const userNameError = ref(false);
const userPasswordError = ref(false);
const userRoleError = ref(false);

const validate = () => {
    userNameError.value = !userName.value;
    userPasswordError.value = !userPassword.value;
    userRoleError.value = !userRole.value;
    return !userNameError.value && !userPasswordError.value && !userRoleError.value;
};

const BtnUserAdd = async () => {
    // Validate form inputs
    if (!validate()) {
        return;
    }

    // Prepare agency data
    const data = {
        agency_name: userName.value,
        agency_address: userPassword.value,
        agency_phone_no: userRole.value
    };

    try {
        // Call agencyCreate API function
        const result = await agencyCreate(data);
        // Handle API response as needed
        console.log('Agency added:', result);

        // Optionally, you can reset form fields after successful submission
        userName.value = '';
        userPassword.value = '';
        userRole.value = '';

        // Navigate to employerlist route after successful save
        router.push({ name: 'employerlist' });
    } catch (error) {
        console.error('Failed to add user:', error);
        // Optionally, show a toast or error message here
    }
};

const BtnCancel = () => {
    router.push({ name: 'dashboard' });
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Profile Details</h5>
                    <div class="p-fluid formgrid grid flex justify-content-center">
                        <div class="field col-12 md:col-8">
                            <div class="field col-12 flex justify-content-center">
                                <Avatar label="MR" class="mr-2" size="xlarge" shape="circle" />
                            </div>
                            <div class="field col-12">
                                <label for="user_name">Username</label>
                                <InputText v-model="userName" id="DfUserName" type="text" placeholder="Enter Username" :class="{ 'p-invalid': userNameError }" />
                                <small v-if="userNameError" class="p-error">Username is required!</small>
                            </div>
                            <div class="field col-12">
                                <label for="user_password">Password</label>
                                <Password v-model="userPassword" id="DfUserPassword" placeholder="Password" toggleMask :class="{ 'p-invalid': userPasswordError }">
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
                                            <li>Minimum 8 characters</li>
                                        </ul>
                                    </template>
                                </Password>
                                <small v-if="userPasswordError" class="p-error">Password is required!</small>
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
