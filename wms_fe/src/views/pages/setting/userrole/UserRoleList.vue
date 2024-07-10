<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';
import { userRoleList } from '../../../../service/Setting';
import axios from 'axios';

const confirm = useConfirm();
const toast = useToast();

const filters1 = ref(null);
const BtnUserRoleAdd = ref(false);
const BtnUserRoleEdit = ref(false);
const userRoleError = ref(false); // Flag to track if userrole input is empty for adding
const editUserRoleError = ref(false); // Flag to track if userrole input is empty for editing

const user_roles = ref([]);
const DFUserRole = ref('');
const editUserRole = ref('');
const selectedUserRole = ref(null);

const isMobileView = ref(window.innerWidth < 991);

const API_URL = import.meta.env.VITE_BASE_URL;

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

const getUserRoleList = async () => {
    try {
        const result = await userRoleList();
        // Add runningNumber property to each employee item
        user_roles.value = result.map((item, index) => ({
            ...item,
            runningNumber: index + 1,
            //is_active: item.active === 1 ? "Active" : "Inactive"
            is_active: item.active === 1 ? true : false
        }));
        console.log('User role list:', user_roles.value);
    } catch (error) {
        console.error('Failed to fetch user role list:', error);
    }
};

onMounted(() => {
    getUserRoleList();
    window.addEventListener('resize', onResize);
});

const initFilters1 = () => {
    filters1.value = {
        is_active: { value: null, matchMode: FilterMatchMode.EQUALS },
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};

initFilters1();

const BtnUserRoleAddSave = async () => {
    // userRoleError.value = !DFUserRole.value.trim();
    // if (!userRoleError.value) {
    //     //await insertUserRole(DFUserRole.value.trim());

    //     const response = await axios.post(`http://127.0.0.1:8000/api/user-roles/insert`, {
    //         role: DFUserRole.value.trim()
    //     });
    //     return response.data;

    //    // DFUserRole.value = '';
    //     BtnUserRoleAdd.value = false;
    // }

    try {
        userRoleError.value = !DFUserRole.value.trim();
        if (!userRoleError.value) {
            const response = await axios.post(`${API_URL}/user-roles/insert`, {
                role: DFUserRole.value.trim()
            });

            // Handle the response as needed
            toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });

            DFUserRole.value = '';
            BtnUserRoleAdd.value = false;

            // get list of user roles
            await getUserRoleList();
        }
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to add user role', life: 3000 });
        console.error('Error adding user role:', error);
        throw error; // Throw the error to handle it in the component
    }
};

// const BtnUserRoleEditSave = () => {
//     editUserRoleError.value = !editUserRole.value.trim();
//     if (!editUserRoleError.value) {
//         selectedUserRole.value.DfUserRole = editUserRole.value.trim();
//         BtnUserRoleEdit.value = false;
//     }
// };

const BtnUserRoleEditSave = async () => {
    try {
        editUserRoleError.value = !editUserRole.value.trim();
        if (!editUserRoleError.value) {
            const response = await axios.put(`${API_URL}/user-roles/update`, {
                id: selectedUserRole.value.id,
                role: editUserRole.value.trim()
            });

            toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });

            BtnUserRoleEdit.value = false;

            await getUserRoleList();
        }
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update user role', life: 3000 });
        console.error('Error updating user role:', error);
    }
};

const BtnUserRoleDelete = (event, userrole) => {
    const actionMessage = userrole.active ? 'delete' : 'reactivate';
    const message = `Do you want to ${actionMessage} this user role?`;

    confirm.require({
        target: event.currentTarget,
        message: message,
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: 'p-button-danger p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: actionMessage.charAt(0).toUpperCase() + actionMessage.slice(1),
        accept: async () => {
            try {
                const response = await axios.put(`${API_URL}/user-roles/delete`, {
                    id: userrole.id,
                    active: userrole.active === 1 ? 0 : 1
                });

                toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
                toast.add({ severity: 'info', summary: 'Confirmed', detail: response.data.message, life: 3000 });

                BtnUserRoleEdit.value = false;

                await getUserRoleList();
            } catch (error) {
                toast.add({ severity: 'error', summary: 'Error', detail: `Failed to ${actionMessage} user role`, life: 3000 });
                console.error(`Error ${actionMessage} user role:`, error);
            }
        }
    });
};

const openEditDialog = (userrole) => {
    selectedUserRole.value = userrole;
    editUserRole.value = userrole.role;
    BtnUserRoleEdit.value = true;
};
</script>

<template>
    <div class="card">
        <DataTable v-model:filters="filters1" filterDisplay="menu" :filters="filters1" paginator :rows="10" :value="user_roles" class="md:col-12" removableSort :globalFilterFields="['role', 'is_active', 'runningNumber']" scrollable>
            <!-- <template #header> -->
            <div class="flex justify-content-between">
                <div v-if="!isMobileView" class="flex align-items-center">
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters1['global'].value" placeholder="Keyword Search" />
                    </IconField>
                </div>
                <div v-else class="flex align-items-center">
                    <IconField iconPosition="left">
                        <InputText v-model="filters1['global'].value" placeholder="Keyword Search" class="col-12" />
                    </IconField>
                </div>

                <div v-if="!isMobileView" class="flex justify-content-end">
                    <Button icon="pi pi-plus" label="Add User Role" @click="BtnUserRoleAdd = true" />
                </div>
                <div v-else class="flex justify-content-between ml-1">
                    <Button icon="pi pi-plus" class="ml-auto" @click="BtnUserRoleAdd = true" v-tooltip.top="'Add User Role'" />
                </div>
                <Dialog v-model:visible="BtnUserRoleAdd" modal header="Add User Role" class="col-6 md:col-4">
                    <div class="flex flex-column mb-3">
                        <label for="role" class="font-semibold w-6rem mb-3">User Role</label>
                        <InputText v-model="DFUserRole" id="role" class="flex-auto mb-1" autocomplete="off" :class="{ 'p-invalid': userRoleError }" />
                        <small v-if="userRoleError" class="p-error">User Role is required!</small>
                    </div>
                    <div class="flex justify-content-end gap-2">
                        <Button type="button" label="Cancel" severity="secondary" @click="BtnUserRoleAdd = false"></Button>
                        <Button type="button" label="Save" @click="BtnUserRoleAddSave"></Button>
                    </div>
                </Dialog>
            </div>
            <!-- </template> -->

            <template #empty> No record found. </template>
            <Column class="col-1" field="runningNumber" header="No." sortable></Column>
            <Column class="col-9" field="role" header="User Role" sortable></Column>

            <Column class="md:col-1" field="is_active" dataType="boolean" header="Active">
                <!-- <template #body="{ data }">
                    <Tag :value="data.is_active" :severity="getSeverity(data.is_active)" />
                </template> -->

                <template #body="{ data }">
                    <i class="pi" :class="{ 'pi-check-circle text-green-500': data.active, 'pi-times-circle text-red-400': !data.active }"> </i>
                </template>
                <template #filter="{ filterModel }">
                    <label for="is_active-filter" class="font-bold"> Is Active </label>
                    <TriStateCheckbox v-model="filterModel.value" inputId="is_active" />
                </template>
            </Column>

            <Column class="col-1" field="action" header="Action">
                <template #body="{ data }">
                    <div class="flex justify-content-center">
                        <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="openEditDialog(data)" rounded />
                        <Dialog v-model:visible="BtnUserRoleEdit" modal header="Edit User Role" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfUserRoleEdit" class="font-semibold w-6rem">User Role</label>
                                <InputText v-model="editUserRole" id="role" class="flex-auto" autocomplete="off" />
                                <small v-if="editUserRoleError" class="p-error">User Role is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnUserRoleEdit = false"></Button>
                                <Button type="button" label="Save" @click="BtnUserRoleEditSave"></Button>
                            </div>
                        </Dialog>

                        <ConfirmPopup></ConfirmPopup>
                        <!-- <Button @click="(event) => BtnUserRoleDelete(event, data)" icon="pi pi-trash" severity="danger"
                            v-tooltip.top="'delete'" rounded></Button> -->
                        <Button @click="(event) => BtnUserRoleDelete(event, data)" :icon="data.active ? 'pi pi-trash' : 'pi pi-refresh'" :severity="data.active ? 'danger' : 'secondary'" v-tooltip.top="data.active ? 'delete' : 'reactivate'" rounded />
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
