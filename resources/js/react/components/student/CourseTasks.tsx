import React from 'react'
import {Exercise} from "./Exercise";
import {CourseWithExercises} from "../../types/types";

type Props = {
    course : CourseWithExercises,
    key: number
}

function CourseTasks(props : Props) {
    return (
        <>
            <h4 className="is-size-4">
                {props.course.name}
            </h4>
            {props.course.exercises.map(exercise => <Exercise exercise={exercise}/>)}
        </>
    );
}

export {CourseTasks}

