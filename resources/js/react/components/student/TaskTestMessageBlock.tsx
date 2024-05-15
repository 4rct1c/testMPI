import {Task} from "../../types/types";

type Props = {
    task: Task
}

const TaskTestMessageBlock = (props: Props) => {

    console.log(props.task.test_message.split('\n'))

    return <div className="box theme-light">
        <h5>Ответ</h5>
        <div style={{whiteSpace: 'pre', fontFamily: 'monospace', overflow: 'auto', position: 'relative'}}>
            {props.task.test_message.split('\n').map(line => <>{line}<br/></>)}
        </div>
    </div>
}

export {TaskTestMessageBlock}
