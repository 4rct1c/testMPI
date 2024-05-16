export type ApiRoutes = {
    load_courses: string
    load_tasks: string
    load_exercise: string
    upload_file: string
    load_user: string
    update_exercise_text: string
    load_groups: string
    load_exercise_students: string
}

export type Course = {
    id: number
    group_id: number
    teacher_id: number
    name: string
    created_at?: string
    updated_at?: string
}

export type CourseWithExercises = Course & {
    exercises: Exercise[]
}

export type CourseWithExercisesTeacher = Course & {
    exercises: TeacherExerciseType[]
}

export type Exercise = {
    id: number
    course_id: number
    title: string
    max_score: number
    deadline: string
    deadline_multiplier: number
    text: string
    is_hidden: boolean
    created_at?: string
    updated_at?: string
}

export type ExerciseWithTaskCounters = Exercise & {
    loaded_tasks: number
    failed_tasks: number
    succeeded_tasks: number
    awaiting_tasks: number
    students_count: number
}

export type TeacherExerciseType = ExerciseWithTaskCounters & ExerciseWithTasksWithTestStatusAndFile


export type ExerciseWithTaskTestStatusAndFile = Exercise & {
    task: TaskWithTestStatusAndFile
}


export type ExerciseWithTasksWithTestStatusAndFile = Exercise & {
    tasks: TaskWithTestStatusAndFile[]
}

export type Task = {
    id: number
    user_id: number
    exercise_id: number
    first_uploaded_at: string
    last_uploaded_at: string
    mark: number
    comment: string
    teacher_comment: string
    test_status_id: number
    test_message: string
    created_at?: string
    updated_at?: string
}

export type TestStatus = {
    id: number
    code: string
    label: string
    created_at?: string
    updated_at?: string
}

export type TaskWithTestStatus = Task & {
    test_status: TestStatus
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

export type TaskWithTestStatusAndFile = TaskWithTestStatus & TaskWithFile

export type User = {
    id: number
    name: string
    second_name: string|null
    last_name: string
    email?: string|null
    email_verified_at?: string|null
    phone: string|null
    login: string
    remember_token?: string
    group_id: number|null
    user_type_id: number
    created_at?: string
    updated_at?: string
}

export type UserWithFullName = User & {
    full_name: string
}

export type Group = {
    id: number
    code: string
    name: string
}

export type GroupWithCourses = Group & {
    courses: CourseWithExercisesTeacher[]
}
