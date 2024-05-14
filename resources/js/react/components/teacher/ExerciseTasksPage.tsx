import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {useLocation} from "react-router-dom";
import {TasksTableBlock} from "./TasksTableBlock";


const ExerciseTasksPage = () => {

    const location = useLocation()

    const [users, setUsers] = useState([])

    const loadUsersAxios = () => {
        return axios.get(getApiRoutes().load_exercise_students + '/' + location.state.exercise.id)
    }

    const loadUsers = () => {
        loadUsersAxios().then(response => {
            setUsers(response.data)
        })
    }

    useEffect(() => {
        loadUsers()
    }, [])

    return (
        <TasksTableBlock exercise={location.state.exercise} users={users}/>
    )
}

export {ExerciseTasksPage}
