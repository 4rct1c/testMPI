import {ExerciseWithTasksWithTestStatusAndFile, UserWithFullName} from "../../types/types";
import {TaskItem} from "./TaskItem";

type Props= {
    exercise: ExerciseWithTasksWithTestStatusAndFile
    users: UserWithFullName[]
}

const TasksTableBlock = (props: Props) => {

    return (
        <div className="box theme-light">
            <table className='table'>
                <thead>
                <tr>
                    <th>Студент</th>
                    <th><abbr title="Результат автоматического тестирования">Статус теста</abbr></th>
                    <th>Оценка</th>
                    <th>Дата загрузки</th>
                    <th>Файл</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                {props.exercise.tasks.map(task => <TaskItem task={task} user={props.users.filter(user => user.id === task.user_id)[0] ?? null}/>)}
                </tbody>
            </table>
        </div>
    )
}

export {TasksTableBlock}
