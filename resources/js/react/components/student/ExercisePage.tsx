import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {TaskFieldsBlock} from "./TaskFieldsBlock";
import Editor from "../Editor";

type Props = {
    editable: boolean
}

const ExercisePage = (props: Props) => {

    const params = useParams()

    const exerciseId = params.id

    const [exercise, setExercise] = useState()

    const [originalText, setOriginalText] = useState('')

    const [taskWasUploaded, setTaskWasUploaded] = useState(false)


    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }
    const updateExerciseTextAxios = () => {
        return axios.put(getApiRoutes().update_exercise_text, {
            id: exercise.id,
            text: exercise.text
        })
    }

    const loadExercise = () => {
        loadExerciseAxios().then(r => {
            setExercise(r.data)
            setOriginalText(r.data.text)
        })
    }

    const changeText = (newText) => {
        let handledExercise = {...exercise}
        handledExercise.text = newText
        setExercise(handledExercise)
    }

    const applyHandler = () => {
        if (!props.editable) return
        updateExerciseTextAxios().then(() => {
            setOriginalText(exercise.text)
        }, () => {
            changeText(originalText)
        })
    }

    const cancelHandler = () => {
        changeText(originalText)
    }

    const viewEditor = () => {
        if (!props.editable) return <div dangerouslySetInnerHTML={{__html: exercise.text}}></div>
        return <Editor content={exercise.text} changeValue={changeText}/>
    }

    const viewControls = () => {
        if (!props.editable) return <></>
        return <div className="is-flex my-2">
            <button className="ml-auto button is-link" onClick={() => applyHandler()}>
                Применить
            </button>
            <button className="mx-2 button is-danger" onClick={() => cancelHandler()}>
                Сбросить
            </button>
        </div>
    }

    const teacherView = () => {
        return <div className="mx-4">
                <div className="box theme-light">
                    <h3 className="is-size-3 m-2">{exercise.title}</h3>
                    {viewEditor()}
                    {viewControls()}
                </div>
        </div>

    }

    const studentView = () => {
        return <div className="columns mx-4">
            <div className="column is-two-thirds-desktop">
                <div className="box theme-light">
                    <h3 className="is-size-3 m-2">{exercise.title}</h3>
                </div>
            </div>
            <div className="column is-one-third-desktop">
                <div className="box theme-light">
                    <TaskFieldsBlock task={taskWasUploaded ? exercise.task : null}
                                     exercise={exercise} updateHandler={loadExercise}/>
                </div>
            </div>
        </div>
    }

    useEffect(() => {
        loadExercise()
    }, [])

    useEffect(() => {
        if (exercise === undefined) return
        setTaskWasUploaded(exercise.hasOwnProperty('task') && exercise.task !== null)
    }, [exercise])

    if (exercise === undefined) return <></>

    return props.editable ? teacherView() : studentView()
}

export {ExercisePage}

