import {Exercise, Task} from "../types/types";



export const getMarkPercentString = (task: Task, exercise: Exercise) => {
    let score = task.mark / exercise.max_score
    const deadlineWasMissed = task.last_uploaded_at > exercise.deadline
    if (deadlineWasMissed) score *= exercise.deadline_multiplier
    return Math.round(score * 100) + '%'
}

export const getMarkInfo = (task: Task, exercise: Exercise) => {
    const deadlineWasMissed = task.last_uploaded_at > exercise.deadline
    let score = task.mark
    let description = ''
    if (deadlineWasMissed) {
        score *= exercise.deadline_multiplier
        description = ' (' + task.mark + ' * ' + exercise.deadline_multiplier + ')'
    }
    return score  + '/' + exercise.max_score + description
}

export const getTestStatusString = (task: Task) => {
    return {
        awaiting: 'Ожидает проверки',
        tested: 'Протестировано',
        appeal: 'На перепроверке',
        approved: 'Подтверждено'
    }[task.test_status] ?? null
}
