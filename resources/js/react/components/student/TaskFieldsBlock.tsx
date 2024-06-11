import {Exercise, TaskWithTestStatusAndFile} from "../../types/types";
import {BulmaLevel} from "../BulmaLevel";
import {BulmaField} from "../BulmaField";
import {getMarkInfo} from "../../helpers/taskHepler";
import {dateForHumans} from "../../helpers/dateHepter";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faPencil} from "@fortawesome/free-solid-svg-icons";

type Props = {
    task: TaskWithTestStatusAndFile
    exercise: Exercise
    updateHandler: Function
}

const TaskFieldsBlock = (props: Props) => {



    const viewFileField = () => {
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
            <BulmaLevel label="Дата сдачи" value={dateForHumans(props.task.last_uploaded_at)}/>
            <BulmaLevel label="Статус проверки" value={(props.task.test_status !== null) ? props.task.test_status.label : null}/>
            <BulmaLevel label="Оценка" value={getMarkInfo(props.task, props.exercise)}/>
            {viewFileField()}
            <BulmaLevel label="Готов к оценке" value={props.task.file !== null ? (props.task.file.ready_for_test ? "да" : "нет") : null}/>
            <BulmaField label="Комментарий преподавателя" value={props.task.teacher_comment}/>
            <div className="field">
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
                    {props.task.comment}
                </div>
            </div>
        </div>
    </div>
}

export {TaskFieldsBlock}
