'use client';

import {FormEvent, useEffect, useState} from "react";
import Project from '@/models/project';

const Index = (props: any) => {
    const [file, setFile] = useState();
    const [uploading, setUploading] = useState(false);

    const onSubmit = async (e: FormEvent) => {
        e.preventDefault();

        try {
            const formData = new FormData();
            // @ts-ignore
            formData.append('file', file);
            setUploading(true);
            let result: any = await Project.upload(formData);
            setUploading(false);
            window.location.href = `/db/project/${result.data.id}`;
        } catch (e: any) {
            setUploading(false);
            alert(e.toString());
        }
    }

    // @ts-ignore
    return (
        <>
            <form onSubmit={onSubmit}>
                <div className="form-check">
                    <input type="file" className="file" name="file" onChange={e => setFile(e.target.files[0])}/>
                </div>
                <button type="submit" className="btn btn-primary">{uploading ? 'Sending...' : 'Submit'}</button>
            </form>
        </>
    )
}

export default Index;
