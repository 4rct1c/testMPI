import {ExerciseBlock} from "../common/ExerciseBlock";
import {Exercise} from "../../types/types";

type Props = {
    exercise: Exercise
    setExercise: Function
}

const ExercisePage = (props: Props) => {


    return <div className="columns mx-4">
        <div className="column is-fullwidth">
            <ExerciseBlock exercise={props.exercise} editable={true}/>
        </div>
    </div>
}

export {ExercisePage}

