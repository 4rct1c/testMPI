import * as React from "react"
import * as ReactDOM from 'react-dom/client'
import {Application} from './Components'
import {
    createBrowserRouter,
    RouterProvider,
} from "react-router-dom"
import ErrorPage from "./components/ErrorPage";
import {StudentPage} from "./components/student/StudentPage";
import {TeacherPage} from "./components/teacher/TeacherPage";

declare global {
    const role: string
}



const getRoutes = (chosen_role : string | null = null) : {path: string, element: any, errorElement: any}[] => {
    let result = []
    let url_prefix : string
    if (chosen_role === null) {
        url_prefix = '/'
        chosen_role = role
    }
    else {
        url_prefix = '/' + chosen_role + '/'
    }
    switch (chosen_role){
        case 'student':
            result.push({
                path: url_prefix,
                element: <StudentPage/>,
                errorElement: <ErrorPage/>
            })
            break
        case 'teacher':
            result.push({
                path: url_prefix,
                element: <TeacherPage/>,
                errorElement: <ErrorPage/>
            })
            break
        case 'admin':
            result.push({
                path: "/",
                element: <Application/>,
                errorElement: <ErrorPage/>
            })
            result = [...result, ...getRoutes('student'), ...getRoutes('teacher')]
            break
    }
    return result
}

const router = createBrowserRouter(getRoutes())

const root = ReactDOM.createRoot(document.getElementById('application'))

root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
