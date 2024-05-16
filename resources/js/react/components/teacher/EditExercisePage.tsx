import {ExerciseWithTaskTestStatusAndFile} from "../../types/types";
import {ExercisePropertiesBlock} from "./ExercisePropertiesBlock";
import Editor from "../Editor";
import {useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";

type Props = {
    exercise: ExerciseWithTaskTestStatusAndFile
    setExercise: Function
}

const EditExercisePage = (props: Props) => {

    const [exercise, setExercise] = useState(props.exercise)


    const updateExerciseAxios = () => {
        return axios.put(getApiRoutes().update_exercise,{
            id: exercise.id,
            title: exercise.title,
            max_score: exercise.max_score,
            deadline: exercise.deadline,
            deadline_multiplier: exercise.deadline_multiplier,
            text: exercise.text,
            is_hidden: exercise.is_hidden,
            }
        )
    }

    const applyHandler = () => {
        updateExerciseAxios().then(r => {
            r.data ? props.setExercise(exercise) : setExercise(props.exercise)
        })
    }


    const cancelHandler = () => {
        setExercise(props.exercise)
    }


    const changeText = (newText) => {
        let handledExercise = {...props.exercise}
        handledExercise.text = newText
        setExercise(handledExercise)
    }


    return <div className="columns mx-4">
        <div className="column is-two-thirds">
            <div className="box theme-light">
                <h3 className="is-size-3 m-2">{exercise.title}</h3>
                <Editor content={exercise.text} changeValue={changeText} editable={true}/>
            </div>
        </div>
        <div className="column is-one-third">
            <ExercisePropertiesBlock exercise={exercise}
                                     setExercise={setExercise}
                                     applyHandler={applyHandler}
                                     cancelHandler={cancelHandler}
            />
        </div>
    </div>
}

export {EditExercisePage}

