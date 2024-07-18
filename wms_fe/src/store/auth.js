// import { defineStore } from 'pinia';

// export const useAuthStore = defineStore('auth', {
//     state: () => ({
//         accessToken: null,
//         tokenType: '',
//         expiresIn: 0,
//         user: null
//     }),
//     actions: {
//         setAuthData(data) {
//             this.accessToken = data.access_token;
//             this.tokenType = data.token_type;
//             this.expiresIn = data.expires_in;
//             this.user = data.user;
//         },
//         clearAuthData() {
//             this.accessToken = null;
//             this.tokenType = '';
//             this.expiresIn = 0;
//             this.user = null;
//         }
//     },
//     getters: {
//         isAuthenticated: (state) => !!state.accessToken,
//         getUser: (state) => state.user
//     }
// });


import { defineStore } from 'pinia';

// Function to load auth data from localStorage
const loadAuthData = () => {
    const accessToken = localStorage.getItem('accessToken');
    const tokenType = localStorage.getItem('tokenType');
    const expiresIn = localStorage.getItem('expiresIn');
    const user = JSON.parse(localStorage.getItem('user'));

    return {
        accessToken,
        tokenType,
        expiresIn: expiresIn ? parseInt(expiresIn, 10) : 0,
        user
    };
};

// Function to save auth data to localStorage
const saveAuthData = (data) => {
    localStorage.setItem('accessToken', data.accessToken);
    localStorage.setItem('tokenType', data.tokenType);
    localStorage.setItem('expiresIn', data.expiresIn);
    localStorage.setItem('user', JSON.stringify(data.user));
};

// Function to clear auth data from localStorage
const clearAuthData = () => {
    localStorage.removeItem('accessToken');
    localStorage.removeItem('tokenType');
    localStorage.removeItem('expiresIn');
    localStorage.removeItem('user');
};

export const useAuthStore = defineStore('auth', {
    state: () => ({
        ...loadAuthData(),
        // Alternatively, if you don't want to load data initially, you can set these to null or appropriate defaults
        // accessToken: null,
        // tokenType: '',
        // expiresIn: 0,
        // user: null
    }),
    actions: {
        setAuthData(data) {
            this.accessToken = data.access_token;
            this.tokenType = data.token_type;
            this.expiresIn = data.expires_in;
            this.user = data.user;
            saveAuthData(this.$state);
        },
        clearAuthData() {
            this.accessToken = null;
            this.tokenType = '';
            this.expiresIn = 0;
            this.user = null;
            clearAuthData();
        }
    },
    getters: {
        isAuthenticated: (state) => !!state.accessToken,
        getUser: (state) => state.user
    }
});
