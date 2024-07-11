<script>
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import EmployeeEditProfile from './EmployeeEditProfile.vue';
import EmployeeEditEmployment from './EmployeeEditEmployment.vue';
import EmployeeEarningEdit from './EmployeeEarningEdit.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;

export default {
    name: 'EmployeeForm',
    components: { EmployeeEditProfile, EmployeeEditEmployment, EmployeeEarningEdit },
    setup() {
        const router = useRouter();
        const route = useRoute();
        const employee = ref(null);
        const earning = ref(null);
        const toast = useToast();

        const fetchEmployeeDetails = async () => {
            try {
                const response = await axios.get(`${API_URL}/employee/details/${route.params.id}`);
                employee.value = response.data;
            } catch (error) {
                console.error('Error fetching employee details:', error);
            }
        };

        const fetchEmployeeEarningDetails = async () => {
            try {
                const response = await axios.get(`${API_URL}/earnings/latest/${route.params.id}`);
                earning.value = response.data;
            } catch (error) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Error fetching employee earning', life: 3000 });
                console.error('Error fetching employee earning:', error);
            }
        };

        onMounted(() => {
            fetchEmployeeDetails();
            fetchEmployeeEarningDetails();
        });

        const saveEmployee = () => {
            router.push({ name: 'employeelist' });
        };

        const cancelEmployee = () => {
            router.push({ name: 'employeelist' });
        };

        return {
            employee,
            earning,
            saveEmployee,
            cancelEmployee
        };
    }
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Edit Employee</h5>
                    <TabView>
                        <TabPanel header="Profile">
                            <!-- <EmployeeEditProfile :profile="employee.value.profile" /> -->
                            <EmployeeEditProfile v-if="employee && employee.profile" :profile="employee.profile" />
                            <EmployeeEditProfile v-else :profile="{}" />
                        </TabPanel>
                        <TabPanel header="Employment">
                            <!-- <EmployeeEditEmployment :employment="employee.value.employment" /> -->
                            <EmployeeEditEmployment v-if="employee && employee.employment" :employment="employee.employment" />
                            <EmployeeEditEmployment v-else :employment="{}" />
                        </TabPanel>
                        <TabPanel header="Earning">
                            <!-- <EmployeeEarningEdit :earnings="earning.value" /> -->
                            <EmployeeEarningEdit v-if="earning && earning.id" :earnings="earning" />
                            <EmployeeEarningEdit v-else :earnings="{}" />
                        </TabPanel>
                    </TabView>
                </div>
            </div>
        </div>
    </div>
</template>
