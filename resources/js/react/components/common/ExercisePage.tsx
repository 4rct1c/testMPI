import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {ExercisePage as StudentPage} from "../student/ExercisePage"
import {EditExercisePage as TeacherPage} from "../teacher/EditExercisePage"

type Props = {
    editable: boolean
}

const ExercisePage = (props: Props) => {

    const params = useParams()

    const exerciseId = params.id

    const [exercise, setExercise] = useState(undefined)


    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }

    const loadExercise = () => {
        loadExerciseAxios().then(r => {
            setExercise(r.data)
        })
    }

    useEffect(() => {
        loadExercise()
    }, [])

    if (exercise === undefined) return <></>

    return props.editable ? <TeacherPage exercise={exercise} setExercise={setExercise}/> : <StudentPage exercise={exercise} setExercise={setExercise} loadExercise={loadExercise}/>
}

export {ExercisePage}

