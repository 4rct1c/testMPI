import {useEffect, useState} from "react";
import {TaskFieldsBlock} from "./TaskFieldsBlock";
import {ExerciseWithTaskTestStatusAndFile} from "../../types/types";
import {TaskTestMessageBlock} from "./TaskTestMessageBlock";
import Editor from "../Editor";
import {UploadFileBlock} from "./UploadFileBlock";

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
            <div className="box theme-light">
                <h3 className="is-size-3 m-2">{props.exercise.title}</h3>
                <Editor content={props.exercise.text} changeValue={() => {}} editable={false}/>
            </div>
        </div>
        <div className="column is-one-third-desktop">
            {taskWasUploaded ? <TaskFieldsBlock task={props.exercise.task}
                                                exercise={props.exercise} updateHandler={props.loadExercise}/> : <></>}
            <UploadFileBlock exerciseId={props.exercise.id}
                             taskId={taskWasUploaded ? props.exercise.task.id : null}
                             updateHandler={props.loadExercise}/>
            {taskWasUploaded ? <TaskTestMessageBlock task={props.exercise.task}/> : <></>}
        </div>
    </div>
}

export {ExercisePage}

