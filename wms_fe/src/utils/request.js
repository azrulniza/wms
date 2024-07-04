import axios from 'axios';

export const METHOD = {
    GET: 'GET',
    POST: 'POST',
    PUT: 'PUT',
    DELETE: 'DELETE'
};

export async function request(url, method = METHOD.GET, params = null, config = {}) {
    let response;

    switch (method) {
        case METHOD.GET:
            response = await axios.get(url, { params, ...config });
            break;
        case METHOD.POST:
            response = await axios.post(url, params, config);
            break;
        case METHOD.PUT:
            response = await axios.put(url, params, config);
            break;
        case METHOD.DELETE:
            response = await axios.delete(url, { ...config });
            break;
        default:
            response = await axios.get(url, { params, ...config });
            break;
    }

    return response.data; // Return response data
}
