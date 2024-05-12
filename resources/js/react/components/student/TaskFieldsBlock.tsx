import {Exercise, TaskWithTestStatusAndFile} from "../../types/types";
import {BulmaLevel} from "../BulmaLevel";
import {BulmaField} from "../BulmaField";
import {UploadFileField} from "./UploadFileField";
import {getMarkInfo} from "../../helpers/taskHepler";
import {dateForHumans} from "../../helpers/dateHepter";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faPencil} from "@fortawesome/free-solid-svg-icons";

type Props = {
    task: TaskWithTestStatusAndFile | null
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

    const viewFileField = () => {
        if (!taskNotNull) return <></>
        if (props.task.file === null) return <></>

        return <div className="level">
            <div className="level-left">
                <div className="level-item">
                    Файл
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <a href={'/labs-storage/' + props.task.file.generated_name + '.' + props.task.file.extension}
                       download={props.task.file.original_name + '.' + props.task.file.extension}>
                        {props.task.file.original_name + '.' + props.task.file.extension}
                    </a>
                </div>
            </div>
        </div>

    }

    return <div className="box theme-light">
        <div className="m-2">
            <BulmaLevel label="Дата сдачи" value={taskNotNull ? dateForHumans(props.task.last_uploaded_at) : null}/>
            <BulmaLevel label="Статус проверки" value={taskNotNull ? props.task.test_status.label : null}/>
            <BulmaLevel label="Оценка" value={taskNotNull ? getMarkInfo(props.task, props.exercise) : null}/>
            {viewFileField()}
            <BulmaField label="Комментарий преподавателя" value={taskNotNull ? props.task.teacher_comment : null}/>
            {viewCommentField()}
            <UploadFileField exerciseId={props.exercise.id}
                             taskId={taskNotNull ? props.task.id : null}
                             updateHandler={props.updateHandler}/>
        </div>
    </div>
}

export {TaskFieldsBlock}
