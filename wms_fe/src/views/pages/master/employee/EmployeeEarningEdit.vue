<script>
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;

export default {
    name: 'EmployeeEarning',
    components: {
        InputText,
        InputNumber,
        Button
    },
    props: {
        earnings: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const router = useRouter();
        const route = useRoute();
        const toast = useToast();

        const bankName = ref(props.earnings.bank_name || '');
        const accountNo = ref(props.earnings.bank_acc || '');
        const basicSalary = ref(props.earnings.basic_salary || null);
        const bankNameError = ref(false);
        const accountNoError = ref(false);
        const basicSalaryError = ref(false);

        // Watch for changes in the earnings prop to update local state
        watch(
            () => props.earnings,
            (newEarnings) => {
                bankName.value = newEarnings.bank_name || '';
                accountNo.value = newEarnings.bank_acc || '';
                basicSalary.value = newEarnings.basic_salary || null;
            }
        );

        const BtnSaveEmployeeProfile = async () => {
            bankNameError.value = !bankName.value;
            accountNoError.value = !accountNo.value;
            basicSalaryError.value = !basicSalary.value;

            if (bankName.value && accountNo.value && basicSalary.value) {
                // router.push({ name: 'employeelist' });
                try {
                    await updateEmploymeeEarning();
                    //router.push({ name: 'employeelist' });
                } catch (error) {
                    console.error('Error updating employee earning:', error);
                }
                console.log('Saving employee earning...');
            }
        };

        const updateEmploymeeEarning = async () => {
            try {
                const payload = {
                    employee_id: route.params.id,
                    bank_name: bankName.value,
                    bank_acc: accountNo.value,
                    basic_salary: basicSalary.value
                };

                const response = await axios.post(`${API_URL}/earnings/insert`, payload);
                toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
                console.log('Employee earning updated successfully:', response.data);
            } catch (error) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update employee earning', life: 3000 });
                console.error('Error updating employee earning:', error);
            }
        };

        const BtnCancelEmployeeProfile = () => {
            router.push({ name: 'employeelist' });
        };

        return {
            bankName,
            accountNo,
            basicSalary,
            bankNameError,
            accountNoError,
            basicSalaryError,
            BtnSaveEmployeeProfile,
            BtnCancelEmployeeProfile
        };
    }
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="col-12 md:col-12">
                <div class="p-fluid formgrid grid">
                    <div class="field col-10 md:col-8 justify-center mx-auto max-w-lg">
                        <div class="field col-12 md:col-12">
                            <label for="TxtBankName">Bank Name</label>
                            <InputText id="TxtBankName" type="text" v-model="bankName" placeholder="Enter bank name" :class="{ 'p-invalid': bankNameError }" />
                            <small v-if="bankNameError" class="p-error">Bank Name is required!</small>
                        </div>
                        <div class="field col-12">
                            <label for="TxtAccountNo">Account No</label>
                            <InputText id="TxtAccountNo" v-model="accountNo" placeholder="Enter account number" :class="{ 'p-invalid': accountNoError }" />
                            <small v-if="accountNoError" class="p-error">Account No is required!</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtBasicSalary">Basic Salary</label>
                            <InputNumber id="TxtBasicSalary" v-model="basicSalary" :useGrouping="false" :minFractionDigits="2" placeholder="Enter basic salary" :class="{ 'p-invalid': basicSalaryError }" />
                            <small v-if="basicSalaryError" class="p-error">Basic Salary is required!</small>
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
