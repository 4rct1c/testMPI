import {Task} from "../../types/types";

type Props = {
    task: Task
}

const TaskTestMessageBlock = (props: Props) => {

    if (props.task.test_message === null || props.task.test_message === undefined){
        return <></>
    }

    return <div className="box theme-light">
        <h5>Ответ</h5>
        <div style={{whiteSpace: 'pre', fontFamily: 'monospace', overflow: 'auto', position: 'relative'}}>
            {props.task.test_message.split('\n').map(line => <>{line}<br/></>)}
        </div>
    </div>
}

export {TaskTestMessageBlock}
