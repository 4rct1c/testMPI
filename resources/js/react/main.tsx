import * as React from "react"
import * as ReactDOM from 'react-dom/client'
import {
    createBrowserRouter,
    RouterProvider,
} from "react-router-dom"
import ErrorPage from "./components/ErrorPage"
import {StudentPage} from "./Components"
import {TeacherPage} from "./Components"
import {AdminPage} from "./Components"
import {ApiRoutes} from "./types/types";
import {ExercisePage} from "./components/common/ExercisePage";
import {ExerciseTasksPage} from "./components/teacher/ExerciseTasksPage";

declare global {
    const role: string
    const apiRoutes: ApiRoutes
}

export const getApiRoutes = () => {
    return apiRoutes
}


const getRoutes = (chosenRole : string | null = null) : {path: string, element: any, errorElement: any}[] => {
    let routes = []
    let prefix = '/portal/'
    if (chosenRole === null) {
        chosenRole = role
    }
    else if (role === 'admin') {
        prefix += chosenRole
    }
    switch (chosenRole){
        case 'student':
            routes.push({
                path: prefix,
                element: <StudentPage/>,
                errorElement: <ErrorPage/>
            })
            routes.push({
                path: prefix + 'exercise',
                element: <ExercisePage editable={false}/>,
                errorElement: <ErrorPage/>,
                children: [
                    {
                        path: prefix + 'exercise/:id',
                        element: <ExercisePage editable={false}/>,
                        errorElement: <ErrorPage/>
                    }
                ]
            })
            break
        case 'teacher':
            routes.push({
                path: prefix,
                element: <TeacherPage/>,
                errorElement: <ErrorPage/>
            })
            routes.push({
                path: prefix + 'exercise',
                element: <ExercisePage editable={true}/>,
                errorElement: <ErrorPage/>,
                children: [
                    {
                        path: prefix + 'exercise/:id',
                        element: <ExercisePage editable={true}/>,
                        errorElement: <ErrorPage/>
                    }
                ]
            })
            routes.push({
                path: prefix + 'exercise/:id/tasks/',
                element: <ExerciseTasksPage/>,
                errorElement: <ErrorPage/>,
            })
            break
        case 'admin':
            routes.push({
                path: prefix,
                element: <AdminPage/>,
                errorElement: <ErrorPage/>
            })
            routes = [...routes, ...getRoutes('student'), ...getRoutes('teacher')]
            break
        case 'guest':
            routes.push({
                path: prefix,
                element: <div>
                    <h4>Необходима регистрация</h4>
                </div>,
                errorElement: <ErrorPage/>
            })
            break
    }
    return routes
}

const router = createBrowserRouter(getRoutes())

const root = ReactDOM.createRoot(document.getElementById('application'))

root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
)
