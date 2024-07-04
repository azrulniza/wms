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

const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const users = ref([
    {
        id: 1,
        user_name: 'admin',
        user_role: 'Administrator',
        active: 1
    }
]);

const filters = ref({
    active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
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

                <DataTable class="md:col-12 mt-0" v-model:filters="filters" :value="users" filterDisplay="menu" :paginator="true" :row-hover="false" :rows="10">
                    <Column class="md:col-1" field="id" id="DfUserAcessID" header="ID" />
                    <Column class="md:col-3" field="user_name" id="DfUserName" header="Username" />
                    <Column class="md:col-4" field="user_role" id="DfUserRole" header="User Role" />
                    <Column class="md:col-1" field="active" header="Status" id="DfUserStatus" dataType="boolean" bodyClass="text-center">
                        <template #body="{ data }">
                            <i class="pi" :class="{ 'pi-check-circle text-green-500': data.verified, 'pi-times-circle text-red-400': !data.verified }"></i>
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <TriStateCheckbox v-model="filterModel.value" @change="filterCallback()" />
                        </template>
                    </Column>
                    <Column class="md:col-1" field="action" header="Action">
                        <template #body="slotProps">
                            <div class="flex justify-content-center">
                                <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnUserEdit(slotProps.data)" rounded />
                                <Toast />
                                <ConfirmPopup></ConfirmPopup>
                                <Button @click="BtnUserDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'deactivate'" rounded></Button>
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
