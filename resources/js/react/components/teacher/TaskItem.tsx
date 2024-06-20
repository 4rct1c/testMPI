import {TaskWithTestStatusAndFile, UserWithFullName} from "../../types/types";
import {dateForHumans} from "../../helpers/dateHepter";
import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";

type Props = {
    task: TaskWithTestStatusAndFile
    user: UserWithFullName|null
}

const TaskItem = (props: Props) => {

    const [mark, setMark] = useState<number>(props.task.mark)

    const viewFile = () => {
        return <a href={'/labs-storage/' + props.task.file.generated_name + '.' + props.task.file.extension}
           download={props.task.file.original_name + '.' + props.task.file.extension}>
            {props.task.file.original_name + '.' + props.task.file.extension}
        </a>
    }

    const updateMarkAxios = () => {
        return axios.put(getApiRoutes().update_mark, {
            'task_id': props.task.id,
            'mark': mark
        })
    }

    //todo: fix
    const updateMark = () => {
        updateMarkAxios().then(r => {
            if (!r.data) {
                setMark(props.task.mark)
            }
        }, e => {
            setMark(props.task.mark)
        })
    }

    useEffect(() => {
        console.log('hey')
        const timeOutId = setTimeout(() => updateMark(), 500)
        return () => clearTimeout(timeOutId)
    }, [mark])


    return (
        <tr>
            <td>{props.user !== null ? props.user.full_name : '—'}</td>
            <td>{dateForHumans(props.task.last_uploaded_at, true)}</td>
            <td>{props.task.test_status.label}</td>
            <td><input className="input is-small" style={{maxWidth: "50px"}} type="number" step={1} min={0} value={mark} onChange={(e) => {setMark(e.target.value)}}/></td>
            <td>{viewFile()}</td>
            <td>{props.task.comment}</td>
            <td>{props.task.teacher_comment}</td>
        </tr>
    )
}

export {TaskItem}
