export type ApiRoutes = {
    load_courses: string
}

export type Course = {
    id: number
    group_id: number
    teacher_id: number
    name: string
}

export type Exercise = {
    id: number
    course_id: number
    title: string
    max_score: number
    deadline: string
    deadline_multiplier: number
}

export type CourseWithExercises = Course & {
    exercises: Exercise[]
}
