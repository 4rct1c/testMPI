import {useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";

type Props = {
    exerciseId: number
    taskId: number|null
    updateHandler: Function
}

const UploadFileField = (props: Props) => {

    const messageInitialState = {
        show: false,
        text: '',
        colorClass: 'has-text-success'
    }

    const [file, setFile] = useState(null)

    const [message, setMessage] = useState(messageInitialState)

    const uploadFileAxios = (data) => {
        return axios({
            method: 'post',
            url: getApiRoutes().upload_file,
            data: data,
            headers: { 'Content-Type': 'multipart/form-data' }
        })
    }

    const uploadFileHandler = () => {
        if (file === null) {
            setMessage({
                show: true,
                text: 'Выберете файл',
                colorClass: 'has-text-warning'
            })
            return
        }
        let formData = new FormData()
        formData.append('answer', file)
        formData.append('task_id', props.taskId !== null ? props.taskId.toString() : '')
        formData.append('exercise_id', props.exerciseId.toString())
        uploadFileAxios(formData).then( response => {
            setFile(null)
            if (response.data){
                setMessage({
                    show: true,
                    text: 'Файл загружен',
                    colorClass: 'has-text-success'
                })
                props.updateHandler()
            } else {
                setMessage({
                    show: true,
                    text: 'Ошибка загрузки',
                    colorClass: 'has-text-danger'
                })
            }
        })
    }


    return <>
        <form>
            <div className="mt-3">
                <input type='file' name="file" onChange={event => setFile(event.target.files[0])}/>
            </div>
        </form>
        <button className="button is-link my-2" onClick={uploadFileHandler}>Загрузить ответ</button>
        {message.show ? <p className={message.colorClass}>{message.text}</p> : <></>}
    </>

}

export {UploadFileField}
