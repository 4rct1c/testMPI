import React, {useEffect} from 'react'
import {TasksColumn} from "./TasksColumn";
import axios from "axios";

function StudentPage() {

    const loadExercisesAxios = () => {
        return axios.get('/api/exercises/load/')
    }

    useEffect(() => {
        loadExercisesAxios().then(r => {
            console.log(r.data)
        })
    }, [])


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

