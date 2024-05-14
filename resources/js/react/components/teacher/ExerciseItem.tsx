import React from 'react'
import {ExerciseWithTaskCounters} from "../../types/types";
import {useNavigate} from "react-router-dom";
import {dateForHumans} from "../../helpers/dateHepter";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faPencilSquare} from "@fortawesome/free-solid-svg-icons";

type Props = {
    exercise: ExerciseWithTaskCounters
    key: number
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

    return (
        <tr className="is-hoverable is-clickable" onClick={openExerciseTasksPage}>
            <td>{props.exercise.title}</td>
            <td>{dateForHumans(props.exercise.deadline)}</td>
            <td>
                <span>{props.exercise.loaded_tasks}/{props.exercise.students_count} (</span>
                <span className="has-text-success">{props.exercise.succeeded_tasks}</span>/
                <span className="has-text-danger">{props.exercise.failed_tasks}</span>/
                <span className="has-text-warning">{props.exercise.awaiting_tasks}</span>)
            </td>
            <td>
                <FontAwesomeIcon icon={faPencilSquare} size='lg' className='my-auto is-clickable is-hoverable is-link' onClick={e => openExercisePage(e)}/>
            </td>
        </tr>
    );
}

export {ExerciseItem}

