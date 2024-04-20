import React from 'react'
import {Link} from "react-router-dom";

function AdminPage() {
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Example Component</div>

                        <div className="card-body">
                            <Link to={'/portal/teacher'}>Teacher</Link>
                            <Link to={'/portal/student'}>Student</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export {AdminPage}

