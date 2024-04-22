import React, {useEffect, useState} from 'react'
import {CourseExercises} from "../common/CourseExercises";
import axios from "axios";
import {getApiRoutes} from "../../main";


function CoursesColumn() {

    const [courses, setCourses] = useState([])
    const [tasks, setTasks] = useState([])

    const loadCoursesAxios = () => {
        return axios.get(getApiRoutes().load_courses)
    }

    const loadTasksAxios = () => {
        return axios.get(getApiRoutes().load_tasks)
    }

    useEffect(() => {
        loadCoursesAxios().then(r => {
            setCourses(r.data)
        })
        loadTasksAxios().then(r => {
            setTasks(r.data)
        })
    }, [])

    return (
        <div className="is-block box theme-light">
            <h3 className="is-size-3 mb-3">
                Мои курсы
            </h3>
            <div className="m-2">
                {courses.map(course => <CourseExercises key={course.id}
                                                        course={course}
                                                        tasks={tasks}
                                                        showTasks={true}
                />)}
            </div>
        </div>
    );
}

export {CoursesColumn}

