import axios from 'axios';

export const baseURL = isServer() ? 'http://host.docker.internal:8080/api' : 'http://localhost:8080/api';

const instance = axios.create({
    baseURL: baseURL
});

function isServer() {
    return typeof window === 'undefined';
}

export default instance;
