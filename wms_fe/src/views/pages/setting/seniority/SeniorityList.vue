<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

const BtnSeniorityAdd = ref(false);
const BtnSeniorityEdit = ref(false);
const newSeniority = ref('');
const editSeniority = ref('');
const newSeniorityError = ref(false);
const editSeniorityError = ref(false);

const seniorities = ref([
    {
        DfSeniorityID: 1,
        DfSeniority: 'Senior'
    },
    {
        DfSeniorityID: 2,
        DfSeniority: 'Junior'
    }
]);

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
});

const filters = ref({
    is_active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const BtnSeniorityDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this seniority?',
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: 'p-button-danger p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: () => {
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        }
    });
};

const saveNewSeniority = () => {
    if (newSeniority.value.trim() === '') {
        newSeniorityError.value = true;
        return;
    }
    seniorities.value.push({
        DfSeniorityID: seniorities.value.length + 1,
        DfSeniority: newSeniority.value
    });
    newSeniority.value = '';
    BtnSeniorityAdd.value = false;
    newSeniorityError.value = false;
};

const saveEditSeniority = () => {
    if (editSeniority.value.trim() === '') {
        editSeniorityError.value = true;
        return;
    }
    // Assuming you have a way to determine which seniority to edit
    // Update the appropriate seniority
    BtnSeniorityEdit.value = false;
    editSeniorityError.value = false;
};
</script>

<template>
    <div class="card">
        <div v-if="!isMobileView" class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" label="Add Seniority" @click="BtnSeniorityAdd = true" />
        </div>
        <div v-else class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" class="ml-auto" @click="BtnSeniorityAdd = true" v-tooltip.top="'Add Seniority'" />
        </div>
        <Dialog v-model:visible="BtnSeniorityAdd" modal header="Add Seniority" class="col-6 md:col-4">
            <div class="flex flex-column gap-3 mb-3">
                <label for="DfSeniorityAdd" class="font-semibold w-6rem">Seniority</label>
                <InputText id="DfSeniorityAdd" class="flex-auto" autocomplete="off" v-model="newSeniority" />
                <small v-if="newSeniorityError" class="p-error">Seniority is required!</small>
            </div>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="BtnSeniorityAdd = false"></Button>
                <Button type="button" label="Save" @click="saveNewSeniority"></Button>
            </div>
        </Dialog>
        <DataTable v-model:filters="filters" filterDisplay="menu" :value="seniorities" class="md:col-12" tableStyle="min-width: 50rem">
            <Column class="col-1" field="DfSeniorityID" header="Id"></Column>
            <Column class="col-9" field="DfSeniority" header="Seniority"></Column>
            <Column class="md:col-1" field="is_active" dataType="boolean" header="Active">
                <template #body="{ data }">
                    <i class="pi" :class="{ 'pi-check-circle text-green-500': data.verified, 'pi-times-circle text-red-400': !data.verified }"></i>
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <TriStateCheckbox v-model="filterModel.value" @change="filterCallback()" />
                </template>
            </Column>
            <Column class="col-1" field="action" header="Action">
                <template #body="">
                    <div class="flex justify-content-center">
                        <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnSeniorityEdit = true" rounded />

                        <Dialog v-model:visible="BtnSeniorityEdit" modal header="Edit Seniority" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfSeniorityEdit" class="font-semibold w-6rem">Seniority</label>
                                <InputText id="DfSeniorityEdit" class="flex-auto" autocomplete="off" v-model="editSeniority" />
                                <small v-if="editSeniorityError" class="p-error">Seniority is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnSeniorityEdit = false"></Button>
                                <Button type="button" label="Save" @click="saveEditSeniority"></Button>
                            </div>
                        </Dialog>
                        <Toast />
                        <ConfirmPopup></ConfirmPopup>
                        <Button @click="BtnSeniorityDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
