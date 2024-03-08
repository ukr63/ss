'use client';

import {useEffect, useState} from "react";
import {useSelector, useDispatch} from "react-redux";
import Project from '@/models/project';
import Link from "next/link";

const Index = (props: any) => {
    const {
        ...rest
    } = props;

    const [items, setItems] = useState([]);

    useEffect(() => {
        let asyncData = async () => {
            const result: any = await Project.getAll();

            const {data} = result;

            setItems(data);
        };
        asyncData();
    }, []);

    const [selectedRows, setSelectedRows] = useState<Array<number>>([]);

    const handleRowSelection = (id: number) => {
        if (isAlreadySelected(id)) {
            const updatedSelection = selectedRows.filter(rowId => rowId !== id);
            setSelectedRows(updatedSelection);
        } else {
            setSelectedRows([...selectedRows, id]);
        }
    };

    const checkedInput = (id: number) => {
        return selectedRows.includes(id);
    }

    const isAlreadySelected = (id: number) => {
        for (const item of selectedRows) {
            if (item === id) {
                return true;
            }
        }

        return false;
    }

    const merge = async () => {
        try {
            let result = await Project.merge(selectedRows);

            const {data: project} = result;

            window.location.href = `/db/project/${project.id}`;
        } catch (e) {
            console.log(e);
        }
    }

    return (
        <>
            <div className="container">
                <button onClick={merge} className="btn btn-success m-1" disabled={selectedRows.length <= 1}>Merge selected projects</button>
                <div className="row">
                    <div className="col-12">
                        <table className="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
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
                                    <th scope="row">
                                        <input
                                            type="checkbox"
                                            className="form-check-input"
                                            style={{
                                                cursor: "pointer"
                                            }}
                                            checked={checkedInput(item.id)}
                                            onChange={() => handleRowSelection(item.id)}
                                        />
                                    </th>
                                    <th scope="row">{item.id}</th>
                                    <td>{item.name}</td>
                                    <td>0</td>
                                    <td>{item.created_at}</td>
                                    <td>
                                        <Link className="btn btn-primary m-1" href={`/db/project/${item.id}`}>
                                            <i className="far fa-eye">Open</i>
                                        </Link>
                                        <button className="btn btn-dark m-1" onClick={() => window.open(Project.download(item.id))}>
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
