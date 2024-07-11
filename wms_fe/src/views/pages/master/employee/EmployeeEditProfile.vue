<script setup>
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputMask from 'primevue/inputmask';
import InputNumber from 'primevue/inputnumber';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;

// Define props
const props = defineProps({
    profile: {
        type: Object,
        required: true
    }
});

const router = useRouter();
const route = useRoute();
const toast = useToast();

const name = ref('');
const icNo = ref('');
const email = ref('');
const staffId = ref('');
const phoneNo = ref('');
const nameError = ref(false);
const icNoError = ref(false);
const emailError = ref(false);
const staffIdError = ref(false);
const phoneNoError = ref(false);

// Watch for changes in the employee prop and update state
watch(
    () => props.profile,
    (newEmployee) => {
        name.value = newEmployee.employee_name || '';
        icNo.value = newEmployee.employee_ic_no || '';
        email.value = newEmployee.employee_email || '';
        staffId.value = newEmployee.employee_staff_id || '';
        phoneNo.value = newEmployee.employee_phone_no || '';
    },
    { immediate: true }
);

const BtnSaveEmployeeProfile = async () => {
    nameError.value = !name.value;
    icNoError.value = !icNo.value;
    emailError.value = !email.value;
    staffIdError.value = !staffId.value;
    phoneNoError.value = !phoneNo.value;

    if (name.value && icNo.value && email.value && staffId.value && phoneNo.value) {
        //router.push({ name: 'employeelist' });
        try {
            await updateEmployeeProfile();
            //router.push({ name: 'employeelist' });
        } catch (error) {
            console.error('Error updating employee profile:', error);
        }
        console.log('Saving employee profile...');
    }
};

const updateEmployeeProfile = async () => {
    try {
        const payload = {
            employee_name: name.value,
            employee_email: email.value,
            employee_phone_no: phoneNo.value,
            employee_staff_id: staffId.value,
            employee_ic_no: icNo.value,
            id: route.params.id
        };

        const response = await axios.put(`${API_URL}/employee/update-profile`, payload);
        toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        console.log('Employmee profile updated successfully:', response.data);
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update employee profile', life: 3000 });
        console.error('Error updating employee profile:', error);
    }
};

const BtnCancelEmployeeProfile = () => {
    router.push({ name: 'employeelist' });
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="col-12 md:col-12">
                <div class="p-fluid formgrid grid">
                    <div class="field col-10 md:col-8 justify-center mx-auto max-w-lg">
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeeName">Name</label>
                            <InputText id="TxtEmployeeName" type="text" v-model="name" placeholder="Enter employee name" :class="{ 'p-invalid': nameError }" />
                            <small v-if="nameError" class="p-error">Name is required!</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeeICNo">IC No.</label>
                            <InputMask id="TxtEmployeeICNo" mask="999999-99-9999" v-model="icNo" placeholder="Enter IC No." v-tooltip.right="'Ex : 999999-99-9999'" :class="{ 'p-invalid': icNoError }" />
                            <small v-if="icNoError" class="p-error">IC No. is required!</small>
                        </div>
                        <div class="field col-12">
                            <label for="TxtEmployeeEmail">Email</label>
                            <InputText id="TxtEmployeeEmail" v-model="email" placeholder="Enter employee email address" :class="{ 'p-invalid': emailError }" />
                            <small v-if="emailError" class="p-error">Email is required!</small>
                        </div>
                        <div class="field col-12">
                            <label for="TxtEmployeeStaffId">Staff ID</label>
                            <InputText id="TxtEmployeeStaffId" v-model="staffId" placeholder="Enter Staff ID" :class="{ 'p-invalid': staffIdError }" />
                            <small v-if="staffIdError" class="p-error">Staff ID is required!</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeePhoneNo">Contact No.</label>
                            <InputNumber id="TxtEmployeePhoneNo" v-model="phoneNo" :useGrouping="false" placeholder="Enter employee contact number" :class="{ 'p-invalid': phoneNoError }" />
                            <small v-if="phoneNoError" class="p-error">Contact No. is required!</small>
                        </div>
                        <div class="field col-8 md:col-4 mx-auto flex gap-4">
                            <Button type="button" label="Save" class="w-full" @click="BtnSaveEmployeeProfile" />
                            <Button type="button" severity="secondary" label="Cancel" class="w-full" @click="BtnCancelEmployeeProfile" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.p-invalid {
    border-color: var(--red-500);
}
</style>
