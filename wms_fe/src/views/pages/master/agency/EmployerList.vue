<script setup>
import { onMounted, ref, watch } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import ProgressBar from 'primevue/progressbar';
import Badge from 'primevue/badge';
import Menu from 'primevue/menu';
import Toast from 'primevue/toast';
import { useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { usePrimeVue } from 'primevue/config';
import { agencyList } from '@/service/Administration';
import TriStateCheckbox from 'primevue/tristatecheckbox';
import { FilterMatchMode } from 'primevue/api';

const router = useRouter();
const confirm = useConfirm();
const toast = useToast();
const $primevue = usePrimeVue();

const agencies = ref([]);
const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);
const BtnAgencyImport = ref(false);

const filters = ref({
    is_active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const isMobileView = ref(window.innerWidth < 991);

const onRemoveTemplatingFile = (file, removeFileCallback, index) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;
};

const getAgencyList = async () => {
    try {
        const result = await agencyList();
        agencies.value = result.map((item, index) => ({
            ...item,
            runningNumber: index + 1
        }));
    } catch (error) {
        console.error('Failed to fetch agency list:', error);
    }
};

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

onMounted(() => {
    getAgencyList();
    window.addEventListener('resize', onResize);
});

watch(isMobileView, (newValue) => {
    if (!newValue) {
        BtnAgencyImport.value = false;
    }
});

const onSelectedFiles = (event) => {
    files.value = event.files;
    files.value.forEach((file) => {
        totalSize.value += parseInt(formatSize(file.size));
    });
};

const uploadEvent = (callback) => {
    totalSizePercent.value = totalSize.value / 10;
    callback();
};

const onTemplatedUpload = () => {
    toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

const formatSize = (bytes) => {
    const k = 1024;
    const dm = 3;
    const sizes = $primevue.config.locale.fileSizeTypes;

    if (bytes === 0) {
        return `0 ${sizes[0]}`;
    }

    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

    return `${formattedSize} ${sizes[i]}`;
};

const BtnAgencyDelete = (event) => {
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

const BtnAgencyAdd = () => {
    router.push({ name: 'employerform' });
};

const BtnAgencyEdit = (agency) => {
    router.push({ name: 'employeredit', params: { id: agency.id } });
};

const menuItems = [
    {
        label: 'Import',
        icon: 'pi pi-file-import',
        command: () => {
            BtnAgencyImport.value = true;
        }
    },
    {
        label: 'Add Agency',
        icon: 'pi pi-plus',
        command: () => {
            BtnAgencyAdd();
        }
    }
];
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="flex justify-content-between align-items-center">
                    <h5 class="col-6 md:col-6 mb-0">Agency List</h5>
                    <div v-if="!isMobileView" class="col-6 md:col-6 flex justify-content-end">
                        <Button label="Import" icon="pi pi-file-import" class="mr-3" @click="BtnAgencyImport = true" />
                        <Button label="Add Agency" icon="pi pi-plus" @click="BtnAgencyAdd" />
                    </div>
                    <div v-else>
                        <Menu :model="menuItems" popup ref="menu" />
                        <Button icon="p-link pi pi-ellipsis-v" class="ml-auto" severity="secondary" @click="$refs.menu.toggle($event)" link />
                    </div>
                </div>

                <Dialog v-model:visible="BtnAgencyImport" modal header="Import Agency" class="w-8 md:w-8">
                    <Toast />
                    <FileUpload name="demo[]" url="/api/upload" @upload="onTemplatedUpload($event)" :multiple="true" accept=".xlsx,.xls,.csv" :maxFileSize="1000000" @select="onSelectedFiles">
                        <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                            <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                                <div class="flex gap-2">
                                    <Button @click="chooseCallback(uploadCallback)" icon="pi pi-file-excel" rounded outlined></Button>
                                    <Button @click="uploadEvent(uploadCallback)" icon="pi pi-cloud-upload" rounded outlined severity="success" :disabled="!files || files.length === 0"></Button>
                                    <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger" :disabled="!files || files.length === 0"></Button>
                                </div>
                                <ProgressBar :value="totalSizePercent" :showValue="false" :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]">
                                    <span class="white-space-nowrap">{{ totalSize }}B / 1Mb</span>
                                </ProgressBar>
                            </div>
                        </template>
                        <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                            <div v-if="files.length > 0">
                                <h5>Pending</h5>
                                <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                                    <div v-for="(file, index) of files" :key="file.name + file.type + file.size" class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                                        <span class="font-semibold">{{ file.name }}</span>
                                        <div>{{ formatSize(file.size) }}</div>
                                        <Badge value="Pending" severity="warning" />
                                        <Button icon="pi pi-times" @click="onRemoveTemplatingFile(file, removeFileCallback, index)" outlined rounded severity="danger" />
                                    </div>
                                </div>
                            </div>

                            <div v-if="uploadedFiles.length > 0">
                                <h5>Completed</h5>
                                <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                                    <div v-for="(file, index) of uploadedFiles" :key="file.name + file.type + file.size" class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                                        <img role="presentation" :alt="file.name" :src="file.objectURL" width="100" height="50" />
                                        <span class="font-semibold">{{ file.name }}</span>
                                        <div>{{ formatSize(file.size) }}</div>
                                        <Badge value="Completed" class="mt-3" severity="success" />
                                        <Button icon="pi pi-times" @click="removeUploadedFileCallback(index)" outlined rounded severity="danger" />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #empty>
                            <div class="flex align-items-center justify-content-center flex-column">
                                <i class="pi pi-cloud-upload border-2 border-circle p-5 text-8xl text-400 border-400" />
                                <p class="mt-4 mb-0">Drag and drop files to here to upload.</p>
                            </div>
                        </template>
                    </FileUpload>
                </Dialog>

                <DataTable class="md:col-12 mt-0" v-model:filters="filters" :value="agencies" filterDisplay="menu" :paginator="true" :row-hover="false" :rows="10">
                    <Column class="md:col-1" field="runningNumber" header="ID" />
                    <Column class="md:col-3" field="agency_name" header="Name" />
                    <Column class="md:col-4" field="agency_address" header="Address" />
                    <Column class="md:col-2" field="agency_phone_no" header="Phone No" />
                    <Column class="md:col-1" field="is_active" header="Active" dataType="boolean" bodyClass="text-center">
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
                                <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnAgencyEdit(slotProps.data)" rounded />
                                <Toast />
                                <ConfirmPopup></ConfirmPopup>
                                <Button @click="BtnAgencyDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'deactivate'" rounded></Button>
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
