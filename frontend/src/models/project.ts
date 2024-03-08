import axios, {baseURL} from '@/lib/axios';

class Project
{
    static getProject(id: number)
    {
        return axios.get(`/db/project/${id}`);
    }

    static getAll()
    {
        return axios.get(`/db`);
    }

    static delete(id: number)
    {
        return axios.delete(`/db/project/${id}`);
    }

    static upload(formData: any)
    {
        return axios.post(`/db/upload`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    }

    static merge(ids: number[])
    {
        return axios.post('/db/project/merge', {ids})
    }

    static downloadFile(id: number): string
    {
        return `${baseURL}/db/project/download/file/${id}`;
    }

    static download(id: number): string
    {
        return `${baseURL}/db/project/download/${id}`;
    }
}

export default Project;
