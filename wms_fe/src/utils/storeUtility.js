// // src/utils/storeUtility.js

// import { useAuthStore } from '../store/auth';
// import { createPinia, setActivePinia } from 'pinia';

// // Initialize Pinia and set it as the active Pinia instance
// const pinia = createPinia();
// setActivePinia(pinia);

// export function performSomeAction() {
//     const authStore = useAuthStore();

//     // Example usage of the store
//     if (authStore.isAuthenticated) {
//         console.log('User is authenticated:', authStore.getUser);
//     } else {
//         console.log('User is not authenticated');
//     }

//     return authStore;
// }
// src/utils/someUtility.js
import pinia from '../store';
import { useMyStore } from '../store/myStore';
import { setActivePinia } from 'pinia';

setActivePinia(pinia);

const myStore = useMyStore();

console.log(myStore.myData);
console.log(myStore.upperCaseData);
myStore.updateData('new data');
