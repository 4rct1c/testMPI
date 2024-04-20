import React from 'react'
import {CourseTasks} from "./CourseTasks";
import {CourseWithExercises} from "../../types/types";


type Props = {
    courses: CourseWithExercises[]
}

function ExercisesColumn(props: Props) {
    return (
        <div className="box is-block">
            <h3 className="is-size-3">
                Курсы
            </h3>
            <div className="my-2">
                {props.courses.map(course => <CourseTasks key={course.id} course={course}/>)}
            </div>
        </div>
    );
}

export {ExercisesColumn}

