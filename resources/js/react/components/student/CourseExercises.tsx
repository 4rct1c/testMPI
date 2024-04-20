import React, {useState} from 'react'
import {Exercise} from "./Exercise";
import {CourseWithExercises, Task} from "../../types/types";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faCaretDown, faCaretUp} from "@fortawesome/free-solid-svg-icons";

type Props = {
    course: CourseWithExercises
    tasks: Task[]
    key: number
}

const CourseExercises = (props : Props) => {

    const [showExercises, setShowExercises] = useState(false)

    const viewExercisesList = () => {
        if (!showExercises) return <></>
        return (
            <table className="table">
                <thead><tr>
                    <th>Наименование</th>
                    <th>Крайний срок</th>
                    <th>Загружено</th>
                    <th>Оценка</th>
                </tr></thead>
                <tbody>
                {props.course.exercises.map(exercise => <Exercise exercise={exercise}
                                                                  key={exercise.id}
                                                                  task={props.tasks.filter(task => task.exercise_id === exercise.id)[0]}
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
                <FontAwesomeIcon icon={showExercises ? faCaretUp : faCaretDown} className="ml-auto is-align-self-center" size='xl'/>
            </div>
            {viewExercisesList()}
        </div>
    );
}

export {CourseExercises}

