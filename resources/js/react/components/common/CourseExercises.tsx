import React, {useState} from 'react'
import {ExerciseItem} from "./ExerciseItem";
import {CourseWithExercises, TaskWithTestStatus} from "../../types/types";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faCaretDown, faCaretUp} from "@fortawesome/free-solid-svg-icons";

type Props = {
    course: CourseWithExercises
    tasks: TaskWithTestStatus[]
    key: number
    teacherMode: boolean
}

const CourseExercises = (props: Props) => {

    const [showExercises, setShowExercises] = useState(false)

    const studentThead = () => {
        return <tr>
            <th width={'55%'}>Наименование</th>
            <th width={'15%'}>Крайний срок</th>
            <th width={'15%'}>Загружено</th>
            <th width={'15%'}>Оценка</th>
        </tr>
    }

    const teacherThead = () => {
        return <tr>
            <th width={'50%'}>Наименование</th>
            <th width={'25%'}>Крайний срок</th>
            <th width={'15%'}><abbr title="загружено / всего студентов (успешно / провалено / ожидает)">Загружено</abbr></th>
            <th width={'10%'}>Действия</th>
        </tr>
    }

    const viewExercisesList = () => {
        if (!showExercises) return <></>
        return (
            <table className="table">
                <thead>
                {props.teacherMode ? teacherThead() : studentThead()}
                </thead>
                <tbody>
                {props.course.exercises.sort((a, b) => (a.deadline > b.deadline) ? 1 : ((b.deadline > a.deadline) ? -1 : 0))
                    .map(exercise => <ExerciseItem exercise={exercise}
                                                   key={exercise.id}
                                                   task={props.tasks.filter(task => task.exercise_id === exercise.id)[0]}
                                                   teacherMode={props.teacherMode}
                    />)}
                </tbody>
            </table>
        )
    }

    return (
        <div>
            <div className="is-flex my-3 is-hoverable is-clickable" onClick={() => setShowExercises(!showExercises)}>
                <h4 className="is-size-4 my-0">
                    {props.course.name}
                </h4>
                <FontAwesomeIcon icon={showExercises ? faCaretUp : faCaretDown} className="ml-auto is-align-self-center"
                                 size='xl'/>
            </div>
            {viewExercisesList()}
        </div>
    );
}

export {CourseExercises}

