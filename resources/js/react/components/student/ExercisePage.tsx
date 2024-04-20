import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {UploadFileField} from "./UploadFileField";

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
        <div className="box is-block theme-light m-3">
            <h3 className="is-size-3 m-2">{exercise.title}</h3>
            <div className="is-block m-2">{exercise.text}</div>
            <UploadFileField exerciseId={exercise.id} taskId={taskWasUploaded ? exercise.task.id : null}/>
        </div>
    )
}

export {ExercisePage}

