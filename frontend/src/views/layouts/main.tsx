'use client';

import Header from "@/views/html/header";
import {useEffect} from "react";


let Bootstrap;
const Layout = (props: any) => {
    const {
        children,
        ...rest
    } = props;

    return (
        <div className="wrapper">
            <div className="header bg-dark">
                <Header />
            </div>
            <div className="content m-1">
                <div className="container">
                    {children}
                </div>
            </div>
            <div className="footer"></div>
        </div>
    )
}

export default Layout;
