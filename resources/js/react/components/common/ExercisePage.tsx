import {useLocation, useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {ExercisePage as StudentPage} from "../student/ExercisePage"
import {EditExercisePage as TeacherPage} from "../teacher/EditExercisePage"
import ErrorPage from "../ErrorPage";

type Props = {
    editable: boolean
}

const ExercisePage = (props: Props) => {

    const params = useParams()

    const location = useLocation()

    const exerciseId = params.id

    if (exerciseId === undefined && location.state.course_id === null){
        return <ErrorPage/>
    }

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
        if (exerciseId !== undefined) {
            loadExercise()
        }else if (location.state.course_id !== null) {
            setExercise({
                id: 0,
                course_id: location.state.course_id,
                title: "Новое задание",
                max_score: 5,
                deadline: null,
                deadline_multiplier: 1,
                text: "",
                is_hidden: true
            })
        }
    }, [])

    if (exercise === undefined) return <></>


    return props.editable ? <TeacherPage exercise={exercise} setExercise={setExercise}/> : <StudentPage exercise={exercise} setExercise={setExercise} loadExercise={loadExercise}/>
}

export {ExercisePage}

