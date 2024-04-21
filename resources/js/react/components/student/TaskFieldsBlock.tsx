import {Exercise, Task} from "../../types/types";
import {BulmaLevel} from "../BulmaLevel";
import {BulmaField} from "../BulmaField";
import {UploadFileField} from "./UploadFileField";
import {getMarkInfo, getTestStatusString} from "../../helpers/taskHepler";
import {dateForHumans} from "../../helpers/dateHepter";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faPencil} from "@fortawesome/free-solid-svg-icons";

type Props = {
    task: Task | null
    exercise: Exercise
    updateHandler: Function
}

const TaskFieldsBlock = (props: Props) => {

    const taskNotNull = props.task !== null


    const viewCommentField = () => {
        if (!taskNotNull) return <></>
        return <div className="field">
            <div className="level">
                <div className="level-left">
                    <div className="level-item">
                        Комментарий
                    </div>
                </div>
                <div className="level-right">
                    <div className="level-item">
                        <FontAwesomeIcon icon={faPencil}/>
                    </div>
                </div>
            </div>
            <div className="field-body">
                {taskNotNull ? props.task.comment : '—'}
            </div>
        </div>
    }

    return <div className="m-2">
        <BulmaLevel label="Дата сдачи" value={taskNotNull ? dateForHumans(props.task.last_uploaded_at) : null}/>
        <BulmaLevel label="Статус проверки" value={taskNotNull ? getTestStatusString(props.task) : null}/>
        <BulmaLevel label="Оценка" value={taskNotNull ? getMarkInfo(props.task, props.exercise) : null}/>
        <BulmaField label="Комментарий преподавателя" value={taskNotNull ? props.task.teacher_comment : null}/>
        {viewCommentField()}
        <UploadFileField exerciseId={props.exercise.id}
                         taskId={taskNotNull ? props.task.id : null}
                         updateHandler={props.updateHandler}/>
    </div>
}

export {TaskFieldsBlock}
