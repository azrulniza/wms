import { API_ENDPOINTS } from '@/router/api';
import { request, METHOD } from '@/utils/request';

export async function agencyList() {
    try {
        const response = await request(API_ENDPOINTS.AGENCY_LIST, METHOD.GET);
        return response.data; // Assuming your API returns data in a structure like { data: [] }
    } catch (error) {
        console.error('Error fetching agency list:', error);
        throw error;
    }
}

export async function agencyCreate(data) {
    try {
        const response = await request(API_ENDPOINTS.AGENCY_CREATE, METHOD.POST, data);
        return response.data; // Assuming your API returns data in a structure like { data: [] }
    } catch (error) {
        console.error('Error creating agency:', error);
        throw error;
    }
}

export async function employeeList() {
    try {
        const response = await request(API_ENDPOINTS.EMPLOYEE_LIST, METHOD.GET);
        return response.data; // Assuming your API returns data in a structure like { data: [] }
    } catch (error) {
        console.error('Error fetching employee list:', error);
        throw error;
    }
}
