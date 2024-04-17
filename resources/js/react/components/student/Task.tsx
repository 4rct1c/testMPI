import React from 'react'

type Props = {
    task: {
        id: number,
        title: string
    }
}

function Task(props : Props) {
    return (
        <div>
            <p>{props.task.title}</p>
        </div>
    );
}

export {Task}

