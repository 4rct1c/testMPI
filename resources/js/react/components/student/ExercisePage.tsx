import {useEffect, useState} from "react";
import {TaskFieldsBlock} from "./TaskFieldsBlock";
import {ExerciseBlock} from "../common/ExerciseBlock";
import {ExerciseWithTaskTestStatusAndFile} from "../../types/types";

type Props = {
    exercise: ExerciseWithTaskTestStatusAndFile
    setExercise: Function
    loadExercise: Function
}

const ExercisePage = (props: Props) => {

    const [taskWasUploaded, setTaskWasUploaded] = useState(false)


    useEffect(() => {
        if (props.exercise === undefined) return
        setTaskWasUploaded(props.exercise.hasOwnProperty('task') && props.exercise.task !== null)
    }, [props.exercise])

    if (props.exercise === undefined) return <></>

    return <div className="columns mx-4">
        <div className="column is-two-thirds-desktop">
            <ExerciseBlock exercise={props.exercise} editable={false}/>
        </div>
        <div className="column is-one-third-desktop">
            <TaskFieldsBlock task={taskWasUploaded ? props.exercise.task : null}
                             exercise={props.exercise} updateHandler={props.loadExercise}/>
        </div>
    </div>
}

export {ExercisePage}

