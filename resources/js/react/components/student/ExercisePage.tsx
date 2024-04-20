import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";


const ExercisePage = () => {

    const params = useParams()

    const exerciseId = params.id

    const [exercise, setExercise] = useState()


    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }

    useEffect(() => {
        loadExerciseAxios().then(r => {
            setExercise(r.data)
        })
    }, [])

    useEffect(() => {
        console.log(exercise)
    }, [exercise])

    if (exercise === undefined) return <></>


    return (
        <div className="box is-block theme-light m-3">
            <h3 className="is-size-3 m-2">{exercise.title}</h3>
        </div>
    )
}

export {ExercisePage}

