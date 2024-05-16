import {Test} from "../../types/types";


type Props = {
    test: Test
    setTestCurrent: Function
}


const TestTableRow = (props: Props) => {
    return <tr className="is-hoverable is-clickable" onClick={() => props.setTestCurrent(props.test)}>
        <td>{props.test.input}</td>
        <td>{props.test.desired_result}</td>
        <td>{props.test.max_divergence}</td>
        <td>{props.test.time_limit}</td>
        <td>{props.test.overdue_multiplier}</td>
        <td>{props.test.error_message}</td>
    </tr>
}

export {TestTableRow}
