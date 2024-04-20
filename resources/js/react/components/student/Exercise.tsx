import React from 'react'
import {Exercise as ExerciseType} from "../../types/types";

type Props = {
    exercise: ExerciseType
}

function Exercise(props : Props) {
    return (
        <div>
            <p>{props.exercise.title}</p>
        </div>
    );
}

export {Exercise}

