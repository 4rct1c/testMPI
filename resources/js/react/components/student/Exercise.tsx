import React from 'react'
import {Exercise as ExerciseType, Task} from "../../types/types";

type Props = {
    exercise: ExerciseType
    task: Task|undefined
    key: number
}

function Exercise(props : Props) {

    const noDataPlaceholder = '—'

    const taskInfo = (props.task ? props.task : {
        last_uploaded_at: noDataPlaceholder,
        test_status: noDataPlaceholder,
        mark: null
    })

    const getMarkString = () => {
        if (taskInfo.test_status === noDataPlaceholder) return noDataPlaceholder
        if (taskInfo.mark === null) return {
            awaiting: 'Ожидает проверки'
        }[taskInfo.test_status] ?? noDataPlaceholder

        const deadlineWasMissed = taskInfo.last_uploaded_at > props.exercise.deadline
        let score = taskInfo.mark / props.exercise.max_score

        console.log(taskInfo.last_uploaded_at, props.exercise.deadline, deadlineWasMissed)

        if (deadlineWasMissed) score *= props.exercise.deadline_multiplier
        return Math.round(score * 100) + '%'
    }

    const dateForHumans = (timestamp: string, withTime: boolean = false) => {
        if (timestamp === noDataPlaceholder) return noDataPlaceholder
        let [date, time] = timestamp.split(' ')
        if (date === undefined || time === undefined) return timestamp
        let [year, month, day] = date.split('-')
        return day + '.' + month + '.' + year + (withTime ? ' ' + time : '')
    }

    return (
        <tr className="is-hoverable is-clickable">
            <td>{props.exercise.title}</td>
            <td>{dateForHumans(props.exercise.deadline)}</td>
            <td>{dateForHumans(taskInfo.last_uploaded_at)}</td>
            <td>{getMarkString()}</td>
        </tr>
    );
}

export {Exercise}

