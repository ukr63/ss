'use client';

import {useEffect, useState} from "react";
import {useSelector, useDispatch} from "react-redux";
import Project from '@/models/project';
import Link from "next/link";

const Index = (props: any) => {
    const {
        ...rest
    } = props;

    const [items, setItems] = useState(props.files ?? []);

    return (
        <>
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <table className="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Size</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {items.map((item: any) => (
                                <tr>
                                    <th scope="row">{item.id}</th>
                                    <td>{item.file_path}</td>
                                    <td>0</td>
                                    <td>{item.created_at}</td>
                                    <td>
                                        <button className="btn btn-dark m-1" onClick={() => (window.open(Project.downloadFile(item.id)))}>
                                            Download
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Index;
