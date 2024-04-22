import {CoursesColumn} from "./CoursesColumn"
import {StudentInfoCard} from "./StudentInfoCard";

function StudentPage() {

    return (
        <div className="columns is-fullwidth mx-4">
            <div className="column is-four-fifths-desktop">
                <CoursesColumn/>
            </div>
            <div className="column is-one-fifth-desktop">
                <StudentInfoCard/>
            </div>
        </div>
    );
}

export {StudentPage}

