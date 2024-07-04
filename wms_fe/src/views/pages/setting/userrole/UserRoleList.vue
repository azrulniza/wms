<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

const BtnUserRoleAdd = ref(false);
const BtnUserRoleEdit = ref(false);
const userRoleError = ref(false); // Flag to track if userrole input is empty for adding
const editUserRoleError = ref(false); // Flag to track if userrole input is empty for editing

const DFUserRole = ref('');
const editUserRole = ref('');
const selectedUserRole = ref(null);

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
});

const userRoles = ref([
    {
        DfUserRoleID: 1,
        DfUserRole: 'Super Admin'
    },
    {
        DfUserRoleID: 2,
        DfUserRole: 'Admin'
    }
]);

const filters = ref({
    is_active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const BtnUserRoleAddSave = () => {
    userRoleError.value = !DFUserRole.value.trim();
    if (!userRoleError.value) {
        userRoles.value.push({
            DfUserRoleID: userRoles.value.length + 1,
            DfUserRole: DFUserRole.value.trim()
        });
        DFUserRole.value = '';
        BtnUserRoleAdd.value = false;
    }
};

const BtnUserRoleEditSave = () => {
    editUserRoleError.value = !editUserRole.value.trim();
    if (!editUserRoleError.value) {
        selectedUserRole.value.DfUserRole = editUserRole.value.trim();
        BtnUserRoleEdit.value = false;
    }
};

const BtnUserRoleDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this userrole?',
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: 'p-button-danger p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: () => {
            userRoles.value = userRoles.value.filter((userrole) => userrole !== selectedUserRole.value);
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        }
    });
};

const openEditDialog = (userrole) => {
    selectedUserRole.value = userrole;
    editUserRole.value = userrole.DfUserRole;
    BtnUserRoleEdit.value = true;
};
</script>

<template>
    <div class="card">
        <div v-if="!isMobileView" class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" label="Add User Role" @click="BtnUserRoleAdd = true" />
        </div>
        <div v-else class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" class="ml-auto" @click="BtnUserRoleAdd = true" v-tooltip.top="'Add User Role'" />
        </div>
        <Dialog v-model:visible="BtnUserRoleAdd" modal header="Add User Role" class="col-6 md:col-4">
            <div class="flex flex-column mb-3">
                <label for="DfUserRoleAdd" class="font-semibold w-6rem mb-3">User Role</label>
                <InputText v-model="DFUserRole" id="DfUserRoleAdd" class="flex-auto mb-1" autocomplete="off" :class="{ 'p-invalid': userRoleError }" />
                <small v-if="userRoleError" class="p-error">User Role is required!</small>
            </div>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="BtnUserRoleAdd = false"></Button>
                <Button type="button" label="Save" @click="BtnUserRoleAddSave"></Button>
            </div>
        </Dialog>

        <DataTable v-model:filters="filters" filterDisplay="menu" :value="userRoles" class="md:col-12" tableStyle="min-width: 50rem">
            <Column class="col-1" field="DfUserRoleID" header="Id"></Column>
            <Column class="col-9" field="DfUserRole" header="User Role"></Column>
            <Column class="md:col-1" field="is_active" dataType="boolean" header="Active">
                <template #body="{ data }">
                    <i class="pi" :class="{ 'pi-check-circle text-green-500': data.verified, 'pi-times-circle text-red-400': !data.verified }"></i>
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <TriStateCheckbox v-model="filterModel.value" @change="filterCallback()" />
                </template>
            </Column>
            <Column class="col-1" field="action" header="Action">
                <template #body="{ data }">
                    <div class="flex justify-content-center">
                        <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="openEditDialog(data)" rounded />
                        <Dialog v-model:visible="BtnUserRoleEdit" modal header="Edit User Role" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfUserRoleEdit" class="font-semibold w-6rem">User Role</label>
                                <InputText v-model="editUserRole" id="DfUserRoleEdit" class="flex-auto" autocomplete="off" />
                                <small v-if="editUserRoleError" class="p-error">User Role is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnUserRoleEdit = false"></Button>
                                <Button type="button" label="Save" @click="BtnUserRoleEditSave"></Button>
                            </div>
                        </Dialog>
                        <Toast />
                        <ConfirmPopup></ConfirmPopup>
                        <Button @click="BtnUserRoleDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>
.p-invalid {
    border-color: var(--red-500);
}
</style>
