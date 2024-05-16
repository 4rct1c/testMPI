import {Test} from "../../types/types";

type Props = {
    test: Test
    setTest: Function
    applyHandler: Function
    cancelHandler: Function
}


const EditTestBlock = (props: Props) => {


    const changeProperty = (property, value) => {
        let handledSettings = {...props.test}
        handledSettings[property] = value
        props.setTest(handledSettings)
    }

    return <div className="box theme-light">

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Ввод
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="text"
                           value={props.test.input}
                           onChange={(e) => changeProperty('input', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Ожидаемый результат
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="text"
                           value={props.test.desired_result}
                           onChange={(e) => changeProperty('desired_result', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Допустимая погрешность
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="number"
                           min={0}
                           step={0.01}
                           value={props.test.max_divergence}
                           onChange={(e) => changeProperty('max_divergence', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Максимальное время (мс)
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="number"
                           min={0}
                           value={props.test.time_limit}
                           onChange={(e) => changeProperty('time_limit', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Множитель просроченного времени
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="number"
                           min={0}
                           max={1}
                           step={0.05}
                           value={props.test.overdue_multiplier}
                           onChange={(e) => changeProperty('overdue_multiplier', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="level my-1">
            <div className="level-left">
                <div className="level-item">
                    Сообщение при ошибке
                </div>
            </div>
            <div className="level-right">
                <div className="level-item">
                    <input className="input"
                           type="text"
                           value={props.test.error_message}
                           onChange={(e) => changeProperty('error_message', e.target.value)}/>
                </div>
            </div>
        </div>

        <div className="mt-2 is-flex">
            <button className="button is-link" onClick={props.applyHandler}>Применить</button>
            <button className="button is-danger ml-2" onClick={props.cancelHandler}>Отменить</button>
        </div>
    </div>
}

export {EditTestBlock}
