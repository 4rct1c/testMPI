import {useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";

type Props = {
    exerciseId: number
    taskId: number|null
}

const UploadFileField = (props: Props) => {

    const [file, setFile] = useState(null)

    const [showWarningMessage, setShowWarningMessage] = useState(false)

    const uploadFileAxios = () => {
        return axios.post(getApiRoutes().upload_file, {
            exercise_id: props.exerciseId,
            task_id: props.taskId,
            file: file
        })
    }

    const uploadFileHandler = () => {
        if (file !== null) {
            uploadFileAxios().then(setFile(null))
            setShowWarningMessage(false)
            return
        }
        setShowWarningMessage(true)
    }


    return <div className="box">
        <div className="my-2">
            <input type='file' onChange={event => setFile(event.target.files)}/>
        </div>
        <button className="button is-link" onClick={uploadFileHandler}>Загрузить ответ</button>
        {showWarningMessage ? <p className="has-text-danger">Выберете файл</p> : <></>}
    </div>
}

export {UploadFileField}
