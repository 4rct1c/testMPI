import {CoursesColumn} from "./CoursesColumn"

function StudentPage() {


    return (
        <div className="columns is-fullwidth mx-4">
            <div className="column is-four-fifths-desktop">
                <CoursesColumn/>
            </div>
            <div className="column is-one-fifth-desktop">
                <div className="box theme-light">
                    Info block
                </div>

            </div>
        </div>
    );
}

export {StudentPage}

