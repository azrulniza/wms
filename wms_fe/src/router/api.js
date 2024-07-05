const BASE_URL = import.meta.env.VITE_BASE_URL;

export const API_ENDPOINTS = {
    AGENCY_LIST: `${BASE_URL}/agency`,
    AGENCY_CREATE: `${BASE_URL}/agency/insert`,

    EMPLOYEE_LIST: `${BASE_URL}/employee`,
    USER_ROLES_LIST: `${BASE_URL}/user-roles`,
    INSERT_USER_ROLES: `${BASE_URL}/user-roles/insert`
};
