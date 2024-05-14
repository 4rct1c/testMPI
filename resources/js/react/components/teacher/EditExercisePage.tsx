import {ExerciseBlock} from "../common/ExerciseBlock";
import {ExerciseWithTaskTestStatusAndFile} from "../../types/types";

type Props = {
    exercise: ExerciseWithTaskTestStatusAndFile
    setExercise: Function
}

const EditExercisePage = (props: Props) => {


    return <div className="columns mx-4">
        <div className="column is-fullwidth">
            <ExerciseBlock exercise={props.exercise} editable={true}/>
        </div>
    </div>
}

export {EditExercisePage}

