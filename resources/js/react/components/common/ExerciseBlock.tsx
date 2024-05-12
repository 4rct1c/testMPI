import {useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import Editor from "../Editor";
import {Exercise} from "../../types/types";

type Props = {
    editable: boolean
    exercise: Exercise
}

const ExerciseBlock = (props: Props) => {

    const [exercise, setExercise] = useState(props.exercise)

    const [originalText, setOriginalText] = useState('')


    const updateExerciseTextAxios = () => {
        return axios.put(getApiRoutes().update_exercise_text, {
            id: exercise.id,
            text: exercise.text
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

    if (exercise === undefined) return <></>

    return <div className="box theme-light">
        <h3 className="is-size-3 m-2">{exercise.title}</h3>
        <Editor content={exercise.text} changeValue={changeText} editable={props.editable}/>
        {viewControls()}
    </div>
}

export {ExerciseBlock}

