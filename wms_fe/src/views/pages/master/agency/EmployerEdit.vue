<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { agencyCreate } from '@/service/Administration'; // Import agencyCreate function

const router = useRouter();

const agencyName = ref('');
const agencyAddress = ref('');
const agencyPhoneNo = ref();

const agencyNameError = ref(false);
const agencyAddressError = ref(false);
const agencyPhoneNoError = ref(false);

const validate = () => {
    agencyNameError.value = !agencyName.value;
    agencyAddressError.value = !agencyAddress.value;
    agencyPhoneNoError.value = !agencyPhoneNo.value;
    return !agencyNameError.value && !agencyAddressError.value && !agencyPhoneNoError.value;
};

const BtnAgencyAdd = async () => {
    // Validate form inputs
    if (!validate()) {
        return;
    }

    // Prepare agency data
    const data = {
        agency_name: agencyName.value,
        agency_address: agencyAddress.value,
        agency_phone_no: agencyPhoneNo.value
    };

    try {
        // Call agencyCreate API function
        const result = await agencyCreate(data);
        // Handle API response as needed
        console.log('Agency added:', result);

        // Optionally, you can reset form fields after successful submission
        agencyName.value = '';
        agencyAddress.value = '';
        agencyPhoneNo.value = '';

        // Navigate to employerlist route after successful save
        router.push({ name: 'employerlist' });
    } catch (error) {
        console.error('Failed to add agency:', error);
        // Optionally, show a toast or error message here
    }
};

const BtnCancel = () => {
    router.push({ name: 'employerlist' });
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Edit Agency</h5>
                    <div class="p-fluid formgrid grid flex justify-content-center">
                        <div class="field col-12 md:col-8">
                            <div class="field col-12">
                                <label for="TxtAgencyName">Name</label>
                                <InputText v-model="agencyName" id="TxtAgencyName" type="text" placeholder="Enter agency name" :class="{ 'p-invalid': agencyNameError }" />
                                <small v-if="agencyNameError" class="p-error">Agency name is required!</small>
                            </div>
                            <div class="field col-12">
                                <label for="TxtAgencyAddress">Address</label>
                                <Textarea v-model="agencyAddress" id="TxtAgencyAddress" placeholder="Enter agency address" rows="4" :class="{ 'p-invalid': agencyAddressError }" />
                                <small v-if="agencyAddressError" class="p-error">Agency address is required!</small>
                            </div>
                            <div class="field col-12">
                                <label for="TxtAgencyPhoneNo">Contact No.</label>
                                <InputNumber v-model="agencyPhoneNo" id="TxtAgencyPhoneNo" :useGrouping="false" placeholder="Enter Agency Contact No" :class="{ 'p-invalid': agencyPhoneNoError }" />
                                <small v-if="agencyPhoneNoError" class="p-error">Agency contact no. is required!</small>
                            </div>
                            <div class="col-12 flex justify-content-center py-3 gap-2 button-group">
                                <Button label="Save" class="col-3 md:col-3 py-2" @click="BtnAgencyAdd" />
                                <Button severity="secondary" label="Cancel" class="col-3 md:col-3 py-2" @click="BtnCancel" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.button-group {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

@media (max-width: 500px) {
    .button-group {
        flex-direction: column;
        align-items: center;
    }

    .button-group .p-button {
        width: 100%;
    }
}
</style>
