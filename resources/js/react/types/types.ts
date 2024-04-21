export type ApiRoutes = {
    load_courses: string
    load_tasks: string
    load_exercise: string
    upload_file: string
}

export type Course = {
    id: number
    group_id: number
    teacher_id: number
    name: string
    created_at?: number
    updated_at?: number
}

export type CourseWithExercises = Course & {
    exercises: Exercise[]
}

export type Exercise = {
    id: number
    course_id: number
    title: string
    max_score: number
    deadline: string
    deadline_multiplier: number
    text: string
    created_at?: number
    updated_at?: number
}

export type ExerciseWithTask = Exercise & {
    task: Task
}

export type ExerciseWithTaskAndFile = Exercise & {
    task: TaskWithFile
}

export type Task = {
    id: number
    user_id: number
    exercise_id: number
    first_uploaded_at: string
    last_uploaded_at: string
    test_status: string
    mark: number
    comment: string
    teacher_comment: string
    created_at?: number
    updated_at?: number
}

export type TaskFile = {
    id: number
    task_id: number
    original_name: string
    generated_name: string
    extension: string
    size: number
    created_at?: string
    updated_at?: string
}

export type TaskWithFile = Task & {
    file: TaskFile
}
