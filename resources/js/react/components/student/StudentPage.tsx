import React from 'react'
import {TasksColumn} from "./TasksColumn";

function StudentPage() {
    return (
        <div className="columns is-fullwidth mx-4">
            <div className="column is-four-fifths-desktop">
                <TasksColumn courses={[]}/>
            </div>
            <div className="column is-one-fifth-desktop">
                <div className="box">
                    there
                </div>

            </div>
        </div>
    );
}

export {StudentPage}

