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
                    <th>Дата загрузки</th>
                    <th><abbr title="Результат автоматического тестирования">Статус теста</abbr></th>
                    <th>Оценка</th>
                    <th>Файл</th>
                    <th>Комментарий студента</th>
                    <th>Мой комментарий</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                {props.exercise.tasks.filter(task => task.file !== undefined && task.file.ready_for_test).map(task => <TaskItem task={task} user={props.users.filter(user => user.id === task.user_id)[0] ?? null}/>)}
                </tbody>
            </table>
        </div>
    )
}

export {TasksTableBlock}
