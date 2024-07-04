<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted } from 'vue';
import { ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

const BtnPositionAdd = ref(false);
const BtnPositionEdit = ref(false);
const newPosition = ref('');
const editPosition = ref('');
const newPositionError = ref(false);
const editPositionError = ref(false);

const positions = ref([
    {
        DfPositionID: 1,
        DfPosition: 'Manager'
    },
    {
        DfPositionID: 2,
        DfPosition: 'Team Leader'
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

const BtnPositionDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this position?',
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

const saveNewPosition = () => {
    if (newPosition.value.trim() === '') {
        newPositionError.value = true;
        return;
    }
    positions.value.push({
        DfPositionID: positions.value.length + 1,
        DfPosition: newPosition.value
    });
    newPosition.value = '';
    BtnPositionAdd.value = false;
    newPositionError.value = false;
};

const saveEditPosition = () => {
    if (editPosition.value.trim() === '') {
        editPositionError.value = true;
        return;
    }
    BtnPositionEdit.value = false;
    editPositionError.value = false;
};
</script>

<template>
    <div class="card">
        <div v-if="!isMobileView" class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" label="Add Position" @click="BtnPositionAdd = true" />
        </div>
        <div v-else class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" class="ml-auto" @click="BtnPositionAdd = true" v-tooltip.top="'Add Position'" />
        </div>
        <Dialog v-model:visible="BtnPositionAdd" modal header="Add Position" class="col-6 md:col-4">
            <div class="flex flex-column gap-3 mb-3">
                <label for="DfPositionAdd" class="font-semibold w-6rem">Position</label>
                <InputText id="DfPositionAdd" class="flex-auto" autocomplete="off" v-model="newPosition" />
                <small v-if="newPositionError" class="p-error">Position is required!</small>
            </div>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="BtnPositionAdd = false"></Button>
                <Button type="button" label="Save" @click="saveNewPosition"></Button>
            </div>
        </Dialog>
        <DataTable v-model:filters="filters" filterDisplay="menu" :value="positions" class="md:col-12" tableStyle="min-width: 50rem">
            <Column class="col-1" field="DfPositionID" header="Id"></Column>
            <Column class="col-9" field="DfPosition" header="Position"></Column>
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
                        <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnPositionEdit = true" rounded />

                        <Dialog v-model:visible="BtnPositionEdit" modal header="Edit Position" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfPositionEdit" class="font-semibold w-6rem">Position</label>
                                <InputText id="DfPositionEdit" class="flex-auto" autocomplete="off" v-model="editPosition" />
                                <small v-if="editPositionError" class="p-error">Position is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnPositionEdit = false"></Button>
                                <Button type="button" label="Save" @click="saveEditPosition"></Button>
                            </div>
                        </Dialog>
                        <Toast />
                        <ConfirmPopup></ConfirmPopup>
                        <Button @click="BtnPositionDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
