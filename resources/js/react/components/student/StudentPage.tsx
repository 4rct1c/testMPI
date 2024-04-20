import React, {useEffect, useState} from 'react'
import {ExercisesColumn} from "./ExercisesColumn";
import axios from "axios";
import {getApiRoutes} from "../../main"

function StudentPage() {

    const [courses, setCourses] = useState([])

    const loadExercisesAxios = () => {
        return axios.get(getApiRoutes().load_courses)
    }

    useEffect(() => {
        loadExercisesAxios().then(r => {
            setCourses(r.data)
        })
    }, [])


    return (
        <div className="columns is-fullwidth mx-4">
            <div className="column is-four-fifths-desktop">
                <ExercisesColumn courses={courses}/>
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

