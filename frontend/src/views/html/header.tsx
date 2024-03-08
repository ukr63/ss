'use client';

import {useSelector, useDispatch} from "react-redux";

import Link from "next/link";

const Header = (props: any) => {
    const language = useSelector((state: any) => state.settings.language);

    return (
        <div className="container">
        <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
            <Link href="/" className="navbar-brand">Home</Link>
            <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarNavDropdown">
                <ul className="navbar-nav">
                    <li className="nav-item">
                        <Link href="/db/upload" className="nav-link">Upload</Link>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
    );
}

export default Header;
