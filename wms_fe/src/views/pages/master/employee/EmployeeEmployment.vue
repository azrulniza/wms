<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import InputSwitch from 'primevue/inputswitch';
import { useToast } from 'primevue/usetoast';
import Textarea from 'primevue/textarea';
import axios from 'axios';

const API_URL = import.meta.env.VITE_BASE_URL;

const props = defineProps({
    profile: {
        type: Object,
        required: true
    }
});

const router = useRouter();
const route = useRoute();
const toast = useToast();

const floors = ref([]);
const seniorities = ref([]);
const agencies = ref([]);
const positions = ref([]);
const positionlvls = ref([]);
const positionStatuses = ref([]);

const selectedFloor = ref('');
const selectedSeniority = ref('');
const selectedAgency = ref('');
const selectedPosition = ref('');
const selectedPositionlvl = ref('');
const selectedPositionStatus = ref('');
const selectedStartJoin = ref('');
const selectedEndJoin = ref('');
const selectedConversion = ref('');
const pdpa = ref(false);
const remarks = ref('');

// Watch for changes in the employee prop and update state
watch(
    () => props.profile,
    (newVal) => {
        if (floors.value.length > 0) {
            selectedFloor.value = floors.value.find((floor) => floor.id === newVal.employee_floor) || null;
        }
        if (seniorities.value.length > 0) {
            selectedSeniority.value = seniorities.value.find((seniority) => seniority.id === newVal.employee_seniority) || null;
        }
        if (agencies.value.length > 0) {
            selectedAgency.value = agencies.value.find((agency_name) => agency_name.id === newVal.employee_agency) || null;
        }
        if (positions.value.length > 0) {
            selectedPosition.value = positions.value.find((position) => position.name === newVal.employee_position) || null;
        }
        if (positionlvls.value.length > 0) {
            selectedPositionlvl.value = positionlvls.value.find((position_lvl) => position_lvl.name === newVal.employee_position_level) || null;
        }
        if (positionStatuses.value.length > 0) {
            selectedPositionStatus.value = positionStatuses.value.find((position_sts) => position_sts.name === newVal.employee_position_status) || null;
        }
        selectedStartJoin.value = newVal.employee_start_join_dt ? newVal.employee_start_join_dt.split(' ')[0] : '';
        selectedEndJoin.value = newVal.employee_end_join_dt ? newVal.employee_end_join_dt.split(' ')[0] : '';
        selectedConversion.value = newVal.employee_conversion_dt ? newVal.employee_conversion_dt.split(' ')[0] : '';
        pdpa.value = newVal.pdpa === 1;
        remarks.value = newVal.employee_remarks || '';
    },
    { immediate: true }
);

const floorError = ref(false);
const seniorityError = ref(false);
const agencyError = ref(false);
const positionError = ref(false);
const positionLvlError = ref(false);
const positionStatusError = ref(false);
const startJoinError = ref(false);
const endJoinError = ref(false);
const conversionError = ref(false);

const BtnSaveEmployeeEmployment = async () => {
    floorError.value = !selectedFloor.value;
    seniorityError.value = !selectedSeniority.value;
    agencyError.value = !selectedAgency.value;
    positionError.value = !selectedPosition.value;
    positionLvlError.value = !selectedPositionlvl.value;
    positionStatusError.value = !selectedPositionStatus.value;
    startJoinError.value = !selectedStartJoin.value;
    //endJoinError.value = !selectedEndJoin.value;
    //conversionError.value = !selectedConversion.value;

    if (!floorError.value && !seniorityError.value && !agencyError.value && !positionError.value && !positionLvlError.value && !positionStatusError.value && !startJoinError.value && !endJoinError.value && !conversionError.value) {
        // Save logic here
        try {
            await updateEmployment();
            //router.push({ name: 'employeelist' });
        } catch (error) {
            console.error('Error updating employment details:', error);
        }
        console.log('Saving employee employment details...');
    }
};

const BtnCancelEmployeeEmployment = () => {
    router.push({ name: 'employeelist' });
};

const getFloors = async () => {
    try {
        const response = await axios.get(`${API_URL}/floor`);
        // Map the API response to match the format expected by the Dropdown component
        floors.value = response.data.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.floor
            }));

        // Update selected floor if employment prop has value and floors are fetched
        if (props.profile.employee_floor && floors.value.length > 0) {
            selectedFloor.value = floors.value.find((floor) => floor.id == props.profile.employee_floor);
            if (!selectedFloor.value) {
                console.warn('No matching floor found for ID:', props.profile.employee_floor);
            }
        }
    } catch (error) {
        console.error('Error fetching floor details:', error);
    }
};

const getSeniorities = async () => {
    try {
        const response = await axios.get(`${API_URL}/seniority`);
        // Map the API response to match the format expected by the Dropdown component
        seniorities.value = response.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.seniority
            }));

        // Update selected floor if employment prop has value and seniority are fetched
        if (props.profile.employee_seniority && seniorities.value.length > 0) {
            selectedSeniority.value = seniorities.value.find((seniority) => seniority.id == props.profile.employee_seniority);
            if (!selectedSeniority.value) {
                console.warn('No matching seniority found for ID:', props.profile.employee_seniority);
            }
        }
    } catch (error) {
        console.error('Error fetching seniority details:', error);
    }
};

const getAgency = async () => {
    try {
        const response = await axios.get(`${API_URL}/agency`);
        // Map the API response to match the format expected by the Dropdown component
        agencies.value = response.data.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.agency_name
            }));

        // Update selected floor if employment prop has value and agency are fetched
        if (props.profile.employee_agency && agencies.value.length > 0) {
            selectedAgency.value = agencies.value.find((agency_name) => agency_name.id == props.profile.employee_agency);
            if (!selectedAgency.value) {
                console.warn('No matching agency found for ID:', props.profile.employee_agency);
            }
        }
    } catch (error) {
        console.error('Error fetching agency details:', error);
    }
};

const getPosition = async () => {
    try {
        const response = await axios.get(`${API_URL}/position`);
        // Map the API response to match the format expected by the Dropdown component
        positions.value = response.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.position
            }));

        // Update selected floor if employment prop has value and positions are fetched
        if (props.profile.employee_position && positions.value.length > 0) {
            selectedPosition.value = positions.value.find((position) => position.id == props.profile.employee_position);
            if (!selectedPosition.value) {
                console.warn('No matching position found for ID:', props.profile.employee_position);
            }
        }
    } catch (error) {
        console.error('Error fetching position details:', error);
    }
};

const getPositionLvl = async () => {
    try {
        const response = await axios.get(`${API_URL}/position-level`);
        // Map the API response to match the format expected by the Dropdown component
        positionlvls.value = response.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.position_lvl
            }));

        // Update selected floor if employment prop has value and positions level are fetched
        if (props.profile.employee_position_level && positionlvls.value.length > 0) {
            selectedPositionlvl.value = positionlvls.value.find((position_lvl) => position_lvl.id == props.profile.employee_position_level);
            if (!selectedPositionlvl.value) {
                console.warn('No matching positionlvls found for ID:', props.profile.employee_position_level);
            }
        }
    } catch (error) {
        console.error('Error fetching positionlvls details:', error);
    }
};

const getPositionSts = async () => {
    try {
        const response = await axios.get(`${API_URL}/position-status`);
        // Map the API response to match the format expected by the Dropdown component
        positionStatuses.value = response.data
            .filter((item) => item.active === 1)
            .map((item) => ({
                id: item.id,
                name: item.position_sts
            }));

        // Update selected floor if employment prop has value and positions status are fetched
        if (props.profile.employee_position_status && positionStatuses.value.length > 0) {
            selectedPositionStatus.value = positionStatuses.value.find((position_sts) => position_sts.id == props.profile.employee_position_status);
            if (!selectedPositionStatus.value) {
                console.warn('No matching position status found for ID:', props.profile.employee_position_status);
            }
        }
    } catch (error) {
        console.error('Error fetching position status details:', error);
    }
};

const updateEmployment = async () => {
    try {
        const formatDate = (datetime) => {
            const date = new Date(datetime);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        const payload = {
            employee_floor: selectedFloor.value.id,
            employee_seniority: selectedSeniority.value.id,
            employee_agency: selectedAgency.value.id,
            employee_position: selectedPosition.value.id,
            employee_position_level: selectedPositionlvl.value.id,
            employee_position_status: selectedPositionStatus.value.id,
            employee_start_join_dt: selectedStartJoin.value ? formatDate(selectedStartJoin.value) : null,
            employee_end_join_dt: selectedEndJoin.value ? formatDate(selectedEndJoin.value) : null,
            employee_conversion_dt: selectedConversion.value ? formatDate(selectedConversion.value) : null,
            pdpa: pdpa.value ? 1 : 0,
            employee_remarks: remarks.value,
            id: props.profile.id
        };

        const response = await axios.put(`${API_URL}/employee/update`, payload);
        toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        console.log('Employment details updated successfully:', response.data);
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update employment details', life: 3000 });
        console.error('Error updating employment details:', error);
    }
};
onMounted(() => {
    getFloors();
    getSeniorities();
    getAgency();
    getPosition();
    getPositionLvl();
    getPositionSts();
});
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="col-12 md:col-12">
                <div class="p-fluid formgrid grid">
                    <div class="field col-10 md:col-8 justify-center mx-auto max-w-lg">
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeeFloor">Floor</label>
                            <Dropdown id="TxtEmployeeFloor" v-model="selectedFloor" :options="floors" optionLabel="name" placeholder="Select a Floor" :class="{ 'p-invalid': floorError }" />
                            <small v-if="floorError" class="p-error">Floor is required</small>
                        </div>
                        <div class="field col-12">
                            <label for="TxtEmployeeSeniority">Seniority</label>
                            <Dropdown id="TxtEmployeeSeniority" v-model="selectedSeniority" :options="seniorities" optionLabel="name" placeholder="Select a Seniority" :class="{ 'p-invalid': seniorityError }" />
                            <small v-if="seniorityError" class="p-error">Seniority is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeeAgency">Agency</label>
                            <Dropdown id="TxtEmployeeAgency" v-model="selectedAgency" :options="agencies" optionLabel="name" placeholder="Select an Agency" :class="{ 'p-invalid': agencyError }" />
                            <small v-if="agencyError" class="p-error">Agency is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeePosition">Position</label>
                            <Dropdown id="TxtEmployeePosition" v-model="selectedPosition" :options="positions" optionLabel="name" placeholder="Select a Position" :class="{ 'p-invalid': positionError }" />
                            <small v-if="positionError" class="p-error">Position is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeePositionLvl">Position Level</label>
                            <Dropdown id="TxtEmployeePositionLvl" v-model="selectedPositionlvl" :options="positionlvls" optionLabel="name" placeholder="Select a Position Level" :class="{ 'p-invalid': positionLvlError }" />
                            <small v-if="positionLvlError" class="p-error">Position Level is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeePositionSts">Position Status</label>
                            <Dropdown id="TxtEmployeePositionSts" v-model="selectedPositionStatus" :options="positionStatuses" optionLabel="name" placeholder="Select a Position Status" :class="{ 'p-invalid': positionStatusError }" />
                            <small v-if="positionStatusError" class="p-error">Position Status is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="DtStartJoin">Start Joining Date</label>
                            <Calendar id="DtStartJoin" dateFormat="dd/mm/yy" v-model="selectedStartJoin" placeholder="Start Joining Date" :class="{ 'p-invalid': startJoinError }" />
                            <small v-if="startJoinError" class="p-error">Start Joining Date is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="DtEndJoin">End Joining Date</label>
                            <Calendar id="DtEndJoin" dateFormat="dd/mm/yy" v-model="selectedEndJoin" placeholder="End Joining Date" :class="{ 'p-invalid': endJoinError }" />
                            <small v-if="endJoinError" class="p-error">End Joining Date is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="DtConversion">Conversion Date</label>
                            <Calendar id="DtConversion" dateFormat="dd/mm/yy" v-model="selectedConversion" placeholder="Conversion Date" :class="{ 'p-invalid': conversionError }" />
                            <small v-if="conversionError" class="p-error">Conversion Date is required!</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="pdpa" class="mr-2">Attend PDPA</label>
                            <InputSwitch v-model="pdpa" class="pt-2" /><br />
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeeRemarks">Remarks</label>
                            <Textarea id="TxtEmployeeRemarks" v-model="remarks" rows="4" placeholder="Remarks" />
                        </div>
                        <div class="field col-8 md:col-4 mx-auto flex gap-4">
                            <Button type="button" label="Save" class="w-full" @click="BtnSaveEmployeeEmployment" />
                            <Button type="button" severity="secondary" label="Cancel" class="w-full" @click="BtnCancelEmployeeEmployment" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.p-invalid {
    border-color: var(--red-500);
}

.field .p-inputswitch {
    display: inline-block;
    vertical-align: middle;
}
</style>
