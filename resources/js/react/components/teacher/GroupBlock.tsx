import {GroupWithCourses} from "../../types/types";
import {CourseExercises} from "../common/CourseExercises";

type Props = {
    group: GroupWithCourses
}

const GroupBlock = (props: Props) => {
    return <div>
        <h4 className="is-size-4">{props.group.code}</h4>
        <div className="m-4">
            {props.group.courses.map(course => <CourseExercises course={course} tasks={[]} key={course.id} teacherMode={true}/>)}
        </div>
    </div>
}

export {GroupBlock}
