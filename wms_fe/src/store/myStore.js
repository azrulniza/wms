// src/stores/myStore.js
import { defineStore } from 'pinia';

export const useMyStore = defineStore({
    id: 'myStore',
    state: () => ({
        myData: 'example data'
    }),
    actions: {
        updateData(newData) {
            this.myData = newData;
        }
    },
    getters: {
        upperCaseData: (state) => state.myData.toUpperCase()
    }
});
