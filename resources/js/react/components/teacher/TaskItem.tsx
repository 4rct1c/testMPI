import {TaskWithTestStatusAndFile, UserWithFullName} from "../../types/types";

type Props = {
    task: TaskWithTestStatusAndFile
    user: UserWithFullName|null
}

const TaskItem = (props: Props) => {

    const viewControls = () => {
        return <></>
    }

    return (
        <tr>
            <td>{props.user !== null ? props.user.full_name : '—'}</td>
            <td>{props.task.test_status.label}</td>
            <td>{props.task.mark}</td>
            <td>{props.task.last_uploaded_at}</td>
            <td>{props.task.file.original_name}</td>
            <td>{viewControls()}</td>
        </tr>
    )
}

export {TaskItem}
