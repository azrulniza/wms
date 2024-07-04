<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';

export default {
    name: 'EmployeeProfile',
    components: {
        InputText,
        InputNumber,
        Button
    },
    setup() {
        const router = useRouter();

        const bankName = ref('');
        const accountNo = ref('');
        const basicSalary = ref(null);
        const bankNameError = ref(false);
        const accountNoError = ref(false);
        const basicSalaryError = ref(false);

        const BtnSaveEmployeeProfile = () => {
            bankNameError.value = !bankName.value;
            accountNoError.value = !accountNo.value;
            basicSalaryError.value = !basicSalary.value;

            if (bankName.value && accountNo.value && basicSalary.value) {
                router.push({ name: 'employeelist' });
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
                            <InputNumber id="TxtBasicSalary" v-model="basicSalary" :useGrouping="false" placeholder="Enter basic salary" :class="{ 'p-invalid': basicSalaryError }" />
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
