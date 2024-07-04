<script setup>
import { FilterMatchMode } from 'primevue/api';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { onMounted } from 'vue';
import { ref } from 'vue';

const confirm = useConfirm();
const toast = useToast();

const BtnPositionLvlAdd = ref(false);
const BtnPositionLvlEdit = ref(false);
const newPositionLvl = ref('');
const editPositionLvl = ref('');
const newPositionLvlError = ref(false);
const editPositionLvlError = ref(false);

const positionlvls = ref([
    {
        DfPositionLvlLvlID: 1,
        DfPositionLvl: 'Manager'
    },
    {
        DfPositionLvlLvlID: 2,
        DfPositionLvl: 'Executive'
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

const BtnPositionLvlDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this position level?',
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

const saveNewPositionLvl = () => {
    if (newPositionLvl.value.trim() === '') {
        newPositionLvlError.value = true;
        return;
    }
    positionlvls.value.push({
        DfPositionLvlLvlID: positionlvls.value.length + 1,
        DfPositionLvl: newPositionLvl.value
    });
    newPositionLvl.value = '';
    BtnPositionLvlAdd.value = false;
    newPositionLvlError.value = false;
};

const saveEditPositionLvl = () => {
    if (editPositionLvl.value.trim() === '') {
        editPositionLvlError.value = true;
        return;
    }
    // Assuming you have a way to determine which position level to edit
    // Update the appropriate position level
    BtnPositionLvlEdit.value = false;
    editPositionLvlError.value = false;
};
</script>

<template>
    <div class="card">
        <div v-if="!isMobileView" class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" label="Add Position Level" @click="BtnPositionLvlAdd = true" />
        </div>
        <div v-else class="flex justify-content-end mb-3">
            <Button icon="pi pi-plus" class="ml-auto" @click="BtnPositionLvlAdd = true" v-tooltip.top="'Add Position Level'"  />
        </div>
        <Dialog v-model:visible="BtnPositionLvlAdd" modal header="Add Position Level" class="col-6 md:col-4">
            <div class="flex flex-column gap-3 mb-3">
                <label for="DfPositionLvlAdd" class="font-semibold w-6rem">Position Level</label>
                <InputText id="DfPositionLvlAdd" class="flex-auto" autocomplete="off" v-model="newPositionLvl" />
                <small v-if="newPositionLvlError" class="p-error">Position level is required!</small>
            </div>
            <div class="flex justify-content-end gap-2">
                <Button type="button" label="Cancel" severity="secondary" @click="BtnPositionLvlAdd = false"></Button>
                <Button type="button" label="Save" @click="saveNewPositionLvl"></Button>
            </div>
        </Dialog>
        <DataTable v-model:filters="filters" filterDisplay="menu" :value="positionlvls" class="md:col-12" tableStyle="min-width: 50rem">
            <Column class="col-1" field="DfPositionLvlLvlID" header="Id"></Column>
            <Column class="col-9" field="DfPositionLvl" header="Position Level"></Column>
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
                        <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnPositionLvlEdit = true" rounded />

                        <Dialog v-model:visible="BtnPositionLvlEdit" modal header="Edit Position Level" class="col-6 md:col-4">
                            <div class="flex flex-column gap-3 mb-3">
                                <label for="DfPositionLvlEdit" class="font-semibold w-6rem">Position Level</label>
                                <InputText id="DfPositionLvlEdit" class="flex-auto" autocomplete="off" v-model="editPositionLvl" />
                                <small v-if="editPositionLvlError" class="p-error">Position level is required!</small>
                            </div>
                            <div class="flex justify-content-end gap-2">
                                <Button type="button" label="Cancel" severity="secondary" @click="BtnPositionLvlEdit = false"></Button>
                                <Button type="button" label="Save" @click="saveEditPositionLvl"></Button>
                            </div>
                        </Dialog>
                        <Toast />
                        <ConfirmPopup></ConfirmPopup>
                        <Button @click="BtnPositionLvlDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
