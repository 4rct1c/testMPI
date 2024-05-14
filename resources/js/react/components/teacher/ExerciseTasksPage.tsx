import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {useLocation, useParams} from "react-router-dom";
import {TasksTableBlock} from "./TasksTableBlock";


const ExerciseTasksPage = () => {


    const params = useParams()

    const exerciseId = params.id

    const location = useLocation()

    const [users, setUsers] = useState([])
    const [exercise, setExercise] = useState(undefined)

    const loadUsersAxios = () => {
        return axios.get(getApiRoutes().load_exercise_students + '/' + exerciseId)
    }

    const loadUsers = () => {
        loadUsersAxios().then(response => {
            setUsers(response.data)
        })
    }

    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }

    const loadExercise = () => {
        loadExerciseAxios().then(response => {
            console.log(response.data)
            setExercise(response.data)
        })
    }

    useEffect(() => {
        if (location.state === null || location.state.exercise === null){
            loadExercise()
        } else {
            setExercise(location.state.exercise)
        }
        loadUsers()
    }, [])

    if (exercise === undefined){
        return <></>
    }

    return (
         <TasksTableBlock exercise={exercise} users={users}/>
    )
}

export {ExerciseTasksPage}
