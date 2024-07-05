import { API_ENDPOINTS } from '@/router/api';
import { request, METHOD } from '@/utils/request';

export async function userRoleList() {
    try {
        const response = await request(API_ENDPOINTS.USER_ROLES_LIST, METHOD.GET);
        return response.data; // Assuming your API returns data in a structure like { data: [] }
    } catch (error) {
        console.error('Error fetching user role list:', error);
        throw error;
    }
}

export async function insertUserRole(role) {
    try {
        const response = await request(API_ENDPOINTS.INSERT_USER_ROLES, METHOD.POST, {
            role: role
        });

       
        return response.data; // Assuming your API returns data in a structure like { data: [] }
    } catch (error) {
        console.error('Error fetching user role insert:', error);
        throw error;
    }
}