<script setup>
import UserRoleList from './userrole/UserRoleList.vue';
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>User Access Setting</h5>
                    <TabView>
                        <TabPanel header="User Role">
                            <UserRoleList />
                        </TabPanel>
                    </TabView>
                </div>
            </div>
        </div>
    </div>
</template>
