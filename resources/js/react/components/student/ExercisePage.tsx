import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {TaskFieldsBlock} from "./TaskFieldsBlock";

const ExercisePage = () => {

    const params = useParams()

    const exerciseId = params.id

    const [exercise, setExercise] = useState()

    const [taskWasUploaded, setTaskWasUploaded] = useState(false)


    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }

    useEffect(() => {
        loadExerciseAxios().then(r => {
            setExercise(r.data)
        })
    }, [])

    useEffect(() => {
        if (exercise === undefined) return
        setTaskWasUploaded(exercise.hasOwnProperty('task') && exercise.task !== null)
    }, [exercise])

    if (exercise === undefined) return <></>

    return (
        <div className="columns mx-4">
            <div className="column is-two-thirds-desktop">
                <div className="box theme-light">
                    <h3 className="is-size-3 m-2">{exercise.title}</h3>
                    <div className="is-block m-2">{exercise.text}</div>
                </div>
            </div>
            <div className="column is-one-third-desktop">
                <div className="box theme-light">
                    <TaskFieldsBlock task={taskWasUploaded ? exercise.task : null} exercise={exercise}/>
                </div>
            </div>
        </div>
    )
}

export {ExercisePage}

