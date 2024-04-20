import React from 'react'
import {Exercise as ExerciseType, Task} from "../../types/types";
import {useNavigate} from "react-router-dom";
import {dateForHumans} from "../../helpers/dateHepter";
import {getMarkPercentString, getTestStatusString} from "../../helpers/taskHepler";

type Props = {
    exercise: ExerciseType
    task: Task|undefined
    key: number
}

function ExerciseItem(props : Props) {


    const navigate = useNavigate()

    const handleTaskLink = () => {
        navigate('/portal/exercise/' + props.exercise.id)
    }

    const taskInfo = (props.task ? props.task : {
        last_uploaded_at: null,
        test_status: null,
        mark: null
    })

    const getMark = () => {
        if (props.task === undefined) return null
        return props.task.mark === null ? getTestStatusString(props.task) : getMarkPercentString(props.task, props.exercise)
    }

    return (
        <tr className="is-hoverable is-clickable" onClick={handleTaskLink}>
            <td>{props.exercise.title}</td>
            <td>{dateForHumans(props.exercise.deadline)}</td>
            <td>{dateForHumans(taskInfo.last_uploaded_at)}</td>
            <td>{getMark()}</td>
        </tr>
    );
}

export {ExerciseItem}

