import React from 'react'
import {Exercise as ExerciseType, ExerciseWithTaskCounters, TaskWithTestStatus} from "../../types/types";
import {useNavigate} from "react-router-dom";
import {dateForHumans} from "../../helpers/dateHepter";
import {getMarkPercentString} from "../../helpers/taskHepler";

type Props = {
    exercise: ExerciseType|ExerciseWithTaskCounters
    task: TaskWithTestStatus|undefined
    key: number
}

function ExerciseItem(props : Props) {


    const navigate = useNavigate()

    const handleTaskLink = () => {
        navigate('/portal/exercise/' + props.exercise.id)
    }

    const getTestStatusLabel = () => {
        if (props.task === undefined || props.task.test_status === null) return '—'
        return props.task.test_status.label
    }

    const getMark = () => {
        if (props.task === undefined) return '—'
        return props.task.mark === null ? getTestStatusLabel() : getMarkPercentString(props.task, props.exercise)
    }

    return (
        <tr className="is-hoverable is-clickable" onClick={handleTaskLink}>
            <td>{props.exercise.title}</td>
            <td>{dateForHumans(props.exercise.deadline)}</td>
            <td>{props.task === undefined ? '—' : dateForHumans(props.task.last_uploaded_at)}</td>
            <td>{getMark()}</td>
        </tr>
    );
}

export {ExerciseItem}

