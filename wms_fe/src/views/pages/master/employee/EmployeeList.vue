<script setup>
import { onMounted, ref, watch } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import { usePrimeVue } from 'primevue/config';
import { employeeList } from '../../../../service/Administration';
import { FilterMatchMode } from 'primevue/api';

const router = useRouter();
const confirm = useConfirm();
const toast = useToast();
const $primevue = usePrimeVue();

const employees = ref([]);
const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);
const BtnEmployeeImport = ref(false);

const isMobileView = ref(window.innerWid991);

const onResize = () => {
    isMobileView.value = window.innerWidth < 991;
};

const getEmployeeList = async () => {
    try {
        const result = await employeeList();
        // Add runningNumber property to each employee item
        employees.value = result.map((item, index) => ({
            ...item,
            runningNumber: index + 1
        }));
        console.log('Employee list:', employees.value);
    } catch (error) {
        console.error('Failed to fetch employee list:', error);
    }
};

onMounted(() => {
    getEmployeeList();
    window.addEventListener('resize', onResize);
});

watch(isMobileView, (newValue) => {
    if (!newValue) {
        BtnEmployeeImport.value = false;
    }
});

const onRemoveTemplatingFile = (file, removeFileCallback, index) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;
};

// const onClearTemplatingUpload = (clear) => {
//     clear();
//     totalSize.value = 0;
//     totalSizePercent.value = 0;
// };

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

const filters = ref({
    is_active: { value: null, matchMode: FilterMatchMode.EQUALS }
});

const BtnEmployeeDelete = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this record?',
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

const BtnEmployeeAdd = () => {
    router.push({ name: 'employeeform' });
};

const BtnEmployeeEdit = (employee) => {
    router.push({ name: 'employeeedit', params: { id: employee.id } });
};

const menuItems = [
    {
        label: 'Import',
        icon: 'pi pi-file-import',
        command: () => {
            BtnEmployeeImport.value = true;
        }
    },
    {
        label: 'Add Employee',
        icon: 'pi pi-plus',
        command: () => {
            BtnEmployeeAdd();
        }
    }
];
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="flex align-items-center justify-content-between">
                    <h5 class="mb-0 col-6 md:col-6">Employee List</h5>
                    <div v-if="!isMobileView" class="col-6 md:col-6 flex justify-content-end">
                        <Button label="Import" icon="pi pi-file-import" class="mr-3" @click="BtnEmployeeImport = true" />
                        <Button label="Add Employee" icon="pi pi-plus" severity="primary" @click="BtnEmployeeAdd" />
                    </div>
                    <div v-else>
                        <Menu :model="menuItems" popup ref="menu" />
                        <Button icon="p-link pi pi-ellipsis-v" class="ml-auto" severity="secondary" @click="$refs.menu.toggle($event)" link />
                    </div>
                </div>
                <!-- import ui -->
                <Dialog v-model:visible="BtnEmployeeImport" modal header="Import Employee" class="w-8 md:w-8">
                    <Toast />
                    <FileUpload name="demo[]" url="/api/upload" @upload="onTemplatedUpload($event)" :multiple="true" accept=".xlsx,.xls,.csv" :maxFileSize="1000000" @select="onSelectedFiles">
                        <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                            <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                                <div class="flex gap-2">
                                    <Button @click="chooseCallback(uploadCallback)" icon="pi pi-file-excel" rounded outlined></Button>
                                    <Button @click="uploadEvent(uploadCallback)" icon="pi pi-cloud-upload" rounded outlined severity="success" :disabled="!files || files.length === 0"></Button>
                                    <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger" :disabled="!files || files.length === 0"></Button>
                                </div>
                                <ProgressBar :value="totalSizePercent" :showValue="false" :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]"
                                    ><span class="white-space-nowrap">{{ totalSize }}B / 1Mb</span></ProgressBar
                                >
                            </div>
                        </template>
                        <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                            <div v-if="files.length > 0">
                                <h5>Pending</h5>
                                <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                                    <div v-for="(file, index) of files" :key="file.name + file.type + file.size" class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                                        <div>
                                            <img role="presentation" :alt="file.name" :src="file.objectURL" width="100" height="50" />
                                        </div>
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
                                        <div>
                                            <!-- <img role="presentation" :alt="file.name" :src="file.objectURL" width="100" height="50" /> -->
                                        </div>
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

                <DataTable v-model:filters="filters" filterDisplay="menu" :value="employees" :paginator="true" :rows="10" scrollable>
                    <Column class="" field="runningNumber" header="ID" />
                    <Column class="" field="employee_name" header="Name" style="min-width: 200px" />
                    <Column class="" field="employee_email" header="Email" style="min-width: 200px" />
                    <Column class="" field="employee_phone_no" header="Phone No" style="min-width: 100px" />
                    <Column class="" field="employee_agency" header="Agency" style="min-width: 100px" />
                    <Column class="" field="employee_position" header="Position" style="min-width: 100px" />
                    <Column class="" field="employee_yow" header="Year of Working" style="min-width: 200px" />
                    <Column class="text-center" field="is_active" dataType="boolean" header="Active">
                        <template #body="{ data }">
                            <i class="pi" :class="{ 'pi-check-circle text-green-500': data.verified, 'pi-times-circle text-red-400': !data.verified }"></i>
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <TriStateCheckbox v-model="filterModel.value" @change="filterCallback()" />
                        </template>
                    </Column>
                    <Column class="" field="action" header="Action" frozen alignFrozen="right">
                        <template #body="slotProps">
                            <div class="flex justify-content-center">
                                <Button icon="pi pi-pencil" class="mr-2" severity="primary" v-tooltip.top="'edit'" @click="BtnEmployeeEdit(slotProps.data)" rounded />
                                <Toast />
                                <ConfirmPopup />
                                <Button @click="BtnEmployeeDelete($event)" icon="pi pi-trash" severity="danger" v-tooltip.top="'delete'" rounded></Button>
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
  border-radius: 66px; /* This creates a fully rounded element */
  color: #6c757d;
}
</style>
