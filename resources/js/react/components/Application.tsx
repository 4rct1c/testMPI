import React from 'react'
import {Link} from "react-router-dom";

function Application() {
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Example Component</div>

                        <div className="card-body">
                            <Link to={'teacher'}>Teacher</Link>
                            <Link to={'student'}>Student</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export {Application}

