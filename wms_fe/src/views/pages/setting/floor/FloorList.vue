<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

const BtnFloorAdd = ref(false);
const BtnFloorEdit = ref(false);
const floorError = ref(false); // Flag to track if floor input is empty for adding
const editFloorError = ref(false); // Flag to track if floor input is empty for editing

const DFfloor = ref('');
const editFloor = ref('');
const selectedFloor = ref(null);

const isMobileView = ref(window.innerWidth < 991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    window.addEventListener('resize', onResize);
});

const floors = ref([
    {
        DfFloorID: 1,
        DfFloor: 'Work From Office'
    },
    {
        DfFloorID: 2,
        DfFloor: 'Work From Home'
    }
]);

const filters = ref({
    is_active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const BtnFloorAddSave = () => {
    floorError.value = !DFfloor.value.trim();
    if (!floorError.value) {
        floors.value.push({
            DfFloorID: floors.value.length + 1,
            DfFloor: DFfloor.value.trim()
        });
        DFfloor.value = '';
        BtnFloorAdd.value = false;
    }
};

const BtnFloorEditSave = () => {
    editFloorError.value = !editFloor.value.trim();
    if (!editFloorError.value) {
        selectedFloor.value.DfFloor = editFloor.value.trim();
        BtnFloorEdit.value = false;
    }
};

const BtnFloorDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this floor?',
        icon: 'pi pi-info-circle',
        rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
        acceptClass: 'p-button-danger p-button-sm',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: () => {
            floors.value = floors.value.filter((floor) => floor !== selectedFloor.value);
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        }
    });
};

const openEditDialog = (floor) => {
    selectedFloor.value = floor;
    editFloor.value = floor.DfFloor;
    BtnFloorEdit.value = true;
};
</script>

<template>
    <div class="card">
        <div v-if="!isMobileView" class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" label="Add Floor" @click="BtnFloorAdd = true" />
        </div>
        <div v-else class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" class="ml-auto" @click="BtnFloorAdd = true" v-tooltip.top="'Add Floor'" />
        </div>
        <Dialog v-model:visible="BtnFloorAdd" modal header="Add Floor" class="col-6 md:col-4">
            <div class="flex flex-column gap-3 mb-3">
                <label for="DfFloorAdd" class="font-semibold w-6rem">Floor</label>
                <InputText v-model="DFfloor" id="DfFloorAdd" class="flex-auto" autocomplete="off" />
                <small v-if="floorError" class="p-error">Floor is required!</small>
            </div>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="BtnFloorAdd = false"></Button>
                <Button type="button" label="Save" @click="BtnFloorAddSave"></Button>
            </div>
        </Dialog>

        <DataTable v-model:filters="filters" filterDisplay="menu" :value="floors" class="md:col-12" tableStyle="min-width: 50rem">
            <Column class="col-1" field="DfFloorID" header="Id"></Column>
            <Column class="col-9" field="DfFloor" header="Floor"></Column>
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
                        <Dialog v-model:visible="BtnFloorEdit" modal header="Edit Floor" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfFloorEdit" class="font-semibold w-6rem">Floor</label>
                                <InputText v-model="editFloor" id="DfFloorEdit" class="flex-auto" autocomplete="off" />
                                <small v-if="editFloorError" class="p-error">Floor is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnFloorEdit = false"></Button>
                                <Button type="button" label="Save" @click="BtnFloorEditSave"></Button>
                            </div>
                        </Dialog>
                        <Toast />
                        <ConfirmPopup></ConfirmPopup>
                        <Button @click="BtnFloorDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
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
