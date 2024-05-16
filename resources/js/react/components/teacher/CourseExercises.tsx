import React, {useState} from 'react'
import {ExerciseItem} from "./ExerciseItem";
import {CourseWithExercisesTeacher} from "../../types/types";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faCaretDown, faCaretUp} from "@fortawesome/free-solid-svg-icons";
import {useNavigate} from "react-router-dom";

type Props = {
    course: CourseWithExercisesTeacher
    key: number
}

const CourseExercises = (props: Props) => {


    const navigate = useNavigate()


    const [showExercises, setShowExercises] = useState(false)


    const addButtonHandler = () => {
        navigate('/portal/exercise', {state: {course_id: props.course.id}})
    }

    const viewExercisesList = () => {
        if (!showExercises) return <></>
        return (
            <table className="table">
                <thead>
                <tr>
                    <th width={'50%'}>Наименование</th>
                    <th width={'25%'}>Крайний срок</th>
                    <th width={'15%'}><abbr title="загружено / всего студентов (успешно / провалено / ожидает)">Загружено</abbr></th>
                    <th width={'10%'}>Действия</th>
                </tr>
                </thead>
                <tbody>
                {props.course.exercises.sort((a, b) => (a.deadline > b.deadline) ? 1 : ((b.deadline > a.deadline) ? -1 : 0))
                    .map(exercise => <ExerciseItem exercise={exercise}
                                                   key={exercise.id}
                    />)}
                </tbody>
            </table>
        )
    }

    return (
        <div>
            <div className="is-flex my-3 is-hoverable is-clickable is-fullwidth" onClick={() => setShowExercises(!showExercises)}>
                <h4 className="is-size-4 my-0 mr-auto">
                    {props.course.name}
                </h4>
                <FontAwesomeIcon icon={showExercises ? faCaretUp : faCaretDown} className="ml-auto mr-0 is-align-self-center"
                                 size='xl'/>
                <button className="ml-2 my-auto mr-0 button is-link" onClick={addButtonHandler}>Добавить задание</button>
            </div>
            {viewExercisesList()}
        </div>
    );
}

export {CourseExercises}

