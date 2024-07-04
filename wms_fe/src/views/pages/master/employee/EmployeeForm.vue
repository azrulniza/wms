<script setup>
import EmployeeProfile from './EmployeeProfile.vue';
import EmployeeEmployment from './EmployeeEmployment.vue';
import EmployeeEarning from './EmployeeEarning.vue';
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>New Employee</h5>
                    <TabView>
                        <TabPanel header="Profile">
                            <EmployeeProfile />
                        </TabPanel>
                        <TabPanel header="Employment">
                            <EmployeeEmployment />
                        </TabPanel>
                        <TabPanel header="Earning">
                            <EmployeeEarning />
                        </TabPanel>
                    </TabView>
                </div>
            </div>
        </div>
    </div>
</template>
