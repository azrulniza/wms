<script setup>
import { onMounted, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Menu from 'primevue/menu';
import Toast from 'primevue/toast';
import { useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import TriStateCheckbox from 'primevue/tristatecheckbox';
import { FilterMatchMode } from 'primevue/api';
import axios from 'axios';

const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const API_URL = import.meta.env.VITE_BASE_URL;

const users = ref([]);
//const DFUserRole = ref('');
//const editUserRole = ref('');
//const selectedUserRole = ref(null);

const getUserAccessList = async () => {
    try {
        const response = await axios.get(`${API_URL}/user-access`);
        // Add runningNumber property to each employee item
        users.value = response.data.data.map((item, index) => ({
            ...item,
            runningNumber: index + 1,
            is_active: item.active === 1 ? true : false
        }));
        //console.log('User access list:', users.value);
    } catch (error) {
        console.error('Failed to fetch user access list:', error);
    }
};

const filters = ref({
    active: { value: null, matchMode: FilterMatchMode.EQUALS },
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
    getUserAccessList();
});

const BtnUserDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to deactivate this record?',
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: 'p-button-danger p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: 'deactivate',
        accept: () => {
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        }
    });
};

const BtnUserAdd = () => {
    router.push({ name: 'useraccessform' });
};

const BtnUserEdit = (user) => {
    router.push({ name: 'useraccessedit', params: { id: user.id } });
};

const menuItems = [
    {
        label: 'Add Agency',
        icon: 'pi pi-plus',
        command: () => {
            BtnUserAdd();
        }
    }
];
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="flex justify-content-between align-items-center">
                    <h5 class="col-6 md:col-6 mb-0">User Access</h5>
                    <div v-if="!isMobileView" class="col-6 md:col-6 flex justify-content-end">
                        <Button label="Add User" icon="pi pi-plus" @click="BtnUserAdd" />
                    </div>
                    <div v-else>
                        <Menu :model="menuItems" popup ref="menu" />
                        <Button icon="p-link pi pi-ellipsis-v" class="ml-auto" severity="secondary" @click="$refs.menu.toggle($event)" link />
                    </div>
                </div>

                <DataTable
                    class="md:col-12 mt-0"
                    v-model:filters="filters"
                    :value="users"
                    filterDisplay="menu"
                    :paginator="true"
                    :row-hover="false"
                    :rows="10"
                    removableSort
                    :globalFilterFields="['user_name', 'user_role_name', 'user_email', 'runningNumber']"
                    scrollable
                >
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                    </IconField>
                    <template #empty> No record found. </template>

                    <Column class="md:col-1" field="runningNumber" id="DfUserAcessID" header="No." sortable />
                    <Column class="md:col-3" field="user_name" id="DfUserName" header="Username" sortable />
                    <Column class="md:col-3" field="user_email" id="DfUserName" header="Email" sortable />
                    <Column class="md:col-4" field="user_role_name" id="DfUserRole" header="User Role" sortable />
                    <Column class="md:col-1" field="active" header="Status" id="DfUserStatus" dataType="boolean" bodyClass="text-center">
                        <template #body="{ data }">
                            <i class="pi" :class="{ 'pi-check-circle text-green-500': data.active, 'pi-times-circle text-red-400': !data.active }"></i>
                        </template>

                        <template #filter="{ filterModel }">
                            <label for="is_active-filter" class="font-bold"> Is Active </label>
                            <TriStateCheckbox v-model="filterModel.value" inputId="is_active" />
                        </template>
                    </Column>
                    <Column class="md:col-1" field="action" header="Action">
                        <template #body="{ data }">
                            <div class="flex justify-content-center">
                                <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnUserEdit(data)" rounded />
                                <Toast />
                                <ConfirmPopup></ConfirmPopup>
                                <Button
                                    @click="(event) => BtnUserDelete(event, data)"
                                    :icon="data.active ? 'pi pi-trash' : 'pi pi-refresh'"
                                    :severity="data.active ? 'danger' : 'secondary'"
                                    v-tooltip.top="data.active ? 'delete' : 'reactivate'"
                                    rounded
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<style scoped>
.uploader-ui {
  border-width: 2px;
  border-style: solid;
  border-radius: 66px;
  color: #6c757d;
}

@media (max-width: 991px) {
    .toplist {
        flex-direction: column;
    }
}
</style>
