export type ApiRoutes = {
    load_courses: string
    load_tasks: string
    load_exercise: string
}

export type Course = {
    id: number
    group_id: number
    teacher_id: number
    name: string
    created_at?: number
    updated_at?: number
}

export type Exercise = {
    id: number
    course_id: number
    title: string
    max_score: number
    deadline: string
    deadline_multiplier: number
    created_at?: number
    updated_at?: number
}

export type CourseWithExercises = Course & {
    exercises: Exercise[]
}

export type Task = {
    id: number
    user_id: number
    exercise_id: number
    first_uploaded_at: string
    last_uploaded_at: string
    test_status: string
    mark: number
    file: string
    created_at?: number
    updated_at?: number
}
