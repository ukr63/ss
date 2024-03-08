'use client';

import {useEffect, useState} from "react";
import {useSelector, useDispatch} from "react-redux";

const Index = (props: any) => {
    const {
        text,
        ...rest
    } = props;

    const dispatch = useDispatch();

    const settings = useSelector((state: any) => state.settings);

    const [field, setField] = useState(text);

    return (
        <>
            <p>Some content...</p>
        </>
    )
}

export default Index;
