<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';

const router = useRouter();

const floors = ref([{ name: 'Work From Office' }, { name: 'Work From Home' }]);
const seniorities = ref([{ name: 'Senior' }, { name: 'Elite' }, { name: 'Junior' }, { name: 'New' }]);
const agencies = ref([{ name: 'XOX' }, { name: 'XOXTECH' }]);
const positions = ref([{ name: 'Manager' }, { name: 'Team Leader' }, { name: 'Trainer' }, { name: 'Agent' }, { name: 'Quality Assurance' }]);
const positionlvl = ref([{ name: 'Manager' }, { name: 'Assistant Manager' }, { name: 'Executive' }, { name: 'Senior Executive' }, { name: 'Junior Executive' }, { name: 'Contract' }]);
const positionStatus = ref([{ name: 'Permanent' }, { name: 'Contract' }]);

const selectedFloor = ref('');
const selectedSeniority = ref('');
const selectedAgency = ref('');
const selectedPosition = ref('');
const selectedPositionlvl = ref('');
const selectedPositionStatus = ref('');
const selectedStartJoin = ref('');
const selectedEndJoin = ref('');
const selectedAppraisal = ref('');
const selectedConversion = ref('');
const pdpa = ref(false);
const remarks = ref('');

const floorError = ref(false);
const seniorityError = ref(false);
const agencyError = ref(false);
const positionError = ref(false);
const positionLvlError = ref(false);
const positionStatusError = ref(false);
const startJoinError = ref(false);
const endJoinError = ref(false);
const appraisalError = ref(false);
const conversionError = ref(false);

const BtnSaveEmployeeEmployment = () => {
    floorError.value = !selectedFloor.value;
    seniorityError.value = !selectedSeniority.value;
    agencyError.value = !selectedAgency.value;
    positionError.value = !selectedPosition.value;
    positionLvlError.value = !selectedPositionlvl.value;
    positionStatusError.value = !selectedPositionStatus.value;
    startJoinError.value = !selectedStartJoin.value;
    endJoinError.value = !selectedEndJoin.value;
    appraisalError.value = !selectedAppraisal.value;
    conversionError.value = !selectedConversion.value;

    if (
        !floorError.value &&
        !seniorityError.value &&
        !agencyError.value &&
        !positionError.value &&
        !positionLvlError.value &&
        !positionStatusError.value &&
        !startJoinError.value &&
        !endJoinError.value &&
        !appraisalError.value &&
        !conversionError.value
    ) {
        router.push({ name: 'employeelist' });
    }
};

const BtnCancelEmployeeEmployment = () => {
    router.push({ name: 'employeelist' });
};
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
                            <Dropdown id="TxtEmployeePositionLvl" v-model="selectedPositionlvl" :options="positionlvl" optionLabel="name" placeholder="Select a Position Level" :class="{ 'p-invalid': positionLvlError }" />
                            <small v-if="positionLvlError" class="p-error">Position Level is required</small>
                        </div>
                        <div class="field col-12 md:col-12">
                            <label for="TxtEmployeePositionSts">Position Status</label>
                            <Dropdown id="TxtEmployeePositionSts" v-model="selectedPositionStatus" :options="positionStatus" optionLabel="name" placeholder="Select a Position Status" :class="{ 'p-invalid': positionStatusError }" />
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
                        <!--<div class="field col-12 md:col-12">
                            <label for="DtAppraisal">Appraisal Date</label>
                            <Calendar id="DtAppraisal" dateFormat="dd/mm/yy" v-model="selectedAppraisal" placeholder="Appraisal Date" :class="{ 'p-invalid': appraisalError }" />
                            <small v-if="appraisalError" class="p-error">Appraisal Date is required!</small>
                        </div> -->
                        <div class="field col-12 md:col-12">
                            <label for="pdpa" class="mr-2">Attend PDPA</label>
                            <InputSwitch v-model="pdpa" class="pt-2" /><br />
                            <!--  <small v-if="pdpaError" class="p-error">End Joining Date is required!</small> -->
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
    display: inline-block; /* Ensures InputSwitch is displayed inline */
    vertical-align: middle; /* Aligns InputSwitch vertically with label */
}
</style>
