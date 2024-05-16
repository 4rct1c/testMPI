import {TestTableRow} from "./TestTableRow";
import {useEffect, useState} from "react";
import {useLocation, useParams} from "react-router-dom";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {EditTestBlock} from "./EditTestBlock";


const TestsPage = () => {

    const params = useParams()

    const location = useLocation()

    const exerciseId = params.id

    const [exercise, setExercise] = useState(undefined)

    const getExerciseId = () => {
        if (exerciseId !== undefined) return exerciseId
        if (location.state !== null && location.state.exercise !== null) return location.state.exercise
        if (exercise !== undefined) return exercise.id
        return undefined
    }

    const chosenTestInitial = {
        id: 0,
        exercise_id: getExerciseId(),
        input: '',
        desired_result: '',
        max_divergence: 0,
        time_limit: 1000,
        overdue_multiplier: 1,
        error_message: '',
    }

    const [chosenTest, setChosenTest] = useState(chosenTestInitial)

    const loadExerciseAxios = () => {
        return axios.get(getApiRoutes().load_exercise + '/' + exerciseId)
    }

    const updateTestAxios = () => {
        return axios.put(getApiRoutes().update_test, chosenTest)
    }

    const addTestAxios = () => {
        return axios.post(getApiRoutes().add_test, chosenTest)
    }

    const applyHandler = () => {
        ((chosenTest.id === 0) ? addTestAxios() : updateTestAxios()).then(r => {
            if (r.data) {
                loadExerciseAxios().then(r => setExercise(r.data))
            }
            setChosenTest(chosenTestInitial)
        })
        /*if (addMode){
            addTestAxios().then(r => {
                if (r.data) {
                    loadExerciseAxios().then(r => setExercise(r.data))
                }
                setChosenTest(chosenTestInitial)
            })
        } else {
            updateTestAxios().then(r => {
                if (r.data) {
                    loadExerciseAxios().then(r => setExercise(r.data))
                }
                setChosenTest(chosenTestInitial)
            })
        }*/
    }

    const cancelHandler = () => {
        setChosenTest(chosenTestInitial)
    }

    useEffect(() => {
        if (location.state === null || location.state.exercise === null || location.state.exercise.tests === undefined){
            loadExerciseAxios().then(r => setExercise(r.data))
        } else {
            setExercise(location.state.exercise)
        }
    }, [])


    return <div className="columns">
        <div className="column is-two-thirds">
            <div className="box theme-light">
                <table className="table">
                    <thead>
                    <tr>
                        <th>Ввод</th>
                        <th>Ожидаемый ответ</th>
                        <th>Погрешность ответа</th>
                        <th>Лимит по времени</th>
                        <th>Множитель превышения времени</th>
                        <th>Сообщение при провале теста</th>
                    </tr>
                    </thead>
                    <tbody>
                    {(exercise !== undefined && exercise.tests !== undefined) ?
                        exercise.tests.map(test => <TestTableRow test={test} setTestCurrent={setChosenTest}/>) : <></>}
                    </tbody>
                </table>
            </div>
        </div>
        <div className="column is-one-third">
            <EditTestBlock test={chosenTest}
                           setTest={setChosenTest}
                           applyHandler={applyHandler}
                           cancelHandler={cancelHandler}
            />
        </div>
    </div>
}

export {TestsPage}
