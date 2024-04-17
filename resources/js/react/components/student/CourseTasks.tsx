import React from 'react'
import {Task} from "./Task";

type Props = {
    course : {
        id: number,
        name: string,
        tasks: {
            id: number,
            title: string
        }[]
    },
    key: number
}

function CourseTasks(props : Props) {
    return (
        <>
            <h4 className="is-size-4">
                {props.course.name}
            </h4>
            {props.course.tasks.map(task => <Task task={task}/>)}
        </>
    );
}

export {CourseTasks}

