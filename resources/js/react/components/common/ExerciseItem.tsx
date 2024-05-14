import React from 'react'
import {Exercise as ExerciseType, ExerciseWithTaskCounters, TaskWithTestStatus} from "../../types/types";
import {useNavigate} from "react-router-dom";
import {dateForHumans} from "../../helpers/dateHepter";
import {getMarkPercentString} from "../../helpers/taskHepler";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faPencilSquare} from "@fortawesome/free-solid-svg-icons";

type Props = {
    exercise: ExerciseType|ExerciseWithTaskCounters
    task: TaskWithTestStatus|undefined
    key: number
    teacherMode: boolean
}

function ExerciseItem(props : Props) {


    const navigate = useNavigate()

    const openExercisePage = (e?) => {
        if (e !== undefined){
            e.stopPropagation()
        }
        navigate('/portal/exercise/' + props.exercise.id)
    }

    const openExerciseTasksPage = () => {
        navigate('/portal/exercise/' + props.exercise.id + '/tasks', {state: {exercise: props.exercise}})
    }

    const handleTaskLink = () => {
        props.teacherMode ? openExerciseTasksPage() : openExercisePage()
    }

    const taskInfo = (props.task ? props.task : {
        last_uploaded_at: null,
        test_status: null,
        mark: null
    })

    const getTestStatusLabel = () => {
        if (props.task === undefined || props.task.test_status === null) return '—'
        return props.task.test_status.label
    }

    const getMark = () => {
        if (props.task === undefined) return '—'
        return props.task.mark === null ? getTestStatusLabel() : getMarkPercentString(props.task, props.exercise)
    }

    const teacherFields = () => {
        if (!('loaded_tasks' in props.exercise)){
            return <></>
        }
        return <>
            <td>
                <span>{props.exercise.loaded_tasks}/{props.exercise.students_count} (</span>
                <span className="has-text-success">{props.exercise.succeeded_tasks}</span>/
                <span className="has-text-danger">{props.exercise.failed_tasks}</span>/
                <span className="has-text-warning">{props.exercise.awaiting_tasks}</span>)
            </td>
            <td>
                <FontAwesomeIcon icon={faPencilSquare} size='lg' className='my-auto is-clickable is-hoverable is-link' onClick={e => openExercisePage(e)}/>
            </td>
        </>
    }

    const studentFields = () => {
        return <>
            <td>{dateForHumans(taskInfo.last_uploaded_at)}</td>
            <td>{getMark()}</td>
        </>
    }

    return (
        <tr className="is-hoverable is-clickable" onClick={handleTaskLink}>
            <td>{props.exercise.title}</td>
            <td>{dateForHumans(props.exercise.deadline)}</td>
            {props.teacherMode ? teacherFields() : studentFields()}
        </tr>
    );
}

export {ExerciseItem}

