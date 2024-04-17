import React from 'react'
import {CourseTasks} from "./CourseTasks";


type Props = {
    courses : {
        id: number,
        name: string,
        tasks: {
            id: number,
            title: string
        }[]
    }[]
}

function TasksColumn(props : Props){
    return (
        <div className="box is-block">
            <h3 className="is-size-3">
                Задания
            </h3>
            <div className="my-2">
                {props.courses.map(course => <CourseTasks key={course.id} course={course}/>)}
            </div>
        </div>
    );
}

export {TasksColumn}

