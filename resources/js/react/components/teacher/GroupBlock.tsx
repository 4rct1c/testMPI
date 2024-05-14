import {GroupWithCourses} from "../../types/types";
import {CourseExercises} from "./CourseExercises";

type Props = {
    group: GroupWithCourses
}

const GroupBlock = (props: Props) => {
    return <div>
        <h4 className="is-size-4">{props.group.code}</h4>
        <div className="m-4">
            {props.group.courses.map(course => <CourseExercises course={course} key={course.id}/>)}
        </div>
    </div>
}

export {GroupBlock}
