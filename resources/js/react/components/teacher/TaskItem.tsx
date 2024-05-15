import {TaskWithTestStatusAndFile, UserWithFullName} from "../../types/types";
import {dateForHumans} from "../../helpers/dateHepter";

type Props = {
    task: TaskWithTestStatusAndFile
    user: UserWithFullName|null
}

const TaskItem = (props: Props) => {


    const viewFile = () => {
        return <a href={'/labs-storage/' + props.task.file.generated_name + '.' + props.task.file.extension}
           download={props.task.file.original_name + '.' + props.task.file.extension}>
            {props.task.file.original_name + '.' + props.task.file.extension}
        </a>
    }

    const viewControls = () => {
        return <></>
    }

    return (
        <tr>
            <td>{props.user !== null ? props.user.full_name : '—'}</td>
            <td>{props.task.test_status.label}</td>
            <td>{props.task.mark}</td>
            <td>{dateForHumans(props.task.last_uploaded_at, true)}</td>
            <td>{viewFile()}</td>
            <td>{props.task.comment}</td>
            <td>{props.task.teacher_comment}</td>
            <td>{viewControls()}</td>
        </tr>
    )
}

export {TaskItem}
