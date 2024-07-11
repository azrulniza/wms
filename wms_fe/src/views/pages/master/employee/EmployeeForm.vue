<script setup>
import EmployeeProfile from './EmployeeProfile.vue';
import EmployeeEmployment from './EmployeeEmployment.vue';
import EmployeeEarning from './EmployeeEarning.vue';
import { ref } from 'vue';

const profile = ref({});

const onProfileSaved = (emp) => {
    profile.value = emp.data;
    console.log('Employee profile saved:', profile.value.id);
};

</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>New Employee</h5>
                    <TabView>
                        <TabPanel header="Profile">
                            <EmployeeProfile @profile-saved="onProfileSaved" />
                        </TabPanel>
                        <TabPanel header="Employment" :disabled="!profile || Object.keys(profile).length === 0">
                            <EmployeeEmployment :profile="profile" />
                        </TabPanel>
                        <TabPanel header="Earning" :disabled="!profile || Object.keys(profile).length === 0">
                            <EmployeeEarning :profile="profile" />
                        </TabPanel>
                        <!-- <TabPanel header="Employment">
                            <EmployeeEmployment />
                        </TabPanel>
                        <TabPanel header="Earning">
                            <EmployeeEarning />
                        </TabPanel> -->
                    </TabView>
                </div>
            </div>
        </div>
    </div>
</template>
