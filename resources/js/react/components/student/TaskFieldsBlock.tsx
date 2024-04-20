import {Exercise, Task} from "../../types/types";
import {BulmaLevel} from "../BulmaLevel";
import {BulmaField} from "../BulmaField";
import {UploadFileField} from "./UploadFileField";
import {getMarkInfo, getTestStatusString} from "../../helpers/taskHepler";
import {dateForHumans} from "../../helpers/dateHepter";

type Props = {
    task: Task|null
    exercise: Exercise
}

const TaskFieldsBlock = (props: Props) => {

    const taskNotNull = props.task !== null

    return <div className="m-2">
        <BulmaLevel label="Дата сдачи" value={taskNotNull ? dateForHumans(props.task.last_uploaded_at) : null}/>
        <BulmaLevel label="Статус проверки" value={taskNotNull ? getTestStatusString(props.task) : null}/>
        <BulmaLevel label="Оценка" value={taskNotNull ? getMarkInfo(props.task, props.exercise) : null}/>
        <BulmaField label="Комментарий преподавателя" value={taskNotNull ? props.task.teacher_comment : null}/>
        <BulmaField label="Комментарий" value={taskNotNull ? props.task.comment : null}/>
        <UploadFileField exerciseId={props.exercise.id} taskId={taskNotNull ? props.task.id : null}/>
    </div>
}

export {TaskFieldsBlock}
