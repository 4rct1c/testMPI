import {ColoredMessage, Exercise} from "../../types/types";


type Props = {
    exercise: Exercise
    setExercise: Function
    applyHandler: Function
    cancelHandler: Function
    deleteHandler: Function
    message: ColoredMessage
}


const ExercisePropertiesBlock = (props: Props) => {



    const changeProperty = (property, value) => {
        let handledSettings = {...props.exercise}
        handledSettings[property] = value
        props.setExercise(handledSettings)
    }

    return <div className="box theme-light">
        <h4>Настройки</h4>
        <div>
            <div className="level my-1">
                <div className="level-left">
                    <div className="level-item">
                        Заголовок
                    </div>
                </div>
                <div className="level-right">
                    <div className="level-item">
                        <input className="input"
                               type="text"
                               value={props.exercise.title}
                               onChange={(e) => changeProperty('title', e.target.value)}/>
                    </div>
                </div>
            </div>
            <div className="level my-1">
                <div className="level-left">
                    <div className="level-item">
                        Максимальная оценка
                    </div>
                </div>
                <div className="level-right">
                    <div className="level-item">
                        <input className="input"
                               type="number"
                               min={0}
                               step={1}
                               value={props.exercise.max_score}
                               onChange={(e) => changeProperty('max_score', e.target.value)}/>
                    </div>
                </div>
            </div>
            <div className="level my-1">
                <div className="level-left">
                    <div className="level-item">
                        Крайний срок
                    </div>
                </div>
                <div className="level-right">
                    <div className="level-item">
                        <input className="input"
                               type="datetime-local"
                               value={props.exercise.deadline}
                               onChange={(e) => changeProperty('deadline', e.target.value)}/>
                    </div>
                </div>
            </div>
            <div className="level my-1">
                <div className="level-left">
                    <div className="level-item">
                        Множитель
                    </div>
                </div>
                <div className="level-right">
                    <div className="level-item">
                        <input className="input"
                               type="number"
                               max="1"
                               step={0.05}
                               value={props.exercise.deadline_multiplier}
                               onChange={(e) => changeProperty('deadline_multiplier', e.target.value)}/>
                    </div>
                </div>
            </div>
            <div>
                <label className="is-flex is-hoverable is-clickable">
                    <input type="checkbox" className="checkbox mr-1"
                           defaultChecked={props.exercise.is_hidden}
                           onChange={(e) => changeProperty('is_hidden', e.target.checked)}/>
                    Скрыто
                </label>
            </div>
            <div className="mt-2 is-flex">
                <button className="button is-link" onClick={props.applyHandler}>Применить</button>
                <button className="button is-danger is-light ml-2" onClick={props.cancelHandler}>Отменить</button>
                <button className="button is-danger ml-auto mr-0" onClick={props.deleteHandler}>Удалить</button>
            </div>
            <div>
                <span className={props.message.colorClass}>{props.message.text}</span>
            </div>
        </div>
    </div>
}

export {ExercisePropertiesBlock}
